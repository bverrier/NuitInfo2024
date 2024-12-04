<?php
require_once __DIR__ . '/MainController.php';
require_once __DIR__ . '/../service/SRetro.php';

class CRetro extends MainController
{
    private Session $session;
    private SRetro $sRetro;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->sRetro = new SRetro($session);

        parent::__construct($session, 'Retro', $this->sRetro);
    }

    public function getForm(?Database $db, ?array $data): string
    {
        return ''; // Pas de formulaire nécessaire
    }

    public function getCanvas(?Database $db): string
    {
        return ''; // Pas de canvas nécessaire
    }

    public function display(Database $db): string
    {
        // Récupération des faits sur le rétro gaming depuis le service
        $facts = $this->sRetro->getRetroGamingFacts();

        // Construction du contenu HTML en utilisant les faits dynamiquement
        $content = "
            <div class='retro-container'>
                <button id='sendRetro' class='btn btn-retro' type='button' name='Back'>⬅️ Retour</button>
                <h1>Le Rétro Gaming</h1>
                <p>Le rétro gaming fait référence à la pratique de jouer à des jeux vidéo anciens, souvent sur des consoles d'époque ou des émulateurs.</p>
                <h2>Faits intéressants :</h2>
                <ul>
                    <li><strong>Années d'or :</strong> {$facts['goldenEra']}</li>
                    <li><strong>Consoles emblématiques :</strong> " . implode(', ', $facts['consoles']) . ".</li>
                    <li><strong>Jeux légendaires :</strong> " . implode(', ', $facts['legendaryGames']) . ".</li>
                </ul>
                <h2>Quelques chiffres clés :</h2>
                <ul>";

        // Dynamique des statistiques de jeux
        foreach ($facts['statistics'] as $game => $statistic) {
            $content .= "<li><strong>{$game}</strong> : {$statistic}</li>";
        }

        $content .= "
                </ul>
                <h2>Quelques images du rétro gaming :</h2>
                <div class='retro-images'>
                    <img src='img/old_console.jpg' alt='Console rétro' />
                    <img src='img/pacman_arcade.jpg' alt='Pac-Man' />
                </div>
                <h2>Retour vers l'avenir :</h2>
                <p>Les jeux rétro connaissent un regain d'intérêt grâce aux plateformes de téléchargement et aux consoles de jeux classiques comme la Mini NES ou Mini SNES de Nintendo.</p>
            </div>
        ";

        return $content;
    }
}
