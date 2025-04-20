<?php
session_start();
ob_start();

$modo = 'local'; 
if($modo =='local'){
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "_login";
$port = 3306;
}
try{
    //Conexão com a porta
    $conexao = mysqli_connect($host,$user,$pass,$dbname);
    $conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);
}catch(PDOException $err){
}
?>