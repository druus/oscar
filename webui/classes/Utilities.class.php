<?php
/******************************************************************************
** Filename     Utilities.class.php
** Description  Various useful objects used to provide different simple
**              functions for the AQ asset register
** Created by   Daniel Ruus
** Created      2015-08-05
** Version      1.0
******************************************************************************/


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
class Utilities
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

        try {
            //$dbcon = new PDO("mysql:host=localhost;dbname=oscar", "oscar", "dqXl4mEYW*1fA8uL");
            $dbcon = $this->getdb();
            $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $stmt = $dbcon->prepare( $query );

            $stmt->execute();
            $result = $stmt->fetchAll();

            $statusDetails = array();
            foreach ( $result as $key => $value ) {
                 $statusDetails['id']     = $value->id;
                 $statusDetails['status'] = $value->status;
            }

        } catch(PDOException $ex) {
            return "Unable to retrieve status details during login. Error: " . $ex;
        }

        return $result;

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


        




	function insertComment()
	{
		$db = $this->getdb();
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
		
		print "Inserting comment log with statement: " . $insLog . ".<br/>\n";
		
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
