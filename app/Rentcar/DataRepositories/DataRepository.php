<?php

namespace App\Rentcar\DataRepositories;

use App\Rentcar\FilterManager;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
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
    public Model $instance;

    /**
     * Number of records per page
     */
    public int $perPage = 15;

    /**
     * Columns of table to fetch
     */
    public array $columns = ['*'];

    /**
     * name of the bag which contains page info, default page
     */
    public string $pageName = 'page';

    /**
     * Columns to order by (default: id, desc)
     */
    public array $orderByCols = ['id','desc'];

    /**
     * Add relations to the query
     */
    public array $withRelations = [];

    /**
     * Doctrine query repository variable
     */
    public EloquentBuilder|ScoutBuilder|Model $query;

    /**
     * Filter manager instance
     */
    public FilterManager $filterManager;

    /**
     * Default date format
     */
    public string $defaultDateFormat = 'Y-m-d';

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __construct(Request $request) {
        $this->instance = new $this->model;
        $this->filterManager = new FilterManager($this->instance->getTable());
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
                $perPage = (int) $this->filterManager->getFilter('perPage') ?? $this->perPage,
                $columns = $this->columns,
                $pageName = $this->pageName,
                $page = (int) $this->filterManager->getFilter('page') ?? 1
            );
        }
        else if($this->query instanceof ScoutBuilder){
            $paginator = $this->query->paginate(
                $perPage = (int) $this->filterManager->getFilter('perPage') ?? $this->perPage,
                $pageName = $this->pageName,
                $page = (int) $this->filterManager->getFilter('page') ?? 1
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

        $query_strings['orderByCol'] = $this->filterManager->getFilter('orderByCol') ?? $this->orderByCols[0];
        $query_strings['orderOrientation'] = $this->filterManager->getFilter('orderOrientation') ?? $this->orderByCols[1];

        if($this->filterManager->hasFilter('query'))
            $query_strings['query'] = $this->filterManager->getFilter('query');

        if($this->filterManager->hasFilter('filterCols'))
            $query_strings['filterCols'] = $this->filterManager->getFilter('filterCols');

        if($this->filterManager->hasFilter('filterDateRanges'))
            $query_strings['filterDateRanges'] = $this->filterManager->getFilter('filterDateRanges');

        if($this->filterManager->hasFilter('perPage'))
            $query_strings['perPage'] = $this->filterManager->getFilter('perPage');

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
        $search_term = $this->filterManager->getFilter('query');

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

        if($this->filterManager->hasFilter('filterCols'))
            $filterCols = $this->filterManager->getFilter('filterCols');

        foreach($filterCols as $field => $filter){
            $type = $filter['type'] ?? null;
            $value = $filter['value'];

            $this->query = match(true){
                $type == 'like' => $this->query->where($field, 'like', "%{$value}%"),
                default => $this->query->where($field,$value),
            };

        }
    }

    /**
     * Add order fields to main query
     *
     * @return void
     */
    public function addOrderByFields(): void
    {
        $orderCol = str($this->filterManager->getFilter('orderByCol') ?? $this->orderByCols[0]);
        $orderOrientation = str($this->filterManager->getFilter('orderOrientation') ?? $this->orderByCols[1]);

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
        if($this->filterManager->hasFilter('filterDateRanges')){
            foreach($this->filterManager->getFilter('filterDateRanges') as $field => $dateRange){
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
        if($this->filterManager->hasFilter('perPage'))
            $this->perPage = $this->filterManager->getFilter('perPage');

    }

    /**
     * Save filter in session
     */
    public function saveFilterSession(): void
    {
        if($this->filterManager->hasFilter('query'))
            $this->filterManager->putFilter('query', $this->filterManager->getFilter('query'));

        if($this->filterManager->hasFilter('filterCols'))
            $this->filterManager->putFilter('filterCols', $this->filterManager->getFilter('filterCols'));

        if($this->filterManager->hasFilter('filterDateRanges'))
            $this->filterManager->putFilter('filterDateRanges', $this->filterManager->getFilter('filterDateRanges'));

        if($this->filterManager->hasFilter('perPage'))
            $this->filterManager->putFilter('perPage', $this->filterManager->getFilter('perPage'));

        if($this->filterManager->hasFilter('orderByCol'))
            $this->filterManager->putFilter('orderByCol', $this->filterManager->getFilter('orderByCol'));

        if($this->filterManager->hasFilter('orderOrientation'))
            $this->filterManager->putFilter('orderOrientation', $this->filterManager->getFilter('orderOrientation'));

        if($this->filterManager->hasFilter('page'))
            $this->filterManager->putFilter('page', $this->filterManager->getFilter('page'));
    }

}
