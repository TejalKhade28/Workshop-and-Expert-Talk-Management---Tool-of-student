<?php

include('../config.php');
session_start();
$user_check = $_SESSION['login_user'];

if(!isset($_SESSION['login_user'])){
    header("Location: ../index.php");
    die();
}else{
    // Array for contributions
    $contribution_names = array(
        "Whether the concepts are cleared and will help in course study?",
        "Are you able to analyze the problem in this domain?",
        "Did you get knowledge of modern tools and technologies?",
        "Whether your interest is developed in this domain?",
        "Are you able to relate the content learned with societal and environmental context?",
        "Does this knowledge gained help to understand the ethical responsibilities?",
        "Does this platform helps you for personal growth or team work capabilities?",
        "Are you able to apply this knowledge for project development and management?",
        "Does this knowledge helps you for higher education or entrepreneurship?",
        "Does the knowledge gained help you build competency towards problem solving?",
        "Are you able to appreciate and use the knowledge of current technologies, skillsand tools?",
        "Will the knowledge gained help tomatch current industry requirements?"
    );
    // Array for contribution learning options
    $col_name = array(
    "Strong", 
    "Moderate", 
    "Low", 
    "N/A");

    //$contribution_options = ['Strong', 'Moderate', 'Low', 'Not Applicable'];

    // Getting student name
    $admin_name_sql = "SELECT name FROM students WHERE user_name = '".$user_check."'";
    $name = mysqli_query($db, $admin_name_sql);
    $name = mysqli_fetch_array($name, MYSQLI_ASSOC);
    $name = $name['name'];

    // Getting student id
    $student_id_sql = "SELECT student_id FROM students where user_name = '".$user_check."'";
    $student_id = mysqli_query($db, $student_id_sql);
    $student_id = mysqli_fetch_array($student_id, MYSQLI_ASSOC);
    $student_id = $student_id['student_id'];

    // fetching registered events from student_reg tables
    $schedule_id_sql = "SELECT schedule_id, feedback_done from student_reg where student_id = '".$student_id."'";
    $schedule_id_query = mysqli_query($db, $schedule_id_sql);

    $registered_event_arr = array();
    
    while ($row = mysqli_fetch_array($schedule_id_query)) {
        // if feedback already done, then don't show
        if ($row['feedback_done'] == 1){
            continue;
        }
        //$registered_event_arr[] = serialize(array($row[0]))
        array_push($registered_event_arr, $row[0]);
        //echo $row[1];
        }
    $registered_event_arr = array_unique($registered_event_arr);
    $registered_event_num = count($registered_event_arr);

    // Query for schedule list
    $schedule_sql = "SELECT schedule_id, topic FROM schedule";
    $schedule_query = mysqli_query($db, $schedule_sql);

    if(isset($_POST['feedback_submit'])){
        //echo htmlspecialchars

        $selectedSchedule = mysqli_escape_string($db, $_POST['selectedSchedule']);
        //echo $selectedSchedule;

        //$feedback4 = mysqli_real_escape_string($db, $_POST['feedback4']);
        //echo htmlspecialchars($_POST['feedback4']);
        //echo htmlspecialchars($feedback4);
        $feedback = array();
        for ($i = 1; $i <= 4; $i++){
            array_push($feedback, htmlspecialchars($_POST['feedback'.$i]));
        }
        $contribution = array();
        for ($i = 1; $i <= 12; $i++){
            array_push($contribution, htmlspecialchars($_POST['contribution'.$i]));
        }

        $sql = "INSERT INTO feedback(student_id, schedule_id, feedback1, feedback2, contribution1, contribution2, contribution3, contribution4, contribution5, contribution6, contribution7, contribution8, contribution9, contribution10, contribution11, contribution12, feedback3, feedback4) VALUES ('".$student_id."', '".$selectedSchedule."', '".$feedback[0]."', '".$feedback[1]."', '".$contribution[0]."', '".$contribution[1]."', '".$contribution[2]."', '".$contribution[3]."', '".$contribution[4]."', '".$contribution[5]."', '".$contribution[6]."', '".$contribution[7]."', '".$contribution[8]."', '".$contribution[9]."', '".$contribution[10]."', '".$contribution[11]."', '".$feedback[2]."', '".$feedback[3]."' ) ";

        if (mysqli_query($db, $sql)){ 
            $feedback_done_sql = "UPDATE student_reg SET feedback_done = 1 where schedule_id = '".$selectedSchedule."'";
            if ($db->query($feedback_done_sql) === TRUE){
                echo '<script> alert("Feedback done.")</script>';
            }
        }else{
            echo '<script> alert("Error while submitting feedback form")</script>';
        }

        //print_r($feedback);
        //print_r($contribution);

    } 
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
                          <a href="dashboard.php" class="nav-link  d-block text-dark p-3 rounded "><i class="fa fa-tachometer pr-4"></i> <p class="h5 d-inline  font-weight-bold">Dashboard</p>
                          </a>
                        </li>
                        <!-- Schedule Menu -->
                        <li class="nav-item">
                            <a href="./registered.php" class="d-block  text-dark p-3 m-auto"><i class="fa fa-calendar pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold">Registered Events</p>
                            </a>
                        </li>
                        <!-- Documents Menu -->
                        <li class="nav-item">
                            <a href="./feedback.php" class="d-block active text-dark p-3 m-auto"><i class="fa fa-commenting pr-4  primary-color"></i><p class="h5 d-inline pl-1 font-weight-bold  primary-color">Feedback</p>
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
                            <a class="navbar-brand" href="#"> <p class="h2 font-weight-bold">Feedback</p></a>
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


                <div class="row mt-md-4 ml-md-2 mb-md-5 main-content pb-5 p-5" id="content">
                    <form action="" method="post" class="mt-md-4 ml-md-5" style="width:100%" onsubmit="return confirm('Do you want to submit the form?');" enctype="multipart/form-data">
                            <!-- Select Schedule -->                                            
                            <div class="d-block">
                                <div class="row h5">Select Event</div>
                                <div class="pl-md-3 pt-md-2 row col-lg-7 col-md-7 col-sm-7">
                                    <select name="selectedSchedule" class="selectpicker form-control" data-container="body" data-style="btn-primary" data-size="10" data-live-search="true" id="selectTopic" title="Nothing selected">
                                        <?php
                                            while($result = mysqli_fetch_array($schedule_query)){
                                                if(!(in_array($result['schedule_id'], $registered_event_arr))){
                                                    continue;
                                                }
                                        ?>
                                            <option value="<?php echo $result['schedule_id']; ?>">
                                                <?php echo $result['topic']; ?>
                                            </option>

                                        <?php
                                        }
                                        ?>           
                                    </select>
                                </div>
                            </div>

                            <!-- How was your experience -->
                            <div class="form-group mt-md-5">
                                <div class="row h5 mb-md-3">
                                    How was your experience ?
                                </div>
                                <div class="row btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn rounded btn-light active px-md-5 mr-md-4">
                                        <input type="radio" name="feedback1" value="Great" id="option1" checked> Great
                                        </label>
                                        <label class="btn rounded btn-light px-md-5 mr-md-4"">
                                            <input type="radio" name="feedback1" value="Good" id="option2"> Good
                                        </label>
                                        <label class="btn rounded btn-light px-md-5 mr-md-4"">
                                            <input type="radio" name="feedback1" value="Could have been better" id="option3"> Could have been better
                                    </label>
                                </div>
                            </div>

                            <!-- was it helpful -->
                            <div class="form-group row mt-md-5">
                                <div class="form-group col-md-6" >
                                    <div class="row h5 mb-md-3">
                                    Was it helpful?
                                    </div>
                                    <div class="row btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn rounded btn-light active px-md-5 mr-md-4">
                                            <input type="radio" name="feedback2" value="Yes" id="option1" checked> Yes
                                        </label>
                                        <label class="btn rounded btn-light px-md-5 mr-md-4"">
                                            <input type="radio" name="feedback2" value="No" id="option2"> No
                                        </label>                 
                                    </div>
                                </div>
                            </div>
                            <!-- Contribution to learning -->
                            <div class="form-group row mt-md-5">
                                <div class="">
                                    <div class="pl-3 row h5 mb-md-3">
                                        Contribution to learning
                                    </div>

                                    <table class="table table-light table-borderless ">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                            <th scope="col-6"></th>
                                            <th scope="col">Strong</th>
                                            <th scope="col">Moderate</th>
                                            <th scope="col">Low</th>
                                            <th scope="col">N/A</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                            <?php
                                                for ($i = 0; $i < 12; $i++){
                                            ?>
                                                <tr class="<?php if ($i % 2 != 0){ echo "strip-color";} ?>">
                                                    <td Class="text-left" ><h6> <?php echo $contribution_names[$i] ?> </h6></td>
                                                    <?php 
                                                        for ($j = 0; $j < 4; $j++){
                                                    ?>       
                                                        <td class="" data-label="<?php echo $col_name[$j]?>">
                                                            <div class="form-check form-check-inline">
                                                                <input type="radio" class="form-check-input" name="contribution<?php echo $i+1 ?>" id=inlineRadio<?php echo $j ?> value="<?php echo $col_name[$j] ?>" required>
                                                            </div>
                                                        </td>
                                                    <?php
                                                        }
                                                    ?>
                                                </tr>
                                            <?php
                                                }
                                            ?>

                                        </tbody>
                                        </table>
                                    
                                </div>  
                            </div>

                            <!-- Feedback -->
                            <div class="form-group row mt-md-5">
                                <div class="col-md-6">
                                    <div class="row h5 mb-md-3">
                                        What aspects of this event were most useful or valuable?
                                    </div>
                                    <div class="form-group row ml-md-1">
                                        <textarea name="feedback3" class="form-control border-0" id="" cols="30" rows="3" placeholder="Please give your valueable feedback here" required></textarea>
                                    </div>
                                </div>  
                            </div>

                            <!-- Feedback -->
                            <div class="form-group row mt-md-5">
                                <div class="col-md-6">
                                    <div class="row h5 mb-md-3">
                                        Any other Suggestions?
                                    </div>
                                    <div class="form-group row ml-md-1">
                                        <textarea name="feedback4" class="form-control border-0" id="" cols="30" rows="3" placeholder="Please give your valueable suggestions here" required></textarea>
                                    </div>
                                </div>  
                            </div>

                            <div class="form-group row mt-md-5" style="float: right;">
                                    <button class="btn btn-lg font-weight-bold align-item-right" name="feedback_submit" id="searchbtn" type="submit">Submit</button>
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
    <script src="../js/file-input.js"></script>
    
    


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