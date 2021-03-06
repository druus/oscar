<?php
/******************************************************************************
** Filename     index.php
** Description  Main entry point
** Version      0.5.2
** Created by   Daniel Ruus
** Created      ??
** Modified     2019-11-13
** Modified by  Daniel Ruus
** License      The MIT License (MIT) (See the file LICENSE)
** Copyright (c) 2015, 2016, 2017, 2018, 2019 Daniel Ruus
******************************************************************************/
$APP_VERSION="0.5.2";
$APP_AUTHOR="Daniel Ruus";
$APP_MODIFIED="2019-11-13";

session_start();

/**
 * Setup Twig, our templating engine
 */
include 'Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('views');
// Initialize Twig environment
$twig = new Twig_Environment($loader);


// Include required helper functions
include_once("./include/dbfunctions.inc.php");
include_once("classes/Asset.class.php");
include_once("classes/Authenticate.class.php");
include_once("classes/DbHandler.class.php");
include_once("classes/AdminUtils.class.php");
//require("./include/authenticate.inc.php");

($_SERVER['REQUEST_METHOD'] == 'GET') ? $values = $_GET : $values = $_POST;

if (!isset($values['cmd'])) {
  $values['cmd'] = "";  // Initialize variable if not already set
}

// Read the configuration file config/config.ini.php and extract the
// intresting bits
if ( $settings = parse_ini_file("config/config.ini.php", true) ) {

    $DBNAME   = $settings['database']['dbname'];
    $DBSCHEMA = $settings['database']['schema'];
    $DBUSER   = $settings['database']['username'];
    $DBPASSWD = $settings['database']['password'];
    $DBSERVER = $settings['database']['host'];
    $DBDRIVER = $settings['database']['driver'];

} else {

    $template = $twig->loadTemplate('error.tmpl');
    echo $template->render( array(
      'pageTitle' => "OSCAR - ERROR",
      'error'     => "Unable to open the configuration file 'config/config.ini.php'"
    ) );
    // No point in continuing, kill ourself
    die();

}


/**
 * Maybe the user wants to login?
 */
if ( isset($values['cmd']) && $values['cmd'] == "checklogin" ) {
  $auth = new Authenticate();
  $user = $auth->login( $values['username'], $values['password'] );
}

if ( isset($values['cmd']) && $values['cmd'] == "logout" ) {
  $auth = new Authenticate();
  $user = $auth->logout();
}

/**
 * Check if the user is logged in or not
 */
//$priv = authenticate();
$priv = false;
if ( isset($_SESSION['username']) && isset($_SESSION['password']) ) {
	$auth = new Authenticate();
	$priv = $auth->authenticate( $_SESSION['username'], $_SESSION['password'] );
}


/**
 * Test to load data before the asset form is displayed in preparation of using templates
 *
 * N O T E : Added 2016-07-22
 *           The following will be phased out as the class Utilities will be replaced
 *           by Asset and DbHandler...
 */

include_once("classes/Utilities.class.php");
/*
$utilDb = mysqli_connect( $DBSERVER, $DBUSER, $DBPASSWD, $DBNAME );
if ( $utilDb->connect_error ) {
    echo "Database connection failed: " . $utilDb->connect_error;
} else {
    $utils = new Utilities( $utilDb );

    //$assetCnt  = $utils->getCount();
    //$catArray  = $utils->getCategories();  // Get a list of categories
    //$statArray = $utils->getStatus();      // Get a list of status levels
    //$licArray  = $utils->getLicense();     // Get a list of licenses (disabled 2016-07-27/Daniel)
    //$depArray  = $utils->getDepartments(); // Get a list of departments
    //$supArray  = $utils->getSuppliers();   // Get a list of suppliers
}
*/

/**
 * Create an instance of DbHandler and fetch data to be used in the asset form
 */
try {
    $dbh = new DbHandler( $DBNAME, $DBUSER, $DBPASSWD, $DBDRIVER, $DBSERVER, $DBSCHEMA );
    $assetCnt = $dbh->getCount();
    $catArray  = $dbh->categories();
    $arClients = $dbh->clients();
		$depArray  = $dbh->departments();
		$manufArray = $dbh->manufacturers();
		$statArray = $dbh->status();
		$supArray  = $dbh->suppliers();
} catch (Exception $ex) {
  $template = $twig->loadTemplate('error.tmpl');
  echo $template->render( array(
      'pageTitle' => "OSCAR - ERROR",
      'error'     => $ex->getMessage()
  ) );
  die(); // Not a very nice solution, but let's run with it for now
}

