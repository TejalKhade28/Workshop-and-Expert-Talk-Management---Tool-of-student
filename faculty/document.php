<?php
include('../config.php');
session_start();
$user_check = $_SESSION['login_user'];

if(!isset($_SESSION['login_user'])){
    header("Location: ../index.php");
    die();
}
// Getting faculty name
$faculty_name_sql = "SELECT name FROM faculty where user_name = '".$user_check."'";
$name = mysqli_query($db, $faculty_name_sql);
$name = mysqli_fetch_array($name, MYSQLI_ASSOC);
$name = $name['name'];

// Getting faculty id
$faculty_id_sql = "SELECT faculty_id FROM faculty where user_name = '".$user_check."'";
$faculty_id = mysqli_query($db, $faculty_id_sql);
$faculty_id = mysqli_fetch_array($faculty_id, MYSQLI_ASSOC);
$faculty_id = $faculty_id['faculty_id'];


// Query for schedule list
$schedule_sql = "SELECT schedule_id, schedule_type, class, sem, `date`, no_of_days, `time`, topic, subject, speaker_name, speaker_type, speaker_designation, organized_by FROM schedule";
$schedule_query = mysqli_query($db, $schedule_sql);

// Query for upload_done
$upload_done_sql = "SELECT upload_done from schedule";
$upload_done_query = mysqli_query($db, $upload_done_sql);

// gap sheet submission check
$gap_submit = 0;

$gap_schedule_id = isset($_POST['gap_schedule_id']) ? $_POST['gap_schedule_id'] : null;
$gap_schedule_topic = isset($_POST['gap_schedule_topic']) ? $_POST['gap_schedule_topic'] : null;




