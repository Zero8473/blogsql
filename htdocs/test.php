<?php
//$next_page=0;
//$prev_page=0;
//sets current page with a default value of 1
if(isset($_GET['page'])){
    $page = $_GET['page'];
}else{
    $page = 1;
}

$no_of_articles = 4; //number of displayed articles per page
$offset = ($page-1) * $no_of_articles; //offset for lower limit, first value is 0

$conn = null;
require_once "dbconn.php";
if(!$conn || $conn->connect_error){
    die("Connection error: " .  $conn->connect_error);
}
//get number of entries
$get_rows = "SELECT COUNT(*) FROM blog";
$res = $conn->query($get_rows);
$rows_sql = $res->fetch_assoc();//returns array
$no_of_entries = $rows_sql["COUNT(*)"];//string

$no_of_pages = $no_of_entries/$no_of_articles; //could be a fraction!!!!->change or add if statement to handle fractions
//var_dump($no_of_entries);
//var_dump($no_of_pages);
$select = "SELECT * FROM blog ORDER BY date DESC LIMIT $offset, $no_of_articles";
$articles= $conn->query($select);
//var_dump($articles->fetch_assoc());
while($item=$articles->fetch_assoc()){
    var_dump($item);
    echo "here go the articles";
    echo "<br>";
};
//var_dump($rows_sql);
//echo "<br>";
//echo $no_of_entries;


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link   rel="stylesheet" href="style.css" type="text/css">
        <title>Pagination test</title>
        <meta name="viewport" content="width=device-width , initial-scale =1.0">
    </head>
    <body>
        <div class="header">
            <h1>Paging test</h1>
        </div>
        <div class="main">
        <!--    <p>code goes here</p>
            <form class="new" action="test.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="nextpage" value="<?php /*echo $next_page*/?>">
                <input type="hidden" name="prevpage" value="<?php /*echo $prev_page*/?>">
                <input
            </form>-->
        </div>
    </body>
</html>
