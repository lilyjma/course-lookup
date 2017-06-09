<?php

namespace App;

class SQLiteCreateTable{
    
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function createTable(){
        $command = 'CREATE TABLE IF NOT EXISTS courses(
            Department TEXT NOT NULL,
            Subject TEXT NOT NULL,
            Bulletin_Prefix TEXT NOT NULL,
            Course_Number INTEGER NOT NULL,
            Name TEXT NOT NULL,
            Min_Points INTEGER NOT NULL,
            Max_Points INTEGER NOT NULL);';

       $this->pdo->exec($command);
    }

    public function getTable(){
        $stmt = $this->pdo->query('SELECT * FROM courses;');
        if ($stmt){
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                print_r($row);
            }
        }else{
            echo 'query() did not return PDO object';
        }
    }
}


