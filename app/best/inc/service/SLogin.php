<?php

class SLogin
{

	/**
	 * @param array $post login or mail + mdp
	 * @param bool $test Pour savoir si nous sommes dans un test ou non
	 * @return bool
	 * @throws DataBaseNotConnected
	 * @throws SQLException
	 */
	public function connectUser(array $post, ?bool $test =false) :bool
	{
		if (!empty($post['login']) && !empty($post['mot_de_passe'])) {
			$loginOk = Session::login($post['login'], $post['mot_de_passe'], $test);
			if (!$loginOk) {
				// Erreur au niveau de la bdd
				throw new Exception('erreur de connexion (mauvais login, mauvais mot de passe ou compte désactivé)');
			}
			return true;
		}
		// Le login est le mdp sont obligatoires
		throw new Exception('Le mot de passe et le login sont obligatoires');
	}
}