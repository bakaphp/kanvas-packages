<?php

namespace Kanvas\Packages\AppSearch\Http\QueryParser;

use Baka\Elasticsearch\Query\FromClause;
use Baka\Support\Str;
use Phalcon\Http\RequestInterface;

/**
 * QueryParser translates a complex syntax query provided via an url in an string format to a SQL alike syntax.
 */
class QueryParser
{
    public ?RequestInterface $request = null;
    public array $searchParams = [];
    public array $filters = [];

    /**
     * Constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $this->page = $this->request->getQuery('page', 'int', 1);
        $this->limit = $this->request->getQuery('limit', 'int', 25);
        $this->setPagination($this->page, $this->limit);

        if ($sort = $this->request->getQuery('sort', 'string')) {
            $this->setSort($sort);
        }

        if ($filters = $this->request->getQuery('filters', 'string')) {
            $this->setFilters($filters);
        }
    }

    /**
     * get search params
     * @return array;
     */
    public function getSearchParams() : array
    {
        return $this->searchParams;
    }

    /**
     * get filters params
     * @return array;
     */
    public function getFiltersParams() : array
    {
        return $this->filters;
    }

    /**
     * @param string $sort
     */
    public function setSort(string $sort) : void
    {
        $sort = explode('|', $sort);
        $this->searchParams['sort'][$sort[0]] = $sort[1] ?? 'asc';
    }

    /**
     * @param string $sort
     */
    public function setFilters(string $filters) : void
    {
        //remove ()
        $filters = str_replace(['(',')'], '', $filters);

        foreach (explode(',', $filters) as $filter) {
            $this->parser($filter);
        }

        $this->searchParams['filters'] = $this->filters;
    }

    /**
     * @param int $page
     * @param int $limit
     */
    public function setPagination(int $page = 1, int $limit = 25) : void
    {
        $this->searchParams['page'] = [
            'current' => $page,
            'size' => $limit,
        ];
    }

    /**
    * @param string $filter
    * @return void
    */
    private function parser(string $filter) : void
    {
        //equal
        if (Str::contains($filter, ':')) {
            $fields = explode(':', $filter);
            $this->filters[$fields[0]] = explode('|', $fields[1]);
        }

        //between
        if (Str::contains($filter, '¬')) {
            $fields = explode('¬', $filter);
            $delimiter = explode('|', $fields[1]);

            $this->filters[$fields[0]] = (object) [
                'from' => $delimiter[0],
                'to' => $delimiter[1],
            ];
        }

        //different from
        if (Str::contains($filter, '~')) {
            $fields = explode('~', $filter);
            $this->filters["none"][$fields[0]] = explode('|', $fields[1]);
        }

        //greater than
        if (Str::contains($filter, '>')) {
            $fields = explode('>', $filter);
            $this->filters["any"][$fields[0]] = [
                'from' => $fields[1],
            ];
        }

        //smaller than
        if (Str::contains($filter, '<')) {
            $fields = explode('<', $filter);
            $this->filters["any"][$fields[0]]['to'] = $fields[1];
        }
    }
}
