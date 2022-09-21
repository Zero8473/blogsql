<?php
require_once 'functions.php';
/*$articles=getData();*/
$articleID=$_GET['item'];//article ID
$selected = $_POST["checkbox"];//selected categories
//database connection
$conn = null;
require_once "dbconn.php";
//get element to edit for image in case user doesn't change image
$article = getArticle($conn, $articleID);//get article to edit from database

/*
 *
 *
 *
 1.) alle blog_categories löschen wo blog_id = $articleID
 2.) insert alles aus $articleCats in blog_categories mit $value und $articleID
 *
 *
blog_id     categeory_1
1           1
1           2
1           5
2           1
2           5
 *

unique key (blog_id,category_id)
 */
deleteArticleCats($conn, $articleID);//delete the old categories assigned to the article

setCategories($conn, $selected, $articleID);//set the article's categories

/*checks if new image got uploaded and updates the article's image*/
if($_FILES["image"]["tmp_name"]!=null){
    $tmp_name=$_FILES["image"]["tmp_name"];
    $name=basename($_FILES["image"]["name"]);
    moveImage($tmp_name, $name);
//replaces image with selected image
} elseif ($_POST['select'] && $_POST['select']!="remove") {
    $name = $_POST['select'];
//removes image if remove button was selected in edit.php
} elseif ($_POST['select'] == "remove"){
    $name='';
//otherwise the image remains unchanged
} else {
    $name=$article['image'];
}

//store posted information in variables and prepare for insertion into database
$title = $_POST['title'];
$description = $_POST['description'];
$image = $name;
$text = $_POST['element'];

//update database
updateArticle($conn, $title, $description, $image, $text, $articleID);//update database
header('Location: index.php');