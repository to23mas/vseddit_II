<?php declare(strict_types=1);


namespace Tom\Table;


use Nette\Application\UI\Control;

class Column extends Control
{

    protected string $title;

    /**
     * Column constructor.
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Column
     */
    public function setTitle(string $title): Column
    {
        $this->title = $title;
        return $this;
    }
}