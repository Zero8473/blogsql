<?php
require_once "functions.php";
$toDelete=$_GET['item'];//ID of article to delete
//establish connection to database
$conn = null;
require_once "dbconn.php";
//delete the article
deleteArticle($conn, $toDelete);//delete the article
header('Location: index.php');