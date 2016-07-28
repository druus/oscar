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

} // EOM DbHandler()

?>
