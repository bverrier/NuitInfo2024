<?php


	abstract class DbLoginsMain
	{

		/**
		 * Add a bool to know if we are testing or not
		 * @param bool|null $test
		 */
		public function __construct()
		{
			$this->login = $this->getLogin();
			$this->password = $this->getPassword();
			$this->server = $this->getServer();
			$this->serverName = $this->getName();
		}

		/**
		 * Return login of the database
		 * @return string
		 */
		protected abstract function getLogin(): string;
		/**
		 * Return the password of a user
		 * @return string
		 */

		protected abstract function getPassword(): string;

		/**
		 * Return the type of the connection to the database
		 * @return string
		 */

		protected abstract function getServer():string;


		/**
		 * Return the name of the database
		 * @return string
		 */
		protected abstract function getName(): string;
	}

	class DbLogins extends DbLoginsMain {
		public function getLogin(): string
		{
			return 'best';
		}

		public function getPassword(): string
		{
			return 'best_pass';
		}

		public function getServer(): string
		{
			return 'mariadb_nuitInfo';
		}

		public function getName(): string
		{
			return 'best';
		}


	}

/**
 * Ne pas outlier de créer un autre utilisateur qui ne disposera pas des droit sur la base de données dev/prod
 */
	class DbLoginsTest extends DbLoginsMain {
		public function getLogin(): string
		{
			return 'best';
		}

		public function getPassword(): string
		{
			return 'best_pass';
		}

		public function getServer(): string
		{
			return 'mariadb_nuitInfo';
		}

		public function getName(): string
		{
			return 'best';
		}


	}