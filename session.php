<?php
    include('config.php');
    session_start();

    $user_check = $_SESSION['login_user'];

    $ses_sql = "select * from students where user_name ='".$user_check."'";

    /*
    $ses_sql = "SELECT username FROM students where username ='$user_check'";
    */

    $result = mysqli_query($db, $ses_sql);

    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        
    $login_session = $row['user_name'];

    if (!isset($_SESSION['login_user'])){
        header("Location: index.php");
        die();
    }
?>