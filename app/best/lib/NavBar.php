<?php

require_once __DIR__ . "/../inc/Session.php";
class NavBar
{

	public function getNavBar(Session $session):string
	{
		return
		'<div id="navbar-custom">'."\n".
		'	<nav class="navbar">'."\n".
  		'		<div class="container-fluid">'."\n".
   		'			<a class="navbar-brand text-white" href="./index.php?page=Accueil">'."\n".
      	'				<img src="img/cwassant.png" alt="Logo" width="30" height="24" class="d-inline-block text-center">'."\n".
      	'					Croissant\'show'."\n".
    	'			</a>'."\n".
		'			<div class="navbar-brand text-white offset-1">'."\n".
		'				<i class="fa-solid fa-user"></i>'."\n". $session->getUserLogin() . "\n".
		'				<form action="login.php" method="POST" style="display: inline;">' . "\n".
		'					<input type="hidden" name="logout" value="deconnexion">'."\n".
		'					<button type="submit" class="btn btn-outline-primary ps-4 text-white" style="border: none; background-color: var(--color-cr-primary);">'."\n".
		'						<i class="fa-solid fa-sign-out"></i> Déconnexion'."\n".
		'					</button>'."\n".
		'				</form>'."\n".
		'			</div>'."\n".
  		'		</div>'."\n".
		'	</nav>'."\n".
  		'</div>'."\n";

	}
}