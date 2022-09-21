<?php
require_once "functions.php";
//get the uploaded images
$images=getImages();
$item = $_GET['item'];
$no_of_images = count($images);

$page = getPage();//get page number
$images_per_page = 5;
$offset = ($page-1) * $images_per_page; //lower limit for picture index
$upper_limit = $offset + $images_per_page;//upper  limit for picture index
$no_of_pages = $no_of_images/$images_per_page;
//set correct number of pages if $no_of_pages isn't a whole number
if($no_of_pages!=(int)$no_of_pages){
    $no_of_pages=(int)$no_of_pages+1;
}

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Blog | Images</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale:1.0">
    </head>
    <body>
<!-- create list of images to choose from -->
        <form class="list" action="imagesprocess.php?item=<?php echo $item;?>" method="POST" enctype="multipart/form-data">
            <p>Choose image</p>
            <?php
            foreach($images as $key=>$picture){
                if($key >= $offset && $key <$upper_limit){
                echo "<input type='radio' id='$key' name='select' value='$picture'>";
                echo "<label for='$key'><img class='image' src='preview.php?image=$picture' alt='Could not display image'></label><br>";
                }
            }
            ?>
            <input type="radio" id="no_image" name="select" value="remove">
            <label for="no_image">remove image</label>
            <br>
            <input type="file" name="image" value="Upload image" accept="image/png, image/jpeg">
            <br>
            <input type="submit" id='submit' value="select">
        </form>
        <br>
        <!--pagination links-->

        <div class="pagination">
            <!--previous page-->
            <?php if($page>=2):?>
                <a href="?page=<?php echo $page -1?>&item=<?php echo $item?>" class="page">vorherige Seite</a>
            <?php endif;?>
            <!--list all pages and highlight current page via css-->
            <?php while($pagination<$no_of_pages):?>
                <a href="?page=<?php echo $pagination+1;?>&item=<?php echo $item?>" class="page <?php echo ($page==$pagination+1) ? ' current' : '';?>"><?php echo "Seite " . ($pagination+1)?></a>
                <?php
                $pagination++;
            endwhile;
            ?>
            <!--next page-->
            <?php if($page<$no_of_pages):?>
                <a href="?page=<?php echo $page +1?>&item=<?php echo $item?>" class="page">nächste Seite</a>
            <?php endif;?>
        </div>
    </body>

</html>

<?php
?>