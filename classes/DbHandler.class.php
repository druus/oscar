<?php
/******************************************************************************
** Filename     DbHandler.class.php
** Description  Handles database access and operations
** Created by   Daniel Ruus
** Created      2016-07-22
** Version      1.0
******************************************************************************/



/*******************************************************************************
** Class       DbHandler
** Description Database access and operations handler
** Created by  Daniel Ruus
** Created     2016-07-22
** Modified by -
** Modified    -
** Version     1.0
*******************************************************************************/
class DbHandler {

    private $dbh = null; // Database connection handle
    private $dbname;
    private $dbuser;
    private $dbpasswd;
    private $dbtype;
    private $dbhost;

    function __construct( $name, $user, $passwd, $type = "mysql", $host = "localhost" )
    {

        $this->dbname   = $name;
        $this->dbuser   = $user;
        $this->dbpasswd = $passwd;
        $this->dbtype   = $type;
        $this->dbhost   = $host;

        // Connect to the database
        $dsn = $this->dbtype . ":dbname=" . $this->dbname . ";host=" . $this->dbhost;
        try {
            $this->dbh = new PDO( $dsn, $this->dbuser, $this->dbpasswd );
        } catch (PDOException $e) {
            //echo "Connection failed: " . $e->getMessage();
            throw new Exception( $e->getMessage() );
        }

        return $this->dbh;

    } // EOM __construct()


    /***************************************/
    /**           M E T H O D S            */
    /***************************************/

    /**
     * Count the number of assets
     *
     * @return integer A count of the number of assets available
     */
    function getCount()
    {

        $cntAssets = 0;
        try {
          $stmt = $this->dbh->prepare("SELECT COUNT(asset) AS assets FROM asset");
          $stmt->execute();

          while ( $row = $stmt->fetch() ) {
            $cntAssets = $row['assets'];
          }
        }
        catch (PDOException $e) {
          throw new Exception( $e->getMessage() );
        }

        $stmt = null;

        return $cntAssets;

    } // EOM getCount();


    function getLatestAsset()
    {
        $lastAsset = 0;
        $stmt = $this->dbh->prepare("SELECT MAX(asset) AS asset FROM asset");
        if ( $stmt->execute() ) {
            while ( $row = $stmt->fetch() ) {
                $lastAsset = $row['asset'];
            }
        }
        return $lastAsset;

    } // EOM getLatestAsset();


    /**
     * Get data for a given asset
     * @return array
     */
    function getAssetData( $asset )
    {
      if ( ! is_numeric( $asset ) ) {
        throw new Exception( "Asset '$asset' is not a numerical value");
      } else {

        try {
          $stmt = $this->dbh->prepare("SELECT asset, productcode, manufacturer, model, serial, description, category, client, active, introduced, status, owner_dep, owner_name, po_number, supplier, manuf_invoice, manuf_artno, supplier_artno, barcode, comment, user, asset_entry_created, asset_entry_created_by, asset_modified, asset_modified_by FROM asset WHERE asset = :asset LIMIT 1");
          $stmt->bindParam(':asset', $asset, PDO::PARAM_INT);

          if ( $stmt->execute() ) {
            $assetData = $stmt->fetch();
            if ( is_numeric( $assetData['asset'] ) && $assetData['asset'] > 0 ) {
              return $assetData;
            } else {
              throw new Exception( "Asset '$asset' not found.");
            }
          }
        } catch (Exception $e) {
          throw new Exception($e->getMessage());
        }
      }

      return false;

    } // EOM getAssetData()

    /**
     * Retrieve a list of categories
     * @return array
     */
    function categories()
    {
        $stmt = $this->dbh->prepare("SELECT id, category FROM category WHERE active = 'Yes' ORDER BY category");
        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM categories()

    /**
     * Retrieve a list of clients
     * @return array
     */
    function clients()
    {
        $stmt = $this->dbh->prepare("SELECT cid AS id, client FROM clients WHERE active = 'Yes' ORDER BY client");
        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM clients()

    /**
     * Retrieve a list of departments
     * @return array
     */
    function departments()
    {
        $stmt = $this->dbh->prepare("SELECT dep_id AS id, dep_name AS department FROM departments WHERE active='Yes' ORDER BY dep_name");
        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM departments()

    /**
     * Retrieve a list of manufacturers
     * @return array
     */
    function manufacturers()
    {
        $stmt = $this->dbh->prepare("SELECT DISTINCT(manufacturer) FROM asset ORDER BY manufacturer");
        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM manufacturers()

    /**
     * Retrieve a list of status codes
     * @return array
     */
    function status()
    {
        $stmt = $this->dbh->prepare("SELECT stat_id id, status FROM status WHERE active = 'Yes' ORDER BY status");
        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM status()

    /**
     * Retrieve a list of suppliers
     * @return array
     */
    function suppliers()
    {
        $stmt = $this->dbh->prepare("SELECT supp_id AS id, supplier FROM suppliers ORDER BY supplier");
        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM suppliers()

} // EOM DbHandler()

?>
