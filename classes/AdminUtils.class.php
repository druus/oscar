<?php
/******************************************************************************
** Filename     AdminUtils.class.php
** Description  Handles administration of various features
** Created by   Daniel Ruus
** Created      2016-07-25
** Version      1.0
******************************************************************************/



/*******************************************************************************
** Class       AdminUtils
** Description Administrative utilities
** Created by  Daniel Ruus
** Created     2016-07-25
** Modified by -
** Modified    -
** Version     1.0
*******************************************************************************/
class AdminUtils {

    private $dbh = null; // Database connection handle
    private $dbname;
    private $dbuser;
    private $dbpasswd;
    private $dbtype;
    private $dbhost;

    function __construct( $name, $user, $passwd, $type = "mysql", $host = "localhost" )
    {

        // Read the configuration file
        if (!$settings = parse_ini_file("config/config.ini.php", true)) throw new Exception("Unable to open the configuration file 'config/config.ini'");

        $this->dbname   = $settings['database']['schema'];
        $this->dbuser   = $settings['database']['username'];
        $this->dbpasswd = $settings['database']['password'];
        $this->dbhost   = $settings['database']['host'];
        $this->dbtype   = $settings['database']['driver'];
/*
        $this->dbname   = $name;
        $this->dbuser   = $user;
        $this->dbpasswd = $passwd;
        $this->dbtype   = $type;
        $this->dbhost   = $host;
*/
        // Connect to the database
        $dsn = $this->dbtype . ":dbname=" . $this->dbname . ";host=" . $this->dbhost;
        try {
            $this->dbh = new PDO( $dsn, $this->dbuser, $this->dbpasswd );
        } catch (PDOException $e) {
            throw new Exception( $e->getMessage() );
        }

        //return $this->dbh;

    } // EOM __construct()


    /***************************************/
    /**           M E T H O D S            */
    /***************************************/



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
     * Retrieve a list of suppliers
     * @return array
     */
    function getSuppliers()
    {
        $stmt = $this->dbh->prepare("SELECT supp_id AS id, supplier, description FROM suppliers ORDER BY supplier");
        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM getSuppliers()


    /**
     * Get details for a supplier
     * @return array containing supplier details
     */
    function getSupplier( $supplier_id )
    {
        $stmt = $this->dbh->prepare("SELECT supp_id AS id, supplier, description, address, city, post_code, country, email_main, website FROM suppliers WHERE supp_id = :supplier_id");
        $stmt->bindParam(':supplier_id', $supplier_id, PDO::PARAM_INT);

        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM getSupplier()


    /**
     * Create a new supplier
     *
     */
    function createSupplier( $supplier, $description, $website, $username  )
    {
        $lastId = false;
        try {
            $stmt = $this->dbh->prepare("INSERT INTO suppliers (supp_id, supplier, description, website, entry_created, entry_created_by) VALUES (NULL, :supplier, :description, :website, NOW(), :username)");
            $stmt->bindParam(':supplier', $supplier);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':website', $website);
            $stmt->bindParam(':username', $username);

            $this->dbh->beginTransaction();
            $stmt->execute();
            $lastId = $this->dbh->lastInsertId();

            $stmt->debugDumpParams();
            echo "<br/>Supplier: " . $supplier . "<br/>\n";
            echo "Description: " . $description . "<br/>\n";
            echo "Website   : " . $website . "<br/>\n";
            echo "Username  : " . $username . "<br/>\n";
            $this->dbh->commit();
        } catch(PDOException $e) {
            $this->dbh->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
            die();
        }

        return $lastId;

    } // EOM updateSupplier()


    /**
     * Get details for a supplier
     *
     */
    function updateSupplier( $supplier_id, $supplier, $description, $website  )
    {
        $stmt = $this->dbh->prepare("UPDATE suppliers SET supplier = :supplier, description = :description, website = :website WHERE supp_id = :supplier_id");
        $stmt->bindParam(':supplier', $supplier, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':website', $website, PDO::PARAM_STR);
        $stmt->bindParam(':supplier_id', $supplier_id, PDO::PARAM_INT);

        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM updateSupplier()


    /**
     * Delete a supplier
     * @return
     */
    function deleteSupplier( $supplier_id )
    {
        $stmt = $this->dbh->prepare("DELETE FROM suppliers WHERE supp_id = :supplier_id");
        $stmt->bindParam(':supplier_id', $supplier_id, PDO::PARAM_INT);

        if ( $stmt->execute() ) {
            return $stmt->fetchAll();
        }

        return false;

    } // EOM deleteSupplier()()

} // EOC DbHandler()

?>