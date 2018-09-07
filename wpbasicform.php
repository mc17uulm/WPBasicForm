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

// check if worpress based execution
if(!function_exists('add_action'))
{
    exit;
}

require_once 'vendor/autoload.php';

use objects\App;

// plugin dir
$dir = '/wp-content/plugins/WPBasicForm/';

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

    // shortcode need mail and from attributes to send mail
    $atts = shortcode_atts(array("mail" => "", "from" => ""), $atts, "wbf");
    if(empty($atts["mail"]) || empty($atts["from"]))
    {
        echo "Shortcode invalid";
    }
    else
    {
        $config = json_decode(file_get_contents(__DIR__ . "/objects/config.json"), true);
        $config["from"] = $atts["from"];
        $config["mail"] = $atts["mail"];

        // check if config.json can be accessed by the script. Else the script will fail
        // to change the permissions, check the documentation
        if(!is_writable(__DIR__ . "/objects/config.json"))
        {
            echo "Change permissions for plugin. See documentation.";
        } else{
            file_put_contents(__DIR__ . "/objects/config.json", json_encode($config));

            // show the form based on form.html
            require('objects/form.html');
        }
    }


}

add_shortcode("wbf", 'wbf_shortcode');

// handle ajax request for processing form data
add_action('wp_ajax_nopriv_check_data', 'wbf_handle_ajax_request');
add_action('wp_ajax_check_data', 'wbf_handle_ajax_request');

function wbf_handle_ajax_request()
{
    $data = $_POST["data"];

    $s = filter_var($data["woman"], FILTER_VALIDATE_BOOLEAN);
    $prefix = $s ? "geehrte" : "geehrter";
    $ans = $s ? "Frau" : "Herr";
    $lastname = $data["lastname"];


    date_default_timezone_set('Europe/Berlin');
    setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');
    $c = strlen($data["date"]);
    $time = $c > 10 ? $data["date"]/pow(10, ($c - 10)) : $data["date"];
    $date = strftime("%B %Y", $time);

    // $file gives the relativ path to the temporary .pdf file
    $file = App::object_to_pdf($prefix, $ans, $lastname, $date, App::create_infos($data));
    App::send_mail($data, $time, $file);
}