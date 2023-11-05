<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Include Moodle's necessary files
require_once('../config.php');
require_login(); // Ensure the user is logged in to Moodle

// Get the context and course ID
$context = context_course::instance($COURSE->id);
// Check the user's role within the course
$canRewind = has_capability('moodle/course:manageactivities', $context);
?>
<!DOCTYPE html>
<html>
<head>
<title>Sample Course </title>
</head>
<body>
<h1>Course Video</h1>

<!-- Add video upload form -->
<?php if ($canRewind) { ?>
    <h2>Upload Video:</h2>

    <form action="upload_video.php" method="post" enctype="multipart/form-data">
         <input type="file" name="video" id="video">
        <input type="submit" value="Upload Video" name="submit">
    </form>
<?php } ?>

<!-- Add subtitle upload form -->
<?php if ($canRewind) { ?>
    <h2>Upload Subtitles:</h2>

    <form action="upload_subtitle.php" method="post" enctype="multipart/form-data">
        <input type="file" id="subtitleFile" name="subtitleFile" accept=".vtt" required>
                
         <input type="submit" value="Upload">
    </form>
<?php } ?>

<!-- Display the available videos -->
<h2>Videos:</h2>
<?php
$videoDirectory = 'videos/';
$videos = array_diff(scandir($videoDirectory), array('.', '..', '.DS_Store'));
foreach ($videos as $video) {
    echo "<a href='watch.php?video=" . urlencode($video) . "'>" . $video . "</a><br>";
}
?>
</body>
</html>
