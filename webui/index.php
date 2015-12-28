<?php
/**
 * OSCAR - Open Source Created Asset Register
 *
 * @author Daniel Ruus <daniel@nagiostools.org>
 * @version 0.1.0
 * @copyright 2015 Daniel Ruus
 */

/**
 * start a session
 */
session_start();

/**
 * Retrieve any commands provided
 */
$cmd = $_REQUEST['cmd'];

/**
 * Load our configuration
 */
require 'config/config.php';
require 'classes/User.class.php';

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


if ( $cmd == "logout" ) {
    session_destroy();
    header("Location: index.php");
}

/**
 * Check if user is logged in, otherwise present a login form
 */
if ( isset($_POST['submit']) && $cmd == "login" ) {
    if ( empty($_POST['username']) || empty($_POST['password']) ) {
        $error = "Username or password is invalid.";
    }
    else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = new User();
        //if ( $username == "daniel" && $password == "an1974" ) {
        if ( $user->login( $username, $password ) ) {
            $_SESSION['logged_in_user'] = $username;
            $error = "";
        }
    }
}


if ( !isset($_SESSION['logged_in_user']) ) {
    $template = $twig->loadTemplate('login.tpl');
}
else {
    /**
     * Load a template
     */
    $template = $twig->loadTemplate('main.tpl');
}

/**
 * Store some data in a useful variable...
 */
$cfgData = array(
    'userName' => $_SESSION['logged_in_user']
);

/**
 * Render the template
 */
echo $template->render( array('name' => 'oscar',
                              'cfgData' => $cfgData,
                              'error_msg' => $error) );

?>
