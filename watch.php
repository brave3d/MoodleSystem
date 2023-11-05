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

<!-- watch.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Sample Course</title>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #player {
            max-width: 640px;
        }
    </style>
</head>

<body>
    <h1>Course Video</h1>



    <?php
    // Get the video filename from the URL parameter
    $video = $_GET['video'];
    // Validate the video filename (e.g., check if it exists and is a supported video format)
    // Generate the video URL
    $videoURL = 'videos/' . $video;
    // Generate the subtitle URL
    $subtitleName = pathinfo($video, PATHINFO_FILENAME) . '.vtt';
    $subtitleURL = 'subtitles/' . $subtitleName;

    // Check if the videos folder is empty
    $videosFolder = glob('videos/*');
    if (empty($videosFolder)) {
        echo '<p>No videos available.</p>';
        exit;
    }

    // Check if the subtitles folder is empty
    $subtitlesFolder = glob('subtitles/*');
    if (empty($subtitlesFolder)) {
        echo '<p>No subtitles available.</p>';
        exit;
    }
    ?>

   <div class="row">
    <div class="col-lg-6">
    <div id="player">
        <video style="margin-bottom:20px" id="videoPlayer" crossorigin <?php if (!$canRewind) {
            echo 'disableRemotePlayback';
        } ?> controls
            controlsList="<?php echo ($canRewind ? 'nodownload' : 'nodownload,noseek') ?>">
            <source src="<?php echo $videoURL; ?>" type="video/mp4">
            <track kind="subtitles" label="English" src="<?php echo $subtitleURL; ?>" srclang="en" default>
        </video>
    </div>

    <div id="question">

    </div>
    </div>
    <div class="col-lg-6">

        <?php if ($canRewind) { ?>
            <div class="questions">
                
            </div>
        <?php } ?>
    </div>

   </div>
 

    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    <script src="./pause.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const player = new Plyr('#videoPlayer', {
                disableContextMenu: true,
                hideControls: <?php echo ($canRewind ? 'false' : 'true'); ?>,
                keyboard: { focused: false, global: <?php echo ($canRewind ? 'true' : 'false'); ?> },
                tooltips: { controls: true, seek: <?php echo ($canRewind ? 'true' : 'false'); ?> },
                listeners: {
                    controlshidden: function () {
                        player.currentTime = 0; // Reset the seek position to the beginning when controls are hidden
                    },
                    seek: function (event) {
                        if (!<?php echo ($canRewind ? 'true' : 'false'); ?>) {
                            event.preventDefault();
                            player.currentTime = 0;  // Reset the seek position to the beginning
                        }
                    }
                }
            });

            // Disable seeking using mouse and keyboard
            player.on('seek', function (event) {
                if (!<?php echo ($canRewind ? 'true' : 'false'); ?>) {
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }
            });

            // Disable clicking on the progress bar
            if (!<?php echo ($canRewind ? 'true' : 'false'); ?>) {
                const progressBar = document.querySelector('.plyr__progress');
                if (progressBar) {
                    progressBar.style.pointerEvents = 'none';
                }
            }
            let intervalId;
            ///
            /// Fetch subtitles from the server
            ///
            const interval = 10; // change to 60 in production
            player.on('play', async () => {
                console.log("play");
                intervalId = setInterval(async () => {
                    let currentTime = player.currentTime;
                    const url =  "<?php echo $subtitleURL?>";
                    console.log('subtitleURL', url)
                    const subtitles = await fetchSubtitles(currentTime - interval, currentTime,url);
                    if (subtitles !== null) {
                        player.pause();
                    }
                }, interval * 1000);
            });

            player.on('pause', () => {
                clearInterval(intervalId);
            });

        });
    </script>
</body>

</html>
