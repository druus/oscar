<?php

include('classes/Asset.class.php');
include('classes/DbHandler.class.php');

// Create an instance of Asset
$asset = new Asset();

// Set properties
echo "Setting properties\n";
echo "Asset       : 56\n";
echo "Manufacturer: Gnumaker\n";
echo "Model       : Supermodel\n";

$asset->setAsset( 56 );
$asset->setManufacturer( "Gnumaker" );
$asset->setModel( "Supermodel" );

echo "Read back the properties\n";
echo "Asset       : " . $asset->getAsset() . "\n";
echo "Manufacturer: " . $asset->getManufacturer() . "\n";
echo "Model       : " . $asset->getModel() . "\n";

echo "\nOK, now let us connect to the database.\n";
$dbname="asset_v3";
$dbuser="root";
$dbpasswd="g4dba103";
$dbhost="localhost";
$dbtype="mysql";
echo "dbname=$dbname\n";
echo "dbuser=$dbuser\n";
echo "dbhost=$dbhost\n";
echo "dbtype=$dbtype\n";

// Create an instance of DbHandler
$dbh = new DbHandler( $dbname, $dbuser, $dbpasswd, $dbtype, $dbhost );
echo "Getting the last asset. It seem to be: " . $dbh->getLatestAsset() . "\n";
echo "And list clients...\n";
$clients = $dbh->clients();
echo "\nUsing print_r()\n";
print_r( $clients );
echo "\n";
echo "Looping through the array 'manually'\n";
foreach  ( $clients  as $id => $client) {
    echo "ID = " . $clients[$id]['id'] . " - client = " . $client;
}
$dbh = null; // Close the db handle
?>
