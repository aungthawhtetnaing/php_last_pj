<?php 
$nameErr="";
$statusErr="";
if($_SERVER['REQUEST_METHOD']=='POST'){
   
    // include('connect.php');
    // $conn=openConnection();
    // global $conn;
    // global $result;
    // $query='select * from categories';
    $name=$_POST['name'];
    $descripton=$_POST['description'];
    $status=$_POST['status'];
    
    if (empty(trim($name))) {
        $nameErr=true;
    }
    
    
    if ($status==='2') {
        $statusErr=true;
    }

    if (!$nameErr && !$statusErr){
        try {
           $query="insert into categories (name,description,status) values (:name,:description,:status)";

           $stm=$conn->prepare($query);

           $result= $stm->execute([":name"=>$name,":description"=>$descripton,":status"=>$status]);
           
           if (!empty($result)) {
                header('location:index.php');
        }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit('error');
        }
    }
    // var_dump($_REQUEST);
    // var_dump($_GET);
    // echo $_REQUEST['fname'];
    // return;
}
?>