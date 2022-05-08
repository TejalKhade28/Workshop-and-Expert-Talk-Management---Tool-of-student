<?php
    include('../config.php');
    session_start();

    // Variables;
    $completed_event_num = 0;
    $upcoming_event_num = 0;

    if (!isset($_SESSION['login_user'])){
        header("Location: ../index.php");
        die();
    }else{
        $user_check = $_SESSION['login_user'];
        $faculty_name_sql = "SELECT name FROM faculty where user_name = '".$user_check."'";
        $name = mysqli_query($db, $faculty_name_sql);
        $name = mysqli_fetch_array($name, MYSQLI_ASSOC);
        $name = $name['name'];

        //$today = date("Y-m-d H:i:s");
        $today = date("Y-m-d");

        $today = strtotime($today);

        
        $schedule_sql = "SELECT schedule_type, `date`, `time`, topic, speaker_name, speaker_designation FROM schedule order by schedule_id desc";
        $schedule_query = mysqli_query($db, $schedule_sql);
        $schedule_nums = mysqli_num_rows($schedule_query);

        while($result = mysqli_fetch_array($schedule_query)){
            $sch_date = $result['date'];
            $sch_date = strtotime($sch_date);

            if (($sch_date) >= ($today)){
                $upcoming_event_num++;
            }else{
                $completed_event_num++;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Tool - Dashboard</title>
    <link rel="icon" href="../img/favicon.ico">
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
                          <a href="./dashboard.php" class="nav-link active d-block text-dark p-3 rounded "><i class="fa fa-tachometer pr-4 primary-color"></i> <p class="h5 d-inline primary-color font-weight-bold">Dashboard</p>
                          </a>
                        </li>
                        <!-- Schedule Menu -->
                        <li class="nav-item">
                            <a href="./schedule.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-calendar pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold">Schedule</p>
                            </a>
                        </li>
                        <!-- Documents Menu -->
                        <li class="nav-item">
                            <a href="./document.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-cloud-upload pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold">Documents</p>
                            </a>
                        </li>
                      </ul>

                </div>
                
            </div>

            <!-- Main-Content -->
            <div class="col">
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
                                        <img src="../img/profile_pic.svg" alt="" class="img-fluid rounded-circle avatar mr-2 shadow-lg p-1 bg-body rounded"><?php echo $name ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="#">Profile</a>
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
                        <div  class="h5 font-weight-bold">Upcoming Events (<span ><?php $schedule_query = mysqli_query($db, $schedule_sql); echo $upcoming_event_num; ?></span>) </div>
                        <?php
                                while($result = mysqli_fetch_array($schedule_query)){
                                    $sch_date = $result['date'];
                                    $sch_date = strtotime($sch_date);
                                    if (($sch_date) >= ($today)){
                                        if ($result['schedule_type'] == "Expert Talks"){
                                            $bg_color = "bg-success";
                                        }elseif($result['schedule_type'] == "workshop"){
                                            $bg_color = "bg-primary";
                                        }else{
                                            $bg_color = "bg-danger";  
                                        }
                                    }else{
                                        continue;
                                    }

                            ?>
                        <div class="card text-white text-center <?php echo $bg_color; ?> mb-3" style="max-width: 18rem;" id="schedule-card">
       
                            <div class="card-header"><?php echo $result['schedule_type'] ?></div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $result['topic'] ?>
                                </h5>
                                <span>by</span>
                                <p class="card-text"> 
                                    <span class="font-weight-bold">
                                        <?php echo $result['speaker_name'] ?>
                                    </span>
                                    <span class="d-block">
                                    <?php echo $result['speaker_designation'] ?>
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                <div class="px-2"><i class="fa fa-calendar-o font-weight-lighter pr-2" aria-hidden="true"></i><?php echo $result['date'] ?></div>
                                <div class="px-2"><i class="fa fa-clock-o font-weight-lighter pr-2" aria-hidden="true"></i> <?php echo $result['time'] ?></div>
                            </div>
                            <!-- 
                            <div class="card-footer">
                                <button type="button" data-toggle="modal" data-target="#register-modal" id="RegisterBtn" class="btn btn-light btn-block" ><a class="font-weight-bold text-dark">Register Now</a></button>
                            </div>
                            -->
                        </div>
                        <?php
                            }
                        ?>

                        <?php
                            // Re-initialize schedule query
                            $schedule_query = mysqli_query($db, $schedule_sql);
                        ?>
 
                    </div>
                    <!-- Completed Card -->   
                    <div class="col-md-4 mt-md-4 ">
                        <div class="h5 font-weight-bold">Completed Events (<span><?php echo $completed_event_num; ?></span>) </div>
                            <?php
                                    while($result = mysqli_fetch_array($schedule_query)){
                                        $sch_date = $result['date'];
                                        $sch_date = strtotime($sch_date);
                                        if (($sch_date) < ($today)){
                                            if ($result['schedule_type'] == "Expert Talks"){
                                                $bg_color = "bg-success";
                                            }elseif($result['schedule_type'] == "workshop"){
                                                $bg_color = "bg-primary";
                                            }else{
                                                $bg_color = "bg-danger";  
                                            }
                                        }else{
                                            continue;
                                        }

                                ?>
                            <div class="card text-white text-center <?php echo $bg_color; ?> mb-3" style="max-width: 18rem;" id="schedule-card">
            
                                <div class="card-header"><?php echo $result['schedule_type'] ?></div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $result['topic'] ?>
                                    </h5>
                                    <span>by</span>
                                    <p class="card-text"> 
                                        <span class="font-weight-bold">
                                            <?php echo $result['speaker_name'] ?>
                                        </span>
                                        <span class="d-block">
                                        <?php echo $result['speaker_designation'] ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="card-footer d-flex justify-content-center">
                                    <div class="px-2"><i class="fa fa-calendar-o font-weight-lighter pr-2" aria-hidden="true"></i><?php echo $result['date'] ?></div>
                                    <div class="px-2"><i class="fa fa-clock-o font-weight-lighter pr-2" aria-hidden="true"></i> <?php echo $result['time'] ?></div>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
  
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
</body>
</html>