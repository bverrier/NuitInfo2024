<?php
require_once __DIR__ . '/../../lib/Header.php';
require_once __DIR__ . '/../controller/lib/ElemForm.php';

class CLogin
{
    // Header
    private Header $header;
    private ElemForm $elemForm;

    /**
     * @function Constructeur de la page login
     */
    public function __construct()
    {
        $this->header = new Header();
        $this->header->setFileCss('./css/badlogin.css'); // Utilisation d'une feuille de style affreuse
        $this->elemForm = new ElemForm();
    }

    /**
     * @return string Formulaire de connexion login + mdp
     */
    public function display(): string
    {
        return
            $this->header->getHeader("Connexion").
            '		<div id="log" class="col-sm-6 offset-3 card login mt-4" style="background-color: #ff00ff; color: #00ffff; font-family: Impact, sans-serif; font-size: 25px; text-align: center;">'."\n".
            '			<h1 class="p-4 mx-auto" style="font-size: 100px; font-family: Comic Sans MS; text-shadow: 5px 5px 10px #000000; animation: blink 0.5s infinite, shake 0.1s infinite;">Connexion</h1>'."\n".
            '			<form class="p-4 mx-auto" method="post" autocomplete="off" style="background-color: yellow; padding: 30px; border-radius: 25px; box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.5);">'."\n".

            // Username input
            '				<div class="form-outline mb-4" style="transform: rotate(45deg); overflow: hidden;">'."\n".
            indentString($this->elemForm->getInputText('Login ou mail', 'login', 'login', null, null, true), 5).
            '				</div>'."\n".

            // Password input
            '				<div class="form-outline mb-4" style="transform: rotate(45deg); overflow: hidden;">'."\n".
            '					<label class="form-label" for="mot_de_passe" style="color: #ff0000; font-size: 30px;">Mot de passe</label>'."\n".
            '					<input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control form-control-sm" style="background-color: #ffff00; font-size: 30px; color: #ff0000;" />'."\n".
            '				</div>'."\n".

            // Submit button
            '				<div class="row mb-4" style="transform: rotate(-45deg);">'."\n".
            '					<div class="col d-flex justify-content-center">'."\n".
            '						<button type="submit" class="btn btn-primary btn-block" name="connection" style="animation: blink 0.5s infinite, shake 0.1s infinite; font-size: 40px;">Se connecter</button>'."\n".
            '						<a href="signup.php" class="btn btn-primary ms-4" style="font-size: 35px; color: #ff9900; animation: blink 1s infinite;">S\'inscrire ?</a>'."\n".
            '					</div>'."\n".
            '				</div>'."\n".
            '			</form>'."\n".
            '		</div>'."\n".
            $this->header->getEnd();
    }
}
?>
