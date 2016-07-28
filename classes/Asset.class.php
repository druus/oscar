<?php
/******************************************************************************
** Filename     Asset.class.php
** Description  Plain Old PHP Object (POPO) of an asset
** Created by   Daniel Ruus
** Created      2016-07-22
** Version      1.0
******************************************************************************/


// Create an interface for the POPO
interface Popo {

}

/*******************************************************************************
** Class       Asset
** Description Acts as a popo for an asset entry
** Created by  Daniel Ruus
** Created     2016-07-22
** Modified by -
** Modified    -
** Version     1.0
*******************************************************************************/
class Asset {

    /* Private variables */
    private $asset;
    private $productcode;
    private $manufacturer;
    private $model;
    private $description;
    private $serial;
    private $client = 10; // Temporary hack - set client ID to 10 for now...
    private $category;
    private $status;
    private $department;
    private $supplier;
    private $barcode;
    private $po_number;
    private $manuf_invoice;
    private $asset_entry_created;
    private $asset_entry_modified;
    private $asset_modified;
    private $asset_modified_by;

    /**
     * Constructor
     */
    function __construct() {

    } // EOM __construct()

    /* Destructor */
    function __destruct() {

    } // EOM __destruct()

    /* Setters and getters */
    function setAsset( $asset ) { $this->asset = $asset; }
    function getAsset() { return $this->asset; }

    function setManufacturer( $manufacturer ) { $this->manufacturer = $manufacturer; }
    function getManufacturer() { return $this->manufacturer; }

    function setModel( $model ) { $this->model = $model; }
    function getModel() { return $this->model; }

    function setCategory( $category ) { $this->category = $category; }
    function getCategory() { return $this->category; }

    function setStatus( $status ) { $this->status = $status; }
    function getStatus() { return $this->status; }

    function setDepartment( $department ) { $this->department = $department; }
    function getDepartment() { return $this->Sdepartment; }

    function setSupplier( $supplier ) { $this->supplier = $supplier; }
    function getSupplier() { return $this->supplier; }

    function setBarcode( $barcode ) { $this->barcode = $barcode; }
    function getBarcode() { return $this->barcode; }

    function setPONumber( $po_number ) { $this->po_number = $po_number; }
    function getPONumber() { return $this->po_number; }

    function setInvoice( $manuf_invoice ) { $this->manuf_invoice; }
    function getInvoice() { return $this->manuf_invoice; }

    function setAssetCreated( $asset_entry_created ) { $this->asset_entry_created = $asset_entry_created; }
    function getAssetCreated() { return $this->asset_entry_created; }

    function setAssetCreatedBy( $asset_entry_created_by ) { $this->asset_entry_created_by = $asset_entry_created_by; }
    function getAssetCreatedBy() { return $this->asset_entry_created_by; }

    function setAssetModified( $asset_modified ) { $this->asset_modified = $asset_modified; }
    function getAssetModified() { return $this->asset_modified; }

    function setAssetModifiedBy( $asset_modified_by ) { $this->asset_modified_by = $asset_modified_by; }
    function getAssetModifiedBy() { return $this->asset_modified_by; }

} // EOC Asset


/*******************************************************************************
** Class        Utilities
** Arguments    $db - database connection ID
**              $location - pod/office/lab location
**              $port - network port
** Return value $selPort - selected port (integer)
** Description  Allow easy interaction of comments from the asset history table
** Created by   Daniel Ruus
** Version      1.0
*******************************************************************************/
class UtilitiesTemp
{
    var $db, $asset, $comment, $user;


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


    // Constructor for the class
    function Utilities($db)
    {

        $this->setdb($db);

    }
	
    // Functions for various operations

    /**
     * Count the number of assets
     *
     * @return int
     */
    function getCount()
    {

        $query = "SELECT count(asset) AS assets FROM asset";
        $utildb = $this->getdb();
        $res = $utildb->query( $query );
        if ( $res == false ) {
            return false;
        }
        
        while ( $row = $res->fetch_assoc() ) {
            $cntAssets = $row['assets'];
        }

        $res->close();

        return $cntAssets;

    } // EOM getCount();


