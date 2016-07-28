<?php
//*****************************************************************************
//	$Id: dbfunctions.php,v 1.1 2005/04/01 17:22:58 druus Exp $
//	============================================
// History
// =======
// $Log: dbfunctions.php,v $
// Revision 1.1  2005/04/01 17:22:58  druus
// File moved from the AQasset root directory to the include directory.
// This causes the revision to be reset.
//
// Revision 1.1.1.1  2005/04/01 06:55:06  druus
// Initial release.
//
//*****************************************************************************

// Create a database class
class mysqldb 
{
	// Set up the object
	var $host;
	var $db;
	var $dbuser;
	var $dbpassword;
	var $sql;
	var $numberrows;
	var $dbopenstatus;
	var $dbconnection;
	var $query;
	var $result;
	var $InsertSwitch;

	// Define functions to get and set the values for the above variables.
	function gethost()
	{
		return $this->dbhost;
	}

	function sethost($req_host)
	{
		$this->dbhost = $req_host;
	}

	function getdb()
	{
		return $this->db;
	}

	function setdb($req_db)
	{
		$this->db = $req_db;
	}

	function getdbuser()
	{
		return $this->dbuser;
	}

	function setdbuser($req_user)
	{
		$this->dbuser = $req_user;
	}

	function getdbpassword()
	{
		return $this->dbpassword;
	}

	function setdbpassword($req_password)
	{
		$this->dbpassword = $req_password;
	}

	function getsql()
	{
		return $this->sql;
	}

	function setsql($req_sql)
	{
		$this->sql = $req_sql;
	}

	function getnumberrows()
	{
		return $this->numberrows;
	}

	function setnumberrows($req_numberresults)
	{
		$this->numberrows = $req_numberresults;
	}
	function getdbconnection()
	{
		return $this->dbconnection;
	}

	function setdbconnection($req_dbconnection)
	{
		$this->dbconnection = $req_dbconnection;
	}

	function getInsertSwitch()
	{
		return $this->InsertSwitch;
	}

	function setInsertSwitch($switch)
	{
		$this->InsertSwitch = $switch;
	}

	// Constructor for the object. 
	function mysqldb($HOST, $DB, $WEBUSER, $WEBPASSWORD)
	{
		//global $HOST, $DB, $WEBUSER, $WEBPASSWORD;
		global $TRUE, $FALSE;

		$this->sethost($HOST);
		$this->setdb($DB);
		$this->setdbuser($WEBUSER);
		$this->setdbpassword($WEBPASSWORD);
		$this->setdbconnection($FALSE);
	}


	// Methods used for the object. They provide the opening and closing of a connection
	// to the database, and also execute queries and retrieve the result sets.

	// Open a database connection, return TRUE is it worked, FALSE otherwise.
	function opendbconnection()
	{
		global $TRUE, $FALSE;

		$this->dbconnection = @mysql_connect("$this->dbhost", "$this->dbuser", "$this->dbpassword");

		if ($this->dbconnection == $TRUE)
		{
			$this->db = mysql_select_db("$this->db");
			$this->setdbconnection($TRUE);
		} else {
			$this->setdbconnection($FALSE);
			return false;
		}
		return true;
	}

	// Close an open database connection.
	function closedbconnection()
	{
		if ($this->dbconnection = $TRUE)
		{
			mysql_close($this->dbconnection);
		}
	}

	// Tell the database that we are to begin a transaction.
	function begin()
	{
		if ($this->dbconnection == $FALSE)
		{
			$this->opendbconnection();
		}

		$this->setsql("BEGIN");
		$this->query = mysql_query($this->sql);
		if (! $this->query)
		{
			return false;
		} else {
			return true;
		}
	}

	// Tell the database to roll back previous transactions.
	function rollback()
	{
		if ($this->dbconnection == $FALSE)
		{
			$this->opendbconnection();
		}
		$this->setsql("ROLLBACK");
		$this->query = mysql_query($this->sql);
		if (! $this->query)
		{
			return false;
		} else {
			return true;
		}
	}

	// Tell the database to commit the current transactions.
	function commit()
	{
		if ($this->dbconnection == $FALSE)
		{
			$this->opendbconnection();
		}
		$this->setsql("COMMIT");
		$this->query = mysql_query($this->sql);
		if (! $this->query)
		{
			return false;
		} else {
			return true;
		}
	}

