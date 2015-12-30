<?php

class Asset {

    /**
     * Variables
     */
    private $db;

    function Asset( $dbConnection ) 
    {
         $this->db = $dbConnection; 
    } //EOM Asset()


    /**
     * Get details for a given asset
     *
     * @var asset - Asset ID to get data for
     * @return array - An array containing the asset data
     */
    function getAsset( $asset )
    {

        if ( !is_numeric( $asset ) ) {
            throw new Exception("Asset '$asset' is not a numerical value.");
        }

        try {
            $stmt = $this->db->prepare( "SELECT asset, manufacturer, model, serial, description, category, status, comment, introduced, manuf_artno, supplier_artno, asset_entry_created, asset_modified FROM asset WHERE asset = :asset" );
            $stmt->bindValue( ':asset', intval($asset), PDO::PARAM_INT );

            $stmt->execute();
            $result = $stmt->fetch();
        } catch (Exception $ex) {
            throw new Exception("Unable to retrieve asset data. Error: $ex");
        }

        return $result;

    } // EOM getAsset()


    /**
     * List all assets
     *
     * @return array - list of assets
     */
    function listAssets()
    {

        $stmt = $this->db->prepare( "SELECT asset, manufacturer, model, serial, asset_modified FROM asset ORDER BY asset" );

        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;

    } // EOM listAssets()


} //EOC Asset
?>
