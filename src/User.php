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

    public function setId($id)
    {
        $this->id = $id;
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

    public function encryptPass($plainPass)
    {
        $hashedPass = password_hash(
            $plainPass,PASSWORD_BCRYPT,['cost'=>11]
        );

        $this->hashedPass = $hashedPass;
    }

    public function setHashedPass($hashedPass)
    {
        $this->hashedPass = $hashedPass;
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