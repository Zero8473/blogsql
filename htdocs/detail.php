<?php
require_once "functions.php";

$entry_ID=$_GET['item'];//the article's id

$conn = null;//initialize connection
require_once "dbconn.php";
$item = getDetailArticle($conn, $entry_ID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="style.css" type="text/css" rel="stylesheet">
    <title>Article</title>

</head>
</html>
<!--creates detailed view of the article the user clicks on-->
    <div class="entry card">
        <h2><?php echo $item['title']?></h2>
        <p><?php echo $item['description'];?></p>
        <p><?php echo $item['date']?><p>
        <?php if ($item['image']!=""):?>
            <img class="image" src='./images/<?php echo $item["image"]?>' alt="couldn't load image">
        <?php endif?>
        <p class="post"><?php echo $item['text']?></p>
    </div>

<a href="index.php">Back to Overview</a>