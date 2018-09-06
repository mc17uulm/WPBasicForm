<?php
/**
Plugin Name: WP Basic Form
Description: Basic WP Form
Version: 0.1
Author: mc17uulm
AuthorURI: https://combosch.de
License: GPLv3

=== Plugin Information ===

Version: 0.1
Date: 06.09.2018

*/

if(!function_exists('add_action'))
{
    exit;
}

require_once 'vendor/autoload.php';

use objects\Category;

define('WBF__PLUGIN_DIR', plugin_dir_path(__FILE__));

register_activation_hook(__FILE__, "wbf_activate");
register_deactivation_hook(__FILE__, "wbf_deactivate");

$dir = '/wp-content/plugins/WPBasicForm/';

function wbf_activate()
{


}

function wbf_deactivate()
{


}

function wbf_enqueue_style()
{

    global $dir;

    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', array(), false, 'all');
    wp_enqueue_style('bootstrap-datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css', array('bootstrap'), false, 'all');
    wp_enqueue_style('wbf', $dir . 'css/custom.css', array('bootstrap'), false, 'all');

}

function wbf_enqueue_script()
{

    global $dir;

    wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array('jquery'), null, false);
    wp_enqueue_script("bootstrap-datepicker", "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js", array('jquery'), null, false);
    wp_enqueue_script("bootstrap-datepicker-de", $dir . "js/bootstrap-datepicker.de.js", array('bootstrap-datepicker'), null, false);
    wp_enqueue_script("wbf", $dir . "js/wbf.js", array('jquery'), null, false);

    wp_localize_script('wbf', 'wbf', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'tmpurl' => plugin_dir_url(__FILE__)
    ));


}

add_action('wp_enqueue_scripts', 'wbf_enqueue_style');
add_action('wp_enqueue_scripts', 'wbf_enqueue_script');

function wbf_shortcode($atts)
{

    require('form.html');

}

add_shortcode("wbf", 'wbf_shortcode');

add_action('wp_ajax_nopriv_check_data', 'wbf_handle_ajax_request');
add_action('wp_ajax_check_data', 'wbf_handle_ajax_request');

function wbf_handle_ajax_request()
{
    $data = $_POST["data"];

    $prefix = $data["woman"] ? "geehrte" : "geehrter";
    $ans = $data["woman"] ? "Frau" : "Herr";
    $lastname = $data["lastname"];
    date_default_timezone_set('Europe/Berlin');
    setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');
    $c = strlen($data["date"]);
    $date = $c > 10 ? $data["date"]/pow(10, ($c - 10)) : $data["date"];
    $date = strftime("%B %Y", $date);

    $infos = array();

    if($data["customer"])
    {
        array_push($infos,
            new Category(
                "Allgemeine Angaben",
                "Weil Sie noch kein Kunde sind benötigen wir Ihre Adressdaten."
            )
        );
    }

    if($data["impressum"])
    {
        array_push($infos,
            new Category(
                "Rechtliches",
                "Weil Sie noch kein Impressum haben benötigen wir Angaben zu:
                 <br>
                 <ol>
                    <li>Gesellschaftsform</li>
                    <li>Gesellschafter</li>
                    <li>zuständiges Amtsgericht etc.</li>
                 </ol>"
            )
        );
    }

    if($data["dsgvo"] === "y_dsgvo")
    {
        array_push($infos,
            new Category(
                "Rechtliches",
                "Weil Sie noch keine aktuelle Datenschutzerklärung haben benötigen wir Angaben zu:
                <br>
                <ol>
                    <li>Datenshutzbeauftragem</li>
                    <li>etc.</li>
                </ol>"
            )
        );
    }

    if($data["cms"])
    {
        array_push($infos,
            new Category(
                "Technik",
                "Weil Sie ein CMS wünschen benötigen wir
                <br>
                <ol>
                    <li>Vertragsunterlagen zum Hostinganbieter</li>
                    <li>Zugang zum Webspace</li>
                    <li>etc.</li>
                </ol>"
            )
        );
    }

    foreach($data["important"] as $imp)
    {
        $imp = substr($imp, strlen($imp) - 1);
        switch($imp)
        {
            case "1":
                array_push($infos,
                    new Category(
                        "Wichtiges",
                        "Weil Ihnen besonders wichtig ist, dass Ihr Projekt bei Google rankt, benötigen wir:
                                <br>
                                <ol>
                                    <li>Texte zu Ihrem Service (mind. 300 Wörter)</li>
                                    <li>Bilder von Ihrem Service (Dateiformat JPEG)</li>
                                    <li>etc.</li>
                                </ol>"
                    )
                );
                break;

            case "2":
                array_push($infos,
                    new Category(
                        "Wichtiges",
                        "Weil Ihnen besonders wichtig ist, dass Ihr Projekt individuell ist, benötigen wir:
                                <br>
                                <ol>
                                    <li>eine tolle Idee ;)</li>
                                    <li>etc.</li>
                                </ol>"
                    )
                );
                break;

            case "6":
                array_push($infos,
                    new Category(
                        "Wichtiges",
                        "Weil Sie Produkte verkaufen wollen, benötigen wir eine Liste aller Produkte mit folgenden Angaben:
                                <br>
                                <ol>
                                    <li>Artikelnr.</li>
                                    <li>Artikelbez.</li>
                                    <li>Artikelbeschreibung</li>
                                    <li>etc.</li>
                                </ol>"
                    ),
                    new Category(
                        "Wichtiges",
                        "Weil Sie Produkte verkaufen wollen, benötigen wir von allen Artikeln Produktbilder."
                    )
                );
                break;

            default:
                break;
        }
    }



    $files = glob(__DIR__ . "/tmp/*");
    foreach($files as $file)
    {
        if(is_file($file))
        {
            unlink($file);
        }
    }

    ob_start();
    require "template.php";
    $template = ob_get_clean();

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($template);
    $dompdf->render();
    $dompdf->setBasePath('https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');

    $file = "tmp/" . bin2hex(openssl_random_pseudo_bytes(10)) . ".pdf";
    file_put_contents(__DIR__ . "/". $file, $dompdf->output());
    die(json_encode(array("type" => "success", "msg" => $file)));

}