if (isset($_POST['gap_form_submit'])){
    echo '<script> alert("Now upload all required documents.") </script>';

    $event_type = isset($_POST['event_type']) ? $_POST['event_type'] : null;
    $controlling_subject = isset($_POST['controlling_subject']) ? $_POST['controlling_subject'] : null;
    $gap_identified = isset($_POST['gap_identified']) ? $_POST['gap_identified'] : null;
    $resource_person = isset($_POST['resource_person']) ? $_POST['resource_person'] : null;
    $designation = isset($_POST['designation']) ? $_POST['designation'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    $duration = isset($_POST['duration']) ? $_POST['duration'] : null;
    $organised_by = isset($_POST['organised_by']) ? $_POST['organised_by'] : null;
    $internal_participants = isset($_POST['internal_participants']) ? $_POST['internal_participants'] : null;
    $total_students = isset($_POST['total_students']) ? $_POST['total_students'] : null;
    $per_student = isset($_POST['per_student']) ? $_POST['per_student'] : null;
    $external_participants = isset($_POST['external_participants']) ? $_POST['external_participants'] : null;
    $relevance_pos = isset($_POST['relevance_pos']) ? $_POST['relevance_pos'] : null;
    $relevance_psos = isset($_POST['relevance_psos']) ? $_POST['relevance_psos'] : null;

    $sql = "INSERT INTO gap_sheet(schedule_id, event_type, controlling_subject, gap_identified, resource_person, designation, `date`, duration, organised_by, internal_participants, total_students, per_student, external_participants, relevance_pos, relevance_psos) VALUES ('".$gap_schedule_id."', '".$event_type."', '".$controlling_subject."', '".$gap_identified."', '".$resource_person."', '".$designation."', '".$date."', '".$duration."', '".$organised_by."', '".$internal_participants."', '".$total_students."', '".$per_student."', '".$external_participants."', '".$relevance_pos."', '".$relevance_psos."')";
    

    if (mysqli_query($db, $sql)){
        echo '<script> alert("GAP Sheet submittted.")</script>';
    }else{
        echo '<script> alert("Error while submitting gap sheet")</script>';
    }
    $gap_submit = 1;
}

if(isset($_POST['upload'])){
    $upload = mysqli_escape_string($db, $_POST['upload']);
    $gap_submit = (int)$upload;
    // now selectedSchedule contain more than 2 values; need to change
    $selectedSchedule_post = mysqli_escape_string($db, $_POST['selectedSchedule']);
    list($schedule_id, $selectedSchedule) = explode('|', $selectedSchedule_post);
    //echo $selectedSchedule;

    $current_dir = getCwd();
    chdir ("../uploads");
    $current_dir = getcwd();

    if (!is_dir("$schedule_id")){
        mkdir($schedule_id, 0755, true);
        echo '<script> console.log("Directory successfully created.") </script>';
    }


    $targetDir = $schedule_id."/";
    
    // file type 
    $allowTypes = array('pdf'); 

    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
    $fileNames = array_filter($_FILES['files']['name']); 
    


    if(count($fileNames) == 7){
        if ($gap_submit == 1){
            if(!empty($fileNames)){ 
                foreach($_FILES['files']['name'] as $key=>$val){ 
                    // File upload path 
                    $fileName = basename($_FILES['files']['name'][$key]); 
                    $targetFilePath = $targetDir . $fileName; 

                    // checking whether directory exists or not
                    if (file_exists($targetFilePath)){
                        $statusMsg = "Directory already exists!!!";
                        break;
                    }
                    
                    // Check whether file type is valid 
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                    if(in_array($fileType, $allowTypes)){ 
                        // Upload file to server 
                        if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
                            // pdf db insert sql 
                            $insertValuesSQL .= "('".$fileName."', NOW()),"; 
                        }else{ 
                            $errorUpload .= $_FILES['files']['name'][$key].' | '; 
                        } 
                    }else{ 
                        $errorUploadType .= $_FILES['files']['name'][$key].' | '; 
                        echo '<script> alert("Only pdf files are allowed") </script>';
                    } 
                } 
                
                if(!empty($insertValuesSQL)){ 
                    $insertValuesSQL = trim($insertValuesSQL, ','); 
                    // Insert image file name into database 
                    $insert = $db->query("INSERT INTO uploads(`faculty_id`,`faculty_username`,`faculty_name`,`schedule_id`,`topic`,`attendance`,`feedback`,`hod`,`principal`,`gap`,`photo`,`budget`,`relevance`) VALUES ('$faculty_id', '$user_check', '$name', '$schedule_id', '$selectedSchedule', '$fileNames[0]','$fileNames[1]','$fileNames[2]','$fileNames[3]', '$gap_submit', '$fileNames[4]', '$fileNames[5]','$fileNames[6]')"); 

                    // Insert schedule_id and gap from data in gap_sheet
                    // but for now we will insert only schedule_id



                    //$sql = "INSERT INTO gap_sheet(schedule_id) VALUES ('".$schedule_id."')";
   


                    if($insert){ 
                        $errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
                        $errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
                        $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
                        $statusMsg = "Files are uploaded successfully.".$errorMsg; 
                        // if successfully uploaded then, flag upload_done in schedule 
                        $upload_done_sql = "UPDATE schedule SET upload_done = 1 where schedule_id = '".$schedule_id."'";

                        if ($db->query($upload_done_sql) === TRUE) {
                            //$statusMsg = "Upload_done flag set successfully";
                            $gap_submit = 0;
                        } else {
                            $statusMsg = "Error updating record: " . $db->error;
                        }
                    }else{ 
                        $statusMsg = "Sorry, there was an error uploading your file.";
                        // if error occur then delete the created directory 
                    } 
                } 
            }else{ 
                $statusMsg = 'Please select a file to upload.'; 
            } 
        }else{
            $statusMsg = 'Please first fill the gap sheet.';
        }

    }
    else{            
        echo '<script> alert("You need to upload all files") </script>';
    }
    // Display status message 
    if ($statusMsg != ""){
        echo '<script> alert("'.$statusMsg.'")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Tool - Document</title>
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style1.css">
    <link rel="stylesheet" href="../css/bootstrap-select.css">
    <script src="../js/jquery-3.5.1.min.js"></script>
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
                          <a href="./dashboard.php" class="nav-link active d-block text-dark p-3 rounded "><i class="fa fa-tachometer pr-4"></i> <p class="h5 d-inline font-weight-bold">Dashboard</p>
                          </a>
                        </li>
                        <!-- Schedule Menu -->
                        <li class="nav-item">
                            <a href="./schedule.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-calendar pr-4"></i><p class="h5 d-inline pl-1 font-weight-bold">Schedule</p>
                            </a>
                        </li>
                        <!-- Documents Menu -->
                        <li class="nav-item">
                            <a href="./document.php" class="d-block text-dark p-3 m-auto"><i class="fa fa-cloud-upload pr-4 primary-color"></i><p class="h5 d-inline pl-1 primary-color font-weight-bold">Documents</p>
                            </a>
                        </li>
                      </ul>

                </div>
                
            </div>

            <!-- Main-Content -->
            <div class="col container-fluid"">
                <!-- Top Navigation Bar -->               
                <div class="w-100">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="col">
                            <a class="navbar-brand" href="#"> <p class="h2 font-weight-bold">Documents</p></a>
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
                                <h5 class="modal-title">Gap indentification Sheet</h5>
                                <button type="button" data-dismiss="modal" class="close">
                                    <span id="closeBtn">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- 
                                <form action="" method="post" onsubmit="return confirm('Do you want to submit the form?');">
                                -->
                                    <div class="card card-outline-secondary">
                                        <div class="card-body">
                                            <form id="gap_submit" autocomplete="off" action="../faculty/document.php" method="post" onsubmit="return confirm('Do you want to submit the form?');" class="form" name="RegisterForm" role="form">
                                                <div class="form-group d-none">
                                                    <label for="schedule_id" >Schedule Id</label>
                                                    <input type="text" id="gap_schedule_id" class="form-control" name="gap_schedule_id" placeholder="schedule id" required="">
                                                </div>
                                                
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="font-weight-bold" for="gap1">Event Type</label>
                                                        <input type="text" class="form-control" id="gap1" name="event_type">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="font-weight-bold" for="gap2">Controlling Subject</label>
                                                        <input type="text" class="form-control" id="gap2" name="controlling_subject">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label class="font-weight-bold" for="gap_identified">Gap Identified</label>
                                                        <select name="gap_identified" class="custom-select"  id="gap_identified">
                                                            <option value="Good">Good</option>
                                                            <option value="Average">Average</option>
                                                            <option value="Poor">Poor</option>
                                                        </select>
                                                        <!-- 
                                                        <input type="text" class="form-control" name="gap_identified" >
                                                        -->
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="font-weight-bold" for="gap4">Resource Person</label>
                                                        <input type="text" class="form-control" id="gap4" name="resource_person">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="font-weight-bold" for="gap5">Designation</label>
                                                        <input type="text" class="form-control" id="gap5" name="designation">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                        <label class="font-weight-bold"  for="gap6">Date</label>
                                                        <input type="text" class="form-control" id="gap6" name="date" >
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="font-weight-bold" for="gap7">Duration</label>
                                                        <input type="text" class="form-control" id="gap7" name="duration">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="font-weight-bold" for="gap8">Organised By</label>
                                                        <input type="text" class="form-control" id="gap8" name="organised_by">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-5">
                                                        <label class="font-weight-bold" for="gap3">Internal Participants</label>
                                                        <input type="text" class="form-control" name="internal_participants" id="gap3">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="font-weight-bold" for="total_students">Total No. of Students</label>
                                                        <input type="text" class="form-control" name="total_students" id="total_students">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="font-weight-bold" for="per_student">% of Student</label>
                                                        <input type="text" class="form-control" name="per_student" id="per_student">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label class="font-weight-bold" for="external_participants">No. of External Participants</label>
                                                        <input type="text" class="form-control" name="external_participants" id="external_participants">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="font-weight-bold" for="relevance_pos">Relevance POs</label>
                                                        <input type="text" class="form-control" name="relevance_pos" id="relevance_pos">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="font-weight-bold" for="relevance_psos">Relevance PSOs</label>
                                                        <input type="text" class="form-control" name="relevance_psos" id="relevance_psos">
                                                    </div>
                                                </div>
                                                <!-- 
                                                <div class="form-group">
                                                    <label for="email">Topic</label>
                                                    <input type="text" id="gap_schedule_topic" class="form-control" name="gap_schedule_topic" placeholder="Your Email" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Name of Participant</label>
                                                    <input type="text" class="form-control" name="participant_name" value="ravi" placeholder="Your answer" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="roll">Roll No.</label>
                                                    <input type="text" class="form-control" id="roll" name="roll" placeholder="Your answer" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="year">Class</label>
                                                    <select name="year" class="custom-select">
                                                        <option value="SE">SE</option>
                                                        <option value="TE">TE</option>
                                                        <option value="BE">BE</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="division">Division</label>
                                                    <select name="division" class="custom-select">
                                                        <option value="A">A</option>
                                                        <option value="B">B</option>
                                                        <option value="C">C</option>
                                                        <option value="D">D</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="contactNum">Contact Number</label>
                                                    <input type="tel" class="form-control" name="contactNum" placeholder="Your answer" value="922" required="" >
                                                </div>
                                                -->
                                                <button type="submit" name="gap_form_submit" class="btn btn-success btn-lg float-right">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                <!-- 
                                </form>
                                -->
                            </div>
                        </div>
                    </div>
                </div> 

                <!-- Actual Main Content-->
                <div class="row mt-md-4 ml-md-2 mb-md-5 main-content pb-5 p-5" id="content">
                    <form method="post" action=""  enctype="multipart/form-data">
                        <!-- Select Schedule -->                    
                        <div class="d-block p-5">
                            <div class="row h5">Select Schedule</div>
                            <div class="pl-md-3 pt-md-2 row col-lg-5 col-md-7 col-sm-7">
                                <select required onchange="schedule_details(this.value)" name="selectedSchedule" class="selectpicker form-control" data-container="body" data-style="btn-primary" data-live-search="true" id="selectTopic" title="Nothing selected">
                                    <?php
                                        // showing non uploaded documents schedules;
                                        while (($result = mysqli_fetch_array($schedule_query)) && ($upload_result = mysqli_fetch_array($upload_done_query))){
                                            if (!(is_null($upload_result[0]))){
                                                continue;
                                            }
                                    ?>
                                        <option <?php if($result['schedule_id'] == (int)$gap_schedule_id) { echo "selected";} ?> id="schedule_<?php echo $result['schedule_id'];?>" name="selected_schedule_topic" value="<?php echo $result['schedule_id'];?>|<?php echo $result['topic'];?>|<?php echo $result['schedule_type'];?>|<?php echo $result['subject'];?>|<?php echo $result['class'];?>|<?php echo $result['speaker_name'];?>|<?php echo $result['speaker_designation'];?>|<?php echo $result['date'];?>|<?php echo $result['no_of_days'];?>|<?php echo $result['organized_by'];?>">
                                            <?php echo $result['topic']; ?>
                                        </option>

                                    <?php
                                    }
                                    ?>           
                                </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="container-fluid row">
                                <div class="col-xl-4 pl-5">
                                    <div class="row h5 ">Attendence Sheet</div>
                                    <div class="">
                                        <input type="file" name="files[]" id="fileUpload1">
                                        <label for="fileUpload1" class="row ml-1 p-3 file-label rounded">
                                            <span class="font-weight-bold">Drop your file here</span>
                                            <span class="ml-1">or</span>
                                            <span class="text-danger ml-1 font-weight-bold">Browse</span>
                                            <span class="">Max. File Size : 5MB</span>
                                        </label>
                                        <div id="file-upload-filename1" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-xl-4  pl-5">
                                    <div class="row h5 ">Feedback Response</div>
                                    <div class="">
                                        <input name="files[]" type="file" id="fileUpload2">
                                        <label for="fileUpload2" class="row ml-1 p-3 file-label rounded">
                                            <span class="font-weight-bold">Drop your file here</span>
                                            <span class="ml-1">or</span>
                                            <span class="text-danger ml-1 font-weight-bold">Browse</span>
                                            <span class="">Max. File Size : 5MB</span>
                                        </label>
                                        <div id="file-upload-filename2" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-xl-4 pl-5">
                                    <div class="row h5 ">Permission Letter(HOD)</div>
                                    <div>
                                        <input type="file" name="files[]" id="fileUpload3">
                                        <label for="fileUpload3" class="row ml-1 p-3 file-label rounded">
                                            <span class="font-weight-bold">Drop your file here</span>
                                            <span class="ml-1">or</span>
                                            <span class="text-danger ml-1 font-weight-bold">Browse</span>
                                            <span class="">Max. File Size : 5MB</span>
                                        </label>
                                        <div id="file-upload-filename3" class="text-danger"></div>
                                    </div>
                                </div>
        
                            </div>
                            <div class="container-fluid row mt-5">
                                <div class="col-xl-4  pl-5">
                                    <div class="row h5 ">Permission Letter(Principal Sir)</div>
                                    <div >
                                        <input type="file" name="files[]" id="fileUpload4">
                                        <label for="fileUpload4" class="row ml-1 p-3 file-label rounded">
                                            <span class="font-weight-bold">Drop your file here</span>
                                            <span class="ml-1">or</span>
                                            <span class="text-danger ml-1 font-weight-bold">Browse</span>
                                            <span class="">Max. File Size : 5MB</span>
                                        </label>
                                        <div id="file-upload-filename4" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-xl-4  pl-5">
                                    <div class="row h5 ">Gap indentification Sheet</div>
                                    <div>          
                                        <label class="row ml-1 p-3 file-label rounded">
                                            <button type="button" id="RegisterBtn" data-id="hello_ravi" class="pass_schedule_id btn btn-light btn-block" ><a class="font-weight-bold text-dark"><span class="font-weight-bold text-danger">Click to fill the form</span></a></button>
                                            
            
                                        </label>

                                        <div id="file-upload-filename5d" class="text-danger"> <?php if($gap_submit == 1) { echo "  Successfully done.";}?></div>

                                    </div>
                                </div>
                                <div class="col-xl-4  pl-5">
                                    <div class="row h5 ">College Photo</div>
                                    <div class="form-group">
                                        <input type="file" name="files[]" id="fileUpload5">
                                        <label for="fileUpload5" class="row ml-1 p-3 file-label rounded">
                                            <span class="font-weight-bold">Drop your file here</span>
                                            <span class="ml-1">or</span>
                                            <span class="text-danger ml-1 font-weight-bold">Browse</span>
                                            <span class="">Max. File Size : 5MB</span>
                                        </label>
                                        <div id="file-upload-filename5" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid row mt-5">
                                <div class="col-xl-4  pl-5">
                                    <div class="row h5 ">Budget(Bill+Summary)</div>
                                    <div>
                                        <input type="file" name="files[]" id="fileUpload6">
                                        <label for="fileUpload6" class="row ml-1 p-3 file-label rounded">
                                            <span class="font-weight-bold">Drop your file here</span>
                                            <span class="ml-1">or</span>
                                            <span class="text-danger ml-1 font-weight-bold">Browse</span>
                                            <span class="">Max. File Size : 5MB</span>
                                        </label>
                                        <div id="file-upload-filename6" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-xl-4 pl-5">
                                    <div class="row h5 ">Workshop Relevance</div>
                                    <div>
                                        <input type="file" name="files[]" id="fileUpload7">
                                        <label for="fileUpload7" class="row ml-1 p-3 file-label rounded">
                                            <span class="font-weight-bold">Drop your file here</span>
                                            <span class="ml-1">or</span>
                                            <span class="text-danger ml-1 font-weight-bold">Browse</span>
                                            <span class="">Max. File Size : 5MB</span>
                                        </label>
                                        <div id="file-upload-filename7" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="pl-5" id="document-submit">
                                    <div class="pl-xl-5 pl-2 mb-3">
                                        <button type="submit" value="<?php if($gap_submit == 1) { echo 1;} else { echo 0;}?>" name="upload" class="btn btn-lg bg-main text-white font-weight-bold" >Upload</button>
                                    </div>
                                </div>    
                            </div>

                            
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap-select.min.js"></script>
    <script src="../js/file-input.js"></script>
    <script src="../js/scripts.js"></script>


</body>
</html>