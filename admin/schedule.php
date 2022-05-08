<?php
    include('../config.php');
    session_start();

    $user_check = $_SESSION['login_user'];
    $admin_name_sql = "SELECT name FROM admin WHERE user_name = '".$user_check."'";
    $name = mysqli_query($db, $admin_name_sql);
    $name = mysqli_fetch_array($name, MYSQLI_ASSOC);
    $name = $name['name'];

    $schedule_sql = "SELECT * from schedule";
    $schedule_query = mysqli_query($db, $schedule_sql);
    $schedule_nums = mysqli_num_rows($schedule_query);

    
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
                          <a href="./dashboard.php" class="nav-link  d-block text-dark p-3 rounded "><i class="fa fa-tachometer pr-4 "></i> <p class="h5 d-inline font-weight-bold">Dashboard</p>
                          </a>
                        </li>
                        <!-- Schedule Menu -->
                        <li class="nav-item">
                            <a href="#" class="d-block text-dark p-3 m-auto"><i class="fa fa-calendar primary-color pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold primary-color">Schedule</p>
                            </a>
                        </li>
                        <!-- Documents Menu -->
                        <li class="nav-item">
                            <a href="./users.php" class="active d-block text-dark p-3 m-auto"><i class="fa fa-user pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold"> Users</p>
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
                            <a class="navbar-brand" href="#"> <p class="h2 font-weight-bold">Schedules</p></a>
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
                
                <!-- Actual Main Content-->
                <div class="row mt-md-4 ml-md-2 mb-md-5 main-content text-center pb-5" id="content">

                    <!-- Collapse for users -->  
                    <div class="accordion mt-5" id="accordionExample">
                        <!-- Students -->
                        <div class="card">
                            <div class="card-header" id="headingStudent">
                                <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseStudent" aria-expanded="true" aria-controls="collapseStudent">
                                <span class="pr-3">List of Schedules </span> <span class="badge badge-primary badge-pill"><?php echo $schedule_nums;?></span>
                                </button>
                                </h2>
                             </div>
                      
                            <div id="collapseStudent" class="collapse show" aria-labelledby="headingStudent" data-parent="#accordionExample">
                            <div class="card-body table-responsive">
                                <table class="table table-striped">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Faculty Name</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Year</th>
                                            <th scope="col">Semester</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Topic</th>
                                            <th scope="col">Subject</th>
                                            <th scopr="col" colspan="2">operation</th>
                                        </tr>
                                    </thead>
                                    <tbody class="scrollable">
                                        <?php
                                            while($result = mysqli_fetch_array($schedule_query)){
                                        ?>
                                        <tr>
                                            <th scope="row"> <?php echo $result['schedule_id']; ?></th>
                                            <th scope="row"><?php echo $result['faculty_username']; ?></th>
                                            <th scope="row"><?php echo $result['faculty_name']; ?></th>
                                            <th scope="row"><?php echo $result['schedule_type']; ?></th>
                                            <th scope="row"> <?php echo $result['year']; ?></th>
                                            <th scope="row"><?php echo $result['sem']; ?></th>
                                            <th scope="row"><?php echo $result['schedule_date']; ?></th>
                                            <th scope="row"><?php echo $result['schedule_time']; ?></th>
                                            <th scope="row"><?php echo $result['topic']; ?></th>
                                            <th scope="row"><?php echo $result['subject']; ?></th>
                                            <th scope="row"><i class="fa fa-edit text-success"></i></th>
                                            <th scope="row"><i class="fa fa-trash text-danger"></i></th>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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
</body>
</html>