<?php
require_once "functions.php";

$selected = $_POST["checkbox"];//selected categories

$conn = null; //initialize connection
require_once "dbconn.php";//establish connection
/*
//$sql = $conn->query("SELECT MAX(id) as MAX FROM blog");//works unless newest gets deleted. Then it takes the second highest existing id -> possible solution: create database that only stores article id
//$sql = $conn->query("SELECT `AUTO_INCREMENT` FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'myblog' AND TABLE_NAME = 'blog'");
$sql_rows = $sql->fetch_assoc();
//var_dump($sql_rows["MAX"]);
//$articleID = $sql_rows["MAX"] + 1;
$articleID = $sql_rows["AUTO_INCREMENT"];
var_dump($articleID);


setCategories($conn, $selected, $articleID);//set the article's categories
*/


//image upload/change
if($_FILES["image"]["tmp_name"]!=null){
    /*temporary name of the uploaded file (upload name)*/
    $tmp_name=$_FILES["image"]["tmp_name"];
    /*base name of file*/
    $name=basename($_FILES["image"]["name"]);
    //moves uploaded image to destination path and returns $name
    moveImage($tmp_name,$name);
//set the article's image to either the selected image or "no image" if no selection was made
}else{
    $name=$_POST['select'];
}

//store posted information in variables and prepare for insertion into database
$title=$_POST['title'];
$description= $_POST['description'];
$image = $name;
$text= $_POST['element'];

//$insert = "INSERT INTO blog(title, description, image, text) VALUES ($entry["title"], $entry['description'], $entry['image'], $entry['text'])"; // does not work since mysql does not understand the array data type
//add article to database
insertEntries($conn, $title, $description, $image, $text); //insert new entry into database
header('Location:index.php');
