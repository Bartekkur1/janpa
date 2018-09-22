<?php
require_once "QueryBuilder.php";

class Model
{
    function __construct()
    {
        $this->qb = new QueryBuilder();
    }
}

