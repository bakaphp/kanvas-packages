<?php

declare(strict_types=1);

namespace Kanvas\Packages\AppSearch\Contracts;

use Phalcon\Http\Response;

trait SearchableControllerTrait
{
    /**
     * Overwrite the index for searching.
     *
     * @return Response
     */
    public function index() : Response
    {
        $searchParams = [
            'page' => [
                'current' => $this->request->getQuery('page', 'int', 1),
                'size' => $this->request->getQuery('limit', 'int', 25),
            ]
        ];

        $engine = $this->request->getQuery('index', 'string', getenv('ELASTIC_APP_DEFAULT_ENGINE'));
        $searchString = $this->request->getQuery('q', 'string');

        $results = $this->elasticApp->search(
            $engine,
            $searchString,
            $searchParams
        );

        //overwrite the result
        $results['results'] = $this->parseResults($engine, $results['results']);

        return $this->response(
            $results
        );
    }

    /**
     * Get the suggestion strings from a query.
     *
     * @param string $name
     *
     * @return Response
     */
    public function suggestion(string $query) : Response
    {
        $engine = $this->request->getQuery('index', 'string', getenv('ELASTIC_APP_DEFAULT_ENGINE'));

        $results = $this->elasticApp->querySuggestion(
            $engine,
            strip_tags(urldecode($query))
        );

        if (!empty($results)) {
            foreach ($results['results']['documents'] as $key => $result) {
                $results['results']['documents'][$key]['suggestion'] = strtok(strip_tags($result['suggestion']), ',');
            }
        }

        return $this->response(
            $results
        );
    }

    /**
     * Parse the result set to a storm type.
     *
     * @param string $engine
     * @param array $results
     *
     * @return array
     */
    abstract public function parseResults(string $engine, array $results) : array;
}
