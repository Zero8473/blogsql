<?php
require_once 'functions.php';
//get the uploaded images
$images=getImages();
$no_of_images=count($images);
//get current page
$page = getPage();

$images_per_page = 5;
$offset = ($page-1)*$images_per_page;//lower limit for pictures
$upper_limit = $offset + $images_per_page;//upper limit for pictures
$no_of_pages = $no_of_images/$images_per_page;
//ensure number of pages is a whole number
if($no_of_pages!=(int)$no_of_pages){
    $no_of_pages = (int)$no_of_pages+1;
}

/*database connection and fetching information*/
$conn = null;
require_once "dbconn.php";
$categories = getCategories($conn);//fetch categories from database
?>

<!DOCTYPE html>
<html>
    <head>
        <link href="style.css" type="text/css" rel="stylesheet">
        <title>New Post</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="node_modules/ckeditor4/ckeditor.js"></script>
    </head>
    <body>
    <form class="new" action="newprocess.php" method="POST" enctype="multipart/form-data">
        <!--label for="textfield"></label>-->
    <!-- input for title -->
        <input type="text" name="title" placeholder="Type in title..." required>
        <br>
    <!--  input for post description  -->
        <textarea name="description" id="textfield" placeholder="Type in description..." cols="40" rows="10" required></textarea>
        <br>
    <!--  input for text  -->
        <textarea name="element" id="formattable_text" placeholder="Add new post..." cols="40" rows="10" required></textarea>
        <br>
        <!--list of already uploaded images to choose from-->
        <p>Choose image</p>
        <?php
        foreach($images as $key=>$picture){
            if($key>=$offset && $key<$upper_limit){
            echo "<input type='radio' id='$key' name='select' value='$picture'>";
            echo "<label for='$key'><img class='image' src='preview.php?image=$picture' alt='Could not display image'></label><br>";
            }
        }
        ?>
        <br>
        <!-- pagination links -->
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
    <!--  input for image  -->
        <br>
        <input type="file" name="image" value="Upload image" accept="image/png, image/jpeg">
        <br>
        <!-- Category section -->
        <p>Choose categories for your article</p>
        <?php foreach($categories as $category):?>
            <!-- assign value checked to $checked if $category['id'] exists in array $article_category and leaves it empty otherwise -->
            <input type="checkbox" id = "category-<?php echo $category['id']?>" name="checkbox[]"  value = "<?php echo $category['id']?>">
            <label for = "category-<?php echo $category['id']?>"><?php echo $category['title']?></label>
        <?php endforeach;?>
        <br>
        <br>
        <a href ="index.php" class="cancel-button">Cancel</a>
        <input type="submit" id="submit" value="ADD">
        <script>
            CKEDITOR.replace('formattable_text');
        </script>
    </form>
    </body>
</html>
