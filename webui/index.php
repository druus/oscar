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
 * Time to connect to the database
 */
try {
    $dbcon = new PDO("mysql:host=$DBHOST;dbname=$DBNAME", $DBUSER, $DBPASSWD);
    $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $ex) {
    $template = $twig->loadTemplate('error.tpl');
    $error = "Unable to connect to the database. Error code: $ex";
    echo $template->render( array('error_msg' => $error ) );
    exit;
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
        $user = new User( $dbcon );
        $userDetails = $user->login( $username, $password );
        if ( $userDetails['id'] > 0 ) {
            $_SESSION['logged_in_user'] = $username;
            $_SESSION['user_role']      = $userDetails['role'];
            // Update the login time
            $user->loginTimestamp( $username );
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

    try {
    // Get user details
    $user = new User( $dbcon );

    $asset = new Asset( $dbcon );
    $utils = new Utilities( $dbcon );

    switch ( $cmd ) {
        case "new":
            $template   = $twig->loadTemplate('asset.tpl');
            $cfgData['menuHighlight'] = "new_asset";
            $formTitle  = "--New asset--";
            $statusList = $utils->getStatus();
            echo $template->render( 
                array( 'formTitle' => $formTitle,
                       'cfgData'   => $cfgData,
                       'statusList'=> $statusList,
                ) );
            break;
        case "asset":
            if ( isset($_REQUEST['asset']) ) {
                $assetId = $_REQUEST['asset'];

            //$assetData = array('asset' => $asset, 'manufacturer' => "Hewlett Packard", 'model' => "Blaha", status => 4);
            $template   = $twig->loadTemplate('asset.tpl');
            $formTitle  = "Asset $assetId";
            $statusList = $utils->getStatus();
            $assetData  = $asset->getAsset( $assetId );

            echo $template->render(
                array( 'formTitle' => $formTitle,
                    'cfgData'   => $cfgData,
                    'asset'   => $assetData,
                    'statusList' => $statusList,
                ) );
            } else {
                $template = $twig->loadTemplate('error.tpl');
                echo $template->render( array() );
            }
            break;

        default:
            $cfgData['menuHighlight'] = "home";
            $assetList = $asset->listAssets();
            $template = $twig->loadTemplate('main.tpl');

            echo $template->render( array('name' => 'oscar',
                              'cfgData' => $cfgData,
                              'assetList' => $assetList,
                              ) );

        } // End of switch..

    } catch (Exception $ex) {
        echo "Oww, cr*p. Something didn't go very well: $ex";
    }

}

?>
