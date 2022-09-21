<?php
require_once 'functions.php';
$item=$_GET['item'];


$conn=null;
require_once "dbconn.php";

// get article's image from database (necessary if user doesn't change the image)

$elem = getArticle($conn, $item);

//remove/change image if user selected remove button or an image

        //checks if new image got uploaded and updates the article's image
        if($_FILES["image"]["tmp_name"]!=null) {
            $tmp_name = $_FILES["image"]["tmp_name"];
            $name = basename($_FILES["image"]["name"]);
            moveImage($tmp_name, $name);

        }
        //delete image if delete button is pressed
        elseif($_POST['select']=="remove"){

            $name = '';
        }
        //only changes picture if a picture was selected
        elseif($_POST['select']!=null) {

            $name = $_POST['select'];
        }else{
            $name = $elem['image'];

        }

$image = $name;

updateArticleImage($conn, $image, $item);
//if you want to use variable in header either use concatenation(here) or ""
/*header('Location:edit.php?item='.$item);*/
header('Location:index.php');
?>
