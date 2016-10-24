<?php
/*
* Plugin Name: LSCS REST Demo
* Description: Plugin that displays dynamic data pulled from a LiveSite Content Server using RESTful calls
* Version: 1.0
* Author: John Haldi
*/

include 'lscs-shortcode-banner.php';
include 'lscs-shortcode-promo.php';

add_shortcode('LSCS-Banner', 'lscs_shortcode_banner');
add_shortcode('LSCS-Promo', 'lscs_shortcode_promo');
