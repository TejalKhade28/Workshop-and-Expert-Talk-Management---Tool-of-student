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
        $faculty_name_sql = "SELECT name FROM students where user_name = '".$user_check."'";
        $student_id_sql= "SELECT student_id from students where user_name = '".$user_check."'";
        $student_id = mysqli_query($db, $student_id_sql);
        $student_id = mysqli_fetch_array($student_id, MYSQLI_ASSOC);
        $student_id = $student_id['student_id'];
        $name = mysqli_query($db, $faculty_name_sql);
        $name = mysqli_fetch_array($name, MYSQLI_ASSOC);
        $name = $name['name'];

        //$today = date("Y-m-d H:i:s");
        $today = date("Y-m-d");

        $today = strtotime($today);

        
        $schedule_sql = "SELECT schedule_id, schedule_type, `date`, `time`, topic, speaker_name, speaker_designation FROM schedule";
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

    }

    if (isset($_POST['schedule_id'])){
        $schedule_id = mysqli_real_escape_string($db, $_POST['schedule_id']);

        // checking for duplication registration
        // if schedule_id already present it means registration already done
        $sql = "SELECT schedule_id FROM student_reg";
        $sql_query = mysqli_query($db, $sql);
        //$sql_result = mysqli_fetch_array($sql_query);
        $flag = False;
        while ($sql_result = mysqli_fetch_array($sql_query)){
            if ($schedule_id == $sql_result[0]){
                $flag = True;
                break;
            }
        }
        if ($flag){
            echo '<script> alert("Registration already done!!!") </script>';
            
        } else {
            $sql = "INSERT INTO student_reg(student_id, schedule_id) VALUES ('".$student_id."', '".$schedule_id."')";

            if (mysqli_query($db, $sql)){
                echo '<script> alert("Registration successful!!!") </script>';
            } else {
                echo '<script> alert("Error")</script>';
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
    <link rel="stylesheet" href="../css/bootstrap-select.css">
    
    
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
                          <a href="./dashboard.php" class="nav-link d-block text-dark p-3 rounded "><i class="fa fa-tachometer pr-4 "></i> <p class="h5 d-inline font-weight-bold">Dashboard</p>
                          </a>
                        </li>
                        <!-- Schedule Menu -->
                        <li class="nav-item">
                            <a href="./registered.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-calendar pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold">Registered Events</p>
                            </a>
                        </li>
                        <!-- Documents Menu -->
                        <li class="nav-item">
                            <a href="./feedback.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-commenting pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold">Feedback</p>
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
                            <a class="navbar-brand" href="#"> <p class="h2 font-weight-bold">Profile</p></a>
                        </div>   
                        <!--             
                        <div class="col-6 d-flex justify-content-center">
                            <form class="form-inline my-2 my-lg-0">
                                <input class="form-control" id="searchbar" type="search" placeholder="Type into search" aria-label="Search">
                                <button class="btn my-2 my-sm-0" type="submit" id="searchbtn">Search</button>
                            </form>
                        </div>
                        --> 
                        <div class="col d-md-flex justify-content-end">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="../img/profile_pic.svg" alt="" class="img-fluid rounded-circle avatar mr-2 shadow-lg p-1 bg-body rounded"><?php echo $name ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="./profile.php">Profile</a>
                                        <!-- 
                                        <div class="dropdown-divider"></div>
                                        -->
                                        <a class="dropdown-item" href="../logout.php">Log out</a>
                                    </div>
                                </li>
                            </ul>   
                        </div>
                    </nav>
                </div>          
                
                <!-- Actual Main Content-->
                <div class="row mt-md-4 ml-md-2 mb-md-5 main-content pb-5 p-5" id="content">
                    <form action="" method="post" class="mt-md-4 ml-md-5" onsubmit="return confirm('Do you want to submit the form?');">
  



                        <!-- Topic and Subject -->
                        <div class="form-group row mt-md-5">
                            <div class="col ">
                                <div class="row h5 mb-md-3">
                                    Roll No
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="text" name="topic" class="form-control border-0" placeholder="E.g 18ET7051" required>
                                </div>
                            </div>
                            <div class="col-md-5 ml-md-5">
                                <div class="row h5 mb-md-3">
                                    Department
                                </div>
                                <div class="form-group row ml-md-1">
                                    <select name="department" class="custom-select border-0" >
                                        <option value="Computer Engineering">Computer Engineering</option>
                                        <option value="Electronics Engineering">Electronics Engineering</option>
                                        <option value="Instrumentation Engineering">Instrumentation Engineering</option>
                                        <option value="Information Technology Engineering">Information Technology Engineering</option>
                                        <option value="Electronics & Telecommunication Engineering">Electronics & Telecommunication Engineering</option>
                                    </select>
                                </div>
                            </div>    
                            <div class="col-md-3 ml-md-5">
                                <div class="row h5 mb-md-3">
                                    Year
                                </div>
                                <div class="form-group row ml-md-1">
                                    <select name="year" class="custom-select form-control border-0" data-container="body" data-style="" data-live-search="false" id="selectTopic">
                                        <option value="F.E">F.E</option>
                                        <option value="S.E">S.E</option>
                                        <option value="T.E">T.E</option>
                                        <option value="B.E">B.E</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Conducted By-->
                        <div class="form-group row mt-md-5">
                            <div class="col-md-4">
                                <div class="row h5 mb-md-3">
                                    Name
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="text" name="first_name" class="form-control border-0" placeholder="First Name" required>
                                </div>
                            </div>


                              
                            <div class="col-md-4">
                                <div class="row h5 mb-md-3" style="opacity:0;">
                                    by
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="text" name="middle_name"  class="form-control border-0" placeholder="Middle Name" required>
                                    
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="row h5 mb-md-3" style="opacity:0;">
                                    by
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="text" name="last_name"  class="form-control border-0" placeholder="Last Name" required>
                                    
                                </div>
                            </div>
                        </div>

                        <!-- Organized by -->
                        <div class="form-group row mt-md-5">
                            <div class="col-md-6">
                                <div class="row h5 mb-md-3">
                                    Email Address
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="email" name="email_id" class="form-control border-0" placeholder="Enter your email address" required>
                                </div>
                            </div>
                            <div class="col ml-md-5">
                                <div class="row h5 mb-md-3">
                                    Phone
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="tel" name="phone"  class="form-control border-0" placeholder="Enter your phone no">
                                </div>
                            </div>    
                        </div>

                        <!-- Organized by -->
                        <div class="form-group row mt-md-5">
                            <div class="col-7">
                                <div class="row h5 mb-md-3">
                                    Address
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="email" name="address" class="form-control border-0" placeholder="Enter your address" required>
                                </div>
                            </div>
   
                        </div>
                        <div class="form-group row mt-md-5" style="float: right;">
                                <button class="btn btn-lg font-weight-bold align-item-right" id="searchbtn" type="submit">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../js/jquery-3.5.1.slim.min.js"></script>
    <!-- 
    <script src="../js/bootstrap.min.js"></script>
    -->
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap-select.min.js"></script>
</body>
</html>