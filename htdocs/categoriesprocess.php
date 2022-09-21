<?php
require_once "functions.php";
$conn = null;
require_once "dbconn.php";

$categoryToAdd = $_POST['category'];
$categoryToDelete = $_GET['item'];

//add category to table or delete the selected category from table depending on the users action
processCategories($conn, $categoryToAdd, $categoryToDelete);
header('Location:categories.php');
?>