	// Execute a SELECT statement.
	function selectquery()
	{
		global $TRUE, $FALSE;

		if ($this->dbconnection == $FALSE)
		{
			$this->opendbconnection();
		}

		$this->query = mysql_query($this->sql);
		if (! $this->query)
		{
			return false;
		} else {
			$this->numberrows = mysql_num_rows($this->query);
			if ($this->numberrows > 0)
			{
				for ($x = 0; $x < $this->numberrows; $x++)
				{
					$this->result[$x] = mysql_fetch_array($this->query);
				}
			} else {
				//echo "[Error:] Retrieving data";
				//return false;
			}
			return true;
		}
	}

	// Execute an INSERT statement.
	function insertquery()
	{
		global $TRUE, $FALSE;

		if ($this->dbconnection == $FALSE)
		{
			$this->opendbconnection();
		}

		$this->query = mysql_query($this->sql);
		if (! $this->query)
		{
			return false;
		} else {
			return true;
		}
	}
}


// Create a class that can be used to determine if a user is logged in, and provide user login and name
class User
{
	var $uid, $user_name, $uname, $lname, $priv, $email, $l1, $l2, $adm, $cookiedetail;

	// Constructor for the class
	function User($user)
	{
		$this->setcookiedetail($user);
		$this->logindata($user);

	}

	// Functions used
	function setcookiedetail($cookiedetail)
	{
		$this->cookiedetail = $cookiedetail;
	}

	function getcookiedetail()
	{
		return $this->cookiedetail;
	}

	function setuid($uid)
	{
		$this->uid = $uid;
	}

	function getuid()
	{
		return $this->uid;
	}

	function setloginname($lname)
	{
		$this->lname = $lname;
	}

	function getloginname()
	{
		return $this->lname;
	}

	function setusername($uname)
	{
		$this->uname = $uname;
	}

	function getusername()
	{
		return $this->uname;
	}

	function setlevel1($L1)
	{
		$this->l1 = $L1;
	}

	function getl1()
	{
		return $this->l1;
	}

	function setlevel2($L2)
	{
		$this->l2 = $L2;
	}

	function getl2()
	{
		return $this->l2;
	}
	function setadmin($admin)
	{
		$this->admin = $admin;
	}

	function getadmin()
	{
		return $this->admin;
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
			$result = "SELECT * FROM user WHERE user_name='$uid' AND pwd=PASSWORD('$pwd') ";

			$TRUE = true;
			$FALSE = false;

			// echo "Opening DB connection<BR>\n";
			$thisData = new mysqldb("localhost", "asset_test", "dba", "sql99");

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
					$this->username = $thisData->result[0]['user_name'];
					$this->priv = $thisData->result[0]['priv'];
					//$this->uname = $thisData->result[0]['name']; 
					//$this->lname = $thisData->result[0]['uname']; 
					//$this->email = $thisData->result[0]['email'];
					//$this->l1 = $thisData->result[0]['esc_level_1']; 
					//$this->l2 = $thisData->result[0]['esc_level_2'];
					//$this->admin = $thisData->result[0]['esc_admin']; 
				}
			}


		}
		else
			debug("Sad thing, this!");

	}

} // EOC User


// Function		print_error($error_message, $error_type, $action)
// Arguments	$error_message - error message to print on screen
//				$error_type - type of "error", might be ERROR, WARNING, INFO
//
function print_error($error_message, $error_type = "info")
{
	// Check what kind of "error" type it is, to determine what icon to display.
	switch ($error_type)
	{
		case "error":
			$icon = "images/icon_critical.gif";
			break;
		
		case "warning":
		case "warn":
			$icon = "images/icon_warning.gif";
			break;
			
		case "info":
			$icon = "images/icon_info.gif";
			break;
		
		case "question":
			$icon = "images/icon_question.gif";
			break;
		
		default:
			$icon = "images/blank.gif";
	}
?>

<table align="center" border="0" cellspacing="0" callpadding="2">
<tr bgcolor="#c0c0c0">
	<td><img src="<?php echo $icon; ?>"></td>
	<td><?php echo $error_message; ?></td>
</tr>
</table>

<?php
} // print_error()

