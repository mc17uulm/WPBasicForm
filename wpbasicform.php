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

include_once 'app/App.php';

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

    wp_localize_script('wbf', 'wbf_ajax', array(
       'ajaxurl' => admin_url('admin-ajax.php')
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
    $_POST["data"];
}