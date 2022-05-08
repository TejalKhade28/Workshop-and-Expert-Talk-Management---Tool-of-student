<?php
include('../config.php');
session_start();

// variables
$year_name = ['FE', 'SE', 'TE', 'BE'];
$year = "";

$sem_name = ['sem1', 'sem2', 'sem3', 'sem4', 'sem5', 'sem6', 'sem7', 'sem8'];
$sem = "";

$user_check = $_SESSION['login_user'];
$faculty_name_sql = "SELECT name FROM faculty where user_name = '".$user_check."'";
$name = mysqli_query($db, $faculty_name_sql);
$name = mysqli_fetch_array($name, MYSQLI_ASSOC);
$name = $name['name'];



if (!isset($_SESSION['login_user'])){
    header("Location: ../index.php");
    die();
}


$faculty_name = $name;
$faculty_username = $user_check;

if(isset($_POST['typeofschedule'])){
    $schedule_type = mysqli_real_escape_string($db, $_POST['typeofschedule']);
    $class = "";
    $sem = "";
    if(isset($_POST['class'])){
        $flag = 0;
        foreach($_POST['class'] as $value){
            if ($flag){
                $class = $class.", ".$value;
            }
            else{
                $class = $value;
                $flag = 1;
            }           
        }
    }

    if (isset($_POST['sem'])){
        $flag = 0;
        foreach($_POST['sem'] as $value){
            if ($flag){
                $sem = $sem.", ".$value;
            }
            else{
                $sem = $value;
                $flag = 1;
            }           
        }
    }

    $date = mysqli_real_escape_string($db, $_POST['date']);
    $no_of_days = mysqli_real_escape_string($db, $_POST['no_of_days']);
    $time = mysqli_real_escape_string($db, $_POST['time']);
    $topic = mysqli_real_escape_string($db, $_POST['topic']);
    $subject = mysqli_real_escape_string($db, $_POST['subject']);

    $speaker_name = mysqli_real_escape_string($db, $_POST['speaker_name']);
    $speaker_type = mysqli_real_escape_string($db, $_POST['speaker_type']);
    $speaker_designation = mysqli_real_escape_string($db, $_POST['speaker_designation']);
    $organized_by = mysqli_real_escape_string($db, $_POST['organized_by']);
    $budget = mysqli_real_escape_string($db, $_POST['budget']);   

    // checking duplication in topic i.e schedule list
    // Query for schedule list
    $schedule_sql = "SELECT topic FROM schedule";
    $schedule_query = mysqli_query($db, $schedule_sql);
    $result = mysqli_fetch_array($schedule_query);
    //echo count($result);


    if (in_array($topic, $result)){
        // if duplication found display error message
        echo '<script> alert("Duplicate Entry Found!!!")</script>';
    } else{
        // else insert the query
        $sql = "INSERT INTO schedule (faculty_username, faculty_name, schedule_type, class, sem, `date`, no_of_days, `time`, topic, `subject`, speaker_name, speaker_type, speaker_designation, organized_by, budget) VALUES ('".$faculty_username."', '".$faculty_name."', '".$schedule_type."', '".$class."', '".$sem."', '".$date."', '".$no_of_days."', '".$time."', '".$topic."', '".$subject."', '".$speaker_name."', '".$speaker_type."', '".$speaker_designation."', '".$organized_by."', '".$budget."')";

        if (mysqli_query($db, $sql)){
            echo '<script> alert("New record created Successfully")</script>';
        }else{
            echo '<script> alert("Error")</script>';
        }
    }
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Tool - Schedule</title>
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
                          <a href="./dashboard.php" class="nav-link  d-block text-dark p-3 rounded "><i class="fa fa-tachometer pr-4"></i> <p class="h5 d-inline  font-weight-bold">Dashboard</p>
                          </a>
                        </li>
                        <!-- Schedule Menu -->
                        <li class="nav-item">
                            <a href="./schedule.php" class="d-block active text-dark p-3 m-auto"><i class="fa fa-calendar pr-4 primary-color"></i><p class="h5 d-inline pl-1 font-weight-bold primary-color">Schedule</p>
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
            <div class="col container-fluid">
                <!-- Top Navigation Bar -->               
                <div class="w-100">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="col">
                            <a class="navbar-brand" href="#"> <p class="h2 font-weight-bold">Tentative Schedule</p></a>
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
                
                <!-- Actual Main Content-->
                <div class="row mt-md-4 ml-md-2 mb-md-5 main-content pb-5 p-5" id="content">
                    <form action="" method="post" class="mt-md-4 ml-md-5" onsubmit="return confirm('Do you want to submit the form?');">
                        <!-- Tentative Schedule -->
                        <div class="form-group">
                            <div class="row h5 mb-md-3">
                                Tentative Schedule
                            </div>
                            <div class="row btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn rounded btn-light active px-md-5 mr-md-4">
                                    <input type="radio" name="typeofschedule" value="Workshop" id="option1" checked> Workshop
                                </label>
                                <label class="btn rounded btn-light px-md-5 mr-md-4"">
                                    <input type="radio" name="typeofschedule" value="Expert Talks" id="option2"> Expert Talks
                                </label>
                                <label class="btn rounded btn-light px-md-5 mr-md-4"">
                                    <input type="radio" name="typeofschedule" value="Hands-on Session" id="option3"> Hands-on Session
                                </label>
                                <label class="btn rounded btn-light px-md-5 mr-md-4"">
                                    <input type="radio" name="typeofschedule" value="FDP" id="option4"> FDP
                                </label>
                                <label class="btn rounded btn-light px-md-5 mr-md-4"">
                                    <input type="radio" name="typeofschedule" value="STTP" id="option5"> STTP
                                </label>
                            </div>
                        </div>

                        <div class="form-group row mt-md-5">

                            <div class="form-group col-md-6" >
                                <div class="row h5 mb-md-3">
                                    Years
                                </div>
                                <div class="row btn-group" id="yearButtons">
                                    <select class="selectpicker" name="class[]" multiple>
                                        <option value="F.E">F.E</option>
                                        <option value="S.E">S.E</option>
                                        <option value="T.E">T.E</option>
                                        <option value="B.E">B.E</option>
                                        <option value="Faculty">Faculty</option>
                                    </select>                    
                                </div>
                            </div>

                            <!-- Semester -->
                            <div class="form-group col-md-6">
                                <div class="row h5 mb-md-3">
                                    Semester
                                </div>
                                <div class="row" id="yearButtons">
                                    <select class="selectpicker" name="sem[]" multiple>
                                        <option value="1">&#8544</option>
                                        <option value="2">&#8545</option>
                                        <option value="3">&#8546</option>
                                        <option value="4">&#8547</option>
                                        <option value="5">&#8548</option>
                                        <option value="6">&#8549</option>
                                        <option value="7">&#8550</option>
                                        <option value="8">&#8551</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- Years -->

                        <!-- Date, no of days and Time -->
                        <div class="form-group row mt-md-5">
                            <div class="col-md-4">
                                <div class="row h5 mb-md-3">
                                    Date
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="date" name="date" class="form-control border-0" value="" >
                                </div>
                            </div>
                            <div class="col ml-md-4">
                                <div class="row h5 mb-md-3">
                                    No. of Days
                                </div>
                                <div class="form-group row ml-md-1">
                                        <select name="no_of_days" id="" class="selectpicker form-control border-0">
                                            <?php 
                                                $count = 1;
                                                while ($count < 11){
                                                    ?>
                                                    <option value="<?php echo $count; ?>"> <?php echo $count; ?> </option>                              
                                            <?php
                                                $count++;
                                                }
                                            ?>
                                        </select>
                                </div>
                            </div>
                            <div class="col ml-md-4">
                                <div class="row h5 mb-md-3">
                                    Time
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="time" name="time" class="form-control col border-0" value="" >
                                </div>
                            </div>
                        </div>

                        <!-- Topic and Subject -->
                        <div class="form-group row mt-md-5">
                            <div class="col-md-6">
                                <div class="row h5 mb-md-3">
                                    Topic
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="text" name="topic" class="form-control border-0" placeholder="Enter topic" >
                                </div>
                            </div>
                            <div class="col ml-md-5">
                                <div class="row h5 mb-md-3">
                                    Subject
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="text" name="subject"  class="form-control border-0" placeholder="Enter subject" >
                                </div>
                            </div>    
                        </div>

                        <!-- Conducted By-->
                        <div class="form-group row mt-md-5">
                            <div class="col-md-4">
                                <div class="row h5 mb-md-3">
                                    Conduction Details
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="text" name="speaker_name" class="form-control border-0" placeholder="Name of Speaker" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="row h5 mb-md-3" style="opacity:0;">
                                    by
                                </div>
                                <div class="form-group row ml-md-1">
                                    <select class="selectpicker form-control" data-container="body" data-style="" data-live-search="false" name="speaker_type" title="Nothing selected">
                                        <option value="Internal">Internal</option>
                                        <option value="External">External</option>
                                        <option value="Industry">Industry</option>
                                    </select>
                                    
                                </div>
                            </div>  
                              
                            <div class="col-md-4">
                                <div class="row h5 mb-md-3" style="opacity:0;">
                                    by
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="text" name="speaker_designation"  class="form-control border-0" placeholder="Designation" >
                                    
                                </div>
                            </div>
                        </div>

                        <!-- Organized by -->
                        <div class="form-group row mt-md-5">
                            <div class="col-md-6">
                                <div class="row h5 mb-md-3">
                                    Organized By
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="text" name="organized_by" class="form-control border-0" placeholder="Organization Name" >
                                </div>
                            </div>
                            <div class="col ml-md-5">
                                <div class="row h5 mb-md-3">
                                    Budget
                                </div>
                                <div class="form-group row ml-md-1">
                                    <input type="number" name="budget"  class="form-control border-0" placeholder="Enter amount in ₹">
                                </div>
                            </div>    
                        </div>
                        <div class="form-group row mt-md-5" style="float: right;">
                                <button class="btn btn-lg font-weight-bold align-item-right" id="searchbtn" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../js/jquery-3.5.1.slim.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap-select.min.js"></script>


    <script>
        $(document).ready(function(){
            $(".btn-group").click(function(){
                $(this).button('toggle');
            });
        });

        $(document).ready(function(){
            $("#Demo-boot .btn").click(function(){
                $(this).button('toggle');
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $("#Demo-boot .btn").click(function(){
                $(this).button('toggle');
            });
        });
    </script>

    <script>
    $(document).ready(function(){
        $("#myButtons .btn").click(function(){
            $(this).button('toggle');
        });
    });
    </script>
    
    <script>
        $(document).ready(function(){
            $("#yearButtons .btn").click(function(){
                $(this).button('toggle');
            });
        });
    </script>
</body>
</html>