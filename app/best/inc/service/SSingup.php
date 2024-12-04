<?php

require_once __DIR__ . '/../../lib/ErrorForm.php';
require_once __DIR__ . '/../../lib/croissantLib.php';
require_once __DIR__ . '/../../inc/service/SAdmin.php';
require_once __DIR__ . '/../../inc/controller/CAdmin.php';
require_once __DIR__ . '/../../conf/config.php';
require_once __DIR__ . '/../../inc/service/SCalendar.php';
class SSingup
{

    /**
     * @var ErrorForm
     */
	private ErrorForm $erros;

    /**
     * @var Session
     */
	private Session $session;
    private SAdmin $SAdmin;
    private SMercredi $Smercredi;
	/**
	 * @function  init construct model param
	 */
	public function __construct(Session $session)
	{
		$this->erros = new ErrorForm();
		$this->session = $session;
        $this->SAdmin = new SAdmin($session);
        $this->Smercredi = new SMercredi($session);
	}
    // Function to check if the account status has changed
    function hasAccountStatusChanged($previousStatus, $newStatus) {
        return $previousStatus != $newStatus;
    }

// Function to check if the account has been activated
    function isAccountActivated($previousStatus, $newStatus) {
        return $previousStatus == 0 && $newStatus == 1;
    }

// Function to check if the account has been deactivated
    function isAccountDeactivated($previousStatus, $newStatus) {
        return $previousStatus == 1 && $newStatus == 0;
    }

    /**
     * Vérifie que le formulaire d'inscriprion est bon
     * @param DatabaseMain $db Base de données
     * @param array $post Le formulaire d'inscription
     * @return string
     * @throws DataBaseNotConnected
     * @throws InvalidSessionException
     * @throws SQLException
     * @throws Exception
     */
	public function signupUser(DatabaseMain $db, array $post): string
	{
		// Vérifier le nom
		if (!empty($post['nom'])) {
			if (mb_strlen($post['nom']) < 2 || mb_strlen($post['nom']) > 50) {
				$this->erros->addError('Le nom doit être compris entre 2 et 50 caractéres');
			} else {
				$data['nom'] = $post['nom'];
			}
		} else {
			$this->erros->addError('Le nom est obligatoire');
		}

		// Vérifier le prenom
		if (!empty($post['prenom'])) {
			if (mb_strlen($post['prenom']) < 2 || mb_strlen($post['prenom']) > 50) {
				$this->erros->addError('Le prénom doit être compris entre 2 et 50 caractéres');
			} else {
				$data['prenom'] = $post['prenom'];
			}
		} else {
			$this->erros->addError('Le prénom est obligatoire');
		}

		// Vérifier le courrier électronique
		if (!empty($post['email'])) {
			if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
				$this->erros->addError('L email n\'est valide');
			} else {
				$data['email'] = $post['email'];
			}
		} else {
			$this->erros->addError('L\'email est obligatoire');
		}

		// Vérifier le login
		if (!empty($post['login'])) {
			if (!empty($post['admin']) && $post['admin'] == 'addModifyForm') {
				$data['id'] = $post['id'];
				$e = $this->session::isLoginAvialable($db, $post['login'], $post['id']);
			} else {
				$e = $this->session::isLoginAvialable($db, $post['login'], null);
			}
			if (!empty($e)) {
				$this->erros->addError($e);
			} else {
				$data['login'] = $post['login'];
			}
		} else {
			$this->erros->addError('Le login est obligatoire');
		}

		// Vérifier le mot de passe
		if (!empty($post['motDePasse']) && !empty($post['confirmerMotDePasse'])) {
			$e = $this->session::isPassCorrectlyformatted($post['motDePasse'], $post['confirmerMotDePasse']);
			if (!empty($e)) {
				$this->erros->addError($e);
			} else {
				$data['mot_de_passe'] = $post['motDePasse'];
			}
		} else {
			if (empty($post['admin'])) {
				$this->erros->addError('Le mot de passe est obligatoire');
			}
		}
		if (!empty($_POST['admin']) && $_POST['admin'] == 'addModifyForm') {
			if($data['id'] == ADMINLOGIN){
				$this->erros->addError('Vous ne pouvez pas modifier le compte administrateur');
			}
		}

		if (!empty($post['admin'])) {
			if (!empty($post['activate']) && in_array($post['activate'], array('on', 'off'))) {
				$data['activate'] = $post['activate'];
			} else {
				$this->erros->addError('Choix d\'activation invalide');
			}
			if (!empty($post['isAdmin']) && in_array($post['isAdmin'], array('on', 'off'))) {
				$data['isAdmin'] = $post['isAdmin'];
			} else {
				$this->erros->addError('Choix d\'admin invalide');
			}
		}

		// Ajout du msg success si la requête, c’est bien passé
		if (count($this->erros->getArray()) == 0) {

			// Creation du compte utilisateur
			$res = $this->session::createAccount($db, $data);
			if (!$res instanceof DatabaseResult) {
				$this->erros->addError('Erreur au niveau de l\'insertion de l\'utilisateur');
				return getAlert('danger', $this->erros->getArray());
			}
			if (!empty($_POST['admin']) && $_POST['admin'] == 'addModifyForm') {
				// Main logic to handle status change
				$msg = 'Votre compte à été mis à jours '.$data['email'];
				sendEmail($db, ADMINLOGIN, $post['id'], $msg);
				return getAlert('success', array('L\'utilisateur a bien été modifié'));
			}
			$msg = 'Bienvenue chez croissantShow';
			$local_id = $db->getId();
			sendEmail($db, ADMINLOGIN, $local_id, $msg);
			return getAlert('success', array('L\'utilisateur a bien été ajouté'));
		}


		// Si le tableau contient des erreurs, on les affiche
		return getAlert('danger', $this->erros->getArray());
	}

}