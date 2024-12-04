<?php

require_once __DIR__ . '/../inc/Session.php';
class Menu
{

	public function getMenu(Session $session):string {

		$current_page = basename($_SERVER['PHP_SELF']);

		$html =
		'<div id="menu">'."\n".
		'	<nav id="asideMenu">'."\n".
		'		<ul>'."\n".
		'			<li>'."\n".
		'				<a class="nav-link p-3 ps-4 '. (!empty($_GET['page']) && $_GET['page'] == 'Accueil' ? 'selected' : ''). '" href="./index.php?page=Accueil"><i class="fa-solid fa-house"></i> &nbsp; Accueil</a>'."\n".
		'			</li>'."\n".
		'			<li>'."\n".
		'				<a class="nav-link p-3 ps-4 ' . (!empty($_GET['page']) && $_GET['page'] == 'Retro' ? 'selected' : ''). '" href="./retro.php"><i class="fa-solid fa-gamepad"></i></i> &nbsp; Retro</a>'."\n".
		'			</li>'."\n";

		if ($session->isAdmin()) {
			$html .=
				'			<li>'."\n".
				'				<a class="nav-link p-3 ps-4 ' . (!empty($_GET['page']) && $_GET['page'] == 'Admin' ? 'selected' : ''). '" href="./index.php?page=Admin"><i class="fa-solid fa-gear"></i> &nbsp; Admin</a>'."\n".
				'			</li>'."\n";
		}

		$html .=
			'		</ul>'."\n".
			'	</nav>'."\n".
			'</div>'."\n";

		return $html;

	}
}