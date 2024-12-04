<?php
class Session
{
	private static $instance;

	/**
	 * Singleton
	 */
	private function __construct()
	{
		session_start();
	}

	/**
	 * @return Session La session de l’utilisateur
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new Session();
		}
		return self::$instance;
	}

	/**
	 * @return Database Connexion à la base de données
	 */
	// @codeCoverageIgnoreStart
	private static function connectDBlogin(?bool $test)
	{
		if($test){
			return new DatabaseTest();
		}
		return new Database();
	}

	/**
	 * @param string $login Le login de l’utilisateur
	 * @param string $pass Le mot de passe de l’utilisateur
	 * @param bool $test Pour savoir si nous sommes dans un test unitaire ou non
	 * @return bool si l'utilisateur peut se connecter
	 * @throws DataBaseNotConnected
	 * @throws SQLException
	 */
	public static function login(string $login, string $pass, ?bool $test = false)
	{
		$db = static::connectDBlogin($test);
		$val = self::internalCheckPass($db, $login, $pass, array('id', 'login', 'mail', 'nom', 'prenom', 'is_admin', 'is_activ'));
		if ($val !== false && $val !== null) {
			// Login ok

			// Lit les infos générales du compte
			$userInfos = array(
				'mail' => $val['mail'],
				'nom' => $val['nom'],
				'prenom' => $val['prenom'],
				'is_admin' => $val['is_admin'],
				'is_activ' => $val['is_activ'],
			);
			$db->close();
			if ($val) {
				// Ouvre une nouvelle session
				self::getInstance();
				$_SESSION['id'] = $val['id'];
				$_SESSION['connected'] = true;
				// Reprendre le login de la base de données permet d'avoir toujours les mêmes majuscules et minuscules.
				$_SESSION['login'] = $val['login'];
				$_SESSION['user_infos'] = $userInfos;
				//Ajout du token pour les attaque CSRF
				$_SESSION['token'] = uniqid(rand(), true);
			}
			return true;
		}
		return false;
	}

	/**
	 * @param DataBase $db Base de données
	 * @param array $post Le formulaire de l’utilisateur
	 * @return DatabaseResult Le resultat de l’inscription de la personne à la base de données
	 */
	public static function createAccount(DatabaseMain $db, array $post)
	{
		$tab = array(
			'login' => '\'' . $db->escape($post['login']) . '\'',
			'date_creation_compte' => '\'' . date('Y-m-d H:i:s') . '\'' ,
			'mail' => '\'' . $db->escape($post['email']) . '\'',
			'nom' => '\'' . $db->escape($post['nom']) . '\'',
			'prenom' => 	'\'' . $db->escape($post['prenom']) . '\'' ,
			'is_admin' => ((!empty($post['isAdmin']) && $post['isAdmin']=='on') ? '1' : '\'0\''),
			'is_activ' => ((!empty($post['activate']) && $post['activate']=='on') ? '1' : '\'1\'')
		);
		if (!empty($_POST['admin']) && $_POST['admin'] == 'addModifyForm') {
			$wheres = array(
				'id = '.$post['id']
			);
			$res = $db->update('users', $wheres, $tab);
		} else {
			$tab['mot_de_passe'] = '\'' . $db->escape(password_hash($post['mot_de_passe'], PASSWORD_DEFAULT)) . '\'';
			$res = $db->insert('users', $tab);
			$id = $db->getId();
		}

		// Prévoir envoie d'un mail pour une nouvelle inscription d'un utilisateur (voir avec le client);
		return $res;
	}

	/**
	 *    \brief Regarde si le mot de passe est utilisable. Vérifie les différents paramètres.
	 *    \param $newPass Le mot de passe à tester.
	 *    \param $repeatPass Répétition du mot de passe.
	 *    \returns null si tout va bien.
	 *    \returns un texte décrivant pourquoi ce mot de passe n’est pas utilisable pour un nouveau compte. Peut contenir des balises HTML
	 **/
	public static function isPassCorrectlyformatted(string $newPass, string $repeatPass)
	{
		if (strlen($newPass) < 8) {
			return 'mot de passe trop court, le minimum est de 8 caractères';
		}
		if (strlen($newPass) > 32) {
			return 'mot de passe trop long, le maximum est de 32 caractères';
		}

		$authorized = '!"#$%&\'()*+,-./:;<=>?@_';

		if (!preg_match('/^[0-9A-Za-z' . preg_quote($authorized, '/') . ']+$/', $newPass)) {
			$authList = '';
			$len = strlen($authorized) - 1;
			for ($i = 0; $i <= $len; $i++) {
				if ($i == $len) {
					$authList .= ' ' . 'et' . ' ';
				} elseif ($i) {
					$authList .= ', ';
				}
				$authList .= '<span class="toucheClavier">' . substr($authorized, $i, 1) . '</span>';
			}
			return sprintf('Pour un mot de passe, seuls les caractères alphanumériques, les symboles %s sont autorisés.', $authList);
		}

		if ($newPass != $repeatPass) {
			return 'Le mot de passe n\'est pas répété correctement';
		}
		return null;
	}

