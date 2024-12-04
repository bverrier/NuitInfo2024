<?php
class SRetro
{
    private Session $session;
    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getRetroGamingFacts(): array
    {
        // Ces faits peuvent venir d'une base de données ou d'un fichier
        return [
            'goldenEra' => 'Les années 80 et 90 ont été l\'âge d\'or des jeux vidéo rétro.',
            'consoles' => [
                'Atari',
                'Nintendo Entertainment System (NES)',
                'Sega Genesis',
                'Super Nintendo'
            ],
            'legendaryGames' => [
                'Mario Bros',
                'Pac-Man',
                'The Legend of Zelda',
                'Sonic the Hedgehog'
            ],
            'statistics' => [
                'Pac-Man' => 'Plus de 400 000 bornes d\'arcade vendues dans le monde.',
                'Super Mario Bros' => '40 millions d\'exemplaires vendus à travers le monde.',
                'Sega Genesis' => 'Plus de 30 millions d\'unités vendues.'
            ]
        ];
    }
}