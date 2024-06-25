<?php
if (isset($success_msg)) {
    foreach ($success_msg as $msg) {
        echo '<div class="label-container success" style="background: #CCFFCC;">
        <span class="message">'.$msg.'</span>
        <span class="close-btn" onclick="closeLabel(this)">&times;</span>
    </div>';
    }
}
if (isset($warning_msg)) {
    foreach ($warning_msg as $msg) {
        echo '<div class="label-container error" style="background: #FFFF44;">
        <span class="message">'.$msg.'</span>
        <span class="close-btn" onclick="closeLabel(this)">&times;</span>
    </div>
    ';
    }
}
if (isset($error_msg)) {
    foreach ($error_msg as $msg) {
        echo '<div class="label-container error" style="background: #FF8888 !important;">
        <span class="message">'.$msg.'</span>
        <span class="close-btn" onclick="closeLabel(this)">&times;</span>
        </div>';
    }
}
if (isset($info_msg)) {
    foreach ($info_msg as $msg) {
        echo '<div class="label-container info" style="background: #CCCCFF;>
        <span class="message">'.$msg.'</span>
        <span class="close-btn" onclick="closeLabel(this)">&times;</span>
        </div>';
    }
}
?>
