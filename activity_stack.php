<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['activity_stack'])) {
    $_SESSION['activity_stack'] = [];
}


function push_activity($message) {
    $log_entry = "[" . date('H:i:s') . "] " . $message;
    
    array_push($_SESSION['activity_stack'], $log_entry);
    
    if (count($_SESSION['activity_stack']) > 10) {
        array_shift($_SESSION['activity_stack']);
    }
}


function get_stack_activities() {
    return array_reverse($_SESSION['activity_stack']);
}
?>