<?php
require 'vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use GuzzleHttp\Client;

//SKapa en HTTP-client
$client = new Client();

// anropa URL
$url = 'http://unicorns.idioti.se';
if (isset($_GET['id'])) {
  $url .= "/".$_GET['id'];
}

$res = $client->request('GET', $url, [
  'headers' => [
        'Accept'     => 'application/json',
      ]
]);


// omvandla jsonsvar till datatyper
$data = json_decode($res->getBody());

$log = new Logger('Laboration 1');
$log->pushHandler(new StreamHandler('greetings.log', Logger::INFO));
if(isset($_GET['id'])) {
//Log if user is checking specific unicorn or all
$log->info("Unicorns LOG: Request info about: ".$data->name);
} else {
  $log->info("Unicorns LOG: Request info about: all unicorns");
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Unicorns mf!!</title>
    <link rel="stylesheet" type="text/css" href="background.css">
  </head>
  <body>
  <h1> Den fantastiska enhörningsdatabasen </h1> <br>
<?php

if(isset($_GET['id'])) {
  echo("<button onclick=\"location.href='index.php'\"  style='margin-left: 100px;'>Alla Enhörningar</button>");
  echo "<h3>".$data->name."</h3>";
  echo "<p>".$data->spottedWhen->date."</p>";
  echo "<p>".$data->description."</p>";
  echo "<p><strong>Personen som kikat på djuret:</strong> ".$data->reportedBy."</p>";
  echo "<img src='$data->image'/>";
} else {
  echo "<div class='container'>";
    echo "<h1>Lista på alla enhörningar</h1>";
    foreach ($data as $key => $value) {
      //echo "<a href='index.php?id=".$value->id."'><h2>".$value->name."</h2></a>";
      echo "<h2>".$value->name;
      echo("<button onclick=\"location.href='index.php?id=$value->id'\"  style='float: right;'>Läs mer</button>");
      echo "</h2>";
      echo "<hr>";
    }
  echo "</div";
}
?>
</body>
</html>
