<?php

require_once __DIR__ . '/../lib/Header.php';
require_once __DIR__ . '/../lib/NavBar.php';
require_once __DIR__ . '/../lib/Menu.php';
require_once __DIR__ . '/../inc/controller/lib/ElemForm.php';
require_once __DIR__ . '/../lib/croissantLib.php';
class Page
{
	private Header $header;
	private NavBar $navBar;
	private Menu $menu;
	private ElemForm $elemForm;

	public function __construct(Session $session, string $titre)
	{
		$this->session = $session;
		$this->titre = $titre;
		$this->header = new Header();
		$this->navBar = new NavBar();
		$this->menu = new Menu();
		$this->elemForm = new ElemForm();
	}

	public function getDocument(string $titreOnglet, string $content, bool $is_modal = true) : string
	{
		return
		$this->header->getHeader($titreOnglet). "\n".
		$this->navBar->getNavBar($this->session). "\n".
		$this->menu->getMenu($this->session). "\n".
		'	<div id="page">'. "\n".
		'		<div class="spinner">'."\n".
  		'			<div></div>'."\n".
  		'			<div></div>'."\n".
  		'			<div></div>'."\n".
  		'			<div></div>'."\n".
  		'			<div></div>'."\n".
  		'			<div></div>'."\n".
  		'			<div></div>'."\n".
  		'			<div></div>'."\n".
  		'			<div></div>'."\n".
  		'			<div></div>'."\n".
		'		</div>'."\n".
		'		<div class="container-fluid mt-3">'."\n".
		'			<h1>'.htmlsecure($this->titre)."\n".$this->getAddButton($is_modal).'</h1>'."\n".
		'		</div>'."\n".

		'		<div class="card">'."\n".
		'			<div class="card-body">'."\n".
		indentString($content,4)."\n".
		'			</div>'."\n".
		'		</div>'."\n".
		'	</div>'."\n".
		$this->header->getEnd();

	}

    public function getAddButton(bool $is_modal = true) : string
    {
        if ($is_modal){
            return '<button id="addButton" type="button" class="btn btn-sm btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#'.str_replace(" ","",htmlsecure($this->titre)).'">'."\n".
            '	<i class="fa-solid fa-plus"></i> Ajout'."\n".
            '</button>'."\n";
        }
        return '';
    }

}