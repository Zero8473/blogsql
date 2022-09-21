<?php

//document root is the directory in which this php file is located in regardless of whether it's been moved
//the part behind it is the folder relative to this file's directory
$cache_dir=$_SERVER['DOCUMENT_ROOT']."/thumbnails/";
$image_dir=$_SERVER['DOCUMENT_ROOT']."/images/";
//original images
$image = basename($_GET['image']);



//default directory
$readfromdirectory = 'images';
//default file to read
$filenametoread = $image;
//type of the image(jpg, png etc.)
$imagetype = exif_imagetype($image_dir.basename($image));

        list($width_orig, $height_orig) = getimagesize($image_dir . basename($image));
        //create thumbnail if image is bigger than the specified dimensions
        if ($width_orig >= 300 || $height_orig >= 300) {
            //reassign directory
            $readfromdirectory = 'thumbnails';
            //new dimensions for preview picture
            $new_width = 300;
            // TODO: Aspect ratio ekrennen für hochkant bilder
            //check if image is portrait or landscape and adjusts ratio accordingly
            if ($height_orig > $width_orig) {
                $ratio = $width_orig / $height_orig;//portrait
            } else {
                $ratio = $height_orig / $width_orig;//landscape and even ratio
            }
            //calculate height based on the ratio of the original image and the width of the preview image
            //"convert" new height to int since imagecreatecolor requires the arguments to be integers
            $new_height = (int)$new_width * $ratio;
            //reassign filename to name of the thumbnail
            // new height has to be converted to int since the created value is of type float
            $filenametoread = 'thumb_' . $image . '_' . $new_width . '_' . (int)$new_height . '.jpg';
        }
        //check if thumbnail doesn't exists or if thumbnail is older than the original image and create thumbnail if either of the conditions evaluates to true
        if (
            !is_file($_SERVER['DOCUMENT_ROOT']."/".$readfromdirectory.'/'.$filenametoread)
            ||
            filemtime($_SERVER['DOCUMENT_ROOT']."/".$readfromdirectory.'/'.$filenametoread) < filemtime($image_dir.$image)

        ) {

            $preview = imagecreatetruecolor($new_width, $new_height);
            //check if image is jpg or png and create thumbnail from jpg or png respectively
            switch($imagetype){
                //jpg
                case '2':
                    $source = imagecreatefromjpeg($image_dir . basename($image));
                    imagecopyresampled($preview, $source, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
                    //header('Content-Type: image/jpeg');
                break;
                //png
                case '3':
                    $source = imagecreatefrompng($image_dir.basename($image));
                    imagecopyresampled($preview, $source, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
                    //header('Content-Type: image/png');
                break;
            }
            $imagetype = 2; // force to be jpeg



            //output thumbnail to brower and save it to $cache_dir.$filenametoread (which also names the file)
            imagejpeg($preview, $cache_dir.$filenametoread, 100);
        }
        //output correct type
        //thumbnail will always be a jpeg(line 91), the original can be either jpg or png
        switch($imagetype) {
            case 2:
                header('Content-Type: image/jpeg');
                break;
            case 3:
                header('Content-Type: image/png');
                break;
        }

        //output image or thumbnail depending on whether directory or filename was reassigned
        readfile($_SERVER['DOCUMENT_ROOT']."/".$readfromdirectory.'/'.$filenametoread);





