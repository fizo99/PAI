<?php

require_once __DIR__.'/../../Database.php';

class Repository {
    private static $database;

    static function init() {
        if(Repository::$database == null){
            Repository::$database = new Database();
        }
    }

    public static function connect(): PDO {
        return Repository::$database->connect();
    }
}

Repository::init();