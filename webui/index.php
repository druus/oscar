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
if ( isset($_REQUEST['cmd']) ) {
    $cmd = $_REQUEST['cmd'];
} else {
    $cmd = null;
}

/**
 * Load our configuration
 */
require 'config/config.php';
require 'classes/Asset.class.php';
require 'classes/User.class.php';
require 'classes/Utilities.class.php';

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
 * Respect the users wish to logout, so destroy the session and reload the page
 */
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
        $userDetails = $user->login( $username, $password );
        if ( $userDetails['id'] > 0 ) {
            $_SESSION['logged_in_user'] = $username;
            $_SESSION['user_role']      = $userDetails['role'];
            $error = "";
        } else {
            $error = "Username or password is invalid.";
        }
    }
}


/**
 * If not logged in, present the user with a login form
 */
if ( !isset($_SESSION['logged_in_user']) ) {
    $template = $twig->loadTemplate('login.tpl');
    echo $template->render( array('error_msg' => $error ) );
}
else {

    /**
     * Store some data in a useful variable...
     */
    $cfgData = array(
        'userName' => $_SESSION['logged_in_user'],
        'userRole'  => $_SESSION['user_role'],
    );

    $dbcon = new PDO("mysql:host=localhost;dbname=oscar", "oscar", "dqXl4mEYW*1fA8uL");
    $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    // Get user details
    $user = new User();

    $asset = new Asset( $dbcon );

    switch ( $cmd ) {
        case "new":
            $template = $twig->loadTemplate('asset.tpl');
            $formTitle = "--New asset--";
            echo $template->render( 
                array( 'formTitle' => $formTitle,
                       'cfgData'   => $cfgData 
                ) );
            break;
        case "asset":
            if ( isset($_REQUEST['asset']) ) {
                $asset = $_REQUEST['asset'];
            }

            $assetData = array('asset' => $asset, 'manufacturer' => "Hewlett Packard", 'model' => "Blaha");
            $template = $twig->loadTemplate('asset.tpl');
            $formTitle ="Asset $asset";
            echo $template->render(
                array( 'formTitle' => $formTitle,
                    'cfgData'   => $cfgData,
                    'asset'   => $assetData,
                ) );
            break;

        default:

    $assetList = $asset->listAssets();

    $template = $twig->loadTemplate('main.tpl');


    /**
     * Render the template
     */
    echo $template->render( array('name' => 'oscar',
                              'cfgData' => $cfgData,
                              'assetList' => $assetList,
                              ) );

    } // End of switch..
}

?>
