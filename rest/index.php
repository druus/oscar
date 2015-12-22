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

    $message = $request->get('message');

    //mail('feedback@yoursite.com', '[YourSite] Feedback', $message);

    return new Response("Thank you for your feedback with message $message", 201);
});


$app->run();
?>
