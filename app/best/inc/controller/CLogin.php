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
		$this->header->setFileCss('./css/login.css');
		$this->elemForm = new ElemForm();
	}

	/**
	 * @return string Formulaire de connexion login + mdp
	 */
	public function display(): string
	{
		return
			$this->header->getHeader("Connexion").
			'		<div id="log" class="col-sm-6 offset-3 card login mt-4">'."\n".
			'			<h1 class="p-4 mx-auto"><i class="fa-solid fa-user"></i> Connexion</h1>'."\n".
			'			<form class="p-4 mx-auto" method="post" autocomplete="off">'."\n".
			//<!-- Username input -->
			'				<div class="form-outline mb-4">'."\n".
			indentString($this->elemForm->getInputText('Login ou mail', 'login', 'login', null, null, true),5).
			'				</div>'."\n".

			//<!-- Password input -->
			'				<div class="form-outline mb-4">'."\n".
			'					<label class="form-label" for="mot_de_passe">Mot de passe</label>'."\n".
			'					<input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control form-control-sm" />'."\n".
			'				</div>'."\n".

			//<!-- 2 column grid layout for inline styling -->
			'				<div class="row mb-4">'."\n".
			'					<div class="col d-flex justify-content-center">'."\n".

			//<!-- Submit button -->
			'						<button type="submit" class="btn btn-primary btn-block" name="connection">Se connecter</button>'."\n".
			'						<a href="signup.php" class="btn btn-primary ms-4"> S\'inscrire ?</a>'."\n".
			'					</div>'."\n".
			'				</div>'."\n".
			'			</form>'."\n".
			'		</div>'."\n".
			$this->header->getEnd();
	}
}
