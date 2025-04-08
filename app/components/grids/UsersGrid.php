<?php

namespace Component\Grid;

use Nette\Application\UI\Control;
use Ublaboo\DataGrid\Components\DataGridPaginator\DataGridPaginator;
use Ublaboo\DataGrid\DataGrid;
use UsersModel;

class UsersGrid extends Control
{
    public function __construct(private UsersModel $usersModel) {
    }
    public function render()
	{
		$this->template->setFile(__DIR__.'/templates/default.latte');
		$this->template->render();
	}

    public function createComponentGrid($name){
		$grid = new DataGrid($this, $name);
        $translator = new \Ublaboo\DataGrid\Localization\SimpleTranslator([
            'ublaboo_datagrid.no_item_found_reset' => 'Žádné položky nenalezeny. Filtr můžete vynulovat',
			'ublaboo_datagrid.no_item_found' => 'Žádné položky nenalezeny.',
			'ublaboo_datagrid.here' => 'zde',
			'ublaboo_datagrid.items' => 'Položky',
			'ublaboo_datagrid.all' => 'všechny',
			'ublaboo_datagrid.from' => 'z',
			'ublaboo_datagrid.reset_filter' => 'Resetovat filtr',
			'ublaboo_datagrid.group_actions' => 'Hromadné akce',
			'ublaboo_datagrid.show_all_columns' => 'Zobrazit všechny sloupce',
			'ublaboo_datagrid.hide_column' => 'Skrýt sloupec',
			'ublaboo_datagrid.action' => 'Akce',
			'ublaboo_datagrid.previous' => 'Předchozí',
			'ublaboo_datagrid.next' => 'Další',
			'ublaboo_datagrid.choose' => '...',
			'ublaboo_datagrid.multiselect_choose' => '-',
			'ublaboo_datagrid.execute' => 'Provést',
			'ublaboo_datagrid.per_page_submit' => 'Provést',
			'ublaboo_datagrid.cancel' => 'Zrušit',
			'ublaboo_datagrid.save' => 'Uložit',
		]);
        $grid->setCustomPaginatorTemplate(__DIR__.'/templates/paginator.latte');

        $grid->setTranslator($translator);
		$grid->setRememberState(false); // Or turned on again: $grid->setRememberState(true);
        $grid->setAutoSubmit(false);
        $grid->setTemplateFile(__DIR__ .'/templates/custom_grid.latte');
        $grid->setColumnsHideable(true);
		$grid->setDataSource($this->usersModel->getRows());

        $grid->addColumnText('id', 'ID')
            ->setSortable()
            ->setFilterText();
            $grid->addColumnText('name', 'Jméno')
            ->setSortable()
            ->setFilterText();
        $grid->addColumnText('surname', 'Příjmení')
            ->setSortable()
            ->setFilterText();
        $grid->addColumnText('username', 'Uživatelské jméno')
            ->setSortable()
            ->setFilterText();
        $grid->addColumnText('email', 'E-mail')
            ->setSortable()
            ->setFilterText();

        $roleSelect = [
            'admin' => 'Admin',
            'user' => 'Uživatel',
        ];
        $grid->addColumnText('role', 'Role')
            ->setSortable()
            ->setFilterSelect($roleSelect)
            ->setPrompt("--vyberte--");

        if($this->getPresenter()->getUser()->isAllowed("editUser")){
            $grid->addAction('edit', 'Upravit')
                ->setRenderer(function ($item) {
                    return "<a class=\"btn btn-primary\" href=" . $this->getPresenter()->link('editUser', $item->id) . ">Upravit</a>";
                })
                ->setTemplateEscaping(false);
        }

    }
}