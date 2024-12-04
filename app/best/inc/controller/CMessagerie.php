<?php

require_once __DIR__ . '/MainController.php';
require_once __DIR__ . '/lib/ElemForm.php';
require_once __DIR__ . '/../service/SMessagerie.php';
require_once __DIR__ . '/../../lib/croissantLib.php';
require_once __DIR__ . '/../Session.php';

class CMessagerie extends MainController
{
	private Session $session;

	/**
	 * @param Session $session Session de l'utilisateur
	 */
	public function __construct(Session $session)
	{
		$this->session = $session;
		parent::__construct($session, 'Messagerie', null);

	}

	public function display(DatabaseMain $db): string
	{
		$sMessagerie = new SMessagerie($this->session);

		$html =
			'<div id="MyDataTable">' . "\n" .
			'	<table id="dataTable" class="table table-sm table-striped">' . "\n" .
			'		<thead>' . "\n" .
			'			<tr>' . "\n" .
			'				<th hidden>Id</th>' . "\n" .
			'				<th>De</th>' . "\n" .
			'				<th>À</th>' . "\n" .
			'				<th>Le</th>' . "\n" .
			'				<th hidden>Message</th>' . "\n" .
			'				<th>Message</th>' . "\n" .
			'			</tr>' . "\n" .
			'		</thead>' . "\n" .
			'		<tbody>' . "\n";

		$mails = $sMessagerie->getAllMail($db);

		foreach ($mails as $mail) {
			$html .=
				'			<tr>' . "\n" .
				'				<td hidden>' . htmlsecure($mail['id']) . '</td>' . "\n" .
				'				<td>' . htmlsecure($mail['usr_from']) . '</td>' . "\n" .
				'				<td>' . htmlsecure($mail['usr_to']) . '</td>' . "\n" .
				'				<td>' . htmlsecure($mail['time']) . '</td>' . "\n" .
				'				<td hidden>' . htmlsecure($mail['msg']) . '</td>' . "\n" .
				'				<td> <button class="btn btn-sm btn-outline-success showMessage" data-bs-toggle="modal" data-bs-target="#Messagerie" data-bs-whatever="@mdo"><i class="fa-solid fa-eye"></i></button></td>' . "\n";

			$html .=
				'			</tr>' . "\n";
		}

		$html .=
			'		</tbody>' . "\n" .
			'	</table>' . "\n" .
			'</div>' . "\n";
		return $html;

	}

	function getForm(?DatabaseMain $db, ?array $data): string
	{
		$html =
			'<form id="formMessagerie" method="post" class="col-sm-10 offset-1 mt-4">' . "\n" .
			indentString($this->elemForm->getInputToken($this->session->getUserToken()), 1) .
			'	<div class="row mb-4">' . "\n" .
			'		<div class="col-sm-3 form-group">' . "\n" .
			'			<select class="form-select" name="userTo">'."\n".
			'				<option selected>Destinataire</option>'."\n";

			$sAdmin = new SMessagerie($this->session);
			$users = $sAdmin->getAllLogin($db);

			foreach ($users as $user) {
				$html .=
					'				<option value="'.htmlsecure($user['id']).'">'.htmlsecure($user['login']).'</option>'."\n";
			}

			$html .=
			'			</select>'."\n".
			'		</div>' . "\n" .
			'		<div class="col-sm-10 form-group">' . "\n" .
			indentString($this->elemForm->getInputTextArea('Message', 'Message', 'inputMessage', null, null, true), 3) .
			'		</div>' . "\n" .
			'	</div>' . "\n" .
			'	<div class="row mb-4">' . "\n" .
			'		<div class="offset-5 col-sm-4 form-group">' . "\n" .
			indentString($this->elemForm->getBtnValidateForm('Messagerie'), 3) .
			'		</div>' . "\n" .
			'	</div>' . "\n" .
			'</form>' . "\n";

			  return $html;
	}
}