<?php

namespace App\Response;

class SuccessResponse extends BaseResponse
{
    private $results;

    /**
     *
     * @return object
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     *
     * @param object $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }
}
