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
}