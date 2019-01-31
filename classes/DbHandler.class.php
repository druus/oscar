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

    // Functions used for initialising variables
  	function setdb($db)
  	{
  		$this->db = $db;
  	}

  	function getdb()
  	{
  		return $this->db;
  	}

  	function setasset($asset)
  	{
  		$this->asset = $asset;
  	}

  	function getasset()
  	{
  		return $this->asset;
  	}

  	function setcomment($comment)
  	{
  		$this->comment = $comment;
  	}

  	function getcomment()
  	{
  		return $this->comment;
  	}

  	function setuser($user)
  	{
  		$this->user = $user;
  	}

  	function getuser()
  	{
  		return $this->user;
  	}

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
          $stmt = $this->dbh->prepare("SELECT asset, productcode, manufacturer, model, serial, description, long_description, category, client, active, introduced, status, owner_dep, owner_name, po_number, supplier, manuf_invoice, manuf_artno, supplier_artno, barcode, comment, user, asset_entry_created, asset_entry_created_by, asset_modified, asset_modified_by FROM asset WHERE asset = :asset LIMIT 1");
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


    /**
     * Search för assets
     *
     * @return array
     */
    function searchAssets($search_category, $search_department, $search_client, $search_manuf, $search_text, $list_order = "asset")
    {

        $searchAsset =<<< EOQ
SELECT DISTINCT(a.asset), a.manufacturer, a.model, a.description, c.category, d.dep_name AS department
FROM asset a, category c, departments d, clients o, asset_history h
WHERE a.category = c.id
AND a.owner_dep = d.dep_id
AND a.client = o.cid
AND a.asset = h.asset
EOQ;

        if (isset($search_category))
                $searchAsset .= " AND c.id IN (" . implode(",", $search_category) . ") ";

        if (isset($search_department))
                $searchAsset .= " AND d.dep_id IN (" . implode(",", $search_department) . ") ";

        if (isset($search_client))
                $searchAsset .= " AND a.client IN (" . implode(",", $search_client) . ") ";

    if (isset($search_manuf))
        $searchAsset .= " AND a.manufacturer IN ('" . implode("','", $search_manuf) . "') ";

        if (isset($search_text))
            $searchAsset .= " AND (a.asset LIKE '%$search_text%' OR manufacturer LIKE '%$search_text%' OR model LIKE '%$search_text%' OR serial LIKE '%$search_text%' OR a.description LIKE '%$search_text%' OR a.comment LIKE '%$search_text%' OR parent_id LIKE '%$search_text%' OR h.comment LIKE '%$search_text%' OR a.barcode LIKE '%$search_text%' OR a.supplier_artno LIKE '%$search_text%') ";

        $searchAsset .= "ORDER BY " . $list_order;

/*
        $utildb = $this->getdb();
        $res = $utildb->execute( $searchAsset );
        if ( $res == false ) {
            echo "ERROR: " . $this->getdb()->error;
        } else {
            return $res->fetch_all(MYSQLI_ASSOC);
        }
*/



        if ( $stmt = $this->dbh->query($searchAsset) ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM searchAssets()


    /**
     * Create a new asset
     * @return Asset number
     */
    function createAsset( $values, $username )
    {
	      $productcode  = $values['productcode'];
        $manufacturer = $values['manufacturer'];
        $model        = $values['model'];
        $description  = $values['description'];
        $long_description = $values['long_description'];
        $introduced   = $values['introduced'];
        $serial       = $values['serial'];
        $status       = $values['status'];
        $category     = $values['category'];
        $owner_dep    = $values['owner_dep'];
        $supplier     = $values['supplier'];
        $supplier_artno=$values['supplier_artno'];
        $client       = $values['client'];
        $barcode      = $values['barcode'];
        $po_number    = $values['po_number'];
        $manuf_invoice= $values['manuf_invoice'];

        $query = "INSERT INTO asset (productcode, manufacturer, model, description, long_description, introduced, serial, category, status, owner_dep, client, supplier, supplier_artno, barcode, po_number, manuf_invoice, asset_entry_created, asset_entry_created_by) ";
        $query .= "VALUES ('". $productcode . "', '" . $manufacturer . "', '" . $model . "', '" . $description . "', '" . $long_description . "', '" . $introduced . "', '" . $serial . "', " . $category . ", " . $status . ", " . $owner_dep . ", " . $client . ", " . $supplier . ", '" . $supplier_artno . "', '" . $barcode . "', '". $po_number . "', '" . $manuf_invoice . "', NOW(), '" . $username . "')";

        if ( $stmt = $this->dbh->query($query) ) {
            return $this->dbh->lastInsertId();
        }

        return false;

    } // EOM createAsset()


    /**
     * Update an existing asset
     */
    function updateAsset($asset, $values, $username)
    {

        if ( $asset > 0 ) {
            $productcode  = $values['productcode'];
            $manufacturer = $values['manufacturer'];
            $model        = $values['model'];
            $description  = $values['description'];
            $long_description = $values['long_description'];
            $introduced   = $values['introduced'];
            $serial       = $values['serial'];
            $status       = $values['status'];
            $category     = $values['category'];
            $owner_dep    = $values['owner_dep'];
            $supplier     = $values['supplier'];
            $supplier_artno= $values['supplier_artno'];
            $client       = $values['client'];
            $barcode      = $values['barcode'];
            $po_number    = $values['po_number'];
            $manuf_invoice= $values['manuf_invoice'];

            $query = "UPDATE asset SET productcode = '" . $productcode . "', manufacturer = '" . $manufacturer . "', model = '" . $model . "', description = '" . $description . "', long_description = '" . $long_description . "', introduced = '" . $introduced . "', serial = '" . $serial . "', category = " . $category . ", status = " . $status . ", owner_dep = " . $owner_dep . ", supplier = " . $supplier . ", supplier_artno = '" . $supplier_artno . "', client = " . $client . ", barcode = '" . $barcode . "', po_number = '" . $po_number . "', manuf_invoice = '" . $manuf_invoice . "', asset_modified = NOW(), asset_modified_by = '" . $username . "' WHERE asset = " . $asset;

            if ( $stmt = $this->dbh->query($query) ) {
                return true;
            }

            return false;
        }

    } // EOM updateAsset()

    /**
     * Insert a comment into the history table
     */
    function insertComment()
  	{
  		$asset = $this->getasset();
  		$comment = $this->getcomment();
  		$user = $this->getuser();

  		// Construct an INSERT statement
  		$insLog = "INSERT INTO asset_history ";
  		$insLog .= "(asset, comment, updated_by, updated_time) ";
  		$insLog .= "VALUES ('" . $asset . "', ";
  		$insLog .= "'" . $comment . "', ";
  		$insLog .= "'" . $user . "', ";
  		$insLog .= "NOW()) ";

      if ( $stmt = $this->dbh->query($insLog) ) {
          return true;
      }

      return false;

  	}
} // EOM DbHandler()

?>
