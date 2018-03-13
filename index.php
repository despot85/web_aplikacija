<?php

session_start();

include("db_mysqli.php");
if ( ! isset($_SESSION['login'])) {
    include("register.php");
} else {
    include("vesti.php");
}
