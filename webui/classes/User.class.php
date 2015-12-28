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
     * Connect to the database
     *
     * Establish a connection to the database using supplied connection details
     */
    function dbConnect()
    {
    } // EOM dbConnect()


    /**
     * Attempt to login the user with the supplied username and password
     *
     * @return boolean - true if login is succesful, false otherwise
     */
    function login( $username, $password )
    {

        try {
            $dbcon = new PDO("mysql:host=localhost;dbname=oscar", "oscar", "dqXl4mEYW*1fA8uL");
            $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $stmt = $dbcon->prepare( "SELECT COUNT(username) AS cnt FROM users WHERE username = :username AND password = SHA2(:password, 256)" );
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            $stmt->execute();
            $result = $stmt->fetchAll();

            foreach ( $result as $key => $value ) {
                $loginResult = $value->cnt;
            }

            if ( $loginResult == 1 ) {
                $success = true;
            } else {
                $success = false;
            }
        } catch(PDOException $ex) {
            return new Response("Unable to retrieve user details during login. Error: " . $ex);
        }
        $conn = null; 

        return $success;
    } // EOM login()


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