    /**
     * Get details for a given asset
     *
     */
    function getAssetData( $asset )
    {

        $query = "SELECT asset, productcode, manufacturer, model, serial, date_code, description, category, computer_user, client, parent_id, license_type, active, introduced, status, owner_dep, owner_name, po_number, supplier, manuf_invoice, manuf_artno, supplier_artno, barcode, email, comment, user, asset_entry_created, asset_entry_created_by, asset_modified, asset_modified_by FROM asset WHERE asset = " . $asset . " LIMIT 1";
        $utildb = $this->getdb();
        $res = $utildb->query( $query );
        if ( $res == false ) {
            return false;
        }

        while ( $row = $res->fetch_assoc() ) {
            $cntAsset = $row;
        }

        $res->close();

        return $cntAsset;
    } // EOM getAssetData()


    /**
     * Retrieve a list of categories
     *
     * @return array
     */
    function getCategories()
    {

        $query = "SELECT id, category FROM category WHERE active = 'Yes' ORDER BY category";
        $catdb = $this->getdb();
        $res = $catdb->query( $query );
        if ( $res == false ) {
            echo "ERROR: " . $this->getdb()->error;
        } else {
            return $res->fetch_all(MYSQLI_ASSOC);
        }

    } // EOM getCategories()


    /**
     * Retrieve a list of status levels
     *
     * @return array
     */
    function getStatus()
    {

        $query = "SELECT stat_id id, status FROM status WHERE active = 'Yes' ORDER BY status";
        $utildb = $this->getdb();
        $res = $utildb->query( $query );
        if ( $res == false ) {
            echo "ERROR: " . $this->getdb()->error;
        } else {
            return $res->fetch_all(MYSQLI_ASSOC);
        }

    } // EOM getStatus()


    /**
     * Retrieve a list of software licenses
     *
     * @return array
     */
    function getLicense()
    {

        $query = "SELECT id, license_name license FROM sw_license WHERE active = 'Yes' ORDER BY license_name";
        $utildb = $this->getdb();
        $res = $utildb->query( $query );
        if ( $res == false ) {
            echo "ERROR: " . $this->getdb()->error;
        } else {
            return $res->fetch_all(MYSQLI_ASSOC);
        }

    } // EOM getLicense()


    /**
     * Retrieve a list of departments
     *
     * @return array
     */
    function getDepartments()
    {

        $query = "SELECT dep_id AS id, dep_name AS department FROM aq_department WHERE active='Yes' ORDER BY dep_name";
        $utildb = $this->getdb();
        $res = $utildb->query( $query );
        if ( $res == false ) {
            echo "ERROR: " . $this->getdb()->error;
        } else {
            return $res->fetch_all(MYSQLI_ASSOC);
        }

    } // EOM getDepartments()


    /**
     * Retrieve a list of departments
     *
     * @return array
     */
    function getManufacturers()
    {

        $query = "SELECT DISTINCT(manufacturer) FROM asset ORDER BY manufacturer";
        $utildb = $this->getdb();
        $res = $utildb->query( $query );
        if ( $res == false ) {
            echo "ERROR: " . $this->getdb()->error;
        } else {
            return $res->fetch_all(MYSQLI_ASSOC);
        }

    } // EOM getManufacturers()


    /**
     * Retrieve a list of suppliers
     *
     * @return array
     */
    function getSuppliers()
    {

        $query = "SELECT supp_id AS id, supplier FROM aq_supplier ORDER BY supplier";
        $utildb = $this->getdb();
        $res = $utildb->query( $query );
        if ( $res == false ) {
            echo "ERROR: " . $this->getdb()->error;
        } else {
            return $res->fetch_all(MYSQLI_ASSOC);
        }

    } // EOM getManufacturers()


