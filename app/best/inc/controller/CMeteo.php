<?php
require_once __DIR__ . '/MainController.php';
require_once __DIR__ . '/../service/SMeteo.php';

class CMeteo extends MainController
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
        parent::__construct($session, '', new SMeteo($session));
    }

    public function getForm(?Database $db, ?array $data): string
    {
        // Formulaire pour saisir une ville
        $html = '<form method="POST" action="">
                    <label for="city">Entrez une ville :</label>
                    <input type="text" id="city" name="city" required>
                    <button type="submit">Soumettre</button>
                </form>';

        return $html;
    }

    public function getCanvas(?Database $db): string
    {
        return '';
    }

    protected function display(Database $db): string
    {
        $html = '<h1>Bienvenue sur la page Météo</h1>';
        $html .= '<iframe src="https://www.meteoblue.com/en/weather/maps/widget?windAnimation=1&gust=1&satellite=1&cloudsAndPrecipitation=1&temperature=1&sunshine=1&extremeForecastIndex=1&geoloc=detect&tempunit=C&windunit=km%252Fh&lengthunit=metric&zoom=5&autowidth=auto"  frameborder="0" scrolling="NO" allowtransparency="true" sandbox="allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox" style="width: 100%; height: 720px"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href="https://www.meteoblue.com/en/weather/maps/index?utm_source=map_widget&utm_medium=linkus&utm_content=map&utm_campaign=Weather%2BWidget" target="_blank" rel="noopener">meteoblue</a></div>';
        return $html;
    }

}
