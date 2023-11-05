<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = 'videos/';
    $targetFile = $targetDir . basename($_FILES['video']['name']);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file is a video
    $allowedFormats = ['mp4', 'avi', 'mov'];
    if (!in_array($videoFileType, $allowedFormats)) {
        echo 'Error: Only MP4, AVI, and MOV files are allowed.';
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo 'Error: File already exists.';
        $uploadOk = 0;
    }

    // Check file size (limit it to 100MB)
    $maxFileSize = 100 * 1024 * 1024;
    if ($_FILES['video']['size'] > $maxFileSize) {
        echo 'Error: File size exceeds the limit of 100MB.';
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo 'Error: File was not uploaded.';
    } else {
        if (move_uploaded_file($_FILES['video']['tmp_name'], $targetFile)) {
            echo 'Success: The file ' . basename($_FILES['video']['name']) . ' has been uploaded.';
        } else {
            echo 'Error: There was an error uploading your file.';
        }
    }
}
?>
