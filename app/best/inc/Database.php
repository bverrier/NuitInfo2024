<?php

use JetBrains\PhpStorm\NoReturn;

require_once __DIR__ . '/../conf/DbLogins.php';


abstract class DatabaseMain
{

	protected string $dbServer;
	protected string $dbUser;
	protected string $dbPass;
	protected string $dbName;

	private $conn;

	private $result;
	private bool $isConnected;

	private  $dblogin;


	public function __construct($dblogin)
	{
		$this->conn = false;
		$this->dblogin = $dblogin;
		$this->init();
		$this->open();
	}

	/**
	 * Initialisation de la base de données;
	 * @return void
	 */
	protected function init(): void
	{
		$this->dbServer = $this->dblogin->getServer();
		$this->dbUser = $this->dblogin->getLogin();
		$this->dbPass = $this->dblogin->getPassword();
		$this->dbName = $this->dblogin->getName();
	}

	/**
	 * Connect to the database, if it's failed, display the error
	 * @return void
	 */
	private function open(): void
	{
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		//We try to connect to the database, it's fail, we display error(s)
		try {
			$this->conn = mysqli_connect($this->dbServer, $this->dbUser, $this->dbPass, $this->dbName);
			$this->isConnected = true;
		} catch (mysqli_sql_exception $e) {
			$err['titre'] = 'Connecting issues';
			$err['code'] = $e->getCode();
			$err['message'] = $e->getMessage();
			$err['appels'] = $e->getTraceAsString(); //call stack
			$err['autres'] = array('Parameters' => 'BD_SERVER : ' . $this->dbServer
				. "\n" . 'BD_USER : ' . $this->dbUser
				. "\n" . 'BD_PASS : ' . $this->dbPass
				. "\n" . 'BD_NAME : ' . $this->dbName);
			$this->dbError($err); //stop the script*/
		}
		//We try to defin a charset, if it's fail, we display the error(s)
		try {
			// define a charset to use when sending data from and to the database
			mysqli_set_charset($this->conn, 'utf8');
			return;     // ===> exit connexion OK
		} catch (mysqli_sql_exception $e) {
			$err['titre'] = 'ErrorForm while setting charset';
			$err['code'] = $e->getCode();
			$err['message'] = $e->getMessage();
			$err['appels'] = $e->getTraceAsString();
			$this->dbError($err); //stop the script
		}
	}

	/**
	 * Close the connection to the database
	 *
	 * @return void
	 */
	public function close(): void
	{
		if ($this->conn != null && !(mysqli_close($this->conn))) {
			$err['titre'] = 'ErrorForm when closing the connection to the database';
			$this->dbError($err);
		}
	}

	/**
	 * Stop the script if we got an error in the database, and display error(s)
	 *
	 * Display error(s) and exit the script.
	 * @param array $err is an array of error(s)
	 * @return void
	 */
    private function dbError(array $err): void
	{
		ob_end_clean();  // delete all it would have been generated

		echo
			'<!DOCTYPE html>' . "\n" .
			'<html lang="fr">' . "\n" .
			'   <head>' . "\n" .
			'     <meta charset="UTF-8">' . "\n" .
			'     <title>ErrorForm database </title>' . "\n" .
			'  </head>' . "\n" .
			'  <body>';
		// Print all information in $err
		echo '   <h4>' . $err['titre'] . '</h4>' . "\n" .
			'   <pre>' . "\n" .
			'   <strong>ErrorForm mysqli</strong> : ' . $err['code'] . "\n" .
			utf8_encode($err['message']) . "\n";
		//$err['message'] is a string encode in ISO-8859-1
		if (isset($err['autres'])) {
			echo "\n";
			foreach ($err['autres'] as $cle => $valeur) {
				echo '   <strong>' . $cle . '</strong> :' . "\n" . $valeur . "\n";
			}
		}
		echo "\n" . '   <strong>Pile des appels de fonctions :</strong>' . "\n" . $err['appels'] . '</pre>';
		echo '  </body>' . "\n" . '</html>';
		exit(1);
	}

	/**
	 * We're going to remove html syntax and escaping to avoid XSS attack or SQL injection
	 * @param string $text
	 * @return string|null
	 */
	public function importTxt(string $text): ?string
	{
		if ($text == '') {
			return $text;
		} else {
			return $this->escape(strip_tags($text));
		}
	}

	/**
	 * @param $sql
	 * @return DatabaseResult|false
	 * @throws DataBaseNotConnected
	 */
	public function query($sql)
	{

		if (!$this->isConnected) {
			throw new DataBaseNotConnected('Error connexion database;');
		}
		try {
			$this->result =mysqli_query($this->conn, $sql);
		}catch (mysqli_sql_exception $e) {
			if ($this instanceof Database) {
				syslog(LOG_ERR, $e->getMessage());
			}
			return false;
		}
		return new DatabaseResult($this->result);
	}


	/**
	 * To prevent XSS attack, we remplace escaping character
	 * @param $txt
	 * @return string|void
	 */
	public function escape($txt)
	{
		if (!$this->isConnected) {
			exit(1);
		}

		return mysqli_real_escape_string($this->conn, $txt);
	}

	/**
	 * Return the value of the auto_increment field
	 * return 0 if there was no previous query
	 * @return int|string
	 */
	public function getId()
	{
		return mysqli_insert_id($this->conn);
	}


