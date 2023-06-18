<?php
    $_SESSION = array();//change session empty
    session_destroy();//delete session
    header("Location:/login");
?>