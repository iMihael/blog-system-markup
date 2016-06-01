<?php

//    session_start();
//    unset($_SESSION['user']);
//    header("Location: index.php");

    session_start();
    session_destroy();
    header("Location: index.php");