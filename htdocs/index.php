<?php
require_once "functions.php";
//$res = $conn->query($select);
//$articles= $res->fetch_assoc(); // das hier holt immer nur die nächste zeile
//$articles=sortDesc($articles); // < sortieren in der DB !!
//while ($article = $res->fetch_assoc()) { // wenn kein Artikel (mehr) da ist zum holen ist der ausdruck == false
//    var_dump($article); // das sollte jetzt die einzlene zeile haben, und alle bis zum schluss ausgeben
//}

//database connection
$conn= null;
require_once "dbconn.php";

//get page number when user clicks on one of the page links or set current page with a default value of 1
$page = getPage();
$pagination=0;//variable for pagination loop
$no_of_articles = 4; //number of displayed articles per page
$offset = ($page-1) * $no_of_articles; //offset for lower limit, first value is 0

//get number of pages in relation to the number of articles per page
$no_of_pages=getNumberOfPages($conn,$no_of_articles);

//get Entries from database for the current page
$articles=getEntries($conn, $offset, $no_of_articles);
echo "<a href='categories.php'>Go to categories</a>";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="style.css" type="text/css" rel="stylesheet">
        <title>My blog</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>
    <body>
        <div class="header">
            <h1>My Blog</h1>
        </div>

        <div class="main">
            <div class="new-Post">
                <a href="new.php" class="new-Post-button">New Blog post</a>
                <br>
            </div>
            <div class="left column">
                <!--iterates over array and creates the article cards-->
                <?php
                //if(is_resource($articles)):
                if ($articles):
                ?>
                    <?php
                        //foreach($articles->fetch_assoc() as $item): //mysql results aren't iterable, so foreach won't work->PDO results are iterable
                    while($item = $articles->fetch_assoc())://fetches all rows of the database
                    ?>
                        <div class="entry card">
                            <h2><a class="title" href="detail.php?item=<?php echo $item['id']?>"><?php echo $item['title']?></a></h2>
                            <p><?php echo $item['description'];?></p>
                            <p><?php echo $item['date']?><p>
                            <?php if ($item['image']!=""):?>
                                <a href="images.php?item=<?php echo $item['id'];?>"><img class="image" src="preview.php?image=<?php echo $item["image"]?>" alt="couldn't load image"></a>
                            <?php endif?>
                        </div>
                        <a href="delete.php?item=<?php echo $item['id']?>" class="delete">Delete</a>
                        <a href="edit.php?item=<?php echo $item['id']?>" class="edit">Edit</a>
                    <?php
                    //endforeach;
                    endwhile;
                    ?>
                    <br>
                <?php endif;?>
            </div>

            <div class="right column">
                <div class="info card">
                    <h2>About</h2>
                    <div class="image">Placeholder for image</div>
                    <p>This is my blog! Thank you for reading!</p>
                </div>

                <div class="popular card">
                    <h2>You might like these posts</h2>
                    <div>
                        <div class="image">Image Placeholder</div>
                        <div class="image">Image Placeholder</div>
                        <div class="image">Image Placeholder</div>
                    </div>
                </div>
            </div>

        </div>
        <div class="footer">
            <!--pagination links-->
            <?php if($no_of_pages!=0):?>
            <div class="pagination">
                <!--previous page-->
                <?php if($page>=2):?>
                    <a href="?page=<?php echo $page -1?>" class="page">vorherige Seite</a>
                <?php endif;?>
                <!--list all pages and highlight current page via css-->
                <?php while($pagination<$no_of_pages):?>
                    <a href="?page=<?php echo $pagination+1;?>" class="page <?php echo ($page==$pagination+1) ? ' current' : '';?>"><?php echo "Seite " . ($pagination+1)?></a>
                    <?php
                    $pagination++;
                endwhile;
                ?>
                <!--next page-->
                <?php if($page<$no_of_pages):?>
                    <a href="?page=<?php echo $page +1?>" class="page">nächste Seite</a>
                <?php endif;?>
            </div>
            <?php endif;?>
        </div>
    </body>
</html>