    /**
     * Search för invoices based on suppliers
     */
    function searchInvoices( $suppliers )
    {

        $query = "SELECT s.supplier, p.id AS invoice_no, p.description, p.ordered_by, p.supplier_invoice_no, p.order_date FROM aq_supplier s, purchase_orders p WHERE s.supp_id = p.aq_supplier AND s.supp_id IN (" . implode(",", $suppliers) . ")";
        echo "$query\n";
        $utildb = $this->getdb();
        $res = $utildb->query( $query );
        if ( $res == false ) {
            echo "ERROR: " . $this->getdb()->error;
        } else {
            return $res->fetch_all(MYSQLI_ASSOC);
        }
        

    } // EOM searchInvoices()


    /**
     * Search för assets
     *
     * @return array
     */
    function searchAssets()
    {

        $query = " SELECT DISTINCT(a.asset), a.manufacturer, a.model, a.description, c.category, d.dep_name AS department, a.original_location, a.network_port, a.computer_user
 FROM asset a, category c, aq_department d, clients o, asset_history h
 WHERE a.category = c.id
 AND a.owner_dep = d.dep_id
 AND a.client = o.cid
 AND a.asset = h.asset AND a.client IN (10)  AND (a.asset LIKE '%%' OR manufacturer LIKE '%%' OR model LIKE '%%' OR serial LIKE '%%' OR a.description LIKE '%%' OR a.comment LIKE '%%' OR computer_user LIKE '    %%' OR parent_id LIKE '%%' OR h.comment LIKE '%%') ORDER BY asset";

        $utildb = $this->getdb();
        $res = $utildb->query( $query ); 
        if ( $res == false ) {
            echo "ERROR: " . $this->getdb()->error;
        } else {
            return $res->fetch_all(MYSQLI_ASSOC);
        }
    } // EOM searchAssets(9


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
        $introduced   = $values['introduced'];
        $serial       = $values['serial'];
        $status       = $values['status'];
        $category     = $values['category'];
        $owner_dep    = $values['owner_dep'];
        $supplier     = $values['supplier'];
        $barcode      = $values['barcode'];
        $po_number    = $values['po_number'];
        $manuf_invoice= $values['manuf_invoice'];

        $query = "INSERT INTO asset (productcode, manufacturer, model, description, introduced, serial, category, status, owner_dep, supplier, barcode, po_number, manuf_invoice, asset_entry_created, asset_entry_created_by, client) ";
        $query .= "VALUES ('". $productcode . "', '" . $manufacturer . "', '" . $model . "', '" . $description . "', '" . $introduced . "', '" . $serial . "', " . $category . ", " . $status . ", " . $owner_dep . ", '" . $supplier . "', '" . $barcode . "', '". $po_number . "', '" . $manuf_invoice . "', NOW(), '" . $username . "', 10)";

        $utildb = $this->getdb();
            $res = $utildb->query( $query );
            if ( $res == false ) {
                echo "ERROR: " . $this->getdb()->error;
            }

