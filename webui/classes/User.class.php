<?php
/******************************************************************************
** Filename     User.class.php
** Description  User handling
** Created by   Daniel Ruus
** Created      2015-08-05
** Version      1.0
******************************************************************************/


/*******************************************************************************
** Class        User
** Arguments    $db - database connection ID
**              $location - pod/office/lab location
**              $port - network port
** Return value $selPort - selected port (integer)
** Description  Allow easy interaction of comments from the asset history table
** Created by   Daniel Ruus
** Version      1.0
*******************************************************************************/
class User
{
    var $db, $asset, $comment, $user, $isDbConnected = false;

    /**
     * Constructor
     *
     * Establish a connection to the database using supplied connection details
     */
    function User( $dbconnect )
    {
        $this->db = $dbconnect;
    } // EOM dbConnect()


    /**
     * Attempt to login the user with the supplied username and password
     *
     * @return array - an array with user details
     */
    function login( $username, $password )
    {

        try {
            //$dbcon = new PDO("mysql:host=localhost;dbname=oscar", "oscar", "dqXl4mEYW*1fA8uL");
            $dbcon = $this->db;
            $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $stmt = $dbcon->prepare( "SELECT id, username, role FROM users WHERE username = :username AND password = SHA2(:password, 256)" );
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            $stmt->execute();
            $result = $stmt->fetchAll();

            $userDetails = array();
            foreach ( $result as $key => $value ) {
                 $userDetails['id']       = $value->id;
                 $userDetails['username'] = $value->username;
                 $userDetails['role']     = $value->role;
            }

        } catch(PDOException $ex) {
            return new Response("Unable to retrieve user details during login. Error: " . $ex);
        }
        $conn = null; 

        return $userDetails;

    } // EOM login()


    /**
     * Update timestamp for last login
     *
     * @var user The username affected
     */
    function loginTimestamp( $username )
    {
        if ( $username == null || strlen($username) < 2 ) {
            throw new Exception("Not a valid username: '$username'.");
        }

        try {
            $dbcon = $this->db;
            $stmt  = $dbcon->prepare( "UPDATE users SET last_login = NOW() WHERE username = :username" );
            $stmt->bindParam(':username', $username);
            $stmt->execute();
        } catch (Exception $ex) {
            throw new Exception("Could not uptime timestamp for the user '$username'. Error message: $ex");
        }

    } // EOM loginTimestamp


    /**
     * Retrieve the username
     */
    function getUsername()
    {
    } // EOM getUsername()


    /**
     * Get the logged in users current role
     */
    function getRole( $username )
    {

    } // EOM getRole()

} // EOC User
?>
