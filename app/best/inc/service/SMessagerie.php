<?php

require_once __DIR__ . '/../model/MMessagerie.php';
require_once __DIR__ . '/../Session.php';
require_once __DIR__ . '/../../lib/ErrorForm.php';
require_once __DIR__ . '/../../lib/croissantLib.php';

class SMessagerie
{

	private MMessagerie $model;

	private Session $session;

	private ErrorForm $errorForm;

	public function __construct(Session $session) {

		$this->session = $session;
		$this->model = new MMessagerie();
		$this->errorForm = new ErrorForm();
	}

	/**
	 * Return all message to a single users
	 * @param DatabaseMain $db
	 * @return array|null
	 * @throws InvalidSessionException
	 */
	public function getAllMail(DatabaseMain $db) : ?array
	{
		if ($this->session->sessionIsValid()) {
			$res = $this->model->getAllMail($db, $this->session->getIdUser());
			if (!$res instanceof DatabaseResult) {
				throw new Exception("Erreur de la base de données");
			}
			return $res->toList();
		}
		return [];
	}

	/**
	 * Return all logins
	 * @param DatabaseMain $db
	 * @return array|null
	 * @throws Exception
	 */
	public function getAllLogin(DatabaseMain $db) : ?array
	{
		if ($this->session->sessionIsValid()) {
			$res = $this->model->getAllLogin($db);
			if (!$res instanceof DatabaseResult) {
				throw new Exception("Erreur de la base de données");
			}
			return $res->toList();
		}
		return [];
	}

	public function checkForm(DatabaseMain $db, array $data) : String
	{
		if (!$this->session->isFormulaireGood($data['token'])) {
			return getAlert('danger', array('Session invalide'));
		}
		if (!ctype_digit($data['userTo'])) {
			$this->errorForm->addError('Utilisateur invalide');
		}
		if (empty($data['Message'])) {
			$this->errorForm->addError('Vous ne pouvez pas envoyer de mail vide');
		}

		if (count($this->errorForm->getArray()) == 0) {
			//On ajoute
			$val = array(
				"usr_to" => '\''.$db->escape($data['userTo']).'\'',
				"usr_from" => '\''.$db->escape($this->session->getIdUser()).'\'',
				"msg" => '\''.$db->escape($data['Message']).'\'',
			);

			$res = $db->insert('messagerie', $val);
			if (!$res instanceof DatabaseResult) {
				return getAlert('danger', array('Erreur d\'insertion'));
			}

		} else {
			return getAlert('danger', $this->errorForm->getArray());
		}

		return getAlert('success', array('Mail envoyé avec succès'));
	}
}