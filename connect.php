<?php 
$host='localhost';
$dbname='exercises';
$db_user='root';
$db_pass='1234';
$db_option=[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
];
function openConnection(){
    global $host;
    global $dbname;
    global $db_user;
    global $db_pass;
    global $db_option;
    try {
        $conn=new PDO("mysql:host=$host;dbname=$dbname",$db_user,$db_pass);
        // var_dump($conn);
        return $conn;
    } catch (Exception $ex) {
        echo $ex->getMessage();
        // var_dump($ex)->getMessage();
    }
}
// openConnection();
?>