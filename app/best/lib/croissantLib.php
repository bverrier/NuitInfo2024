<?php

/**
 * Escape a string for his HTML display
 *
 * @param string|null $str
 * @return string
 */
function htmlsecure(?string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * @param string $form Attention le form n’est pas echappé
 * @param string $title
 * @param string $id
 * @param string $size
 * @return string
 */
function getModal(string $form, string $title, string $id, string $size = "lg"): string
{
    $id = str_replace(" ", "", $id);
    return
        '<div class="modal fade" id="' . htmlsecure($id) . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="' . htmlsecure($id) . 'Label" aria-hidden="true">' . "\n" .
        '	<div class="modal-dialog modal-dialog-centered modal-' . htmlsecure($size) . '">' . "\n" .
        '		<div class="modal-content">' . "\n" .
        '			<div class="modal-header">' . "\n" .
        '				<h5 class="modal-title" id="' . htmlsecure($id) . 'Label">' . htmlsecure($title) . '</h5>' . "\n" .
        '				<button id="close' . htmlsecure($id) . '" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' . "\n" .
        '			</div>' . "\n" .
        '			<div class="modal-body">' . "\n" .
        indentString($form, 3) ."\n".
        '			</div>' . "\n" .
        '		</div>' . "\n" .
        '	</div>' . "\n" .
        '</div>'."\n";
}

/**
 * @param string $class Class bootstrap
 * @param array $msg Les messages de l'alert
 * @return string
 */

function getAlert(string $class, array $msg)
{
    $html =
        '<div class="alert alert-'.htmlsecure($class).' alert-dismissible fade show mt-4" role="alert">'."\n".
        '	<ul>'."\n";
    foreach ($msg as $m) {
        $html .=
            '		<li>'.htmlsecure($m).'</li>'."\n";
    }
    $html .=
        '	</ul>'."\n".
        '	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'."\n".
        '</div>'."\n";

    return $html;
}

/**
 * @param mixed $string texte à indenter
 * @param  mixed $nb nombre de tabulations à ajouter (obligatoirement positif)
 * @return string Indente un texte en rajoutant un/des tabulations devant chaque ligne
 */
function indentString($string, $nb)
{
    if ($string == '') {
        return '';
    } else {
        $ident = str_repeat("\t", $nb);
        $lastIsNL = (substr($string, -1) == "\n");
        if ($lastIsNL) {
            $string[strlen($string) - 1] = '%';
        }
        $string = $ident . str_replace("\n", "\n$ident", $string);
        if ($lastIsNL) {
            $string[strlen($string) - 1] = "\n";
        }
        return $string;
    }

}

/**
 * @param String $date string containing date
 * @return bool result is True only if date is in valid yyyy-mm-dd format
 */
function isValidDate(String $date): bool
{
    // Create a DateTime object from the string
    $d = DateTime::createFromFormat('Y-m-d', $date);

    // Check if the date is valid by ensuring it matches the input format and that it's a real date
    return $d && $d->format('Y-m-d') === $date;
}