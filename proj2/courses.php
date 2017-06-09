<?php

require 'vendor/autoload.php';

use App\SQLiteConnection as SQLiteConnection;
use App\SQLiteCreateTable as SQLiteCreateTable;
use App\SQLiteInsert; 


if ($argc === 2 || $argc == 3){
    $sqlite = new SQLiteConnection;
    $pdo = $sqlite->connect(); 

    if ($pdo == null){
        echo "PDO connection failed \n";
        exit; 
    }
    if($argv[1] === "stats"){
        stats($pdo); 
        $sqlite = null;
    }
    if($argv[1] === "import"){
        import($argv[2], $pdo); 
        $sqlite = null; 
    }
    if($argv[1] === "lookup"){
        lookup($argv[2], $pdo);
        $sqlite = null; 
    }
}else{
    echo_usage();
}

function echo_usage(){
      echo "usage:
        1. php courses.php import [name.csv]
        2. php courses.php lookup CALL_NUMBER
        3. php courses.php stats\n";
      exit;
}

function import($file, $pdo){

    $sql_table = new SQLiteCreateTable($pdo); 
    $sql_table->createTable(); 

    $ins = new SQLiteInsert($pdo);
    $f = fopen($file, 'r') or exit("Cannot open file($file)\n"); 

    if(fgetcsv($f) !== FALSE){ //skips first one line
        while(($arr = fgetcsv($f)) !== FALSE){
            if(count($arr) === 7){
                $ins->insertCourses($arr[0], $arr[1], $arr[2], 
                    $arr[3], $arr[4], $arr[5], $arr[6]);
            }else{
                echo "Missing fields in csv file \n";
                exit; 
            }     
       }
        echo "Sucessfully imported $file. \n";
    }else{
        echo "Import failed \n";
    }
}

function lookup($call_num, $pdo){
    //call number format: {Subject} {Bulletin_Prefix}{Course_Number};
    //ex: COMS W3157

    $arr = explode(" ", strtoupper($call_num));
    $subject = $arr[0];
    $prefix ="";
    $course_num = "";

    if(strlen($call_num) === 10){
        $prefix = substr($arr[1], 0, 1);
        $course_num = substr($arr[1], 1, strlen($arr[1]));
    }else if (strlen($call_num) === 11){
    //some Bulletin_Prefixes have two letters
        $prefix = substr($arr[1], 0, 2);
        $course_num = substr($arr[1], 2, strlen($arr[1]));
    }else{
        echo "Check length of call number \n";
        exit; 
    }

    $stmt = $pdo->query('SELECT * FROM courses;');
    if ($stmt){
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            if ($row['Subject'] === $subject && 
                $row['Bulletin_Prefix'] === $prefix &&
                $row['Course_Number'] === $course_num){
                    echo "Department: $row[Department]\nSubject: $row[Subject]\nBulletin Prefix: $row[Bulletin_Prefix]\nCourse Number: $row[Course_Number]\nName: $row[Name]\nMin Points: $row[Min_Points]\nMax Points: $row[Max_Points]\n";
                    return; 
                }
        }
        echo "The course $call_num was not found. \n";       
    }
}

function stats($pdo){
    echo "Departments with the most courses: \n";
    //var_dump($pdo);

    $q = "SELECT Department AS \"Academic_Department\",
        COUNT(*) AS \"Number_of_courses\"
        FROM courses
        GROUP BY Department
        ORDER BY COUNT(*) DESC LIMIT 5;";

    $stmt = $pdo->query($q);
    // var_dump($stmt);
    if ($stmt){
        echo "Academic Department | Number of Courses \n"; 
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            echo str_pad($row['Academic_Department'],22);
            echo "$row[Number_of_courses]\n";
       }
    }

    echo "\nThe 10 most frequently used words (case insensitive): \n";

    $q = "SELECT Name FROM courses;";
    $stmt = $pdo->query($q);
    if ($stmt){
        //store word and associated frequency in words[]
        $words = array(); 
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $arr = explode(" ", $row['Name']);
            for($i=0; $i<count($arr); $i++){
                $word = strtolower(preg_replace("/[^a-z\d-]+/i", "", $arr[$i]));
                if(strlen($word)!==0 && ctype_alnum($word)){
                    if(!(array_key_exists($word, $words))){
                        $words[$word] = 1;
                    }else{
                        $words[$word] +=1;
                    } 
                }
            }
        }

        /**
         * Search for top 10 highest frequency words in words[] 
         * by storing and removing each max freq word from words[]
         * */

        $top_ten_word = array(); 
        $top_ten_freq = array();
        for($i=0; $i<10; $i++){
            $max_freq = 0;
            $max_freq_word = "";  
            foreach ($words as $key=>$value){
                if ($value > $max_freq){
                    $max_freq = $value;
                    $max_freq_word = $key;  
                }
            }
            array_push($top_ten_word, $max_freq_word);
            array_push($top_ten_freq, $max_freq); 
            unset($words[$max_freq_word]);
        }
        $arr = array_combine($top_ten_word, $top_ten_freq);
        echo str_pad("Word", 10);
        echo "|Frequency\n"; 
        foreach ($arr as $key=>$value){
            echo str_pad($key, 12);
            echo "$value \n";
        }

    }

}

?>
