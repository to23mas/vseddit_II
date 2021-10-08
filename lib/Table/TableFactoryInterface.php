<?php declare(strict_types=1);


namespace Tom\Table;


interface TableFactoryInterface
{
    public function create(int $itemsPerPage = 4): Table;
}