<?php
    include('config.php');
    session_start();
    /*
    $text = 'rav.mal.rt18@rait.ac.in';
    echo substr_count($text, ".");
    admin : ravimallah@rait.ac.in;
    faculty : ravi.mallah@rait.ac.in;
    student : rav.mal.rt18@rait.ac.in;
    */

   $error = "";
    if ($db){
        echo '<script> console.log("Connection Successful") </script>';
    }else{
        echo '<script> console.log("No Connection") </script>';
    }
    
    if(isset($_POST['username'])){

        // $myusername = $_POST['username'];
        //$mypassword = $_POST['password'];
        $myusername = mysqli_real_escape_string($db, $_POST['username']);
        $mypassword = mysqli_real_escape_string($db, $_POST['password']);

        $dotcount = substr_count($myusername, ".");

        if ($dotcount == 3){
            // for faculty
            $sql = "select * from faculty where user_name = '".$myusername."' and passcode = '".$mypassword."' limit 1";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) == 1){
                // session_register("myusername");
                $_SESSION['login_user'] = $myusername;
                header('Location: faculty/dashboard.php');
            }else{
                $error = '<div class="text-danger text-center m-auto p-3 bg-light fa fa-exclamation-triangle">  Invalid login, please try again</div>';
            }

        } elseif($dotcount == 2){
            // for admin
            $sql = "select * from admin where user_name = '".$myusername."' and passcode = '".$mypassword."' limit 1";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) == 1){
                // session_register("myusername");
                $_SESSION['login_user'] = $myusername;
                header('Location: admin/dashboard.php');
            }else{
                $error = '<div class="text-danger text-center m-auto p-3 bg-light fa fa-exclamation-triangle">  Invalid login, please try again</div>';
            }
        }else{
            // for students
            $sql = "select * from students where user_name = '".$myusername."' and passcode = '".$mypassword."' limit 1";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) == 1){
                // session_register("myusername");
                $_SESSION['login_user'] = $myusername;
                header('Location: student/dashboard.php');
            }else{
                $error = '<div class="text-danger text-center m-auto p-3 bg-light fa fa-exclamation-triangle">  Invalid login, please try again</div>';
            }
        }

        /*
        $sql = "select * from students where user_name ='".$myusername."' and passcode='".$mypassword."' limit 1";
        */

        //$result = mysqli_query($db, $sql);


        /*
        if (mysqli_num_rows($result) == 1){
            echo '<script> console.log("You have successfully logged In") </script>';
            exit();
        }else{
            echo '<script> console.log("You have Entetred Incorrect Password")</script>';
        }
        */

        /*
        if (mysqli_num_rows($result) == 1){
            // session_register("myusername");
            $_SESSION['login_user'] = $myusername;
            header('Location: faculty/dashboard.php');
        }else{
            $error = '<div class="text-danger text-center m-auto p-3 bg-light fa fa-exclamation-triangle">  Invalid login, please try again</div>';
        }
        */
        
    }


    /*
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // username and password sent from form 
        
        // $myusername = mysqli_real_escape_string($db,$_POST['username']);
        // $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
        $myusername = (isset($_POST['username']) ? $_POST['username'] : '');
        $mypassword = (isset($_POST['password']) ? $_POST['password'] : '');

        
        $sql = "SELECT id FROM students WHERE user_name = '$myusername' and passcode = '$mypassword'";
        $result = mysqli_query($db,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $active = $row['active'];
        
        $count = mysqli_num_rows($result);
        
        // If result matched $myusername and $mypassword, table row must be 1 row
          
        if($count == 1) {
           session_register("myusername");
           $_SESSION['login_user'] = $myusername;
           
           header("location: welcome.php");
        }else {
           $error = "Your Login Name or Password is invalid";
        }
     }
     */


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Tool - Login</title>
    <link rel="icon" href="./img/favicon.ico">

    <!-- CSS-->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="./css/style1.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- leftside -->
            <div class="col-sm-6 col-md-7 " id="leftside">
                <div class="row">
                    <a href="">
                        <img src="./img/dyp_logo.png" alt="Dy Patil" class="img-fluid p-3 p-md-4">
                    </a>
                </div>
                <div class="col" id="backgroundimg">
                    <img src="./img/blue.svg" class="img-fluid p-5 .d-md-none .d-lg-block" alt="">
                </div>
            </div>
            <!-- rightside -->
            <div class="col" >
                <div class="row justify-content-center align-items-center" id="rightside">
                    <!-- Login form -->
                    <form action="" method="post" class="w-75">
                        <div class="form-group text-center mb-md-5">
                            <label for="" class="h2 font-weight-bold">Login</label>
                        </div>
                        <div class="form-group shadow-sm">
                          <input type="text" name="username" placeholder="username" class="form-control btn-lg border-0" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group shadow-sm">
                          <input type="password" name="password" placeholder="password" class="form-control btn-lg border-0" id="exampleInputPassword1">
                        </div>
                        <div class="form-group shadow-sm mt-4">
                            <button class="btn btn-lg w-100" id="btn-signin">Sign In</button>
                        </div>
                        <div class="text-center" style = "font-size:16px"><?php echo $error ?></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="./js/jquery-3.5.1.slim.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/bootstrap.bundle.min.js"></script>
</body>


</html>