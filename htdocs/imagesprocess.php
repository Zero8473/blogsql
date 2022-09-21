<?php
require_once 'functions.php';
$item=$_GET['item'];
//$articles=getData();

$conn=null;
require_once "dbconn.php";

// get article's image from database (necessary if user doesn't change the image)
/*$select = "SELECT * FROM blog WHERE id='{$item}'"; //can't just select image, since it returns an array. You'd still have to assign $elem['image'] to the variable you want to insert
$article = $conn->query($select);
$elem = $article->fetch_assoc();*/
$elem = getArticle($conn, $item);

//remove/change image if user selected remove button or an image

        //checks if new image got uploaded and updates the article's image
        if($_FILES["image"]["tmp_name"]!=null) {
            $tmp_name = $_FILES["image"]["tmp_name"];
            $name = basename($_FILES["image"]["name"]);
            moveImage($tmp_name, $name);
            //$articles[$key]['image']=$name;
        }
        //delete image if delete button is pressed
        elseif($_POST['select']=="remove"){
           // $articles[$key]['image'] = '';
            $name = '';
        }
        //only changes picture if a picture was selected
        elseif($_POST['select']!=null) {
            //$articles[$key]['image'] = $_POST['select'];
            $name = $_POST['select'];
        }else{
            $name = $elem['image'];
        // das würde reichen: (atomarer update)
        }

$image = $name;
//update image
/*$update = "UPDATE blog SET image='{$image}' WHERE id='{$item}'";
$conn->query($update);*/
updateArticleImage($conn, $image, $item);
//if you want to use variable in header either use concatenation(here) or ""
/*header('Location:edit.php?item='.$item);*/
header('Location:index.php');
?>
<!--<a href='index.php'>Back to Overview</a>-->