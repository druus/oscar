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

        $stmt = $this->db->prepare( "SELECT asset, manufacturer, model, serial, description, created, asset_modified FROM asset WHERE asset = :asset" );
        $stmt->bindValue( "i", $asset );

        $stmt->execute();

        return $stmt->fetchAll();

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
