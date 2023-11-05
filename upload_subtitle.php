<?php
    // Define the directory to store the subtitles
    $subtitleDirectory = 'subtitles/';

    // Check if the subtitle file was uploaded successfully
    if ($_FILES['subtitleFile']['error'] === UPLOAD_ERR_OK) {
        $video = $_POST['video'];
        $subtitleFile = $_FILES['subtitleFile']['tmp_name'];
        $subtitleName = $_FILES['subtitleFile']['name'];

        // Generate the subtitle file path
        $subtitlePath = $subtitleDirectory . $subtitleName;

        // Move the subtitle file to the designated directory
        if (move_uploaded_file($subtitleFile, $subtitlePath)) {
            // Store the association between the video and subtitle in a database if needed

            // Display success message
            echo "Subtitle uploaded successfully!";
        } else {
            // Display error message
            echo "Failed to upload the subtitle.";
        }
    } else {
        // Display error message
        echo "Error uploading the subtitle.";
    }
?>
