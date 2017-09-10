<?php

require_once __DIR__."/../src/DB.php";
require_once __DIR__."/../src/Tweet.php";
require_once __DIR__."/../src/User.php";

$db = new DB();
$tweet = $db->getById('Tweet',2);
$user = $db->getById('User',1);

var_dump($tweet);
var_dump($user);