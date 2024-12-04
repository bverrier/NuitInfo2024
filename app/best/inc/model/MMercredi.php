<?php

class MMercredi
{

	/**
	 * Récupèrent tout les évènements de la base de données
	 * @param DatabaseMain $db
	 * @return DatabaseResult|false
	 * @throws DataBaseNotConnected
	 */
	public function getAllEventByDate(DatabaseMain $db, string $date)
	{
		$sql =
			'SELECT '.
				'id '.
			'FROM event '.
			'WHERE date_event >= "'.$db->escape($date).'";';
		return $db->query($sql);

	}

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
				'is_activ '.
			'FROM users; ';
		return $db->query($sql);

	}


}