	/**
	 *    \brief Executes an INSERT query composed according to the parameters.
	 *    \param $table Name of the table in which to insert.
	 *    \param $values Key/value list where the key is the name of the field and the value is the value to insert.
	 *    \param $returning Fields to put in RETURNING, to retrieve the primary key when it is in serial.
	 *    \warning The values are not escaped to be able to put functions in them.
	 *          It is also necessary to put single quotes (') for the values.
	 *    \warning The table, the names of the fields (keys of the $values table, values of $returning) are not escaped.
	 *
	 */
	public function insert($table, $values)
	{
		$keys = array();
		$vals = array();
		foreach ($values as $key => $val) {
			$keys[] = $key;
			if (empty($val) || $val == '\'\'') {
				$vals[] = 'NULL';
			} else {
				$vals[] = $val;
			}
		}
		return $this->query('INSERT INTO '.$table.'('.implode(', ', $keys).') VALUES ('.implode(', ', $vals).');');

	}
//    // fct pour l'insertion des champ repetiitfs dans une bdd
//    public function insertReapetingFields($table, $values)
//    {
//        $keys = array();
//        $vals = array();
//        foreach ($values as $key => $val) {
//            $keys[] = $key;
//            if (empty($val) || $val == '\'\'') {
//                $vals[] = 'NULL';
//            } else {
//                $vals[] = $val;
//            }
//        }
//        return $this->query('INSERT INTO '.$table.'('.implode(', ', $keys).') VALUES ('.implode(', ', $vals).');');
//
//    }
	private function makeWhereAnd($args)
	{
		$str = '';
		foreach ($args as $arg) {
			$str .= $arg . 'AND';
		}
		return preg_replace("/AND$/", '', $str);
	}

	/**
	 *    \brief Executes an UPDATE query composed according to the parameters.
	 *    \param $table Name of the table to modify.
	 *    \param $wheres List of criteria that must be folded for the values to be modified.
	 *    \param $values Key/value list where the key is the name of the field and the value is the value to be modified.
	 *                   The values ($wheres and $values) are not escaped in order to be able to put functions on them.
	 *                 Single quotes (\') are also required for values.
	 *    \warning The table, the names of the fields (keys of the table $wheres and $value) are not escaped either.
	 *    \warning $where must be correct to avoid modifying more records than necessary.
	 **/
	public function update($table, $wheres, $values)
	{
		$nValues = array();
		foreach ($values as $key => $val) {
			if ($val === null || $val == '\'\'') {
				$nValues[] = $key . '= NULL';
			} else {
				$nValues[] = $key . '=' . $val;
			}

		}

		return $this->query('UPDATE ' . $table . ' SET ' . implode(', ', $nValues) . ' WHERE ' . $this->makeWhereAnd($wheres));
	}

	/**
	 *    \brief Executes a DELETE request composed according to the parameters.
	 *    \param $table Name of the table to modify.
	 *    \param $wheres List of criteria that must be folded for the values to be modified.
	 *    The values in $wheres are not escaped to be able to put functions there.
	 *    Single quotes (') must also be used for the values.
	 *    \warning The table, the names of the fields (keys of the $wheres table) are not escaped either.
	 *    \warning $where must be correct to avoid modifying more records than necessary.
	 **/
	public function delete($table, $wheres)
	{
		return $this->query('DELETE FROM ' . $table . ' WHERE ' . $this->makeWhereAnd($wheres));
	}

	/**
	 * Retourne le nombre d'enregistrements modifiés/ajoutés/supprimés
	 * @return int|string
	 */
	public function numAffectedRows()
	{
		return mysqli_affected_rows($this->conn);
	}

	/**
	 * \brief Déplace le pointeur sur un enregistrement donné.
	 * \param $offset Le numéro le l'enregistrement, le premier est 0, le dernier est num_rows()-1.
	 **/
	public function seek($offset)
	{
		return mysqli_data_seek($this->result, $offset);
	}

}

class DataBaseNotConnected extends exception
{
}

class DatabaseResult
{

	private $res;

	/// Crée le résultat d'une requête avec l'objet retourné par le SGBD$

	/**
	 * @param $res
	 */
	public function __construct($res)
	{
		$this->res = $res;
	}

	/**
	 * Transform a mysqli_result object to an assoc array
	 * @param $key
	 * @return array
	 */
	public function toList($key = null)
	{
		$list = array();
		while ($val = $this->res->fetch_assoc()) {
			if ($key) {
				$list [$val [$key]] = $val;
			} else {
				$list [] = $val;
			}

		}
		return $list;
	}

	/**
	 * \brief Retourne un enregistrement et met le pointeur sur le suivant
	 * \returns Un tableau associatif avec les valeurs lues.
	 * \returns false si on arrive à la fin du tableau.
	 **/
	public function fetchAssoc()
	{
		return mysqli_fetch_assoc($this->res);
	}

	/**
	 * Retourne le nombre d'enregistrements lus
	 * @return int|string
	 */
	public function numRows()
	{
		return mysqli_num_rows($this->res);
	}

	/**
	 * Retourne le nombre de champs
	 * @return mixed
	 */
	public function getFieldCount()
	{
		return $this->res->field_count;
	}

}

/// Exception soulevée en cas d'erreur dans une requête SQL
class SQLException extends Exception {

};


/**
 * Class de base de données
 */
class Database extends DatabaseMain
{
	public function __construct()
	{
		parent::__construct(new DbLogins());
	}

}


/**
 * Class de base de données
 */
class DatabaseTest extends DatabaseMain
{
	public function __construct()
	{
		parent::__construct(new DbLoginsTest());
	}

}
