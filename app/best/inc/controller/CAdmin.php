<?php

require_once __DIR__ . '/MainController.php';
require_once __DIR__ . '/lib/ElemForm.php';
require_once __DIR__ . '/../service/SAdmin.php';
require_once __DIR__ . '/../../lib/croissantLib.php';
require_once __DIR__ . '/../Session.php';

class CAdmin extends MainController
{

	private Session $session;

	/**
	 * @param Session $session Session de l'utilisateur
	 */
	public function __construct(Session $session)
	{
		$this->session = $session;
		parent::__construct($session, 'Admin', null);

	}

    /**
     * @throws DataBaseNotConnected
     */
    public function display(DatabaseMain $db): string
	{
        $sAdmin = new SAdmin($this->session);

		$html =
			'<div id="MyDataTable">'."\n".
            '    <table id="dataTable" class="table table-sm table-striped">'."\n".
            '        <thead>'."\n".
            '            <tr>'."\n".
            '                <th hidden>Id</th>'."\n".
            '                <th>Login</th>'."\n".
            '                <th>Prénom</th>'."\n".
            '                <th>Nom</th>'."\n".
            '                <th>Mail</th>'."\n".
            '                <th>Date de création du compte</th>'."\n".
            '                <th class="text-center">Admin</th>'."\n".
            '                <th class="text-center">Activé</th>'."\n".
            '                <th class="text-center">Croissants achetés</th>'."\n".
            '                <th>Actions</th>'."\n".
            '            </tr>'."\n".
            '        </thead>'."\n".
            '        <tbody>'."\n";

        $users = $sAdmin->getAllUsers($db);

        foreach ($users as $user) {
            $html .=
            '            <tr>'."\n".
            '                <td hidden>' . htmlsecure($user['id']) . '</td>' ."\n".
            '                <td>' . htmlsecure($user['login']) . '</td>' ."\n".
            '                <td>' . htmlsecure($user['prenom']) . '</td>'."\n".
            '                <td>' . htmlsecure($user['nom']) . '</td>' ."\n".
            '                <td>' . htmlsecure($user['mail']) . '</td>'."\n".
            '                <td>' . htmlsecure($user['date_creation_compte']) . '</td>'."\n".
            '                <td class="text-center"><i class="fa-solid ' .($user['is_admin'] ? 'fa-check text-success':'fa-close') . '"></i></td>'."\n".
            '                <td class="text-center"><i class="fa-solid ' .($user['is_activ'] ? 'fa-check text-success':'fa-close') . '"></i></td>'."\n".
            '                <td class="text-center">' . htmlsecure($user['croissant_buy']) . '</td>' ."\n";

            $html .=
                '                <td>' ."\n".
                '                    <button class="btn btn-sm btn-outline-info modify" data-bs-toggle="modal" data-bs-target="#Admin" data-bs-whatever="@mdo"><i class="fa-solid fa-pencil-alt"></i></button>' ."\n".
                '                </td>'. "\n".
                '            </tr>'."\n";
        }

        $html .=
            '        </tbody>'."\n".
            '    </table>'."\n".
            '</div>'."\n";
		return $html;

	}

    /**
     * @throws InvalidSessionException
     */
    function getForm(?Database $db, ?array $data): string
    {
        if ($this->session->isAdmin()){
            return
            '<form id="formAdmin" method="post" class="col-sm-10 offset-1 mt-4">'."\n".
            indentString($this->elemForm->getInputToken($this->session->getUserToken()), 1).
            '	<div class="row mb-4">'."\n".
            '		<div class="offset-2 col-sm-4 form-group">'."\n".
            indentString($this->elemForm->getInputText('Login', 'login', 'inputLogin', (!empty($data[0]["login"]) ? htmlsecure($data[0]["login"]) : null),null,true), 3).
            '		</div>'."\n".
            '		<div class="col-sm-4 form-group">'."\n".
            indentString($this->elemForm->getInputText('Nom', 'nom', 'inputNom', (!empty($data[0]["nom"]) ? htmlsecure($data[0]["nom"]) : null),null,true), 3).
            '		</div>'."\n".
            '	</div>'."\n".
            '	<div class="row mb-4">'."\n".
            '		<div class="offset-2 col-sm-4 form-group">'."\n".
            indentString($this->elemForm->getInputText('Prénom', 'prenom', 'inputPrenom', (!empty($data[0]["prenom"]) ? htmlsecure($data[0]["prenom"]) : null), null,true), 3).
            '		</div>'."\n".
            '		<div class="col-sm-4 form-group">'."\n".
            indentString($this->elemForm->getInputMail('Mail', 'email', 'inputMail', (!empty($data[0]["mail"]) ? htmlsecure($data[0]["mail"]) : null),null), 3).
            '		</div>'."\n".
            '	</div>'."\n".
            '	<div class="row mb-4">'."\n".
            '		<div class="offset-2 col-sm-4 form-group">'."\n".
            '			<label for="motDePasse">Mot de passe</label>' . "\n" .
            '			<input type="password" class="form-control form-control-sm" name="motDePasse" id="inputMotDePasse" required>' . "\n".
            '		</div>'."\n".
            '		<div class="col-sm-4 form-group">'."\n".
            '			<label for="motDePasse">Confirmer mot de passe</label>' . "\n" .
            '			<input type="password" class="form-control form-control-sm" name="confirmerMotDePasse" id="inputConfirmerMotDePasse" required>' . "\n".
            '		</div>'."\n".
            '	</div>'."\n".
            '	<div class="row mb-4">'."\n".
            '		<span class="offset-4 form-group">Activer le compte ?</span>'."\n".
            '		<div class="offset-2 col-sm-4 form-group">'."\n".
            indentString($this->elemForm->getRadioElem('Activer', 'activer', 'activate', 'on', ((!empty($data) && $data[0]['is_activ'] !== '1' && $data[0]["is_activ"] !== NULL) ? false : true)), 3).
            '		</div>'."\n".
            '		<div class="offset-2 col-sm-4 form-group">'."\n".
            indentString($this->elemForm->getRadioElem('Désactiver', 'desactiver', 'activate', 'off', ((!empty($data) && $data[0]['is_activ'] !== '0' && $data[0]["is_activ"] !== NULL) ? false : true)), 3).
            '		</div>'."\n".
            '	</div>'."\n".
            '	<div class="row mb-4">'."\n".
            '		<span class="offset-4 form-group">Utilisateur administrateur ?</span>'."\n".
            '		<div class="offset-2 col-sm-4 form-group">'."\n".
            indentString($this->elemForm->getRadioElem('Oui', 'oui', 'isAdmin', 'on', ((!empty($data) && $data[0]['is_admin'] !== '1' && $data[0]["is_admin"] !== NULL) ? false : true)), 3).
            '		</div>'."\n".
            '		<div class="offset-2 col-sm-4 form-group">'."\n".
            indentString($this->elemForm->getRadioElem('Non', 'non', 'isAdmin', 'off', ((!empty($data) && $data[0]['is_admin'] !== '0' && $data[0]["is_admin"] !== NULL) ? false : true)), 3).
            '		</div>'."\n".
            '	</div>'."\n".
            '	<div class="row mb-4">'."\n".
            '		<div class="offset-5 col-sm-4 form-group">'."\n".
            indentString($this->elemForm->getBtnValidateForm('Admin'), 3).
            '		</div>'."\n".
            '	</div>'."\n".
            '</form>'. "\n";
        }

        return '';
    }
}