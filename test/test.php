<?php

require __DIR__."/../src/User.php";
require __DIR__."/../src/Tweet.php";

$conn = new PDO("mysql:host=localhost;dbname=twitter",'root',null,[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$conn->query(file_get_contents(__DIR__."/../sql/main.sql"));

if(User::getById($conn,1)->getEmail() === "adam@spadam.pl") {
    echo "ok\n";
} else {
    echo "no\n";
}

if(count(User::getAll($conn)) === 1) {
    echo "ok\n";
} else {
    echo "no\n";
}

$user = new User();
$user->setEmail("aleks@kleks.pl");
$user->setName("Ala");
$user->setHashedPass("pass");
$user->save($conn);

if(count(User::getAll($conn)) === 2) {
    echo "ok\n";
} else {
    echo "no\n";
}

$user->setEmail("marek@zakamarek.pl");
$user->save($conn);


if((User::getById($conn,2))->getEmail() === "marek@zakamarek.pl") {
    echo "ok\n";
} else {
    echo "no\n";
}


$tweet = new Tweet();
$tweet->setContent("ok");
$tweet->setUser($user);
$tweet->save($conn);

//var_dump(Tweet::getAll($conn));

if(count(Tweet::getAll($conn))===3){
    echo "ok\n";
} else {
    echo "no\n";
}

if(count(Tweet::getAllTweetsByUserId($conn,1))===2){
    echo "ok\n";
} else {
    echo "no\n";
}

$user->delete($conn);
unset($user);

if(count(User::getAll($conn)) === 1) {
    echo "ok\n";
} else {
    echo "no\n";
}
