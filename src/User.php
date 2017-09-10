<?php

class User
{
    private $id;
    private $name;
    private $email;
    private $hashedPass;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getHashedPass()
    {
        return $this->hashedPass;
    }

    public function setHashedPass($plainPass)
    {
        $hashedPass = password_hash(
            $plainPass,PASSWORD_BCRYPT,['cost'=>11]
        );

        $this->hashedPass = $hashedPass;
    }

    public static function getById(PDO $conn, $id) {
        $stmt  =$conn->prepare("SELECT * FROM user WHERE id=:id");
        $res = $stmt->execute(['id'=>$id]);
        if($res) {
            $array = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new User();
            $user->id = $array["id"];
            $user->setName($array["name"]);
            $user->setEmail($array["email"]);
            $user->hashedPass = $array["hashed_pass"];
            return $user;
        }
        return false;
    }

    public static function getAll(PDO $conn) {
        $stmt  =$conn->query("SELECT * FROM user");
        $users = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $array) {
            $user = new User();
            $user->id = $array["id"];
            $user->setName($array["name"]);
            $user->setEmail($array["email"]);
            $user->hashedPass = $array["hashed_pass"];
            $users[] = $user;
        }
        return $users;
    }

    public function save(PDO $conn) {
        if(!$this->id) {
            $stmt = $conn->prepare("INSERT INTO user (email,name,hashed_pass)
                      VALUES (:email,:name,:hashed_pass)");
            $res = $stmt->execute([
                'email'=>$this->getEmail(),
                'name'=>$this->getName(),
                'hashed_pass'=>$this->getHashedPass()
            ]);
            if($res) {
                $this->id = $conn->lastInsertId();
            }
            } else {
            $stmt = $conn->prepare("UPDATE user 
            SET email = :email, name = :name, hashed_pass = :hashed_pass
            WHERE id = :id;");
            $res = $stmt->execute([
                'email'=>$this->getEmail(),
                'name'=>$this->getName(),
                'hashed_pass'=>$this->getHashedPass(),
                'id'=>$this->getId()
            ]);
        }
        return (bool) $res;
    }

    public function delete(PDO $conn) {
        if($this->getId()) {
            $stmt = $conn->prepare("DELETE FROM user WHERE id=:id");
            $res = $stmt->execute([
                'id'=>$this->getId()
            ]);
            return (bool) $res;
        }
        return false;
    }

}