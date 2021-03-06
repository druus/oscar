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

    function __construct( $name, $user, $passwd, $type = "mysql", $host = "localhost", $schema = "" )
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
            throw new Exception( $e->getMessage() . " (dsn:" . $dsn . ")" );
        }

	// If we are using PostgreSQL, set schema
	if ( $type == "pgsql" ) {
		try {
			$this->dbh->exec("SET search_path TO " . $schema);
		} catch (PDOException $e) {
			throw new Exception( $e->getMessage() );
		}
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
     * Get purchase order items
     * @return array
     */
    function getPOitems( $asset )
    {
      if ( ! is_numeric( $asset ) ) {
        throw new Exception( "Asset '$asset' is not a numerical value");
      } else {

        try {
          $stmt = $this->dbh->prepare("SELECT a.asset AS asset, p.po_id AS po_id, p.item AS item, p.description, p.supplier_artno, p.qty AS qty, p.price FROM po_items p, asset a WHERE p.po_id = a.po_number AND asset = :asset");
          $stmt->bindParam(':asset', $asset, PDO::PARAM_INT);

          if ( $stmt->execute() ) {
            $assetData = $stmt->fetchAll();
            //if ( is_numeric( $assetData['asset'] ) && $assetData['asset'] > 0 ) {
              return $assetData;
            //} else {
            //  throw new Exception( "PO items for asset '$asset' not found");
            //}
          }
        } catch (Exception $e) {
          throw new Exception($e->getMessage());
        }
      }

      return false;
    } // EOM getPOItems()


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
            #$searchAsset .= " AND (a.asset LIKE '%$search_text%' OR manufacturer LIKE '%$search_text%' OR model LIKE '%$search_text%' OR serial LIKE '%$search_text%' OR a.description LIKE '%$search_text%' OR a.comment LIKE '%$search_text%' OR parent_id LIKE '%$search_text%' OR h.comment LIKE '%$search_text%' OR a.barcode LIKE '%$search_text%' OR a.supplier_artno LIKE '%$search_text%') ";
            $searchAsset .= " AND (manufacturer LIKE '%$search_text%' OR model LIKE '%$search_text%' OR serial LIKE '%$search_text%' OR a.description LIKE '%$search_text%' OR a.comment LIKE '%$search_text%' OR parent_id LIKE '%$search_text%' OR h.comment LIKE '%$search_text%' OR a.barcode LIKE '%$search_text%' OR a.supplier_artno LIKE '%$search_text%') ";

        $searchAsset .= "ORDER BY " . $list_order;


	// Run the query
	try {
        	if ( $stmt = $this->dbh->query($searchAsset) ) {
          		return $stmt->fetchAll();
		}
        } catch ( PDOException $e ) {
		throw new Exception( $e->getMessage() );
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

        // If no PO number has been given, set it to 0
        if ( !isset($po_number) || $po_number == "" ) {
          $po_number = 0;
        }

        $querytmp = "INSERT INTO asset (productcode, manufacturer, model, description, long_description, introduced, serial, category, status, owner_dep, client, supplier, supplier_artno, barcode, po_number, manuf_invoice, asset_entry_created, asset_entry_created_by) ";
        $querytmp .= "VALUES ('". $productcode . "', '" . $manufacturer . "', '" . $model . "', '" . $description . "', '" . $long_description . "', '" . $introduced . "', '" . $serial . "', " . $category . ", " . $status . ", " . $owner_dep . ", " . $client . ", " . $supplier . ", '" . $supplier_artno . "', '" . $barcode . "', ". $po_number . ", '" . $manuf_invoice . "', NOW(), '" . $username . "')";

        if ($this->dbh->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql') {
          $query = "INSERT INTO asset (productcode, manufacturer, model, description, long_description, introduced, serial, category, status, owner_dep, client, supplier, supplier_artno, barcode, po_number, manuf_invoice, asset_entry_created, asset_entry_created_by) ";
          $query .= "VALUES (:productcode, :manufacturer, :model, :description, :long_description, :introduced, :serial, :category, :status, ";
          $query .= ":owner_dep, :client, :supplier, :supplier_artno, :barcode, :po_number, :manuf_invoice, NOW(), :username) ";
          $query .= "RETURNING asset";
        } else {
          $query = "INSERT INTO asset (productcode, manufacturer, model, description, long_description, introduced, serial, category, status, owner_dep, client, supplier, supplier_artno, barcode, po_number, manuf_invoice, asset_entry_created, asset_entry_created_by) ";
          $query .= "VALUES (:productcode, :manufacturer, :model, :description, :long_description, :introduced, :serial, :category, :status, ";
          $query .= ":owner_dep, :client, :supplier, :supplier_artno, :barcode, :po_number, :manuf_invoice, NOW(), :username)";
        }

        #print $querytmp; die();


            $stmt = $this->dbh->prepare( $query );

            $stmt->bindParam(':productcode', $productcode, PDO::PARAM_STR);
            $stmt->bindParam(':manufacturer', $manufacturer, PDO::PARAM_STR);
            $stmt->bindParam(':model', $model, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':long_description', $long_description, PDO::PARAM_STR);
            $stmt->bindParam(':introduced', $introduced, PDO::PARAM_STR);
            $stmt->bindParam(':serial', $serial, PDO::PARAM_STR);
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':owner_dep', $owner_dep, PDO::PARAM_INT);
            $stmt->bindParam(':client', $client, PDO::PARAM_INT);
            $stmt->bindParam(':supplier', $supplier, PDO::PARAM_INT);
            $stmt->bindParam(':supplier_artno', $supplier_artno, PDO::PARAM_STR);
            $stmt->bindParam(':barcode', $barcode, PDO::PARAM_STR);
            $stmt->bindParam(':po_number', $po_number, PDO::PARAM_INT);
            $stmt->bindParam(':manuf_invoice', $manuf_invoice, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);

          try {
            if ( ! $stmt->execute() ) {
              $errorCode = $stmt->errorInfo();
              throw new Exception($errorCode[2]); // Return error message
            }



            if ($this->dbh->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql') {
              $tmpVal = $stmt->fetchAll();
              $tmpVal2 = $tmpVal[0][0];
              $lastInsertedId = $tmpVal2;
            } else {
               $lastInsertedId = $this->dbh->lastInsertId('asset_id_seq');
            }


        } catch (PDOException $pe) {
            throw new Exception($pe->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }


        return $lastInsertedId;

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

            $query = "UPDATE asset SET productcode = '" . $productcode . "', manufacturer = '" . $manufacturer . "', model = '" . $model . "', description = '" . $description . "', long_description = '" . $long_description . "', introduced = '" . $introduced . "', serial = '" . $serial . "', category = " . $category . ", status = " . $status . ", owner_dep = " . $owner_dep . ", supplier = " . $supplier . ", supplier_artno = '" . $supplier_artno . "', client = " . $client . ", barcode = '" . $barcode . "', po_number = " . $po_number . ", manuf_invoice = '" . $manuf_invoice . "', asset_modified = NOW(), asset_modified_by = '" . $username . "' WHERE asset = " . $asset;

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

        if ( ! is_numeric( $asset ) ) {
            throw new Exception( "Asset '$asset' is not a numerical value" );
        }

        try {
            if ($this->dbh->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql') {
              $stmt = $this->dbh->prepare("INSERT INTO asset_history (asset, comment, updated_by, updated_time) VALUES (:asset, :comment, :user, NOW()) RETURNING hid");
            } else {
              $stmt = $this->dbh->prepare("INSERT INTO asset_history (asset, comment, updated_by, updated_time) VALUES (:asset, :comment, :user, NOW())");
            }

            $stmt->bindParam(':asset', $asset, PDO::PARAM_INT);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);

            $stmt->execute();

            if ($this->dbh->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql') {
              $tmpVal = $stmt->fetchAll();
              $tmpVal2 = $tmpVal[0][0];
              $lastInsertedId = $tmpVal2;
            } else {
               $lastInsertedId = $this->dbh->lastInsertId('asset_id_seq');
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

        return $lastInsertedId;

    } // EOM insertComment()

    /**
     * Retrieve log comments for a given asset
     */
    function getComments($asset)
    {
        if ( ! is_numeric( $asset ) ) {
          throw new Exception( "Asset '$asset' is not a numerical value" );
        }

        try {
            $stmt = $this->dbh->prepare("SELECT comment, updated_by, updated_time FROM asset_history WHERE asset = :asset ORDER BY updated_time");
            $stmt->bindParam(':asset', $asset, PDO::PARAM_INT);

            if ( $stmt->execute() ) {
                return $stmt->fetchAll();
            }
        } catch (Exception $e) {
          throw new Exception($e->getMessage);
        }
    }
} // EOC DbHandler()

?>
