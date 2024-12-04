<?php

require_once __DIR__ . '/MainController.php';
require_once __DIR__ . '/../service/SAccueil.php';
require_once __DIR__ . '/../../inc/controller/lib/ElemForm.php';
require_once __DIR__ . '/../service/SFindNextResp.php';

class CAccueil extends MainController
{

    private $sAccueil;
    protected ElemForm $elemForm;

    public function __construct(Session $session, SAccueil $sAccueil = null)
    {
        $this->session = $session;
        $this->sAccueil = $sAccueil ?? new SAccueil();
        $this->elemForm = new ElemForm();
        parent::__construct($session, 'Accueil', new SFindNextResp());
    }

    public function display($db): string
    {
        $content =
    '<div class="container mt-5">'."\n".
	'	<h1>Liste des prochains Croissant\'Show</h1>'."\n".
	'	<div id="dataTableAcceuil">'."\n".
	'		<table id="dataTable" class="table table-striped table-sm">'."\n".
	'			<thead>'."\n".
	'				<tr>'."\n".
	'					<th hidden>id</th>'."\n".
	'					<th hidden>iAmPresent</th>'."\n".
	'					<th hidden>myNbCroissant</th>'."\n".
	'					<th>Date</th>'."\n".
	'					<th>Responsable</th>'."\n".
	'					<th>Details</th>'."\n".
	'				</tr>'."\n".
	'			</thead>'."\n".
	'			<tbody>'."\n";

        $events = $this->sAccueil->getEvents($db);
        foreach ($events as $event) {
            $participants = array_filter($event['participants'], function ($participant) {
               return $participant['is_present'];
            });

            $responsable = sizeof($participants) >= 2 ? array_filter($participants, function ($participant) {
                return $participant['is_responsable'];
            }) : [];
            $responsable = array_values($responsable)[0];

            $responsable_fullname = empty($responsable) ? "Aucun" : htmlsecure($responsable['nom']) . ' ' . htmlsecure($responsable['prenom']);
            $date_event = htmlsecure($event['date_event']);
			$event_id = htmlsecure($event['event_id']);

            $i_am_present = false;
            $my_nb_croissant = 0;
            foreach ($participants as $participant) {
                if ($participant['id_user'] == $this->session->getIdUser()) {
                    $i_am_present = $participant['is_present'];
                    $my_nb_croissant = $participant['nb_croissant'];
                    break;
                }
            }

            $content .=
        		'				<tr>'."\n".
            	'					<td hidden>' . $event_id . '</td>'."\n".
            	'					<td hidden>' . $i_am_present . '</td>'."\n".
            	'					<td hidden>' . $my_nb_croissant . '</td>'."\n".
            	'					<td>' . $date_event . '</td>'."\n".
            	'					<td>' . $responsable_fullname . '</td>'."\n".
            	'					<td>'."\n".
                '						<button class="btn btn-sm btn-outline-success open-modal showInfo" data-bs-toggle="modal" data-bs-target="#Accueil" data-event="' . htmlsecure($event['event_id']) . '">'."\n".
				'							<i class="fa-solid fa-eye"></i>'."\n".
                '						</button>'."\n".
            	'					</td>'."\n".
        		'				</tr>'."\n";
        }

        $content .=
        '			</tbody>'."\n".
        '		</table>'."\n".
		'	</div>'."\n".
		'</div>'."\n";

        $modalContent =

			indentString($this->getForm(null, null), 1);

        return $content;
    }

    function getForm(?Database $db, ?array $data): string
    {
        return
            '<p><strong>Date:</strong> <span id="detailsEventDate"></span></p>'."\n".
            '<p class="text-center"><span style="font-weight: bold; color: red" id="messageEvent"></span></p>'."\n".
            '<div id="modalContent">'."\n".
            '      <p><strong>Responsable:</strong> <span id="detailsEventResponsable"></span></p>'."\n".
            '      <p><strong>Participants:</strong></p>'."\n".
            '      <div style="height: 300px; overflow: auto">'."\n".
            '           <table id="detailsEventParticipants" class="table table-striped table-sm">'."\n".
            '                <thead>'."\n".
            '	                <tr>'."\n".
            '                      <th>Nom</th>'."\n".
            '                      <th>Nb Croissants</th>'."\n".
            '                      <th>Présent?</th>'."\n".
            '                   </tr>'."\n".
            '                 </thead>'."\n".
            '                 <tbody></tbody>'."\n".
            '           </table>'."\n".
            '      </div>'."\n".
            '</div>'."\n".
            '<form id="formValiderPresenceEvent" method="post">'."\n".
            indentString($this->elemForm->getInputToken($this->session->getUserToken()),1) .
            '<div class="row">'."\n".
            '<div class="text-center col-sm-3 offset-4">'."\n".
            $this->elemForm->getInputText("Nb Croissants souhaités", "nb_croissant", "form_nb_croissant", null, null, true) .
            '</div>'."\n".
            '</div>'."\n".
            '<p class="text-center form-group">Je serai Présent:</p>' . "\n".
            '<div class="row">'."\n".
            '<div class="col-sm-2 offset-4">'."\n".
            indentString($this->elemForm->getRadioElem("Oui", "present_oui", "present", "1", false),3) .
            '</div>'."\n".
            '<div class="col-sm-2">'."\n".
            indentString($this->elemForm->getRadioElem("Non", "present_non", "present", "0", true), 3) .
            '</div>'."\n".
            '</div>'."\n".
            '<div class="offset-5 form-group">'."\n".
            indentString($this->elemForm->getBtnValidateForm("ValiderPresenceEvent"), 2) .
            '</div>'."\n".
            '</form>'."\n";
    }
}