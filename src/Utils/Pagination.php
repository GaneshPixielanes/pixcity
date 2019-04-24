<?php

namespace App\Utils;

class Pagination
{
    private $index = 1;
    private $limit = 10;
    private $totalItems;
    private $totalPages;

    /**
     * Pagination constructor.
     * @param int $index
     * @param int $limit
     */
    public function __construct($index = 1, $limit = 10)
    {
        $this->index = $index;
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * @param mixed $totalItems
     */
    public function setTotalItems($totalItems)
    {
        $this->totalItems = intval($totalItems);
        $this->totalPages = ceil($this->totalItems / $this->limit);
    }

    /**
     * @return mixed
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param mixed $totalPages
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }

}