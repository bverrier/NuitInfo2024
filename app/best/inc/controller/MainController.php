<?php

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../../lib/croissantLib.php';
require_once __DIR__ . '/../../inc/Page.php';
require_once __DIR__ . '/lib/ElemForm.php';

abstract class MainController
{
	/**
	 * @var string
	 */
	protected string $title;

	/**
	 * @var ElemForm
	 */
	protected ElemForm $elemForm;

	/**
	 * @var mixed
	 */
	protected $service;

	/**
	 * @var Page
	 */
	protected Page $page;

	/**
	 * @param mixed $service elle n’est pas échappée.
	 * @function init controller
	 */
	public function __construct(Session $session, string $title, $service)
	{
		$this->title = $title;
		$this->elemForm = new ElemForm();
		$this->page = new Page($session, $this->title);
		$this->service = $service;
	}

	/**
	 * @param DatabaseMain $db Base de données
	 * @param array|null $data Les valeurs envoyées en $_POST par l’utilisateur
	 * @param string|null $msg
	 * @param bool $indicateur
	 * @return string La page
	 * @throws InvalidSessionException
	 */
	public function getDocument(DatabaseMain $db, ?array $data, ?string $msg): string
	{
		$content = $msg;
		if (empty($msg)) {
            $content = $this->getModal($db, $data, 'Ajouter '.$this->title, $this->title);
			$content .= $this->display($db);
		}
		return $this->page->getDocument("NuitInfo - " . $this->title, $content, true);
	}

	/**
	 * @return string contenue de la page
	 */
	abstract protected function display(Database $db): string;

    abstract function getForm(?Database $db, ?array $data): string;

    protected function getModal(?Database $db, ?array $data, string $titreModal, string $id): string
    {
        return getModal($this->getForm($db,$data), $titreModal, $id);
    }


}