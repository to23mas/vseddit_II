<?php
declare(strict_types=1);

namespace Tom\Table;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\ComponentModel\IComponent;
use Nette\SmartObject;
use Nette\Utils\Paginator;


use Tom\Application\Pals\Service\PalsService;
use Tom\Table\Column;
use Tracy\Debugger;

class Table extends Control {

    public const SORT_ASC = 'ASC',
        SORT_DESC = 'DESC';

    protected Paginator $paginator;

    /** @var bool @persistent */
    public bool $edit = false;

    /** @var int @persistent */
    public int $page = 1;

    /** @var string|null @persistent */
    public ?string $sortColumn = null;

    /** @var string|null @persistent */
    public ?string $sortOrder = null;

    protected array $data = [];

    protected string $primaryColumn = 'id';

    public $onDelete;

    public $onUpdate;

    public $onCreate;

    public $onLoadEditData;

    public $onEditForm;

    public function __construct(int $itemsPerPage) {
        $this->paginator = $this->createPaginator($itemsPerPage);
    }

    protected function createPaginator(int $itemsPerPage = 4): Paginator {
        $paginator = new Paginator();
        $paginator->setItemsPerPage($itemsPerPage);
        return $paginator;
    }

    public function render(): void {
        $this->paginator->setPage($this->page);

        $template = $this->template;

        $template->edit = $this->edit;
        $template->primaryColumn = $this->primaryColumn;
        $template->paginator = $this->paginator;
        $template->sortColumn = $this->sortColumn;
        $template->sortOrder = $this->sortOrder;
        $template->data = $this->selectDataForPage($this->sortData($this->data));
        $template->columns = $this->getColumns();

        $template->render(__DIR__ . '/templates/table.latte');
    }

    protected function selectDataForPage(array $data): array {
        $this->paginator->setItemCount(count($data));
        return array_slice($data, $this->paginator->getOffset(), $this->paginator->getItemsPerPage());
    }

    protected function sortData(array $data): array {
        if ($this->sortOrder === null || $this->sortColumn === null) {
            return $data;
        }
        $column = array_column($data, $this->sortColumn);
        array_multisort($column, $this->sortOrder === self::SORT_ASC ? SORT_ASC : SORT_DESC, $data);

        return $data;
    }

    public function getData(): array {
        return $this->data;
    }

    public function setData(array $data): Table {
        $this->data = $data;
        return $this;
    }

    public function addColumn(string $name, string $title): Table {
        $this->addComponent(new Column($title), $name);

        return $this;
    }

    public function getColumns(): \Iterator {
        return $this->getComponents(false, Column::class);
    }

    public function handleToPage(int $page): void {
        $this->redrawControl('table');
    }

    public function handleSortColumn(string $column): void {
        if ($column === $this->sortColumn && $this->sortOrder !== null) {
            if ($this->sortOrder === self::SORT_ASC) {
                $this->sortColumn = $column;
                $this->sortOrder = self::SORT_DESC;
            } else if ($this->sortOrder === self::SORT_DESC) {
                $this->sortOrder = null;
                $this->sortColumn = null;
            }
        } else {
            $this->sortColumn = $column;
            $this->sortOrder = self::SORT_ASC;
        }
        $this->redrawControl('table');
    }

    public function createComponentAddForm(): Form {
        $form = ($this->onEditForm)('Create');

        $form->onSuccess[] = [$this, 'addRecord'];

        return $form;
    }

    public function createComponentEditForm(): Form {
        $form = ($this->onEditForm)('Edit');

        $form->onSuccess[] = [$this, 'onSuccessEditForm'];

        return $form;
    }

    public function onSuccessEditForm(Form $form, array $values): void {
        if ($form->isSubmitted() === $form->getComponent('hide')) {
            $this->edit = false;
        } else {
            ($this->onUpdate)($values);
        }
        $this->redrawControl('table');
    }

    public function addRecord(Form $form, array $values): void {
        ($this->onCreate)($values);
        $this->redirect('this');
    }

    public function handleDelete(int $id): void {
        ($this->onDelete)($id);
        $this->redirect('this');
    }

    public function handleEdit(int $id): void {
        $this->edit = true;
        $data = ($this->onLoadEditData)($id);
        $this['editForm']->setDefaults($data);
//        $this['editForm']->
        $this->redrawControl('table');

    }

    /**
     * @param string $primaryColumn
     */
    public function setPrimaryColumn(string $primaryColumn): void {
        $this->primaryColumn = $primaryColumn;
    }
}