<?php

require_once __DIR__.'/Tweet.php';
require_once __DIR__.'/User.php';
require_once __DIR__.'/DB.php';

class Controller
{
    private $db;
    static $conn;

    public function __construct()
    {
        $this->db = new DB();
        if(! self::$conn instanceof \PDO) {
            self::$conn = new PDO("mysql:host=localhost;dbname=twitter",'root',null,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
    }

    private function render($template,$data)
    {
        $html = file_get_contents(__DIR__."/../template/".$template.".html");
        foreach ($data as $key => $value) {
            $html = str_replace('{{'.$key.'}}',$value,$html);
        }
        return $html;
    }

    public function showMainPage()
    {
        return $this->render('homepage',['item'=>"kota"]);
    }

    public function showTweet($id)
    {
        $tweet = $this->db->getById('Tweet',$id);

        if(! $tweet instanceof Tweet) {
            return $this->render('not_fount',['id'=>$id,'object'=>'tweet']);
        }

        return $this->render('single_tweet',[
           'id' => $tweet->getId(),
           'content' => $tweet->getContent(),
           'user_id' => $tweet->getUserId(),
           'created_at' => $tweet->getCreatedAtAsText(),
        ]);
    }

    public function showAllTweets($data = ["error"=>""]) {
        $tweets = $this->db->getAll('Tweet');

        $html = '';
        foreach ($tweets as $tweet) {
            if($tweet instanceof Tweet) {
                $html .= $this->render('tweet', [
                    'id' => $tweet->getId(),
                    'content' =>$tweet->getContent(),
                    'created_at' =>$tweet->getCreatedAtAsText(),
                    'user_id' =>$this->db->getById('User',
                        $tweet->getUserId()
                    )->getName(),
                ]);
            }
        }

        return $this->render('all_tweets',array_merge(
            ['content' => $html],
            $data
        ));
    }

    public function addTweet()
    {
        if(!isset($_POST["content"]) OR !strlen($_POST["content"])) {
            return $this->showAllTweets(["error"=>"Form is empty!!!"]);
        }

        $tweet = new Tweet();
        $tweet->setContent($_POST["content"]);
        $tweet->setUserId(1);
        $tweet->save(self::$conn);

        return $this->showAllTweets();
    }
}