<?php
require_once "functions.php";

$selected = $_POST["checkbox"];//selected categories

$conn = null; //initialize connection
require_once "dbconn.php";//establish connection



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

//add article to database
insertEntries($conn, $title, $description, $image, $text); //insert new entry into database
header('Location:index.php');
