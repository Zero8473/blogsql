<?php
//get articles from dbz


//takes in an array and sorts the element in descending order
function sortDesc($articles){
    //defining the compare function as an anonymous function inside usort
    // 1 means it will be put at the end of the list, -1 at the beginning of the list, 0 means no change
    usort($articles, function($a, $b){
        //converts variable to time format
        $aTimestamp=strtotime($a['date']);
        $bTimestamp=strtotime($b['date']);
        //the greater element will be put at the top of the list
        if($aTimestamp>$bTimestamp){
            return -1;
        }
        //the smaller element will be put at the bottom
        if($aTimestamp<$bTimestamp){
            return 1;
        }
        return 0;
    });
    return $articles;
}

/*image functions*/

//moves uploaded image so it can be used for the blog
function moveImage($tmp_name,$name){
    /*destination path to move the image to*/
    $uploads_dir= './images';
    /*moves the uploaded file to the specified destination path and sets its name*/
    move_uploaded_file($tmp_name, "$uploads_dir/$name");
}

//gets uploaded images
function getImages(){
    $dir = "./images";
    $images=[];
    //open directory and add the names of the images to the array
    if(is_dir($dir)){
        if($dh=opendir($dir)){
            while(($file = readdir($dh)) !== false) {
                if($file!='..' && $file!='.'){ // was wenn $file ein unter-verzeichniss ist?
                    $images[]=$file;
                }
            }
            closedir($dh);
        }
    }
    return $images;
}

/*page functions*/

//get page number when user clicks on one of the page links or set current page with a default value of 1
function getPage(){
    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }else{
        $page = 1;
    }
    return $page;
}

//calculate number of pages
function getNumberOfPages($conn,$no_of_articles){
    //get number of entries
    $get_rows = "SELECT COUNT(*) AS COUNTROWS FROM blog"; // es bietet sich an sowas zu machen: SELECT COUNT(*) as blogcount FROM blog
    $sql = $conn->query($get_rows);
    $rows_sql = $sql->fetch_assoc();//returns array
    $no_of_entries = $rows_sql["COUNTROWS"];//string // dann kann man hier $rows_sql['blogcount'] machen

// floor() / ceil() / round()
    $no_of_pages = $no_of_entries/$no_of_articles; //could be a fraction!!!!->change or add if statement to handle fractions
//if no of pages is a fraction, it will be increased to the next greater integer number
    if($no_of_pages!=(int)$no_of_pages){
        $no_of_pages=(int)$no_of_pages +1;
    }
    return $no_of_pages;
}


/*article functions*/

//get Entries from database
function getEntries($conn, $offset, $no_of_articles){

    //%d is placeholder for decimals(integers), %s for string. If var is a string it will return 0. You can also change the order in which arguments are taken in i.e. LIMIT %2$d,%1$d and which would be $no_of_articles, $offset
    $normalesquery = "SELECT * FROM blog ORDER BY date DESC LIMIT %d, %d";

    $select = sprintf($normalesquery, $offset, $no_of_articles);//sprintf returns a formatted string, while printf is like echo  // prepare und bind anschauen, zumindest sicherstellen das wir hier integer reinkloppen
    $articles = $conn->query($select);
    return $articles;
}

//insert new entry into database
function insertEntries($conn, $title, $description, $image, $text){

    $insert = "INSERT INTO blog(title, description, image, text) VALUES(?, ?, ?, ?)";
    $stmt = $conn->prepare($insert);// prepare SQL statement
    $stmt->bind_param("ssss", $title, $description, $image, $text);//bind parameters   //s for strings and i for integers
    $stmt->execute();//connect to database and execute query
}

//get article to edit from database
function getArticle($conn, $item){

    $stmt = $conn->prepare("SELECT * FROM blog WHERE id=?");// prepare SQL statement
    $stmt->bind_param("i", $item);//bind parameters and makes sure it's of the specified type
    $stmt->execute();//connect to database and execute query
    $article=$stmt->get_result();//get mysql result
    $elem = $article->fetch_assoc();//fetch data
    return $elem;//return article to website

}
function getDetailArticle($conn, $entry_ID){
    $stmt = $conn->prepare("SELECT * FROM blog WHERE id=?");
    $stmt->bind_param("i", $entry_ID);
    $stmt->execute();
    $article= $stmt->get_result();
    $item = $article->fetch_assoc();
    return $item;
}

//update article in the database
function updateArticle($conn, $title, $description, $image, $text, $item){
    $stmt=$conn->prepare("UPDATE blog SET title = ?, description = ?, image = ?, text = ? WHERE id = ? ");
    //$stmt->bind_param("ssssi", $title, $description, $image, (string)$text, (int)$item);
    $stmt->bind_param("ssssi", $title, $description, $image, $text, $item);

    $stmt->execute();
}

//delete article from database
function deleteArticle($conn, $toDelete){
    //prepare and bind
    $stmt = $conn->prepare("DELETE FROM blog WHERE id = ?");
    $stmt ->bind_param("i", $toDelete);
    $stmt->execute();
}

//update the article's image
function updateArticleImage($conn, $image, $item){
    $stmt= $conn->prepare("UPDATE blog SET image = ? WHERE id = ?");
    $stmt->bind_param("si", $image, $item);
    $stmt->execute();
}


/*category functions*/

//get categories from database
function getCategories($conn){
    $getCategories = "SELECT * FROM categories";
    $categories = $conn->query($getCategories);
    return $categories->fetch_all(MYSQLI_ASSOC); //returns associative array (key=>value pairs)
}

//add category to table or delete the selected category from table depending on the users action
function processCategories($conn, $categoryToAdd, $categoryToDelete){
    if(isset($categoryToAdd)){
        $insert  = "INSERT INTO categories(title) VALUES(?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("s", $categoryToAdd);
        $stmt->execute();
    }elseif(isset($categoryToDelete)) {
        $delete = "DELETE FROM categories WHERE id = ?";
        $stmt = $conn->prepare($delete);
        $stmt->bind_param("i", $categoryToDelete);
        $stmt->execute();
    }
}

//get the selected article's categories
function getBlogCategories($conn, $ID){
    $getBlogCategories = "SELECT * FROM blog_categories WHERE blog_id = '{$ID}'";
    $categories = $conn->query($getBlogCategories);
    $return = [];
    while($row = $categories->fetch_assoc()) {
        $return[] = $row['category_id'];
    }
    return $return;
}

//delete the old categories assigned to the article (to make sure only the previously selected ones are assigned to the article)
function deleteArticleCats($conn, $articleID){
$query = "DELETE FROM blog_categories WHERE blog_id = '{$articleID}' ";
$conn->query($query);
}

//set the article's categories
function setCategories($conn, $selected, $articleID){
    //foreach loop that iterates over the array checkbox[] that has the selected options stored
    foreach($selected as $key=>$selectedOption){
        //add each selected category to the database while assigning the same blog id to all of the rows
        $stmt = $conn->prepare("INSERT INTO blog_categories (blog_id, category_id) VALUES (?,?)");
        $stmt->bind_param("ii", $articleID, $selectedOption);
        $stmt->execute();
    }
}





