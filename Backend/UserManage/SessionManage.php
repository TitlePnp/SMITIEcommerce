<?php 
function clearSession() {
    session_start();
    session_unset();
    session_destroy();
}
?>