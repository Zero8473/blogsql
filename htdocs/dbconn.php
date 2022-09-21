<?php
//db setup
$db_host='localhost';
$db_user='root';
$db_password='D3@th5t@r';
$db_name='myblog';
$db_port='3306';

//Database connection
$conn= new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);
if($conn->connect_error){
    $error=$conn->connect_error;
}
//disconnect on error and output error message
if(!$conn||$conn->connect_error){
    die("Connection error: " . $conn->connect_error); // move to dbconn.php
}