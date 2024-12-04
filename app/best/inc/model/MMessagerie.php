<?php
class MMessagerie
{

	/**
	 * Return all the mails from a single users
	 *
	 * @param DatabaseMain $db
	 * @param String $id
	 * @return DatabaseResult|false
	 * @throws DataBaseNotConnected
	 */
	public function getAllMail(DatabaseMain $db, string $id)
	{
		$sql =
			'SELECT '.
				'messagerie.id, '.
				'U_FROM.login AS usr_from, '.
				'U_TO.login AS usr_to, '.
				'msg, '.
				'time '.
			'FROM messagerie '.
			'INNER JOIN users AS U_FROM ON U_FROM.id = messagerie.usr_from '.
			'INNER JOIN users AS U_TO ON U_TO.id = messagerie.usr_to '.
			'WHERE usr_to = '. $db->escape($id). ' OR usr_from = '. $db->escape($id);
		return $db->query($sql);
	}

	/**
	 * Return all the login
	 * @param DatabaseMain $db
	 * @return DatabaseResult|false
	 * @throws DataBaseNotConnected
	 */
	public function getAllLogin(DatabaseMain $db)
	{
		$sql =
			'SELECT '.
				'id,'.
				'login '.
			'FROM users';
		return $db->query($sql);

	}
}