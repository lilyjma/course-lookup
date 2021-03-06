<?php

namespace App;


class SQLiteInsert{
    
    private $pdo;
        
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function insertCourses($Department, $Subject, $Bulletin_Prefix, 
        $Course_Number, $Name, $Min_Points, $Max_Points){

            $query = "INSERT INTO courses(
                Department, Subject, Bulletin_Prefix, 
                Course_Number, Name, Min_Points, Max_Points) ". "VALUES(
                    :Department, :Subject, :Bulletin_Prefix, 
                    :Course_Number, :Name, :Min_Points, :Max_Points);";

            $stmt = $this->pdo->prepare($query);
           
            $result = $stmt->execute(array(
                ':Department'=> $Department,
                ':Subject'=> $Subject,
                ':Bulletin_Prefix'=> $Bulletin_Prefix,
                ':Course_Number'=> $Course_Number,
                ':Name'=> $Name,
                ':Min_Points'=> $Min_Points,
                ':Max_Points'=> $Max_Points
            ));

        }
    
  }
?>
