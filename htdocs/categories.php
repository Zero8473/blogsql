<?php
require_once "functions.php";

$conn = null;
require_once "dbconn.php";

$categories=getCategories($conn); //fetch categories from database
echo "<a href = 'index.php'>Back to main page</a>";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="style.css" type="text/css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>blog categories</title>
    </head>
    <body>
        <div class="header">
            <h1>My Blog</h1>
        </div>
        <div class="main">
            <ol class = "left card">
                <?php foreach($categories as $item):?>
                    <li><?php echo $item['title']?></li>
                    <a class="delete" href="categoriesprocess.php?item=<?php echo $item['id']?>">DELETE</a>
                    <br>
                <?php endforeach;?>
            </ol>
        </div>
        <form class="categories" action="categoriesprocess.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="category" placeholder="Add new category here..." required>
            <input type="submit" VALUE="ADD">
        </form>
    </body>
</html>
