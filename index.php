<?php
/******************************************************************************
** Filename     index.php
** Description  Main entry point
** License      The MIT License (MIT) (See the file LICENSE)
** Copyright (c) 2015, 2016 Daniel Ruus
******************************************************************************/
$APP_VERSION="0.1.0";
$APP_AUTHOR="Daniel Ruus";
$APP_MODIFIED="2016-07-27";

session_start();

/**
 * Setup Twig, our templating engine
 */
include 'Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('views');
// Initialize Twig environment
$twig = new Twig_Environment($loader);



// Read the configuration file if exists
//include_once("./config/admin.conf.php");

// Include required helper functions
include_once("./include/dbfunctions.inc.php");
//include_once("./include/functions.inc.php");
include_once("classes/Asset.class.php");
include_once("classes/DbHandler.class.php");
include_once("classes/AdminUtils.class.php");
require("./include/authenticate.inc.php");

// Check that the config file has been read!
if (!isset($DBSERVER))
	die("Unable to read contents of configuration file.");

($_SERVER['REQUEST_METHOD'] == 'GET') ? $values = $_GET : $values = $_POST;

// Read the configuration file config/config.ini.php and extract the
// intresting bits
// Read the configuration file

if ( $settings = parse_ini_file("config/config.ini.php", true) ) {

    $DBNAME   = $settings['database']['schema'];
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


// Create a connection to the database
$mydb = new mysqldb($DBSERVER, $DBNAME, $DBUSER, $DBPASSWD);
if (! $mydb)
	print_error("Unable to connect to database.<br/><b>MySQL error</b><br/>Errno: " . mysql_errno() . "<br/>Error: " . mysql_error(), "error");




/**
 * Check if the user is logged in or not
 */
$priv = authenticate();

/**
 * Test to load data before the asset form is displayed in preparation of using templates
 *
 * N O T E : Added 2016-07-22
 *           The following will be phased out as the class Utilities will be replaced
 *           by Asset and DbHandler...
 */
include_once("classes/Utilities.class.php");
$utilDb = mysqli_connect( $DBSERVER, $DBUSER, $DBPASSWD, $DBNAME );
if ( $utilDb->connect_error ) {
    echo "Database connection failed: " . $utilDb->connect_error;
} else {
    $utils = new Utilities( $utilDb );

    //$assetCnt  = $utils->getCount();
    $catArray  = $utils->getCategories();  // Get a list of categories
    $statArray = $utils->getStatus();      // Get a list of status levels
    //$licArray  = $utils->getLicense();     // Get a list of licenses (disabled 2016-07-27/Daniel)
    $depArray  = $utils->getDepartments(); // Get a list of departments
    $supArray  = $utils->getSuppliers();   // Get a list of suppliers
}

/**
 * Create an instance of DbHandler and fetch data to be used in the asset form
 */
try {
    $dbh = new DbHandler( $DBNAME, $DBUSER, $DBPASSWD, $DBTYPE, $DBSERVER );
    $assetCnt = $dbh->getCount();
    $arClients = $dbh->clients();
} catch (Exception $ex) {
  $template = $twig->loadTemplate('error.tmpl');
  echo $template->render( array(
      'pageTitle' => "OSCAR - ERROR",
      'error'     => $ex->getMessage()
  ) );
  die(); // Not a very nice solution, but let's run with it for now
}

/**
 * Construct an array with some useful data in it
 */
$cfgData = array(
    'dbServer' => $DBSERVER,
    'dbName'   => $DBNAME,
    'dbType'   => $DBTYPE,
    'dbUser'   => $DBUSER,
    'assetCnt' => $assetCnt,
    'priv'     => $priv,
    'userName' => $_SESSION['username']
);


// Check what part to load, if any
switch ($values['cmd'])
{
    /*
     * User has asked for help, so display some info
     */
    case "help":
        $template = $twig->loadTemplate('help.tmpl');
        echo $template->render(array(
          'pageTitle' => 'OSCAR - Help',
          'cfgData'   => $cfgData
        ));
        break;

    case "new":
        //asset_form($mydb, 0, $resArray, $licArray, $statArray);
        $template = $twig->loadTemplate('asset_form2.tmpl');
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
        $assetData = $utils->getAssetData( $asset );
        $template = $twig->loadTemplate('asset_form2.tmpl');
        $cfgData['cmd'] = "UpdateAssetEntry";
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

    case "search_criteria":
        //search_assets($mydb, $values['search_category'], $values['search_department'], $values['search_client'], $values['search_manuf'], $values['search_text']);
	$searchRes = $utils->searchAssets($values['search_category'], $values['search_department'], $values['search_client'], $values['search_manuf'], $values['search_text']);
        $cfgData['searchCnt'] = sizeof($searchRes);

        $template = $twig->loadTemplate('search_result.tmpl');
        echo $template->render( array(
            'pageTitle' => "OSCAR v3 Search Results",
            'cfgData'   => $cfgData,
            'categories' => $catArray,
            'searchRes'  => $searchRes
        ) );
        break;

//	case "CreateNewAsset":
//		create_asset_entry($mydb, $values);
//		break;

    case "CreateAsset":
        $asset = $utils->createAsset( $values, $_SESSION['username'] );
        if ( $asset > 0 ) {
            $assetData = $utils->getAssetData( $asset, $_SESSION['username'] );
            $utils->setasset( $asset );
            $utils->setuser( $_SESSION['username'] );
            $utils->setcomment( 'Asset created' );
            $utils->insertComment();
        }
        $template = $twig->loadTemplate('asset_form2.tmpl');
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
            $utils->updateAsset( $asset, $values, $_SESSION['username'] );
						$utils->setasset( $asset );
						$utils->setuser( $_SESSION['username'] );
						$utils->setcomment( 'Asset modified' );
						$utils->insertComment();
        }

        $assetData = $utils->getAssetData( $asset );
        $template = $twig->loadTemplate('asset_form2.tmpl');
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
	    //insert_history_comment($mydb, $values);
	    break;

	case "DuplicateAsset":
	    //duplicate_asset($mydb, $values['asset']);
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
        // Assemble a few arrays
        $depArray   = $utils->getDepartments();
        $manufArray = $utils->getManufacturers();
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

// Close the database connection
$utilDb->close();

?>
