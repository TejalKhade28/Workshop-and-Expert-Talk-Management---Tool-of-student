<?php
include('../config.php');
session_start();

// Variables;
$registered_event_num = 0;
$completed_event_num = 0;
$upcoming_event_num = 0;

if (!isset($_SESSION['login_user'])){
    header("Location: ../index.php");
    die();
}else{
    $user_check = $_SESSION['login_user'];

    // Getting student name
    $student_name = "SELECT name FROM students WHERE user_name = '".$user_check."'";
    $name = mysqli_query($db, $student_name);
    $name = mysqli_fetch_array($name, MYSQLI_ASSOC);
    $name = $name['name'];

    // Getting student_id
    $student_id_sql= "SELECT student_id from students where user_name = '".$user_check."'";
    $student_id = mysqli_query($db, $student_id_sql);
    $student_id = mysqli_fetch_array($student_id, MYSQLI_ASSOC);
    $student_id = $student_id['student_id'];

    //$today = date("Y-m-d H:i:s");
    $today = date("Y-m-d");

    $today = strtotime($today);

    // fetching registered events from student_reg tables
    $schedule_id_sql = "SELECT schedule_id, feedback_done from student_reg where student_id = '".$student_id."'";
    $schedule_id_query = mysqli_query($db, $schedule_id_sql);
    //$schedule_id = mysqli_fetch_array($schedule_id_query, MYSQLI_ASSOC);
    $registered_event_arr = array();
    $feedback_done = array();
    while ($row = mysqli_fetch_array($schedule_id_query)) {
        //$registered_event_arr[] = serialize(array($row[0]));
        if ($row['feedback_done'] == 1){
            array_push($feedback_done, 1);
        } else{
            array_push($feedback_done, 0);
        }
        array_push($registered_event_arr, $row[0]);
    }
    $registered_event_arr = array_unique($registered_event_arr);
    $registered_event_num = count($registered_event_arr);

    mysqli_free_result($schedule_id_query);
    
    $schedule_sql = "SELECT schedule_id, schedule_type, `date`, `time`, topic, speaker_name, speaker_designation FROM schedule";
    
    $schedule_query = mysqli_query($db, $schedule_sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Tool - Registered Events</title>

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
                          <a href="./dashboard.php" class="nav-link active d-block text-dark p-3 rounded "><i class="fa fa-tachometer pr-4 "></i> <p class="h5 d-inline  font-weight-bold">Dashboard</p>
                          </a>
                        </li>
                        <!-- Registered Events -->
                        <li class="nav-item">
                            <a href="./registered.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-calendar primary-color pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold primary-color">Registered Events</p>
                            </a>
                        </li>
                        <!-- Documents Menu -->
                        <li class="nav-item">
                            <a href="../student/feedback.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-commenting pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold">Feedback</p>
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
                            <a class="navbar-brand" href="#"> <p class="h2 font-weight-bold">Registered Events</p></a>
                        </div>   
                        <div class="col d-md-flex justify-content-end">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="../img/profile_pic.svg" alt="" class="img-fluid rounded-circle avatar mr-2 shadow-lg p-1 bg-body rounded"><?php echo $name ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="./profile.php">Profile</a>
                                        
                                        <a class="dropdown-item" href="../logout.php">Log out</a>
                                    </div>
                                </li>
                            </ul>   
                        </div>
                    </nav>
                </div>
                
                <!-- Actual Main Content-->
                <div class="mt-md-4 ml-md-2 mb-md-5 main-content text-center pb-5" id="content"> 
                    <div  class="h5 mt-5 mb-0  font-weight-bold">Registered Events (<span ><?php echo $registered_event_num; ?></span>) </div>        
                    <div class="row">
                        <?php
                            if ($registered_event_num != 0) {
                                //echo "Hi";
                                while($result = mysqli_fetch_array($schedule_query)){
                                    //echo $result['topic'];
                                    if(!(in_array($result['schedule_id'], $registered_event_arr))){
                                        continue;
                                    }
                                    $feedback_done_key = array_search($result['schedule_id'], $registered_event_arr);
                                    $feedback_done_key = $feedback_done[$feedback_done_key];
                                    $sch_date = $result['date'];
                                    $sch_date = strtotime($sch_date);
                                    if ($result['schedule_type'] == "Expert Talks"){
                                        $bg_color = "bg-success";
                                    }elseif($result['schedule_type'] == "workshop"){
                                        $bg_color = "bg-primary";
                                    }else{
                                        $bg_color = "bg-danger";  
                                    }
                            ?>
                            <div class="col">
                                <div class="card text-white m-md-4 text-center <?php echo $bg_color; ?> mb-3" style="max-width: 18rem;" id="schedule-card">
            
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

                                    <div class="card-footer">
                                        <button type="button" id="feedbackBtn" class="btn btn-light btn-block" <?php if($feedback_done_key == 1) { echo "disabled";} ?>><a href="./feedback.php" class="font-weight-bold text-dark" >Feedback</a></button>
                                    </div>  
                                </div>
                            </div>
                        <?php
                            }
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