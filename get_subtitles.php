<?php
$subtitleFile = $_GET['subtitleFile'];

try {
    $fileContents = file_get_contents($subtitleFile);
    echo $fileContents;
} catch (Exception $e) {
    echo "Error: ", $e->getMessage();
}
?>
