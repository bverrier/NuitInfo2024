<?php

require_once __DIR__ . '/../model/MAdmin.php';
require_once __DIR__ . '/../Session.php';
require_once __DIR__ . '/../../lib/ErrorForm.php';
require_once __DIR__ . '/../../lib/croissantLib.php';
class SAdmin
{

	private MAdmin $model;

	private Session $session;

	private ErrorForm $errorForm;

	public function __construct(Session $session) {

		$this->session = $session;
		$this->model = new MAdmin();
		$this->errorForm = new ErrorForm();
	}

	/**
	 * Vérifie que la requete est bonne
	 * @param DatabaseMain $db
	 * @return array|null
	 * @throws DataBaseNotConnected
	 */
	public function getAllUsers(DatabaseMain $db) : ?array
	{
		if ($this->session->sessionIsValid() && $this->session->isAdmin()) {
			$res = $this->model->getAllUsers($db);
			if (!$res instanceof DatabaseResult) {
				throw new Exception("Erreur de la base de données");
			}
			return $res->toList();
		}
		return [];
	}

    /**
     * Vérifie que la requete est bonne
     * @param DatabaseMain $db
     * @return array|null
     * @throws DataBaseNotConnected
     */
    public function getInfoUser(DatabaseMain $db, string $id) : ?array
    {
        if ($this->session->sessionIsValid() && $this->session->isAdmin()) {
            $res = $this->model->getInfoUser($db, $id);
            if (!$res instanceof DatabaseResult) {
                throw new Exception("Erreur de la base de données");
            }
            return $res->toList();
        }
        return [];
    }



}