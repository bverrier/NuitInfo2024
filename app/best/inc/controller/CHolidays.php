<?php

namespace controller;
use Database;
use DataBaseNotConnected;
use MainController;
use Session;
use SHolidays;

require_once __DIR__ . '/MainController.php';
require_once __DIR__ . '/../Session.php';
require_once __DIR__ . '/../service/SHolidays.php';

class CHolidays extends MainController
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
        parent::__construct($session, 'Vacances', null);

    }

    /**
     * @throws DataBaseNotConnected
     * @throws Exception
     */
    protected function display(Database $db): string
    {
        $sHolidays = new SHolidays($this->session);
        $html =
            '<div id="MyDataTable">' . "\n" .
            '    <table id="dataTable" class="table table-sm table-striped">' . "\n" .
            '        <thead>' . "\n" .
            '            <tr>' . "\n" .
            '                <th hidden>Id</th>' . "\n" .
            '                <th>Vacances</th>' . "\n" .
            '                <th>Date de début</th>' . "\n" .
            '                <th>Date de fin</th>' . "\n" .
            '                <th>Année</th>' . "\n" .
            '                <th>Actions</th>' . "\n" .
            '            </tr>' . "\n" .
            '        </thead>' . "\n" .
            '        <tbody>' . "\n";
        $holidays = $sHolidays->getAllHolidays($db);
        foreach ($holidays as $holiday) {
            $html .=
                '            <tr>' . "\n" .
                '                <td hidden>' . htmlsecure($holiday['id']) . '</td>' . "\n" .
                '                <td>' . htmlsecure($holiday['Nom']) . '</td>' . "\n" .
                '                <td>' . htmlsecure($holiday['debut']) . '</td>' . "\n" .
                '                <td>' . htmlsecure($holiday['fin']) . '</td>' . "\n" .
                '                <td>' . htmlsecure($holiday['annee']) . '</td>' . "\n";

            $html .=
                '                <td>' . "\n" .
                '                    <button class="btn btn-sm btn-outline-info modify" data-bs-toggle="modal" data-bs-target="#Vacances" data-bs-whatever="@mdo"><i class="fa-solid fa-pencil-alt"></i></button>' . "\n" .
                '                    <button class="btn btn-sm btn-outline-danger delete" data-bs-toggle="modal" data-bs-target="#Vacances" data-bs-whatever="@mdo"><i class="fa-solid fa-trash"></i></button>' . "\n" .
                '                </td>' . "\n" .
                '            </tr>' . "\n";
        }
        $html .=
            '        </tbody>' . "\n" .
            '    </table>' . "\n" .
            '</div>' . "\n";
        return $html;

    }
    function getDelete():string
    {
        return
            '<div class="container-fluid mb-4 pb-4">'."\n".
            '	<div class="float-end d-flex gap-1">'."\n".
            indentString(
                '<button type="button" class="btn btn-sm delete btn-danger" data-bs-dismiss="modal" aria-label="Close">Non</button>'.
                $this->elemForm->getBtnDeleteElem('deleteYes', 'success'), 2).
            '	</div>'."\n".
            '</div>'."\n";
    }
    function getForm(?Database $db, ?array $data): string
    {
        if ($this->session->isAdmin()) {
            $startYear = date('Y') . '-01-01'; // January 1st of the current year
            $endYear = date('Y') . '-12-31';   // December 31st of the current year
            return
                '<form id="formHoliday" method="post" class="col-sm-10 offset-1 mt-4 d-flex flex-column gap-4 ">' . "\n" .
                '   <div id="holidays-list" style="max-height: 400px; overflow-y: auto; overflow-x: hidden;">' . "\n" .

                '   <div class="form-group p-2 shadow-sm p-3 mb-5 bg-white rounded">' . "\n" .
                '    <div class="row mb-6">' . "\n" .
                '        <div class="offset-1 col-sm-8">' . "\n" .
                indentString($this->elemForm->getInputText('Nom des vacances', 'Nom', 'holidayName', '', null, true), 3) . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .
                '    <div class="d-flex" style="gap:0.5em">' . "\n" .
                '        <div class="offset-1 col-sm-4 ">' . "\n" .
                indentString($this->elemForm->getInputDate('Du', 'debut', 'debut', '', '', $startYear, $endYear), 3) . "\n" .
                '    </div>' . "\n" .
                '        <div class="col-sm-4 ">' . "\n" .
                indentString($this->elemForm->getInputDate('Au', 'fin', 'fin', '', '', $startYear, $endYear), 3) . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .
                '    <div class="row mt-6">' . "\n" .
                '        <div class="offset-1 col-sm-8">' . "\n" .
                '<button class="btn btn-primary js-add--holiday-row"><i class="fa-solid fa-plus"></i> Ajouter une période </button>' . "\n" .
                '    </div>' . "\n" .
                '        <div class="offset-1 col-sm-8">' . "\n" .
                $this->elemForm->getBtnValidateForm("Holidays") . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .


                '</form>' . "\n";
        }
        return "";
    }
    function getModifForm(?Database $db, ?array $data): string
    {
        if ($this->session->isAdmin()) {
            $startYear = date('Y') . '-01-01'; // January 1st of the current year
            $endYear = date('Y') . '-12-31';   // December 31st of the current year
            return
                '<form id="formHoliday" method="post" class="col-sm-10 offset-1 mt-4 d-flex flex-column gap-4 ">' . "\n" .
                '   <div id="holidays-list" style="max-height: 400px; overflow-y: auto; overflow-x: hidden;">' . "\n" .

                '   <div class="form-group p-2 shadow-sm p-3 mb-5 bg-white rounded">' . "\n" .
                '    <div class="row mb-6">' . "\n" .
                '        <div class="offset-1 col-sm-8">' . "\n" .
                indentString($this->elemForm->getInputText('Nom des vacances', 'Nom', 'holidayName', $data[0]['Nom'], null, true), 3) . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .
                '    <div class="d-flex" style="gap:0.5em">' . "\n" .
                '        <div class="offset-1 col-sm-4 ">' . "\n" .
                indentString($this->elemForm->getInputDate('Du', 'debut', 'debut', $data[0]['debut'], '', $startYear, $endYear), 3) . "\n" .
                '    </div>' . "\n" .
                '        <div class="col-sm-4 ">' . "\n" .
                indentString($this->elemForm->getInputDate('Au', 'fin', 'fin', $data[0]['fin'], '', $startYear, $endYear), 3) . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .
                '    <div class="row mt-6">' . "\n" .
                '        <div class="offset-1 col-sm-8">' . "\n" .
                $this->elemForm->getBtnValidateForm("Holidays") . "\n" .
                '    </div>' . "\n" .
                '    </div>' . "\n" .


                '</form>' . "\n";
        }
        return "";
    }
}