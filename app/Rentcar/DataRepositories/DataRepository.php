<?php

namespace App\Rentcar\DataRepositories;

use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Laravel\Scout\Builder as ScoutBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DataRepository {

    /**
     * Model of the repository
     */
    public $model;

    /**
     * Instance of the model of the repository
     */
    public $instance;

    /**
     * Number of records per page
     */
    public $perPage = 15;

    /**
     * Columns of table to fetch
     */
    public $columns = ['*'];

    /**
     * name of the bag which contains page info, default page
     */
    public $pageName = 'page';

    /**
     * Columns to order by (default: id, desc)
     */
    public $orderByCols = ['id','desc'];

    /**
     * Add relations to the query
     */
    public $withRelations = [];

    /**
     * Doctrine query repository variable
     */
    public $query;

    /**
     * Request instance
     */
    public $request;

    /**
     * Default date format
     */
    public $defaultDateFormat = 'Y-m-d';

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __construct(Request $request) {
        $this->request = $request;
        $this->buildQuery();
        // $this->request->mergeIfMissing($this->getQueryStringValues());
    }

    /**
     * Get paginator instance from query
     *
     * @return \Illuminate\Pagination\Paginator
     */
    public function getPaginator()
    {
        if($this->query instanceof EloquentBuilder){
            $paginator = $this->query->paginate(
                $perPage = (int) $this->getFilter('perPage') ?? $this->perPage,
                $columns = $this->columns,
                $pageName = $this->pageName,
                $page = (int) $this->getFilter('page') ?? 1
            );
        }
        else if($this->query instanceof ScoutBuilder){
            $paginator = $this->query->paginate(
                $perPage = (int) $this->getFilter('perPage') ?? $this->perPage,
                $pageName = $this->pageName,
                $page = (int) $this->getFilter('page') ?? 1
            );
        }

        $paginator = $this->addQueryStringValues($paginator);

        return $paginator;
    }

    /**
     * Get Eloquent query instance from query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Add query string values to paginator
     *
     * @return \Illuminate\Pagination\Paginator
     */
    public function addQueryStringValues($paginator)
    {
        $query_strings = $this->getQueryStringValues();

        if(count($query_strings) > 0){
            $paginator->appends($query_strings);
        }

        return $paginator;
    }

    public function getQueryStringValues(): array {
        $query_strings = [];

        $query_strings['orderByCol'] = $this->getFilter('orderByCol') ?? $this->orderByCols[0];
        $query_strings['orderOrientation'] = $this->getFilter('orderOrientation') ?? $this->orderByCols[1];

        if($this->hasFilter('query'))
            $query_strings['query'] = $this->getFilter('query');

        if($this->hasFilter('filterCols'))
            $query_strings['filterCols'] = $this->getFilter('filterCols');

        if($this->hasFilter('filterDateRanges'))
            $query_strings['filterDateRanges'] = $this->getFilter('filterDateRanges');

        if($this->hasFilter('perPage'))
            $query_strings['perPage'] = $this->getFilter('perPage');

        return $query_strings;
    }

    /**
     * Create base query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildQuery(): void
    {
        $this->query = $this->instance = new $this->model;

        if(count($this->withRelations) > 0)
            $this->query = $this->query->with($this->withRelations);

        $this->addFreeSearch();
        $this->addFilters();
        $this->addDateRangeFilters();
        $this->addPerPageFilter();
        $this->addOrderByFields();

        $this->saveFilterSession();

    }

    /**
     * Add free search statements to main query
     * Using Laravel Scout
     *
     * @return void
     */
    public function addFreeSearch(): void
    {
        $search_term = $this->getFilter('query');

        if($search_term)
            $this->query = $this->query->search($search_term);

    }

    /**
     * Add filter columns to main query
     *
     * @return void
     */
    public function addFilters(): void
    {
        $filterCols = [];

        if($this->request->filled('filterCols'))
            $filterCols = $this->request->input('filterCols');
        else if($this->request->session()->has('filters.filterCols'))
            $filterCols = $this->request->session()->get('filters.filterCols');

        foreach($filterCols as $field => $value){
            $this->query = $this->query->where($field, 'like', "%{$value}%");
        }
    }

    /**
     * Add order fields to main query
     *
     * @return void
     */
    public function addOrderByFields(): void
    {
        $orderCol = str($this->getFilter('orderByCol') ?? $this->orderByCols[0]);
        $orderOrientation = str($this->getFilter('orderOrientation') ?? $this->orderByCols[1]);

        if($orderCol->contains('.')){
            /**
             * Here is a relation order table and column ie: "owner.name"
             * Create a join with the table and search with the column
             */

            $relation_table = (string) $orderCol->before('.');
            $model_table = $this->instance->getTable();
            $model_join_pivot = Arr::join([$model_table, str($relation_table)->singular() . "_id"], '.');
            $relation_join_pivot = Arr::join([$relation_table,'id'], '.');

            $this->query =
                $this->query->join($relation_table, $model_join_pivot, "=", $relation_join_pivot)
                ->orderBy((string) $orderCol, $orderOrientation);
        }
        else{
            $this->query = $this->query->orderBy($orderCol,$orderOrientation);
        }
    }

    /**
     * Add date range filters to main query
     *
     * @return void
     */
    public function addDateRangeFilters(): void
    {
        if($this->hasFilter('filterDateRanges')){
            foreach($this->getFilter('filterDateRanges') as $field => $dateRange){
                try {
                    $rawStartDate = $dateRange['start'];
                    $rawEndDate = $dateRange['end'];

                    $startDate = Carbon::createFromFormat($this->defaultDateFormat, $rawStartDate);
                    $endDate = Carbon::createFromFormat($this->defaultDateFormat, $rawEndDate);

                    if($this->query instanceof ScoutBuilder){
                        $this->query = $this->query
                                            ->query(fn(EloquentBuilder $query) =>
                                                $query->whereBetween($field, [$startDate, $endDate])
                                        );
                    }
                    else
                        $this->query = $this->query->whereBetween($field, [$startDate, $endDate]);
                } catch (\InvalidArgumentException $th) {
                    Log::warning('invalid argument in Carbon date parsing in DataRepository', $th);
                }

            }
        }
    }

    /**
     * Add per page filter to main query
     *
     * @return void
     */
    public function addPerPageFilter(): void
    {
        if($this->hasFilter('perPage'))
            $this->perPage = $this->getFilter('perPage');

    }

    /**
     * Save filter in session
     */
    public function saveFilterSession(): void
    {
        if($this->hasFilter('query'))
            $this->putFilter('query', $this->getFilter('query'));

        if($this->hasFilter('filterCols'))
            $this->putFilter('filterCols', $this->getFilter('filterCols'));

        if($this->hasFilter('filterDateRanges'))
            $this->putFilter('filterDateRanges', $this->getFilter('filterDateRanges'));

        if($this->hasFilter('perPage'))
            $this->putFilter('perPage', $this->getFilter('perPage'));

        if($this->hasFilter('orderByCol'))
            $this->putFilter('orderByCol', $this->getFilter('orderByCol'));

        if($this->hasFilter('orderOrientation'))
            $this->putFilter('orderOrientation', $this->getFilter('orderOrientation'));

        if($this->hasFilter('page'))
            $this->putFilter('page', $this->getFilter('page'));
    }

    private function getSession(): Session {
        return $this->request->session();
    }

    private function hasFilter(string $filter): bool {
        return !! $this->getFilter($filter);
    }

    private function putFilter(string $filter, mixed $value): void {
        $this->getSession()->put("{$this->instance->getTable()}.filters.{$filter}", $value);
    }

    private function getFilter(string $filter): mixed {
        return $this->request->input($filter) ?? $this->getSession()->get("{$this->instance->getTable()}.filters.{$filter}");
    }

    public function flushFilters(): void {
        $this->getSession()->forget("{$this->instance->getTable()}.filters");
    }

}