		/**
	*	\brief		Regarde si le login est utilisable pour un nouveau compte. Vérifie les différents paramètres.
	*	\param		$db		Connexion à la base de données.
	*	\param		$login	Le login à tester.
	*   \param      $id L'id de l'utilisateur s'il s'agit d'une modification.
	*	\returns	null si tout va bien.
	*	\returns	un texte décrivant pourquoi ce nom n’est pas utilisable pour un nouveau compte.
	**/
	public static function isLoginAvialable(DatabaseMain $db, string $login, ?string $id) {
		$len = empty($login)?0:mb_strlen($login);
		if (($len < 2) || ($len > 25)) {
			return 'La longueur du login doit être comprise entre 2 et 25';
		} elseif (!ctype_alnum($login)) {
			return 'Le login ne doit comporter que des caractères alphanumériques';
		} else {
			$sql = 'SELECT id FROM users WHERE LOWER(login)=LOWER(\''.$db->escape($login).'\')';
			$resLoginExists = $db->query($sql);
			if ($resLoginExists->numRows() && $resLoginExists->toList()[0]['id'] != $id)
				return 'Ce login existe, choisissez-en un autre';
		}
		return null;
	}

	/**
	 * @param DataBase $db Base de données
	 * @param string $login Le login de l’utilisateur
	 * @param string $pass Le mot de passe de l’utilisateur saisi
	 * @param array|null $otherCols Tableau des champs à récupérer de la base de données avec le mot de passe
	 *                  exemple ['mot_de_passe', 'login', 'is_admin', 'mail', ....]
	 * @return array|false|null
	 * @throws DataBaseNotConnected
	 * @throws SQLException
	 */
	public static function internalCheckPass(DatabaseMain $db, string $login, string $pass, ?array $otherCols)
	{
		if ($otherCols === null) {
			$otherColsSQL = 'mot_de_passe';
		} else {
			// On transforme le tableau en chaine de caractéres comme :$otherColsSQL = login,mail,pass....
			$otherColsSQL = implode(', ', array_merge(array('mot_de_passe'), $otherCols));
		}
		$sql = 'SELECT ' . $otherColsSQL . ' FROM users WHERE login=\'' . $db->escape($login) . '\' OR mail=\'' . $db->escape($login) . '\'';
		$res = $db->query($sql);
		if (!$res) {
			throw new SQLException('Erreur lors de la lecture des informations de l\'utilisateur');
		}

		// Si le login n’éxiste pas
		$val = $res->fetchAssoc();
		if ($val === false) {
			return false;
		}

		// Pas de mot de passe : refuse toujours
		if ($val !== null && $val['mot_de_passe'] == null) {
			return false;
		}

		// Mauvais mot de passe → refuser l’accès
		if ($val !== null && !password_verify($pass, $val['mot_de_passe'])) {
			return false;
		}
		unset($val['mot_de_passe']);
		return $val;
	}

	/**
	 * @return bool Est-ce que la session est valide
	 */
	function sessionIsValid()
	{
		if (!isset($_SESSION)) {
			return false;
		}
		if (!isset($_SESSION['id'])) {
			return false;
		}
		if (!isset($_SESSION['connected'])) {
			return false;
		}
		if (!isset($_SESSION['login'])) {
			return false;
		}
		if (empty($_SESSION['user_infos']['is_activ'])) {
			return false;
		}
		if (!isset($_SESSION['token'])) {
			return false;
		}

		return true;
	}

	/**
	 * @return bool Vérifier si l’utilisateur est un administrateur
	 */
	function isAdmin()
	{
		if ($this->sessionIsValid() && $_SESSION['user_infos']['is_admin']) {
			return true;
		}
		return false;
	}

	/**
	 * Destruction de la session de l’utilisateur
	 * @return void
	 */
	public function logout()
	{
		// Destruction de la session utilisateur
		unset($_SESSION['id']);
		unset($_SESSION['login']);
		unset($_SESSION['connected']);
		unset($_SESSION['user_infos']);
		unset($_SESSION['token']);
		$_SESSION =array();
		session_destroy();
	}

    /**
     * @return mixed
     * @throws InvalidSessionException
     */
	function getIdUser() {
		if (!$this->sessionIsValid()) {
            throw new InvalidSessionException();
        }
		return $_SESSION['id'];
	}

	/**
	*	\brief	Retourne le nom de login de l’utilisateur
	*	\throws	InvalidSessionException si la session est invalide.
	**/
	public function getUserLogin()
	{
		if (!$this->sessionIsValid())  {
            throw new InvalidSessionException();
        }
		return $_SESSION['login'];
	}

	public function getUserToken()
	{
		if (!$this->sessionIsValid())  {
			throw new InvalidSessionException();
		}
		return $_SESSION['token'];
	}

	/**
	 * @param string $formToken
	 * @return bool
	 * @throws InvalidSessionException
	 */
	public function isFormulaireGood(string $formToken) {
		if ($formToken === $this->getUserToken()) {
			return true;
		}
		return false;
	}
}

/// Exception soulevée en cas de session invalide
class InvalidSessionException extends Exception {};
