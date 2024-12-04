<?php

require_once __DIR__ . '/../../lib/ErrorForm.php';
require_once __DIR__ . '/../../lib/croissantLib.php';
require_once __DIR__ . '/../../inc/service/SAdmin.php';
require_once __DIR__ . '/../../inc/model/MMercredi.php';

class SMercredi
{

	/**
	 * @var ErrorForm
	 */
	private ErrorForm $errors;

	/**
	 * @var MMercredi
	 */
	private MMercredi $model;

	public function __construct()
	{
		$this->errors = new ErrorForm();
		$this->model = new MMercredi();
	}


	/**
	 * Vérifie que la requete est bonne
	 * @param DatabaseMain $db
	 * @return array|null
	 * @throws DataBaseNotConnected
	 */
	public function getAllEventByDate(DatabaseMain $db, string $date): ?array
	{
		$res = $this->model->getAllEventByDate($db, $date);
		if (!$res instanceof DatabaseResult) {
			throw new Exception("Erreur de la base de données");
		}
		return $res->toList();
	}

	/**
	 * Vérifie que la requete est bonne
	 * @param DatabaseMain $db
	 * @return array|null
	 * @throws DataBaseNotConnected
	 */
	public function getAllUsers(DatabaseMain $db): ?array
	{
		$res = $this->model->getAllUsers($db);
		if (!$res instanceof DatabaseResult) {
			throw new Exception("Erreur de la base de données");
		}
		return $res->toList();
	}

	/**
	 * Update event table when a user is added or updated
	 * @param DatabaseMain $db
	 * @param array $data
	 * @param string $id users
	 * @return void
	 * @throws DataBaseNotConnected
	 */
	public function updateAllWednesday(DatabaseMain $db, array $data, string $id): void
	{
		$date = date('Y-m-d');
		$events = $this->getAllEventByDate($db, $date);
		$db->query('START TRANSACTION');

		//Le compte est desactivé
		if ($data['is_activ'] == 0) {
			//On retire des events
			foreach ($events as $event) {
				$wheres = array(
					'id_user =' . $db->escape($id) . ' ',
					' id_event = ' . $db->escape($event['id']),
				);
				$res = $db->delete('participant', $wheres);
				if (!$res instanceof DatabaseResult) {
					$db->query('ROLLBACK');
					$this->errors->addError('Failed to delete user from event');
				}
				$db->query('COMMIT');
			}
		} else {
			foreach ($events as $event) {
				$values = array(
					'id_user' => '\'' . $db->escape($id) . '\'',
					'id_event ' => '\'' . $db->escape($event['id']) . '\'',
					'is_responsable' => '\'0\'',
					'is_present' => '\'1\'',
					'nb_croissant' => '\'1\'',
				);
				$res = $db->insert('participant', $values);
				if (!$res instanceof DatabaseResult) {
					$db->query('ROLLBACK');
					$this->errors->addError('Failed to insert user from event');
				}
			}
			$db->query('COMMIT');
		}
	}

	/**
	 * Update event table once
	 * @throws DataBaseNotConnected
	 */
	public function updateAllWednesdayOnce(DatabaseMain $db): void
	{
		$date = date('Y-m-d');
		$events = $this->getAllEventByDate($db, $date);
		$users = $this->getAllUsers($db);
		$db->query('START TRANSACTION');

		//Le compte est desactivé
		foreach ($users as $user) {
			if ($user['is_activ'] == 0) {
				//On retire des events
				foreach ($events as $event) {
					$wheres = array(
						'id_user =' . $db->escape($user['id']) . ' ',
						' id_event = ' . $db->escape($event['id']),
					);
					$res = $db->delete('participant', $wheres);
					if (!$res instanceof DatabaseResult) {
						$db->query('ROLLBACK');
						$this->errors->addError('Failed to delete user from event');
					}
					$db->query('COMMIT');
				}
			} else {
				foreach ($events as $event) {
					$values = array(
						'id_user' => '\'' . $db->escape($user['id']) . '\'',
						'id_event ' => '\'' . $db->escape($event['id']) . '\'',
						'is_responsable' => '\'0\'',
						'is_present' => '\'1\'',
						'nb_croissant' => '\'1\'',
					);
					$res = $db->insert('participant', $values);
					if (!$res instanceof DatabaseResult) {
						$db->query('ROLLBACK');
						$this->errors->addError('Failed to insert user from event');
					}
				}
				$db->query('COMMIT');
			}
		}
	}


	}