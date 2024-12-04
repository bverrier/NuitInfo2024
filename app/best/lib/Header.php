<?php

require_once __DIR__.'/croissantLib.php';

	class Header
	{
		private $css;

		/**
		 * Create a HTML5 header with a title
		 *
		 * @param string $title name of the internet page
		 * @return string
		 */
		public function getHeader(string $title):string
		{
			$html =
				'<!doctype html>'."\n".
				'<html lang="fr">'."\n".
				'	<head>'."\n".
				'		<meta charset="utf-8">'."\n".
				'		<meta name="viewport" content="width=device-width, initial-scale=1">' ."\n".
				'		<script src="./js/bootstrap/bootstrap.bundle.min.js"></script>' ."\n".
				'		<link rel="shortcut icon" href="./img/cwassant.png" type="image/x-icon">'."\n".
				'		<link rel="stylesheet" href="./css/bootstrap/bootstrap.min.css" media="screen, print">' ."\n".
				'		<link rel="stylesheet" href="./css/fontawsomne/css/fontawesome.min.css">' ."\n".
				'		<link rel="stylesheet" href="./css/fontawsomne/css/all.css">' ."\n".
				'		<link rel="stylesheet" href="css/index.css">' ."\n".
				indentString($this->getFileCss(), 2).
				'		<script src="./js/jquery-3.5.1.js"></script>'."\n".
				'		<script src="./js/jquery.dataTables.min.js"></script>'."\n".
				'		<script src="./js/bootstrap/dataTables.bootstrap5.min.js"></script>'."\n".
                '		<script src="./js/index.js"></script>'."\n";

            $html .= (!empty($_GET['page']) && $_GET['page'] == 'Admin') ? '<script src="./js/ajax/admin.js"></script>'."\n" : '';
            $html .= (!empty($_GET['page']) && $_GET['page'] == 'Accueil') ? '<script src="./js/ajax/accueil.js"></script>'."\n" : '';
            $html .= (!empty($_GET['page']) && $_GET['page'] == 'Messagerie') ? '<script src="./js/ajax/messagerie.js"></script>'."\n" : '';

            $html .= (!empty($_GET['page']) && $_GET['page'] == 'Holidays') ? '<script src="./js/ajax/holidays.js"></script>'."\n" : '';
            $html .=
                '		<title>'.strip_tags($title).'</title>'."\n".
                '	</head>'."\n".
                '	<body>'."\n";
            return $html;
		}

		/**
		 * @param string $css
		 * @return void
		 */
		public function setFileCss(string $css='') {
			$this->css = '<link rel="stylesheet" href='.strip_tags($css).'>'."\n";
		}

		/**
		 * @param string $css
		 * @return string
		 */
		public function getFileCss(string $css='') {
			return $this->css;
		}

		/**
		 * Display the end of a HTML5 document
		 * @return string
		 */
		public function getEnd():string
		{
				return
					'	</body>'."\n".
					'</html>';
		}
	}
