<?php

	class ErrorForm
	{
		private array $errs = array();

		/**
		 * Add a given error in the array
		 *
		 * @param string $str
		 * @return void
		 */
		public function addError(string $str)
		{
			$this->errs[] = $str;
		}

		/**
		 * Return the cast of an assoc array to an array of error(s) string
		 * @return array
		 */
		public function getArray()
		{
			return array_values($this->errs);
		}

		/**
		 * Delete previous error(s)
		 *
		 * @return void
		 */
		public function clearArray(){
			$this->errs =array();
		}

	}
