<?php

class DB
{
    private static $conn;

    public function __construct()
    {
        if(! self::$conn instanceof \PDO) {
            self::$conn = new PDO("mysql:host=localhost;dbname=twitter",'root',null,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
    }

    public function __destruct()
    {
        self::$conn = null;
    }

    private static function validateTableName($name) {
        if(in_array($name,[
            'User',
            'Tweet'
        ])) {
            return strtolower($name);
        }
        throw new \Exception("Table not allowed");
    }

    private static function toCamelCase($name) {
        $parts = explode("_",$name);
        $name = "";
        foreach ($parts as $part) {
            $name .= ucfirst($part);
        }
        return $name;
    }

    private static function keysToIterate($array) {
        return array_keys($array);
    }

    public function getById($name, $id) {
        $name = self::validateTableName($name);
        $stmt  =self::$conn->prepare("SELECT * FROM $name WHERE id=:id");
        $res = $stmt->execute(['id'=>$id]);
        if($res) {
            $array = $stmt->fetch(PDO::FETCH_ASSOC);
            $object = new $name();
            foreach (self::keysToIterate($array) as $key) {
                $object->{"set".self::toCamelCase($key)}($array[$key]);
            }
            return $object;
        }
        return false;
    }

    public function getAll($name) {
        $name = self::validateTableName($name);
        $stmt  =self::$conn->query("SELECT * FROM ".$name);
        $objects = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $array) {
            $object = new $name();
            foreach (self::keysToIterate($array) as $key) {
                $object->{"set".self::toCamelCase($key)}($array[$key]);
            }
            $objects[] = $object;
        }
        return $objects;
    }
}