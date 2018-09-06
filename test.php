<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 06.09.2018
 * Time: 20:00
 */

require_once "vendor/autoload.php";

/**
$prefix = "geehrter";
$ans = "Herr";
$lastname = "Combosch";
$date = "Januar 2019";
$infos = array("first");

ob_start();
require "template.php";
$template = ob_get_clean();

$dompdf = new \Dompdf\Dompdf();

$dompdf->loadHtml($template);
$dompdf->render();
$dompdf->setBasePath('https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
file_put_contents("out.pdf", $dompdf->output());
*/

date_default_timezone_set('Europe/Berlin');
setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');
var_dump(strftime("%B, %Y", 1546988400));