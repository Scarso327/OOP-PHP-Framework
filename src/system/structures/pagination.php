<?php

namespace System\Structures;

use System\Requests\Incoming;

class Pagination {

    private $table;
    private $page;
    private $totalPages;
    private $perPage;
    private $start;

    public function __construct($table, $perPage = 10, $page = 1) {
        // Attempted to use ?? and ? : but it didn't work...
        if (Incoming::I()->page && is_numeric(Incoming::I()->page)) {
            $page = Incoming::I()->page;
        }

        if (Incoming::I()->perPage && is_numeric(Incoming::I()->perPage)) {
            $perPage = Incoming::I()->perPage;
        }

        $this->totalPages = \System\DB::I()->Query("CEILING(COUNT(id) / " . $perPage . ") AS `pages` FROM " . $table . " WHERE active = '1'", array(), false)->pages;

        $this->table = $table;
        $this->page = min($page, $this->totalPages);
        $this->perPage = $perPage;
        $this->start = ($this->page - 1) * $perPage;
    }

    public function Do($where = "") {
        $start = max($this->start - 3, 1);

        return (object) array(
            "page" => $this->page,
            "amount" => $this->perPage,
            "start" => $start,
            "end" => min($start + 6, $this->totalPages),
            "results" => \System\DB::I()->Query("* FROM " . $this->table . " WHERE active = '1' " . $where . " LIMIT " . $this->start . ", " . ($this->start + $this->perPage))
        );
    }
}