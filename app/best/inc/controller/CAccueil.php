<?php
require_once __DIR__ . '/MainController.php';
require_once __DIR__ . '/../service/SAccueil.php';

class CAccueil extends MainController
{

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
        parent::__construct($session, 'Accueil', new SAccueil($session));
    }

    public function getForm(?Database $db, ?array $data): string
    {
        return '';
    }

    public function getCanvas(?Database $db): string
    {
        return '';
    }

    protected function display(Database $db): string
    {
        return '';
    }
}