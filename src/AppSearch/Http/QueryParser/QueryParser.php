<?php

namespace Kanvas\Packages\AppSearch\Http\QueryParser;

use Baka\Elasticsearch\Query\FromClause;
use Baka\Support\Str;
use Baka\Support\Date;
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

        if ($filters = $this->request->getQuery('filters')) {
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
        foreach (explode(",", $sort) as $s) {
            $s = explode('|', $s);
            $this->searchParams['sort'][][$s[0]] = $s[1] ?? 'asc';
        }
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
        
        $this->searchParams['filters'] = $this->rearrangeFilters();
    }

    /**
     * If there are any or none parameters we configure the array
     * @return array
     */
    public function rearrangeFilters() : array
    {
        if (!isset($this->filters['any']) && !isset($this->filters['none'])) {
            return $this->filters;
        }
        $filters = [];
        foreach ($this->filters as $key => $value) {
            if (in_array($key, ['any','none'])) {
                $filters[$key] = $value;
                continue;
            }
            $filters['all'][$key] = $value;
        }
        $this->filters = $filters;
        return $this->filters;
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

            $from = Date::validate($delimiter[0]) ? $delimiter[0] : (int) $delimiter[0];
            $to = Date::validate($delimiter[1]) ? $delimiter[1] : (int) $delimiter[1];

            $this->filters[$fields[0]] = (object) [
                'from' => $from,
                'to' => $to,
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
                'from' => (int) $fields[1],
            ];
        }

        //smaller than
        if (Str::contains($filter, '<')) {
            $fields = explode('<', $filter);
            $this->filters["any"][$fields[0]]['to'] = (int) $fields[1];
        }
    }
}
