<?php
/**
 * Plugin Name: Ad Blocking Detector
 * Plugin URI: http://adblockingdetector.jtmorris.net
 * Description: A plugin for detecting ad blocking browser extensions, plugins, and add-ons. It allows you to display alternative content to site visitors who block your ads.
 * Version: 3.6.0
 * Author: Admiral
 * Author URI: http://getadmiral.com
 * License: GPL2
 */

/*  Copyright 2013 - 2014  John Morris  (email : johntylermorris@jtmorris.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/




/*
 * IT IS CRUCIAL THAT YOU UPDATE THIS VERSION NUMBER ALONG WITH THE PLUGIN 
 * HEADER AND README.  NOT DOING SO RUNS THE RISK OF BREAKING BOTH THIS AND THE 
 * BLOCK LIST COUNTERMEASURE PLUGIN IT USES IN VERY SUBTLE WAYS!.
 *
 *     ||      ||      ||      ||
 *     ||      ||      ||      ||
 *     ||      ||      ||      ||
 *     ||      ||      ||      ||
 *    \\//    \\//    \\//    \\//
 *     \/      \/      \/      \/                          */

define( 'ABD_VERSION', '3.6.0' );

/*     /\      /\      /\      /\
 *    //\\    //\\    //\\    //\\
 *     ||      ||      ||      ||
 *     ||      ||      ||      ||
 *     ||      ||      ||      ||
 *     ||      ||      ||      ||                         */
 

$start_time = microtime( true );
$start_mem = memory_get_usage( true );

define ( 'ABD_ROOT_PATH', plugin_dir_path( __FILE__ ) );
define ( 'ABD_ROOT_URL', plugin_dir_url( __FILE__ ) );
define ( 'ABD_PLUGIN_FILE', ABD_ROOT_PATH . 'ad-blocking-detector.php' );
define ( 'ABD_SUBDIR_AND_FILE', plugin_basename(__FILE__) );


require_once ( ABD_ROOT_PATH . 'includes/setup.php' );

ABD_Setup::initialize();

ABD_Log::perf_summary( 'Entire Plugin Init', $start_time, $start_mem );

/**
 * Admiral initialization
 */
require_once( ABD_ROOT_PATH . "vendor/AdmiralAdBlockAnalytics.php");


\abd\AdmiralAdBlockAnalytics::setClientIDSecret("fc9b0df36259299b41a7", "2yfc9b0df36259299b41a700014c9fa9ba078d8105587f7725b4908d422190882a7f00cecc");

function admiraladblock_load_settings_abd()
{
    try {
        $didInitialize = \abd\AdmiralAdBlockAnalytics::initialize("abd", "1.4.0", "Ad Blocking Detector");
        if($didInitialize && (!function_exists('is_admin') || !is_admin())){
            add_action('wp_print_scripts', function(){
                \abd\AdmiralAdBlockAnalytics::printEmbedScripts();
            });
        } else if(!$didInitialize) {
            \abd\AdmiralAdBlockAnalytics::createNewProperty("");
        }
    } catch (Exception $e) {
        error_log("Error loading settings: " . $e->getMessage());
    }
}

admiraladblock_load_settings_abd();