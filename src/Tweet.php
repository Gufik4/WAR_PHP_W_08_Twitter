<?php

class Tweet
{
    private $id;
    private $content;
    private $user;
    private $userId;
    private $createdAt;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCreatedAtAsText()
    {
        return $this->getCreatedAt()->format('Y-m-d H:i:s');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->setUserId($user->getId());
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        if($createdAt instanceof \DateTime) {
            $this->createdAt = $createdAt;
        } else {
            $this->createdAt = new \DateTime($createdAt);
        }
    }

    public function save(PDO $conn) {
        if(!$this->id) {
            $stmt = $conn->prepare("INSERT INTO tweet 
                (content,user_id,created_at)
                      VALUES (:content,:user_id,:created_at)");
            $res = $stmt->execute([
                'content'=>$this->getContent(),
                'user_id'=>$this->getUserId(),
                'created_at'=>$this->getCreatedAtAsText()
            ]);
            if($res) {
                $this->id = $conn->lastInsertId();
            }
            } else {
            $stmt = $conn->prepare("UPDATE tweet 
            SET content = :content, user_id = :user_id, created_at = :created_at
            WHERE id = :id;");
            $res = $stmt->execute([
                'content'=>$this->getContent(),
                'user_id'=>$this->getUserId(),
                'created_at'=>$this->getCreatedAtAsText(),
                'id'=>$this->getId()
            ]);
        }
        return (bool) $res;
    }

    public function delete(PDO $conn) {
        if($this->getId()) {
            $stmt = $conn->prepare("DELETE FROM tweet WHERE id=:id");
            $res = $stmt->execute([
                'id'=>$this->getId()
            ]);
            return (bool) $res;
        }
        return false;
    }

    public static function getAllTweetsByUserId(PDO $conn, int $id) : array {
        $stmt  =$conn->prepare("SELECT * FROM tweet WHERE user_id=:id");
        $stmt->execute(['id'=>$id]);
        $tweets = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $array) {
            $tweet = new Tweet();
            $tweet->id = $array["id"];
            $tweet->setUserId($array["user_id"]);
            $tweet->setContent($array["content"]);
            $tweet->setCreatedAt($array["created_at"]);
            $tweets[] = $tweet;
        }
        return $tweets;
    }
}