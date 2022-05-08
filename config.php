<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'workshoptool');
    define('DB_PORT', '3307');
    $db = mysqli_connect(DB_SERVER, DB_USERNAME,DB_PASSWORD, DB_DATABASE, DB_PORT);

?>