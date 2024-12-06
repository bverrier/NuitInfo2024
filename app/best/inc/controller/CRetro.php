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


        $Cerveau = 'Rôle humain : <span style="font-family: \'Arial\', sans-serif;">Centre de contrôle et de coordination du corps.</span><br>Rôle océanique : <span style="font-family: \'Courier New\', monospace;">Les courants océaniques, comme le Gulf Stream, régulent les climats, transportent des nutriments et coordonnent les écosystèmes océaniques.</span>';
        $Coeur = 'Rôle humain : <span style="font-family: \'Arial\', sans-serif;">Pompe qui distribue le sang et les nutriments à tout le corps.</span><br>Rôle océanique : <span style="font-family: \'Courier New\', monospace;">Les marées, influencées par la gravité lunaire, sont les "battements" de l\'océan, assurant le brassage des eaux et la circulation des nutriments.</span>';
        $Estomac = "Rôle humain : <span style=\"font-family: 'Arial', sans-serif;\">Digestion et traitement des aliments pour extraire l'énergie.</span><br>Rôle océanique : <span style=\"font-family: 'Courier New', monospace;\">Les fonds océaniques jouent un rôle similaire en \"digérant\" les matières organiques tombées et en les recyclant dans l'écosystème.</span>";
        $Os = "Rôle humain : <span style=\"font-family: 'Arial', sans-serif;\">Soutien structurel et protection des organes internes.</span><br>Rôle océanique : <span style=\"font-family: 'Courier New', monospace;\">Les récifs coralliens forment la \"structure osseuse\" de l'océan, offrant un habitat et une protection à de nombreuses espèces marines.</span>";
        $Sang = "Rôle humain : <span style=\"font-family: 'Arial', sans-serif;\">Transporte l'oxygène, les nutriments et élimine les déchets.</span><br>Rôle océanique : <span style=\"font-family: 'Courier New', monospace;\">Les courants marins transportent des nutriments, de l'oxygène dissous et d'autres éléments vitaux pour la vie marine.</span>";
        $Poumon = "Rôle humain : <span style=\"font-family: 'Arial', sans-serif;\">Permet l'échange gazeux (oxygène, dioxyde de carbone).</span><br>Rôle océanique : <span style=\"font-family: 'Courier New', monospace;\">Le phytoplancton, comme des \"poumons océaniques,\" produit plus de 50% de l'oxygène de la planète via la photosynthèse.</span>";
        $Rein = "Rôle humain : <span style=\"font-family: 'Arial', sans-serif;\">Filtrent et éliminent les déchets et l'excès d'eau.</span><br>Rôle océanique : <span style=\"font-family: 'Courier New', monospace;\">Les mangroves et les marais salants filtrent l'eau, éliminant les toxines et prévenant l'érosion.</span>";
        $Muscle = "Rôle humain : <span style=\"font-family: 'Arial', sans-serif;\">Génère mouvement et force.</span><br>Rôle océanique : <span style=\"font-family: 'Courier New', monospace;\">Les vagues et la houle apportent énergie et dynamisme à l'océan, facilitant le mélange des couches d'eau.</span>";
        $Peau = "Rôle humain : <span style=\"font-family: 'Arial', sans-serif;\">Barrière protectrice et régulateur de température.</span><br>Rôle océanique : <span style=\"font-family: 'Courier New', monospace;\">La surface de l'océan régule les échanges thermiques avec l'atmosphère et agit comme une barrière entre l'eau et l'air.</span>";

        $html =
            '<div id="page" class="retro-container" style="display: flex; flex-direction: column; min-height: 100vh;">' . "\n" .
            '   <div class="retro-header" style="background-color: #2c3e50; text-align: center; font-size: 24px; flex-shrink: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px 20px;">' . "\n" .
            '      <h1 style="margin-bottom: 20px;">Retour vers le futur des corps et océans !</h1>' . "\n" .
            '      <button id="sendRetro" class="btn btn-retro" type="button" name="Back">La suite</button>' . "\n" .
            '   </div>' . "\n" .
            '   <div class="retro-item" style="flex-grow: 1; display: flex; justify-content: center; align-items: center; position: relative; padding: 20px;">' . "\n" .
            '       <img src="img/corps.png" alt="corps" class="corps" usemap="#bodymap">' . "\n" .
            '       <div class="highlight" style="position: absolute; top: 85px; left:345px; width: 160px; height: 160px; border-radius: 50%;">' . "\n" .
            '           <div class="info-box">' . $Cerveau . '</div>' . "\n" .
            '       </div>' . "\n" .
            '       <div class="highlight" style="position: absolute; top: 305px; left:345px; width: 160px; height: 160px; border-radius: 50%;">' . "\n" .
            '           <div class="info-box">' . $Coeur . '</div>' . "\n" .
            '       </div>' . "\n" .
            '       <div class="highlight" style="position: absolute; top: 520px; left:345px; width: 160px; height: 160px; border-radius: 50%;">' . "\n" .
            '           <div class="info-box">' . $Estomac . '</div>' . "\n" .
            '       </div>' . "\n" .
            '       <div class="highlight" style="position: absolute; top: 730px; left:345px; width: 160px; height: 160px; border-radius: 50%;">' . "\n" .
            '           <div class="info-box">' . $Os . '</div>' . "\n" .
            '       </div>' . "\n" .
            '       <div class="highlight" style="position: absolute; top: 925px; left:345px; width: 160px; height: 160px; border-radius: 50%;">' . "\n" .
            '           <div class="info-box">' . $Sang . '</div>' . "\n" .
            '       </div>' . "\n" .
            '       <div class="highlight" style="position: absolute; top: 115px; left:1130px; width: 160px; height: 160px; border-radius: 50%;">' . "\n" .
            '           <div class="info-box">' . $Poumon . '</div>' . "\n" .
            '       </div>' . "\n" .
            '       <div class="highlight" style="position: absolute; top: 350px; left:1130px; width: 160px; height: 160px; border-radius: 50%;">' . "\n" .
            '           <div class="info-box">' . $Rein . '</div>' . "\n" .
            '       </div>' . "\n" .
            '       <div class="highlight" style="position: absolute; top: 610px; left:1130px; width: 160px; height: 160px; border-radius: 50%;">' . "\n" .
            '           <div class="info-box">' . $Muscle . '</div>' . "\n" .
            '       </div>' . "\n" .
            '       <div class="highlight" style="position: absolute; top: 850px; left:1130px; width: 160px; height: 160px; border-radius: 50%;">' . "\n" .
            '           <div class="info-box">' . $Peau . '</div>' . "\n" .
            '       </div>' . "\n" .
            '       <map name="bodymap">' . "\n" .
            '           <area shape="circle" coords="59,69,25" alt="Cerveau" title="Cerveau">' . "\n" .
            '           <area shape="circle" coords="315,197,25" alt="Coeur" title="Coeur">' . "\n" .
            '           <area shape="circle" coords="362,325,25" alt="Os" title="Os">' . "\n" .
            '           <area shape="circle" coords="362,325,25" alt="Sang" title="Sang">' . "\n" .
            '           <area shape="circle" coords="362,325,25" alt="Poumon" title="Poumon">' . "\n" .
            '           <area shape="circle" coords="362,325,25" alt="Rein" title="Rein">' . "\n" .
            '           <area shape="circle" coords="362,325,25" alt="Muscle" title="Muscle">' . "\n" .
            '           <area shape="circle" coords="362,325,25" alt="Peau" title="Peau">' . "\n" .
            '       </map>' . "\n" .
            '   </div>' . "\n" .
            '   <div class="retro-footer" style="background-color: #2c3e50; text-align: center; font-size: 18px; flex-shrink: 0; padding: 20px;">'."\n".
            '      <div class="audio-container">'."\n".
            '         <figure>'."\n".
            '            <figcaption>Écouter le Podcast de Florian SEVELEC :</figcaption>'."\n".
            '            <audio id="audioPlayer" controls style="margin-top: 10px;">'."\n".
            '               <source src="video-podcast/FLORIAN_SEVELEC/PODCAST-AUDIO-Florian_Sevellec.m4a" type="audio/mp4">'."\n".
            '               Votre navigateur ne prend pas en charge l\'élément audio.'."\n".
            '            </audio>'."\n".
            '         </figure>'."\n".
            '         <figure>'."\n".
            '            <figcaption>Écouter le Podcast de Frédéric LE MOIGNE :</figcaption>'."\n".
            '            <audio id="audioPlayer" controls style="margin-top: 10px;">'."\n".
            '               <source src="video-podcast/FREDERIC_LE_MOIGNE/PODCAST-AUDIO-Frederic_Le_Moigne.m4a" type="audio/mp4">'."\n".
            '               Votre navigateur ne prend pas en charge l\'élément audio.'."\n".
            '            </audio>'."\n".
            '         </figure>'."\n".
            '      </div>'."\n".
            '      <div class="video-container">'."\n".
            '         <figure>'."\n".
            '            <figcaption>Vidéo 1 de Florian Sevellec</figcaption>'."\n".
            '            <video controls style="margin-top: 10px;">'."\n".
            '               <source src="video-podcast/FLORIAN_SEVELEC/Florian_Sevellec-004-SD_480p.mov" type="video/quicktime">'."\n".
            '               Votre navigateur ne prend pas en charge l\'élément vidéo'."\n".
            '            </video>'."\n".
            '         </figure>'."\n".
            '         <figure>'."\n".
            '            <figcaption>Vidéo 2 de Florian Sevellec</figcaption>'."\n".
            '            <video controls style="margin-top: 10px;">'."\n".
            '               <source src="video-podcast/FLORIAN_SEVELEC/Florian_Sevellec-Oona_Layolle-001-SD_480p.mov" type="video/quicktime">'."\n".
            '               Votre navigateur ne prend pas en charge l\'élément vidéo'."\n".
            '            </video>'."\n".
            '         </figure>'."\n".
            '         <figure>'."\n".
            '            <figcaption>Vidéo 1 de Frédéric Le Moigne</figcaption>'."\n".
            '            <video controls style="margin-top: 10px;">'."\n".
            '               <source src="video-podcast/FREDERIC_LE_MOIGNE/Frederic_Le_Moigne-Part_1-SD_480p.mov" type="video/quicktime">'."\n".
            '               Votre navigateur ne prend pas en charge l\'élément vidéo.'."\n".
            '            </video>'."\n".
            '         </figure>'."\n".
            '         <figure>'."\n".
            '            <figcaption>Vidéo 2 de Frédéric Le Moigne</figcaption>'."\n".
            '            <video controls style="margin-top: 10px;">'."\n".
            '               <source src="video-podcast/FREDERIC_LE_MOIGNE/Frederic_Le_Moigne-Part_2-SD_480p.mov" type="video/quicktime">'."\n".
            '               Votre navigateur ne prend pas en charge l\'élément vidéo.'."\n".
            '            </video>'."\n".
            '         </figure>'."\n".
            '      </div>'."\n".
            '   </div>'."\n".
            '</div>' . "\n";
        return $html;
    }
}
