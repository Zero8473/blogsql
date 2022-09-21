<!DOCTYPE html>
<html lang="en">
    <head>
       <!-- <link href="style.css" type="text/css" rel="stylesheet">-->
        <title>MySQL blog</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
            <h1>SQL connection test</h1>
            <?php
            //Ansatz require dbconn.php
            $conn = null; // init
            require_once "dbconn.php";
            /*
            try{
                require_once "dbconn.php";
            } catch (Exception $ex){
                $error=$ex->getMessage();
            }
            if(isset($error)){
                echo "<p>$error</p>";
            }else{
                echo "<p>Connected!</p>";
            }
            */
            //Ansatz direkte Verbindung
            /*
            $db_host='127.001';
            $db_user='root';
            $db_password='D3@th5t@r';
            $db_name='myblog';
            $db_port='3306';

            //Database connection
            $conn= new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);
            */
            if(!$conn || $conn->connect_error){
               die("Connection failed: ". $conn->connect_error);
            }
            //method 1 (could be used to create a function that fetches data
            $sql  = 'SELECT * FROM blog';
            $res = $conn->query($sql);
            $give = $res->fetch_assoc();
            var_dump($give);
            /*while($give=$res->fetch_assoc()){
                $out=$give['name'];
                var_dump($give);
            }
            */
            //method 2
            /*$sql='SELECT * FROM test';
            $res= mysqli_query($conn,$sql);
            while($give=mysqli_fetch_assoc($res)){
                $out=$give['name'];
                var_dump($out);
            }*/
            /*$sql = 'INSERT INTO test(name, text) VALUES("Attempt", "trying to insert values using php")';
            $conn->query($sql);
            var_dump($out);*/
            /*$sql = 'DELETE FROM test WHERE id=4';
            $conn->query($sql);
            var_dump($out);*/
            echo "<br>".'version update';
            ?>
    </body>
</html>