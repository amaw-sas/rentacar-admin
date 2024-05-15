<?php

namespace App\Rentcar;

use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;

class FilterManager {
    public string $prefix;
    public Request $request;
    public SessionManager $session;

    public function __construct(string $prefix, Request $request = null)
    {
        $this->prefix = $prefix;
        $this->request = $request ?? request();
        $this->session = session();
    }

    public function hasFilter(string $filter): bool {
        return !! $this->getFilter($filter);
    }

    public function putFilter(string $filter, mixed $value): void {
        $this->session->put("{$this->prefix}.filters.{$filter}", $value);
    }

    public function getFilter(string $filter): mixed {
        return $this->request->input($filter) ?? $this->session->get("{$this->prefix}.filters.{$filter}");
    }

    public function flushFilters(): void {
        $this->session->forget("{$this->prefix}.filters");
    }
}
