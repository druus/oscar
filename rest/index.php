<?php
require_once __DIR__.'/vendor/autoload.php';
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
// production environment - false; test environment - true
$app['debug'] = true;
 
$list = array(
 '00001'=> array(
    'name' => 'Peter Jackson',
    'description' => 'Producer | Director',
    'image' => 'MV5BMTY1MzQ3NjA2OV5BMl5BanBnXkFtZTcwNTExOTA5OA@@._V1_SY317_CR8,0,214,317_AL_.jpg',
 ),
 '00002' => array(
    'name' => 'Evangeline Lilly',
    'description' => 'Actress',
    'image' => 'MV5BMjEwOTA1MTcxOF5BMl5BanBnXkFtZTcwMDQyMjU5MQ@@._V1_SY317_CR24,0,214,317_AL_.jpg',
 ),
 '00003' => array(
    'name' => 'Kermit Muppet',
    'description' => 'Muppet',
    'image' => 'MV5BMjEwOTA1MTcxOF5BMl5BanBnXkFtZTcwMDQyMjU5MQ@@._V1_SY317_CR24,0,214,317_AL_.jpg',
 ),
);
 
$app->get('/', function() use ($list) {
 
 return json_encode($list);
});
 
$app->get('/id/{id}', function (Silex\Application $app, $id) use ($list) {
 
 if (!isset($list[$id])) {
     $app->abort(404, "id {$id} does not exist.");
 }
 return json_encode($list[$id]);
});
 

// Get list of assets
$app->get('/assets', function() {

    $mysqli = new mysqli("localhost", "oscar", "dqXl4mEYW*1fA8uL", "oscar");

    if ( $mysqli->connect_errno ) {
        return "Connect failed: " . $mysqli->connect_error;
    }

    $stmt = $mysqli->stmt_init();
    if ( $result = $mysqli->query("SELECT asset, manufacturer, model, description FROM asset ORDER BY asset") ) {
        $resultArray = array();
        while ( $row = $result->fetch_assoc() ) {
            $array = [
              "asset" => $row["asset"],
              "manufacturer" => $row["manufacturer"],
              "model" => $row["model"],
              "description" => $row["description"],
            ];

            $resultArray[] = $array;
        }
    }

    $mysqli->close();
    return json_encode($resultArray);
});


// Get a specific asset
$app->get('/asset/{asset}', function(Silex\Application $app, $asset) {

    $mysqli = new mysqli("localhost", "oscar", "dqXl4mEYW*1fA8uL", "oscar");

    if ( $mysqli->connect_errno ) {
        return "Connect failed: " . $mysqli->connect_error;
    }

    $stmt = $mysqli->stmt_init(); /* Create a prepared statement */
    if ( $stmt = $mysqli->prepare("SELECT asset, manufacturer, model, description FROM asset WHERE asset = ? ") ) {
        $stmt->bind_param( "i", intval($asset) );
        $stmt->execute();
        $stmt->bind_result($asset ,$manufacturer, $model, $description);

        $stmt->fetch(); // Fetch the values

        $array = [
          "asset" => $asset,
          "manufacturer" => $manufacturer,
          "model" => $model,
          "description" => $description,
        ];

        $stmt->close();
    } else {
        return "Unable to execute query: " . $stmt->error;
    }

    $mysqli->close();
    return json_encode($array);
});


// POST
$app->post('/add', function (Request $request) {

    $manufacturer = $request->get('manufacturer');
    $model        = $request->get('model');
    $description  = $request->get('description');
    $serial       = $request->get('serial');

    try {
        $dbcon = new PDO("mysql:host=localhost;dbname=oscar", "oscar", "dqXl4mEYW*1fA8uL");
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dbcon->prepare( "INSERT INTO asset VALUES (NULL, :manufacturer, :model, :serial, :description, 1, 1, NOW(), NOW())" );
        $stmt->bindParam(':manufacturer', $manufacturer);
        $stmt->bindParam(':model',        $model);
        $stmt->bindParam(':serial',       $serial);
        $stmt->bindParam(':description',  $description);

        $stmt->execute();
        //$asset = $dbcon->lastInsertId();
    } catch(PDOException $ex) {
        return new Response("Unable to insert new asset. Error: " . $ex);
    }

    $conn = null;
    return new Response("Inserted a new asset", 201);
});


$app->run();
?>
