<?php

class MAdmin
{

	/**
	 * Récupèrent tout les utilisateurs de la base de données
	 * @param DatabaseMain $db
	 * @return DatabaseResult|false
	 * @throws DataBaseNotConnected
	 */
	public function getAllUsers(DatabaseMain $db)
	{
		$sql =
			'SELECT '.
				'id, '.
				'mail, '.
				'nom, '.
				'login, '.
				'prenom, '.
				'is_admin, '.
				'is_activ, '.
				'date_creation_compte '.
				'FROM users';
		return $db->query($sql);

	}

    public function getInfoUser(DatabaseMain $db, string $id){
        $sql =
            'SELECT '.
				'id, '.
				'mail, '.
				'nom, '.
				'login, '.
				'prenom, '.
				'is_admin, '.
				'is_activ, '.
				'date_creation_compte, '.
            'FROM users '.
            'WHERE id = '. htmlsecure($id);
        return $db->query($sql);
    }

}