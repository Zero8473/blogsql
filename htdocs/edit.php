<?php
require_once 'functions.php';

//TO DO when I get back to work: implement checked category, save multiple categories(only saves one rn), add to new.php

$images=getImages();//get the uploaded images
$articleID = $_GET['item'];//ID of the entry to edit
$no_of_images = count($images);

/*pagination variables*/
$page = getPage();
$images_per_page = 5;
$offset = ($page-1)*$images_per_page;
$upper_limit=$offset + $images_per_page;
$no_of_pages= $no_of_images/$images_per_page;
if($no_of_pages!=(int)$no_of_pages){
    $no_of_pages = (int)$no_of_pages + 1;
}

/*database connection and fetching information*/
$conn = null;
require_once "dbconn.php";
$article = getArticle($conn, $articleID);//get article to edit from database
$categories = getCategories($conn);//fetch categories from database
$article_categories = getBlogCategories($conn, $articleID); //get the selected article's categories



?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <title>Edit task</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="node_modules/ckeditor4/ckeditor.js"></script><!-- relativ zu htdocs -->
    </head>
    <body>

    <div class="main">

        <!-- article details and edit fields -->
        <form class="new" action="editprocess.php?item=<?php echo $articleID;?>" method="POST" enctype="multipart/form-data">
            <label for="textfield"></label>
            <input type="text" name="title" placeholder="Type in title" value='<?php echo $article['title'];?>' required>
            <br>
            <textarea name="description" id="textfield" placeholder="Type in description..." cols="40" rows="10" required><?php echo $article['description']?></textarea>
            <br>
            <textarea name="element" id="formattable_text" placeholder="Add new post..." cols="40" rows="10" required><?php echo $article['text']?></textarea>
            <br>
            <?php if($article['image']!=""):?>
            <img class="image" src="./images/<?php echo $article['image'];?>" alt="Could not display image">
            <br>
            <?php endif?>
            <p>Change image</p>
            <?php
            foreach($images as $key=>$picture){
                if($key >=$offset && $key<$upper_limit){
                echo "<input type='radio' id='$key' name='select' value='$picture'>"; // das aktuelle bild braucht ein 'checked' propery
                echo "<label for='$key'><img class='image' src='preview.php?image=$picture' alt='Could not display image'></label><br>";
                }
            }
            ?>
            <br>
            <input type="radio" id="no_image" name="select" value="remove">
            <label for="no_image">remove image</label>
            <br>

            <!-- page links-->
            <div class="pagination">
                <!-- previous page -->
                <?php if($page>=2):?>
                    <a href="?page=<?php echo $page -1?>&item=<?php echo $articleID?>" class="page">prev</a>
                <?php endif;?>
                <!-- list all pages and highlight current page via css -->
                <?php while($pagination<$no_of_pages):?>
                    <a href="?page=<?php echo $pagination+1;?>&item=<?php echo $articleID?>" class="page <?php echo ($page==$pagination+1) ? ' current' : '';?>"><?php echo "page " . ($pagination+1)?></a>
                    <?php
                    $pagination++;
                endwhile;
                ?>
                <!-- next page -->
                <?php if($page<$no_of_pages):?>
                    <a href="?page=<?php echo $page +1?>&item=<?php echo $articleID?>" class="page">next</a>
                <?php endif;?>
            </div>
            <br>

            <!-- image upload button -->
            <input type="file" name="image" value="Upload image" accept="image/png, image/jpeg">
            <br>

            <!-- Category section -->
            <p>Choose categories for your article</p>
            <?php foreach($categories as $category):?>
            <!-- assign value checked to $checked if $category['id'] exists in array $article_category and leaves it empty otherwise -->
            <?php $checked = in_array($category['id'],$article_categories) ? 'checked' : ''; ?>
                <input type="checkbox" id = "category-<?php echo $category['id']?>" name="checkbox[]"  value = "<?php echo $category['id']?>" <?php echo $checked?>>
                <label for = "category-<?php echo $category['id']?>"><?php echo $category['title']?></label>
            <?php endforeach;?>
            <br>

            <!-- submit form -->
            <input type="submit" id="submit" value="UPDATE">
            <!-- text formatting script -->
            <script>
                // Replace the <textarea id="editor1"> with a CKEditor 4
                // instance, using default configuration.
                CKEDITOR.replace( 'formattable_text' );
            </script>
        </form>
    </div>
    </body>
</html>
