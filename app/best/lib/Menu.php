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
		'				<a class="nav-link p-3 ps-4 '. (!empty($_GET['page']) && $_GET['page'] == 'Météo' ? 'selected' : ''). '" href="./nif.php?page=Météo"><i class="fa-solid fa-cloud-sun"></i> &nbsp; Météo</a>'."\n".
		'			</li>'."\n".
		'			<li>'."\n".
		'				<a class="nav-link p-3 ps-4 ' . (!empty($_GET['page']) && $_GET['page'] == 'Retro' ? 'selected' : ''). '" href="./index.php"><i class="fa-solid fa-gamepad"></i></i> &nbsp; Sujet National</a>'."\n".
		'			</li>'."\n";

		if ($session->isAdmin()) {
			$html .=
				'			<li>'."\n".
				'				<a class="nav-link p-3 ps-4 ' . (!empty($_GET['page']) && $_GET['page'] == 'Admin' ? 'selected' : ''). '" href="./nif.php?page=Admin"><i class="fa-solid fa-gear"></i> &nbsp; Admin</a>'."\n".
				'			</li>'."\n";
		}

		$html .=
			'		</ul>'."\n".
			'	</nav>'."\n".
			'</div>'."\n";

		return $html;

	}
}