        $asset = mysqli_insert_id( $utildb );
        return $asset;

    } // EOM createAsset()


    /**
     *
     */
    function updateAsset($asset, $values, $username)
    {

        if ( $asset > 0 ) {
            $productcode  = $values['productcode'];
            $manufacturer = $values['manufacturer'];
            $model        = $values['model'];
            $description  = $values['description'];
            $introduced   = $values['introduced'];
            $serial       = $values['serial'];
            $status       = $values['status'];
            $category     = $values['category'];
            $owner_dep    = $values['owner_dep'];
            $supplier     = $values['supplier'];
            $barcode      = $values['barcode'];
            $po_number    = $values['po_number'];
            $manuf_invoice= $values['manuf_invoice'];
            
            $query = "UPDATE asset SET productcode = '" . $productcode . "', manufacturer = '" . $manufacturer . "', model = '" . $model . "', description = '" . $description . "', introduced = '" . $introduced . "', serial = '" . $serial . "', category = " . $category . ", status = " . $status . ", owner_dep = " . $owner_dep . ", supplier = '" . $supplier . "', barcode = '" . $barcode . "', po_number = '" . $po_number . "', manuf_invoice = '" . $manuf_invoice . "', asset_modified = NOW(), asset_modified_by = '" . $username . "' WHERE asset = " . $asset;

            $utildb = $this->getdb();
            $res = $utildb->query( $query );
            if ( $res == false ) {
                echo "ERROR: " . $this->getdb()->error;
            }
        }

    } // EOM updateAsset()


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
		
		
            $utildb = $this->getdb();
            $res = $utildb->query( $insLog );
            if ( $res == false ) {
                echo "ERROR: " . $this->getdb()->error;
            }

            return true;
	}


	function readComment($asset)
	{
		$db = $this->getdb();
		//$asset = $this->getasset();
		
		// Construct a SELECT statement to get the comments for a given history ID
		$getLog = "SELECT hid, asset, comment, updated_by, updated_time FROM asset_history ";
		$getLog .= "WHERE asset = '" . $asset . "' ORDER BY updated_by";

		$resLog = mysql_query($getLog, $db) or die("Unable to execute<br/>" . $getLog . "<br/>" . mysql_error($db));
		$numLog = mysql_num_rows($resLog);
		
		if ($numLog == 0)
		    return false;
		else
		    return mysql_fetch_array($resLog);
		
	}

	// Get the number of log entries for a given asset number
	function numLogs()
	{
		$db = $this->getdb();
		$asset = $this->getasset();
		$getNumLogs = "SELECT COUNT(asset) FROM asset_history WHERE asset = " . $asset;
		
		$resNumLogs = mysql_query($getNumLogs, $db) or die("Unable to execute<br/>" . $getNumLogs . "<br/>" . mysql_error($db));
		$numNumLogs = mysql_num_rows($resNumLogs);
		
		if ($numNumLogs == 0)
		    return false;
		else
        {
			$num_logs = mysql_fetch_array($resNumLogs);
			return $num_logs[0];
		}
	}
	function logindata()
	{
		global $prefix, $user_prefix;

		$user = $this->getcookiedetail();

		//echo "Data: $user<BR>\n";
		//if(!is_array($user))
		//{
			$usertmp = base64_decode($user);
			$user = explode(":", $usertmp);
			$uid = "$user[0]";
			$pwd = "$user[2]";
			//echo "UID: $uid<BR>PWD: $pwd<BR><BR>\n";
		/*}
		else
		{
			$uid = "$user[0]";
			$pwd = "$user[2]";
		}*/

		if ($uid != "" AND $pwd != "")
		{
			$result = "select uid, name, pass, uname, email, esc_level_1, esc_level_2, esc_admin from nuke_users where uid='$uid' AND pass='$pwd'";

			$TRUE = true;
			$FALSE = false;

			// echo "Opening DB connection<BR>\n";
			$thisData = new mysqldb("localhost", "nuke_aq", "dba", "sql99");

			$thisData->setsql($result);
			if (!$thisData->selectquery())
			{
				echo "ERROR: " . mysql_error() . "<BR>\n";
				die("MEGA error!");
			}

			if ($thisData->numberrows == 0)
				echo "NO RECORDS FOUND!<BR>\n";
			else
			{
				for ($i = 0; $i <= $thisData->numberrows; $i++)
				{
					//echo "<PRE>"; print_r($thisData->result[$i]); echo "</PRE>\n";
					$this->uid = $thisData->result[0]['uid'];
					$this->uname = $thisData->result[0]['name'];
					$this->lname = $thisData->result[0]['uname'];
					$this->email = $thisData->result[0]['email'];
					$this->l1 = $thisData->result[0]['esc_level_1'];
					$this->l2 = $thisData->result[0]['esc_level_2'];
					$this->admin = $thisData->result[0]['esc_admin'];
				}
			}


		}
		else
			debug("Sad thing, this!");

	}

} // EOC InsertComment
?>
