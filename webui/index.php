<?php
/**
 * OSCAR - Open Source Created Asset Register
 *
 * @author Daniel Ruus <daniel@nagiostools.org>
 * @version 0.1.0
 * @copyright 2015 Daniel Ruus
 */


/**
 * Load our configuration
 */
require 'config/config.php';

/**
 * Include and register Twig
 */
include 'Twig/Autoloader.php';
Twig_Autoloader::register();

/**
 * Define the template directory
 */
$loader = new Twig_Loader_Filesystem('templates');

/**
 * Initialize Twig
 */
$twig = new Twig_Environment($loader);

/**
 * Load a template
 */
$template = $twig->loadTemplate('main.tpl');

/**
 * Render the template
 */
echo $template->render( array('name' => 'oscar') );

?>
