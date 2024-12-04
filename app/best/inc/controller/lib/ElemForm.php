<?php

class ElemForm
{
	/**
	 * @param string $labelName Le nom du label
	 * @param string $name le nom dans le post
	 * @param string $id Identifiant de l'input
	 * @param string|null $val La valeur de l'input si c'est une modification
	 * @param string|null $placeholder Description si elle existe
	 * @return string "HTML" L'input text
	 */
	public function getInputText(string $labelName, string $name, string $id, ?string $val, ?string $placeholder, bool $isActive): string
	{
		return
			'<label for="' . htmlsecure($id) . '">' . htmlsecure($labelName) . '</label>' . "\n" .
			'<input type="text" ' .
			'class="form-control form-control-sm" ' .
			'name="' . htmlsecure($name) . '" ' .
			'id="' . htmlsecure($id) . '" ' .
			'placeholder="' . htmlsecure($placeholder) . '" ' .
			(($isActive) ? '': 'disabled') .' '.
			'value="' . (!empty($val) || $val === '0' ? htmlsecure($val) : '') . '">' . "\n";
	}

	/**
	 * @param string $labelName
	 * @param string $name
	 * @param string $id
	 * @param string|null $val
	 * @param string|null $placeholder
	 * @param bool $isActive
	 * @return string
	 */
	public function getInputTextArea(string $labelName, string $name, string $id, ?string $val, ?string $placeholder, bool $isActive): string
	{
		return
			'<label for="' . htmlsecure($id) . '">' . htmlsecure($labelName) . '</label>' . "\n" .
			'<textarea style="min-height:150px!important;"' .
			'class="form-control form-control-sm" ' .
			'name="' . htmlsecure($name) . '" ' .
			'id="' . htmlsecure($id) . '" ' .
			'placeholder="' . htmlsecure($placeholder) . '" ' .
			(($isActive) ? '': 'disabled') .' '.
			'>' . $val . '</textarea>' . "\n";
	}

	/**
	 * @param string $labelName
	 * @param string $name
	 * @param string $id
	 * @param string|null $val
	 * @param string|null $placeholder
	 * @param string|null $min
	 * @param string|null $max
	 * @return string
	 */
	public function getInputDate(string $labelName, string $name, string $id, ?string $val, ?string $placeholder, ?string $min, ?string $max): string
	{
		return
			'<label for="' . htmlsecure($id) . '">' . htmlsecure($labelName) . '</label>' . "\n" .
			'<input type="date" ' .
			'class="form-control form-control-sm" ' .
			'name="' . htmlsecure($name) . '" ' .
			'id="' . htmlsecure($id) . '" ' .
			'placeholder="' . htmlsecure($placeholder) . '" ' .
			'min="' . htmlsecure($min) . '" ' .
			'max="' . htmlsecure($max) . '" ' .
			'value="' . (!empty($val) ? htmlsecure($val) : '') . '">' . "\n";
	}

	/**
	 * @param string $labelName
	 * @param string $name
	 * @param string $id
	 * @param string|null $val
	 * @param string|null $placeholder
	 * @return string
	 */
	public function getInputMail(string $labelName, string $name, string $id, ?string $val, ?string $placeholder):string
	{
		return
			'<label for="' . htmlsecure($id) . '">' . htmlsecure($labelName) . '</label>' . "\n" .
			'<input type="email" ' .
			'class="form-control form-control-sm" ' .
			'name="' . htmlsecure($name) . '" ' .
			'id="' . htmlsecure($id) . '" ' .
			'placeholder="' . htmlsecure($placeholder) . '" ' .
			'value="' . (!empty($val) ? htmlsecure($val) : '') . '">' . "\n";
	}

	/**
	 * @param string $idBtn Identifiant bouton
	 * @return string Le bouton de validation d'un formulaire
	 */
	public function getBtnValidateForm(string $idBtn): string
	{
		return
			'<button id="send' . htmlsecure($idBtn) . '" class="btn btn-primary btn-sm btn-block mt-4" name="validate" type="button">' ."\n".
			'	<i class="fa-solid fa-check"></i> Valider' ."\n".
			'</button>'."\n";
	}

	/**
	 * Retourne un input autocompleté
	 * @param string $label nom du label
	 * @param string $id id des inputs
	 * @param string $name name de l'input
	 * @param string|null $class
	 * @param bool|null $needLabel
	 * @return string
	 */
	public function getInputAutocomplete(string $label, string $id, string $name, ?string $class='', ?bool $needLabel = true, ?string $valueSelect = '', ?string $valueId='') :string
	{
		return
			($needLabel ? '<label for="choose-'.htmlsecure($id).'">'.htmlsecure($label).'</label>' . "\n"  : '') .
			'<input type="text" value="'.htmlsecure($valueSelect).'" class="'.htmlsecure($class).' form-control form-control-sm autocomplete-'.htmlsecure($id).'" data-suggestions-threshold="0" id="choose-'.htmlsecure($id).'" aria-describedby="'.htmlsecure($id).'">' . "\n".
			'<input hidden type="text" class="form-control form-control-sm" name="'.htmlsecure($name).'" id="'.htmlsecure($id).'" value="'.htmlsecure($valueId).'">' . "\n";
	}

	/**
	 * @param string $id Identifiant du button
	 * @param string $class Class bootstrap pour les couleurs
	 * @return string "HTML" Le bouton
	 */
	public function getBtnDeleteElem(string $id, string $class): string
	{
		return
			'<button id="'.htmlsecure($id).'" type="button" class="btn btn-sm btn-'.htmlsecure($class).'">'.
			($class =="success"?'Oui':'Non').
			'</button>' . "\n";
	}

	/**
	 * @param string $label label du bouton radio
	 * @param string $id id du bouton radio
	 * @param string $name nom du bouton radio
	 * @param string $value valeur du bouton radio
	 * @param bool $checked vrai si coché par défault
	 * @return string
	 */
	public function getRadioElem(string $label, string $id, string $name, string $value, bool $checked)
	{
		return
			'<input class="form-check-input" type="radio" name="'.htmlsecure($name).'" id="'.htmlsecure($id).'" value="'.htmlsecure($value).'"'.($checked ? ' checked' : '').'>'."\n".
			'<label class="form-check-label" for="'.htmlsecure($id).'">'. htmlsecure($label). '</label>'."\n";
	}

	/**
	 * @param string $token
	 * @return string
	 */
	public function getInputToken(string $token)
	{
		return
			'<input type="hidden" name="token" id="token" value="'.htmlsecure($token).'"/>'."\n";
	}
}