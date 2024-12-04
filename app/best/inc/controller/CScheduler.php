<?php

namespace controller;

class CScheduler
{
    public function getResMailBody(array $data){
        return "
        <html>
        <body>
            <p>Bonjour " . $data['prenom'] . " " . $data['nom'] . ",</p>

            <p>
                Je vous informe que vous serez responsable du <strong>Mercredi Croissant</strong> qui aura lieu le 
                <strong>" . $data['date_event'] . "</strong>.
            </p>

            <p>
                Le nombre total de croissants à gérer pour cet événement est de : <strong>" . $data['nb_croissant_total'] . "</strong>.
            </p>

            <p>
                Veuillez ramener <strong>" . $data['nb_croissant_total'] . " croissants</strong> pour l'événement.
            </p>

            <p>Cordialement.</p>
        </body>
        </html>
    ";
    }


}