$utils = new Utilities( $dbh );

/**
 * Construct an array with some useful data in it
 */
$cfgData = array(
    'dbServer' => $DBSERVER,
    'dbName'   => $DBNAME,
    'dbType'   => $DBDRIVER,
    'dbUser'   => $DBUSER,
    'assetCnt' => $assetCnt,
    'priv'     => $priv,
    'userName' => $_SESSION['username']
);


try {
// Check what part to load, if any
switch ($values['cmd'])
{
    /*
     * User has asked for help, so display some info
     */
    case "help":
        $template = $twig->loadTemplate('help.tmpl');
        echo $template->render(array(
          'pageTitle'   => 'OSCAR - Help',
					'appVersion'  => $APP_VERSION,
					'appModified' => $APP_MODIFIED,
          'cfgData'     => $cfgData
        ));
        break;

    case "login":
        $template = $twig->loadTemplate('login.tmpl');
        echo $template->render(array());
        break;

    case "new":
        $template = $twig->loadTemplate('asset_form.tmpl');
        $cfgData['cmd'] = "CreateAsset";
        echo $template->render(array (
            'categories' => $catArray,
            'status'     => $statArray,
            'status'     => $statArray,
            'departments'=> $depArray,
            'suppliers'  => $supArray,
            'clients'    => $arClients,
            'cfgData'    => $cfgData
        ) );
	break;

    case "edit":
    case "Edit":
        $asset = $values['asset'];
        //$assetData = $utils->getAssetData( $asset );
	$assetData   = $dbh->getAssetData( $asset );
        $poItems     = $dbh->getPOItems( $asset );
        $logMessages = $dbh->getComments( $asset );
        if ( sizeof( $poItems ) > 0 ) { $poItemsExists = true; } else { $poItemsExists = false; }
        $template = $twig->loadTemplate('asset_form.tmpl');
        $cfgData['cmd'] = "UpdateAssetEntry";
        echo $template->render(array (
            'assetData'  => $assetData,
            '$poItemsExists' => $poItemsExists,
            'poItems'    => $poItems,
            'categories' => $catArray,
            'status'     => $statArray,
            'departments'=> $depArray,
            'suppliers'  => $supArray,
            'clients'    => $arClients,
            'logMessages'=> $logMessages,
            'cfgData'    => $cfgData
        ) );
        break;

    case "search_criteria":
				//$searchRes = $utils->searchAssets($values['search_category'], $values['search_department'], $values['search_client'], $values['search_manuf'], $values['search_text']);
        $searchRes = $dbh->searchAssets($values['search_category'], $values['search_department'], $values['search_client'], $values['search_manuf'], $values['search_text']);
        $cfgData['searchCnt'] = sizeof($searchRes);

        $template = $twig->loadTemplate('search_result.tmpl');
        echo $template->render( array(
            'pageTitle' => "OSCAR v3 Search Results",
            'cfgData'   => $cfgData,
            'categories' => $catArray,
            'searchRes'  => $searchRes
        ) );
        break;

    case "CreateAsset":
        //$asset = $utils->createAsset( $values, $_SESSION['username'] );
        $asset = $dbh->createAsset( $values, $_SESSION['username'] );
        print "New asset: " . $asset;
        if ( $asset > 0 ) {
            $assetData = $dbh->getAssetData( $asset, $_SESSION['username'] );
            $dbh->setasset( $asset );
            $dbh->setuser( $_SESSION['username'] );

            // Do we have a log message? If not, use a default one
            if ( isset($values['asset_log_message']) && strlen($values['asset_log_message']) > 1 ) {
              $dbh->setcomment( $values['asset_log_message']);
            } else {
						        $dbh->setcomment( 'Asset created' );
            }

            $dbh->insertComment();
        }
        $template = $twig->loadTemplate('asset_form.tmpl');
        echo $template->render(array (
            'assetData'  => $assetData,
            'categories' => $catArray,
            'status'     => $statArray,
            'departments'=> $depArray,
            'suppliers'  => $supArray,
            'clients'    => $arClients,
            'cfgData'    => $cfgData
        ) );

        break;

    case "UpdateAssetEntry":
        $asset = $values['assetno'];

        if ( isset($_SESSION['username']) ) {
            //$utils->updateAsset( $asset, $values, $_SESSION['username'] );
            $dbh->updateAsset( $asset, $values, $_SESSION['username'] );
            $dbh->setasset( $asset );
						$dbh->setuser( $_SESSION['username'] );

            // Do we have a log message? If not, use a default one
            if ( isset($values['asset_log_message']) && strlen($values['asset_log_message']) > 1 ) {
              $dbh->setcomment( $values['asset_log_message']);
            } else {
						        $dbh->setcomment( 'Asset modified' );
            }

						$dbh->insertComment();
            /*
						$utils->setasset( $asset );
						$utils->setuser( $_SESSION['username'] );
						$utils->setcomment( 'Asset modified' );
						$utils->insertComment();
            */
        }


        //$assetData = $utils->getAssetData( $asset );
        $assetData = $dbh->getAssetData( $asset );
        $template = $twig->loadTemplate('asset_form.tmpl');
        echo $template->render(array (
            'assetData'  => $assetData,
            'categories' => $catArray,
            'status'     => $statArray,
            'departments'=> $depArray,
            'suppliers'  => $supArray,
            'clients'    => $arClients,
            'cfgData'    => $cfgData
        ) );

        break;

	case "InsertComment":
	    break;

	case "DuplicateAsset":
	    break;

    // We are testing Twig here
    case "invoices":
        $data01 = "An invoice";
        $data02 = "Invoice no 12345";
        $data03 = "Clas Ohlson";

        $supArray = $utils->getSuppliers();
        // load template
        $template = $twig->loadTemplate('invoices.tmpl');

        // set template variables and render the template
        echo $template->render(array (
            'pageTitle' => $data01,
            'invoiceNo' => $data02,
            'supplier'  => $data03,
            'cfgData'   => $cfgData,
            'suppliers' => $supArray
        ));
        break;

    case "invoicesearch":
        $supArray = $utils->getSuppliers();

        $invoices = $utils->searchInvoices( $values['search_supplier'] );
        //$invoices = array( $values['search_supplier'], 'HP ProLiant server plus memory', 'druus', '23177401', '2013-12-19' );
        $template = $twig->loadTemplate('invoices.tmpl');
        echo $template->render(array (
            'pageTitle' => $data01,
            'cfgData'   => $cfgData,
            'suppliers' => $supArray,
            'invoices'  => $invoices,
            'choosenSuppliers' => $values['search_supplier'],
            'startdate' => $values['startdate'],
            'enddate'   => $values['enddate']
        ) );
        break;


    /***************************************/
    /**     Deal with admin functions      */
    /***************************************/
    case "admin-categories":
        $formCmd = "";
        // Create an instance of AdminUtils
        try {
          $adminUtils = new AdminUtils( $DBNAME, $DBUSER, $DBPASSWD, $DBTYPE, $DBSERVER );
          if ( $values['subcmd'] == "edit" && $values['catid'] > 0 ) {
              $categories = $adminUtils->getCategory( $values['catid'] );
              $categories = $categories[0];
              $formCmd = "update";
              $template = $twig->loadTemplate('admin-categories_edit.tmpl');
          } else if ( $values['subcmd'] == "new" ) {
              $formCmd = "create";
              $template = $twig->loadTemplate('admin-categories_edit.tmpl');
          } else if ( $values['subcmd'] == "delete" && $values['catid'] > 0 ) {
              if ( !$adminUtils->deleteCategory( $values['catid'] ) ) { echo "Bugger, something is not right!<br/>Couldn't delete: " . $values['catid'] . "\n"; }
              $categories = $adminUtils->getCategories();
              $template = $twig->loadTemplate('admin-categories_list.tmpl');
          } else if ( $values['subcmd'] == "create" ) {
              if ( !$category = $adminUtils->createCategory( $values['category'], $values['description'], $_SESSION['username'] ) ) { echo "Bugger, something is not right!<br/>The last inserted ID we received is: " . $category . "\n"; }
              $categories = $adminUtils->getCategory( $category );
              $categories = $categories[0];
              $formCmd = "update";
              $template = $twig->loadTemplate('admin-categories_edit.tmpl');
          } else if ( $values['subcmd'] == "update" && $values['catid'] > 0 ) {
              $adminUtils->updateCategory( $values['catid'], $values['category'], $values['description'], $_SESSION['username'] );
              $categories = $adminUtils->getCategory( $values['catid'] );
              $categories = $categories[0];
              $formCmd = "update";
              $template = $twig->loadTemplate('admin-categories_edit.tmpl');
          } else {
              $categories = $adminUtils->getCategories();
              $template   = $twig->loadTemplate('admin-categories_list.tmpl');
          }
              echo $template->render( array(
                  'pageTitle'   => "OSCAR v3 Admin - Suppliers",
                  'cfgData'     => $cfgData,
                  'formCmd'     => $formCmd,
                  'categories'  => $categories
              ) );
        } catch(Exception $e) {
          $template= $twig->loadTemplate('error.tmpl');
          echo $template->render(array(
            'pageTitle'  => 'OSCAR - ERROR',
            'error'      => $e->getMessage()
          ));
        }
        break;

        case "admin-suppliers":
            $formCmd = "";
            // Create an instance of AdminUtils
            try {
              $adminUtils = new AdminUtils( $DBNAME, $DBUSER, $DBPASSWD, $DBTYPE, $DBSERVER );
              if ( $values['subcmd'] == "edit" && $values['suppid'] > 0 ) {
                  $suppliers = $adminUtils->getSupplier( $values['suppid'] );
                  $suppliers = $suppliers[0];
                  $formCmd = "update";
                  $template = $twig->loadTemplate('admin-suppliers_edit.tmpl');
              } else if ( $values['subcmd'] == "new" ) {
                  $formCmd = "create";
                  $template = $twig->loadTemplate('admin-suppliers_edit.tmpl');
              } else if ( $values['subcmd'] == "delete" && $values['suppid'] > 0 ) {
                  $adminUtils->deleteSupplier( $values['suppid'] );
                  $suppliers = $adminUtils->getSuppliers();
                  $template = $twig->loadTemplate('admin-suppliers_list.tmpl');
              } else if ( $values['subcmd'] == "create" ) {
                  if ( !$supplier = $adminUtils->createSupplier( $values['supplier'], $values['description'], $values['website'], $_SESSION['username'] ) ) { echo "Bugger, something is not right!<br/>The last inserted ID we received is: " . $supplier . "\n"; }
                  $suppliers = $adminUtils->getSupplier( $supplier );
                  $suppliers = $suppliers[0];
                  $formCmd = "update";
                  $template = $twig->loadTemplate('admin-suppliers_edit.tmpl');
              } else if ( $values['subcmd'] == "update" && $values['suppid'] > 0 ) {
                  $adminUtils->updateSupplier( $values['suppid'], $values['supplier'], $values['description'], $values['website'] );
                  $suppliers = $adminUtils->getSupplier( $values['suppid'] );
                  $suppliers = $suppliers[0];
                  $formCmd = "update";
                  $template = $twig->loadTemplate('admin-suppliers_edit.tmpl');
              } else {
                  $suppliers = $adminUtils->getSuppliers();
                  $template = $twig->loadTemplate('admin-suppliers_list.tmpl');
              }
                  echo $template->render( array(
                      'pageTitle'   => "OSCAR v3 Admin - Suppliers",
                      'cfgData'     => $cfgData,
                      'formCmd'     => $formCmd,
                      'suppliers'   => $suppliers
                  ) );
            } catch(Exception $e) {
              $template= $twig->loadTemplate('error.tmpl');
              echo $template->render(array(
                'pageTitle'  => 'OSCAR - ERROR',
                'error'      => $e->getMessage()
              ));
            }
            break;

    case "search":
    default:
        $template = $twig->loadTemplate('search_form.tmpl');
        echo $template->render( array(
            'pageTitle'   => "OSCAR v3 Search",
            'cfgData'     => $cfgData,
            'categories'  => $catArray,
            'departments' => $depArray,
            'clients'     => $arClients,
            'manufacturers' => $manufArray
        ) );


}

} catch (Exception $ex) {
	$template = $twig->loadTemplate('error.tmpl');
  echo $template->render( array(
      'pageTitle' => "OSCAR - ERROR",
      'error'     => $ex->getMessage()
  ) );
}
// Close the database connection
//$utilDb->close();

?>
