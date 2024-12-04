<?php

class CSignup
{
	private Header $header;

	/**
	 * Constructeur de la page d'inscription
	 */
	public function __construct()
	{
		// Le header de la page
		$this->header = new Header();
		$this->header->setFileCss("css/login.css");
	}

	/**
     * @return string formulaire d'inscription
     */
    public function display() {
        return
            $this->header->getHeader("Inscription").
			'		<div id="log" class="col-sm-6 offset-3 card login" style="margin-top: 5%">'."\n".
			'			<h1 class="p-4 mx-auto"><i class="fa-solid fa-sign-in"></i> S\'inscrire</h1>'."\n".
			'			<form method="post" class="p-4 col-sm-6 offset-3 text-center">'."\n".
			'				<input type="hidden" class="form-control" id="login" name="signup" value="signup">'."\n".
			'				<div class="mb-3">'."\n".
			'					<label for="login" class="form-label">Login *</label>'."\n".
			'					<input type="text" class="form-control" id="login" name="login" required>'."\n".
			'				</div>'."\n".
			'				<div class="mb-3">'."\n".
			'					<label for="nom" class="form-label">Nom *</label>'."\n".
			'					<input type="text" class="form-control" id="nom" name="nom" required>'."\n".
			'				</div>'."\n".
			'				<div class="mb-3">'."\n".
			'					<label for="prenom" class="form-label">Prénom *</label>'."\n".
			'					<input type="text" class="form-control" id="prenom" name="prenom">'."\n".
			'				</div>'."\n".
			'				<div class="mb-3">'."\n".
			'					<label for="email" class="form-label">Email *</label>'."\n".
			'					<input type="email" class="form-control" id="email" name="email" required>'."\n".
			'				</div>'."\n".
			'				<div class="mb-3">'."\n".
			'					<label for="motDePasse" class="form-label">Mot de passe *</label>'."\n".
			'					<input type="password" class="form-control" id="motDePasse" name="motDePasse" required>'."\n".
			'				</div>'."\n".
			'				<div class="mb-3">'."\n".
			'					<label for="confirmerMotDePasse" class="form-label">Confirmer le mot de passe *</label>'."\n".
			'					<input type="password" class="form-control" id="confirmerMotDePasse" name="confirmerMotDePasse" required>'."\n".
			'				</div>'."\n".
			'				<a href="login.php" class="btn btn-danger ms-4"> Annuler</a>' ."\n".
			'				<button type="submit" name="inscrire" class="btn btn-primary">S\'inscrire</button>'."\n".
			'			</form>'."\n".
			'		</div>'."\n".
	        $this->header->getEnd();
    }
}