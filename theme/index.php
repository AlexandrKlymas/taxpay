<?php
echo '<h2>Школа бiзнесу</h2>';
$files = scandir(__DIR__);
foreach($files as $file) {
    if ( $file !='.' && $file != '..' && $file != 'index.php' && $file != 'robots.txt'  ) {
        if (!is_dir($file)) {
            echo  '<a target=_blank href="'.$file.'">' . $file .' </a><br> ';
        }
    }
}