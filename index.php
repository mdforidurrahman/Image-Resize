<?php

// Function to resize an image
function resize_image($file, $new_width)
{
    list($width, $height) = getimagesize($file);
    $aspect_ratio = $width / $height;
    $new_height = $new_width / $aspect_ratio;

    $thumb = imagecreatetruecolor($new_width, $new_height);

    // Check the image type
    $info = getimagesize($file);
    $mime_type = $info['mime'];

    // Create the image based on the mime type
    switch ($mime_type) {
        case 'image/jpeg':
            $source = imagecreatefromjpeg($file);
            break;
        case 'image/png':
            $source = imagecreatefrompng($file);
            break;
        case 'image/gif':
            $source = imagecreatefromgif($file);
            break;
        default:
            return false;
    }

    // Resize the image
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Output the resized image to a file
    $output_file = 'resized/' . basename($file);
    imagejpeg($thumb, $output_file);

    // Free up memory
    imagedestroy($thumb);

    return $output_file;
}

// Directory containing images
$directory = 'images/';

// New width for resized images
$new_width = 300;

// Create a directory for resized images if it doesn't exist
if (!file_exists('resized')) {
    mkdir('resized', 0777, true);
}

// Get all files from the directory
$files = glob($directory . '*.{jpg,jpeg,png}', GLOB_BRACE);

// Loop through each file and resize it
foreach ($files as $file) {
    resize_image($file, $new_width);
}

echo "Images resized successfully!";
