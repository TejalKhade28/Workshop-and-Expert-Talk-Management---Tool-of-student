<?php
    include('../config.php');
    session_start();

    $user_check = $_SESSION['login_user'];
    $admin_name_sql = "SELECT name FROM admin WHERE user_name = '".$user_check."'";
    $name = mysqli_query($db, $admin_name_sql);
    $name = mysqli_fetch_array($name, MYSQLI_ASSOC);
    $name = $name['name'];

    if (!isset($_SESSION['login_user'])){
        header("Location: ../index.php");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Tool - Admin</title>

    <!-- CSS-->

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style1.css">
    
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar-->
            <div class="col-lg-2 col-md-3 m-0 sidebar">
                <!-- Logo -->
                <div class="row justify-content-center">
                    <a class="nav-link" href="#">
                        <img src="../img/logo.png" alt="">
                    </a>
                </div>

                <!-- Sidebar Menu -->
                <div class="row justify-content-center mt-5">
                    <ul class="nav" id="menu-list">
                        <!-- Dashboard Menu -->
                        <li class="nav-item">
                          <a href="#" class="nav-link active d-block text-dark p-3 rounded "><i class="fa fa-tachometer pr-4 primary-color"></i> <p class="h5 d-inline primary-color font-weight-bold">Dashboard</p>
                          </a>
                        </li>
                        <!-- Schedule Menu -->
                        <li class="nav-item">
                            <a href="./schedule.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-calendar pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold">Schedule</p>
                            </a>
                        </li>
                        <!-- Documents Menu -->
                        <li class="nav-item">
                            <a href="./users.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-user pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold"> Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./verification.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-file pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold">Verification</p>
                            </a>
                        </li>
                      </ul>

                </div>
                
            </div>

            <!-- Main-Content -->
            <div class="col ">
                <!-- Top Navigation Bar -->               
                <div class="w-100">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="col">
                            <a class="navbar-brand" href="#"> <p class="h2 font-weight-bold">Dashboard</p></a>
                        </div>   

                        <div class="col d-md-flex justify-content-end">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="../img/avatar.jpg" alt="" class="img-fluid rounded-circle avatar mr-2"><?php echo $name ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="#">Profile</a>
                                        <a class="dropdown-item" href="#">Account Setting</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="../logout.php">Log out</a>
                                    </div>
                                </li>
                            </ul>   
                        </div>
                    </nav>
                </div>
                
                <!-- Registration Modal -->
                <div class="modal fade" id="register-modal" role="dialog">
                    <div class="modal-dialog modal-lg" role="content">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Topic Name</h4>
                                <button type="button" data-dismiss="modal" class="close">
                                    <span id="closeBtn">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="">
                                    <div class="card card-outline-secondary">
                                        <div class="card-header">
                                            <h5 class="mb-0 text-dark">Registration</h5>
                                        </div>
                                        <div class="card-body">
                                            <form autocomplete="off" class="form" name="RegisterForm" role="form">
                                                <div class="form-group">
                                                    <label for="email">Email address</label>
                                                    <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Name of Participant</label>
                                                    <input type="text" class="form-control" name="name" placeholder="Your answer" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="roll">Roll No.</label>
                                                    <input type="text" class="form-control" name="roll" placeholder="Your answer" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="class">Class</label>
                                                    <select name="class" id="" class="custom-select">
                                                        <option value="SE">SE</option>
                                                        <option value="TE">TE</option>
                                                        <option value="BE">BE</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="division">Division</label>
                                                    <select name="division" id="" class="custom-select">
                                                        <option value="A">A</option>
                                                        <option value="B">B</option>
                                                        <option value="C">C</option>
                                                        <option value="D">D</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="contactNum">Contact Number</label>
                                                    <input type="tel" class="form-control" name="contactNum" placeholder="Your answer" required="" >
                                                </div>
                                                <button type="submit" class="btn btn-success btn-lg float-right">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>                

                <!-- Actual Main Content-->
                <div class="row mt-md-4 ml-md-2 mb-md-5 main-content text-center pb-5" id="content">
                    <!-- In Progress Card -->     
                                   
                    <div class="col-md-4 mt-4 ">
                        <div class="h5 font-weight-bold">In Progress (24) </div>
                        <div class="card text-white text-center bg-primary mb-3" style="max-width: 18rem;" id="schedule-card">
                            <div class="card-header">Workshop</div>
                            <div class="card-body">
                                <h5 class="card-title">Visual Cryptography: An Introduction</h5>
                                <span>by</span>
                                <p class="card-text"> 
                                    <span class="font-weight-bold">
                                        Dr. Deepika M P
                                    </span>
                                    <span class="d-block">
                                        Associate Professor Kerala Technological University
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="RegisterBtn" class="btn btn-light btn-block" ><a class="font-weight-bold text-dark">Register Now</a></button>
                            </div>
                        </div>

                        <div class="card text-white text-center bg-danger mb-3" style="max-width: 18rem;" id="schedule-card">
                            <div class="card-header">Hands-on-session</div>
                            <div class="card-body">
                                <h5 class="card-title">Visual Cryptography: An Introduction</h5>
                                <span>by</span>
                                <p class="card-text"> 
                                    <span class="font-weight-bold">
                                        Dr. Deepika M P
                                    </span>
                                    <span class="d-block">
                                        Associate Professor Kerala Technological University
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="$=#" class="w-100 btn btn-light font-weight-bold">Register Now</a>
                            </div>
                        </div>
                          
                    </div>
                    <!-- Completed Card -->   
                    <div class="col-md-4 mt-md-4 ">
                        <div class="h5 font-weight-bold">Completed (20) </div>

                        <div class="card text-white text-center bg-primary mb-3" style="max-width: 18rem;" id="schedule-card">
                            <div class="card-header">Workshop</div>
                            <div class="card-body">
                                <h5 class="card-title">Visual Cryptography: An Introduction</h5>
                                <span>by</span>
                                <p class="card-text"> 
                                    <span class="font-weight-bold">
                                        Dr. Deepika M P
                                    </span>
                                    <span class="d-block">
                                        Associate Professor Kerala Technological University
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="$=#" class="w-100 btn btn-light font-weight-bold"> View

                                </a>
                            </div>
                        </div>

                        

                        <div class="card text-white text-center bg-primary mb-3" style="max-width: 18rem;" id="schedule-card">
                            <div class="card-header">Workshop</div>
                            <div class="card-body">
                                <h5 class="card-title">Visual Cryptography: An Introduction</h5>
                                <span>by</span>
                                <p class="card-text"> 
                                    <span class="font-weight-bold">
                                        Dr. Deepika M P
                                    </span>
                                    <span class="d-block">
                                        Associate Professor Kerala Technological University
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="$=#" class="w-100 btn btn-light font-weight-bold"> View

                                </a>
                            </div>
                        </div>
                        
                    </div>
                    <!-- To Do card -->   
                    <div class="col-md-4 mt-md-4 ">
                        <div class="h5 font-weight-bold">To Do (6) </div>
                        <div class="card text-white text-center bg-success mb-3" style="max-width: 18rem;" id="schedule-card">
                            <div class="card-header">Expert Talk</div>
                            <div class="card-body">
                                <h5 class="card-title">Visual Cryptography: An Introduction</h5>
                                <span>by</span>
                                <p class="card-text"> 
                                    <span class="font-weight-bold">
                                        Dr. Deepika M P
                                    </span>
                                    <span class="d-block">
                                        Associate Professor Kerala Technological University
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="$=#" class="w-100 btn btn-light font-weight-bold">Register Now</a>
                            </div>
                        </div>

                        <div class="card text-white text-center bg-success mb-3" style="max-width: 18rem;" id="schedule-card">
                            <div class="card-header">Expert Talk</div>
                            <div class="card-body">
                                <h5 class="card-title">Visual Cryptography: An Introduction</h5>
                                <span>by</span>
                                <p class="card-text"> 
                                    <span class="font-weight-bold">
                                        Dr. Deepika M P
                                    </span>
                                    <span class="d-block">
                                        Associate Professor Kerala Technological University
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="$=#" class="w-100 btn btn-light font-weight-bold">Register Now</a>
                            </div>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../js/jquery-3.5.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
    <script> 
        $(document).ready(function(){
            $('#RegisterBtn').click(function(){
                $('#register-modal').modal('show');
            });
        });
    </script>
</body>
</html>