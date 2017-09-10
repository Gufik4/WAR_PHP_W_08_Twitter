<?php

require __DIR__.'/../src/Controller.php';

$uri = $_SERVER["REQUEST_URI"];
$controller = new Controller();
$res = '';

if($uri === "/") {
    $res = $controller->showMainPage();
} elseif($uri === "/tweet") {
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $res = $controller->addTweet();
    } else {
        $res = $controller->showAllTweets();
    }
} elseif (preg_match('/\/tweet\/\d+/',$uri)) {
    $id = explode('/',$uri)[2];
    $res = $controller->showTweet($id);
}

echo $res;