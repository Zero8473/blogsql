<?php

//document root is the directory in which this php file is located in regardless of whether it's been moved
//the part behind it is the folder relative to this file's directory
$cache_dir=$_SERVER['DOCUMENT_ROOT']."/thumbnails/";
$image_dir=$_SERVER['DOCUMENT_ROOT']."/images/";
//original images
$image = basename($_GET['image']);


// 1. muss ich ein thumbnail generieren (w || h > 300) ?
// 2. wenn ja existiert ein thumbnail?
// 3. ist das bild neuer als das thumbnail?
// 4. wenn 2 == nein oder 3 == ja dann thumbnail generieren
// 5. das passende bild zurückgeben

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

            //var_dump([$preview,$source]);
            //print_r([$ratio,$new_width,$new_height,$width_orig, $height_orig,$image_dir,$image]);
            //echo date('H:i:s').':'.__FILE__.':'.__LINE__;exit;

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

// filemtime
// Wenn thumbnail exisitert & original neuer ist > neu generieren
// ideen cache filename
// - originial.jpg > thumb_original.jpg
// - originial.jpg > thumb_rahmen_shadow_text_tgtwidth_original.jpg
// - sha1('original.jpg'.$tgtwidth) > rtfguybhjnhgdf > rtfguybhjnhgdf.jpg
// readfile($thumbfile);->opens file and immediately outputs it. You can't make further changes
// png support

/*
 *
 *
 * 1. Key - alles sammeln was diesen cache ausmacht. Hier im beispiel: quelldatei, zielhöhe , zielbreite
 * 2. Feststellen ob es diesen Key gibt. Hier im beispiel: key + .jpg als datei im ordner thumbnails
 * 3. Feststellen ob Cache noch gültig ist Hier im beispiel: orginaldatei ist älter als cachedatei
 * 4. Falls notwendig: cache generieren - Die teure aktion!
 * 5. Gecachten inhalt ausgeben
 *
 *
 * */



