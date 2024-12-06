<?php
class SMeteo
{
    /**
     * @param Session $session
     */
    public function __construct(\Session $session)
    {

    }

    public function getWeatherData(): array
    {
        return [
            "Paris" => [
                "temperature" => 18,
                "condition" => "Ensoleillé",
                "icon" => "sunny",
                "forecast" => [
                    ["day" => "Lun", "temp" => 18],
                    ["day" => "Mar", "temp" => 20],
                    ["day" => "Mer", "temp" => 22],
                    ["day" => "Jeu", "temp" => 21],
                    ["day" => "Ven", "temp" => 19]
                ]
            ]
        ];
    }
}
