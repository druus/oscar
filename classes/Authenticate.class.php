<?php
/******************************************************************************
** Filename     Authenticate.class.php
** Description  Handles user authentication
** Created by   Daniel Ruus
** Created      2016-08-22
** Version      1.0
******************************************************************************/



/*******************************************************************************
** Class       Authenticate
** Description Authentication utilities
** Created by  Daniel Ruus
** Created     2016-08-22
** Modified by -
** Modified    -
** Version     1.0
*******************************************************************************/
class Authenticate {

    private $dbh = null; // Database connection handle
    private $dbname;
    private $dbuser;
    private $dbpasswd;
    private $dbtype;
    private $dbhost;

    function __construct( $configfile = "config/config.ini.php" )
    {

        // Read the configuration file
        if (!$settings = parse_ini_file( $configfile, true)) throw new Exception("Unable to open the configuration file '$configfile'");

        $this->dbname   = $settings['database']['schema'];
        $this->dbuser   = $settings['database']['username'];
        $this->dbpasswd = $settings['database']['password'];
        $this->dbhost   = $settings['database']['host'];
        $this->dbtype   = $settings['database']['driver'];

        // Connect to the database
        $dsn = $this->dbtype . ":dbname=" . $this->dbname . ";host=" . $this->dbhost;
        try {
            $this->dbh = new PDO( $dsn, $this->dbuser, $this->dbpasswd );
        } catch (PDOException $e) {
            throw new Exception( $e->getMessage() );
        }

    } // EOM __construct()


    /***************************************/
    /**           M E T H O D S            */
    /***************************************/



    function authenticate( $username, $password )
    {
      if (isset($username) && isset($password)) {
        $hashedPassword = hash('sha256', $password);

        $stmt = $this->dbh->prepare("SELECT role FROM users WHERE username = :username AND password = :password");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        if ( $stmt->execute() ) {
            while ( $row = $stmt->fetch() ) {
              return $row[0];
            }
        }

      }
      return false;

    } // EOM authenticate();


    //*******************************************************************
    // Function		login()
    // Arguments	$values - arrary containing details of user name and
    //				password as entered in function user_show_login()
    // Description	Search the user database table to see if the credentials
    //				entered are valid. If so, register the user in the
    //				current session.
    // Return value	User name if login works, FALSE otherwise
    //*******************************************************************
    function login($username, $password)
    {

    	// Check if login works
    	//$user = new User($values['username']);
    	$hashedPassword = hash('sha256', $password);
    	$privQuery = "SELECT username, role FROM users WHERE username = :username AND password = :password";
      $stmt = $this->dbh->prepare($privQuery);
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

      $privRows = 0;
      if ( $stmt->execute() ) {
        while ( $row = $stmt->fetch() ) {
          $privRows++;
          $priv = $row['priv'];
          $uname = $row['username'];
        }
      }

    	if ($privRows == 0)
    		return FALSE;
    	else
    	{
    		// Store the user name as a session variable
    		//$loggeduser = $username;
    		$_SESSION['username'] = $username;
    		$_SESSION['password'] = $password;
    		echo "You are logged in as '" . $username . "'.<br/>\n";

    		return $uname;
     	}

    } // EOM login()


    //*******************************************************************
    // Function		logout()
    // Arguments	none
    // Description	Clear the session variables user_name and password
    // Return value	TRUE if it works, FALSE otherwise
    //*******************************************************************
    function logout()
    {
    	// Remove the user name from the session variable
    	$username = $_SESSION['username'];
    	unset($_SESSION['username']);
    	unset($_SESSION['password']);

    } // EOF user_logout()

} // EOC Authenticate

?>
