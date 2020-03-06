
<?php
// echo dirname(__DIR__,1).'/server/functions.php';
if (file_exists(dirname(__DIR__, 1) . '/server/functions.php')) {
    include_once dirname(__DIR__, 1) . '/server/functions.php';
}

function applicationDetail()
{

    if (!empty($_GET['a_id'])) {
        $app_id = base64_decode($_GET['a_id']);

        //function to get the student and the course detail...
        $data = getApplicationDetail($app_id);
        // echo "<pre>";
        // print_r($data);

        ?>

        <span>
        <label>Status</label>
        <select id="app_status" name="app_status" class="form-group">
        <option <?php if ($data[0]->app_status == '0') {
            echo 'selected';
        }
        ?> value='0' disabled>Pending</option>
        <option <?php if ($data[0]->app_status == '1') {
            echo 'selected';
        }
        ?> value='1'>Approve</option>
        <option <?php if ($data[0]->app_status == '2') {
            echo 'selected';
        }
        ?> value='2'>Decline</option>
        </select><br>
        </span>

        <style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

/* Button used to open the chat form - fixed at the bottom of the page */
.open-button {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
  width: 280px;
}

/* The popup chat - hidden by default */
.chat-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
}

/* Full-width textarea */
.form-container textarea {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 30px;
}

/* When the textarea gets focus, do something */
.form-container textarea:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/send button */
.form-container .btn {
  background-color: #4CAF50;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
#chatContainer{
    height: 80%;
    overflow: auto;
}

.image-upload > input
{
    display: none;
}

.image-upload img
{
    width: 80px;
    cursor: pointer;
}
</style>

        <div style="width: 550px; height: 1300px;border: solid 1px #ccc; display: inline-block;">
        <h3>Student Detail</h3>

        <button class="open-button" onclick="openForm()">Chat</button>

        <div class="chat-popup" id="chatContainer">

    <form class="form-container" id="chatForm">

        <h3 id="user_name"></h3>

        <span id='previous_messages'>
        </span><br>

        <input type="hidden" id="chat_user" name="receiver_id" value=<?=$data[0]->student_id?>>

    <label for="msg"><b>Message</b></label>
    <textarea placeholder="Type message.." name="message" required></textarea>

    <div class="image-upload">
    <label for="file_input">
        <img src="https://goo.gl/pB9rpQ"/>
    </label>

    <input id="file_input" name="file_input[]" type="file" multiple/>Attach File
    </div>

    <button type="submit" class="btn">Send</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
    </form>
    </div>


        <label>Name:</label>&nbsp;&nbsp;<span id="stu_name"><?=$data[0]->f_name . " " . $data[0]->l_name?></span><br>
        <label>Email:</label>&nbsp;&nbsp;<?=$data[0]->email?><br>
        <label>Date Of Birth:</label>&nbsp;&nbsp;<?=$data[0]->dob?><br>
        <label>Passport Number:</label>&nbsp;&nbsp;<?=$data[0]->passport_no?><br>
        <label>Language Proficient:</label>&nbsp;&nbsp;<?=$data[0]->u_l_name?><br>

        <?php
$grades = json_decode($data[0]->u_grade_name, true);
        $grade_name = array_keys($grades);

        ?>
        <label>Highest Qualification:</label>&nbsp;&nbsp;<?=$grade_name[0]?><br>
        <label>Grade Scheme:</label>&nbsp;&nbsp;<?=$data[0]->grade_scheme?><br>
        <label>Average Score:</label>&nbsp;&nbsp;<?=$data[0]->score?><br>

        <label>Nationality:</label>&nbsp;&nbsp;<?=$data[0]->cntry_name?><br>

        <label>Visa:</label>&nbsp;&nbsp;

<?php
switch ($data[0]->has_visa) {
            case '0':
                echo "No,I don't have this<br>";
                break;

            case '1':
                echo "USA<br>";
                break;

            case '2':
                echo "Canada<br>";
                break;
        }
        ?>

        <?php
switch ($data[0]->gender) {
            case '1':
                ?>
            <label>Gender:</label>&nbsp;&nbsp;Male<br>
                <?php
break;

            case '2':
                ?>
            <label>Gender:</label>&nbsp;&nbsp;Female<br>
            <?php
break;
        }
        ?>
        <label>Exams Given:</label><br>
        <?php
$exams = getExams($data[0]->exam);
        foreach ($exams as $exam_name => $sub_arr) {
            echo "<label>" . $exam_name . "</label>";
            foreach ($sub_arr as $subject => $marks) {
                echo "<p>" . $subject . " " . $marks . " marks</p>";
            }
        }
        ?>

        <label>Intake</label>&nbsp;&nbsp;
        <?php
if (!empty($data[0]->intake)) {
            $user_intakes = json_decode($data[0]->intake, true);
            $monthNum = $user_intakes['month'];

            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // March
            echo $monthName . "-" . $user_intakes['year'] . "<br>";
        } else {
            echo "No intake selected. <br>";
        }
        ?>

<label>Application Submitted On&nbsp;&nbsp;</label><?=date("Y-m-d", strtotime($data[0]->created_on))?><br>

        <label>Profile Image:</label><br>;<img src="<?=student_asset_url . "/images/" . $data[0]->u_image?>" width="200px" heigth="200px"><br>


        <?php

        $documents = getStudentDocuments($data[0]->student_id);
        echo "<label>Documents Uploaded</label>";

        echo "<ul>";

        if (count($documents) > 0) {

            foreach ($documents as $key => $object) {
                $type = pathinfo($object->document, PATHINFO_EXTENSION);

                switch ($type) {
                    case 'pdf':
                        echo "<li><a href='" . student_asset_url . "/documents/" . $object->document . "' download='" . $object->document . "'><img src='https://www.downloadexcelfiles.com/sites/all/themes/anu_bartik/icon/pdf48.png' width='48' height='48'>PDF</a></li><br>";
                        break;

                    case 'docx':
                        echo "<li><a href='" . student_asset_url . "/documents/" . $object->document . "' download='" . $object->document . "'><img src='https://www.downloadexcelfiles.com/sites/all/themes/anu_bartik/icon/xlsx48.png' width='48' height='48'>XLSX</a></li><br>";
                        break;

                    case 'png':
                        echo "<li><div style='display: none;' id='hidden_image_" . $key . "'><img src='" . student_asset_url . "/documents/" . $object->document . "' width='80%' height='80%'></div><a href='" . student_asset_url . "/documents/" . $object->document . "' data-fancybox data-src='#hidden_image_" . $key . "' download='" . $object->document . "'>Image</a></li><br>";
                        break;

                    case 'jpg':
                        echo "<li><div style='display: none;' id='hidden_image_" . $key . "'><img src='" . student_asset_url . "/documents/" . $object->document . "' width='80%' height='80%'></div><a href='" . student_asset_url . "/documents/" . $object->document . "' data-fancybox data-src='#hidden_image_" . $key . "' download='" . $object->document . "'>Image</a></li><br>";
                        break;

                    case 'jpeg':
                        echo "<li><div style='display: none;' id='hidden_image_" . $key . "'><img src='" . student_asset_url . "/documents/" . $object->document . "' width='80%' height='80%'></div><a href='" . student_asset_url . "/documents/" . $object->document . "' data-fancybox data-src='#hidden_image_" . $key . "' download='" . $object->document . "'>Image</a></li><br>";
                        break;
                }
            }
            echo "</ul>";

        } else {
            echo "<b>No document Uploaded</b>";
        }
        ?>
    </div>



<div style="width: 550px; height: 1300px;border: solid 1px #ccc; display: inline-block; float:right">
<h3>Course Detail</h3>
<label>Name:</label>&nbsp;&nbsp;<?=$data[0]->name?><br>
<label>Code:</label>&nbsp;&nbsp;<?=$data[0]->code?><br>
<label>Description:</label>&nbsp;&nbsp;<?=$data[0]->description?><br>
<label>Type:</label>&nbsp;&nbsp;<?=$data[0]->type_name?><br>
<label>Category:</label>&nbsp;&nbsp;<?=$data[0]->category_name?><br>

<?php $duration = json_decode($data[0]->duration, true)?>
<label>Duration:</label>&nbsp;&nbsp;<?=$duration['day_span'] . " " . $duration['time_span']?><br>

<label>International Fees:</label><br>
<label>Tution Fee:</label>&nbsp;&nbsp;<?=$data[0]->int_tution_fee?><br>
<label>Total Fee:</label>&nbsp;&nbsp;<?=$data[0]->int_total_fee?><br>


<label>Domestic Fees:</label><br>
<label>Tution Fee:</label>&nbsp;&nbsp;<?=$data[0]->dom_tution_fee?><br>
<label>Total Fee:</label>&nbsp;&nbsp;<?=$data[0]->dom_total_fee?><br>


<label>Application Processing Time:</label>&nbsp;&nbsp;
<?php $process_time = json_decode($data[0]->process_time, true)?>
<?=$process_time['day_span'] . " " . $process_time['time_span']?><br>


<?php
switch ($data[0]->internship) {
            case '0':
                ?>
    <label>Internship:</label>&nbsp;&nbsp;No<br>
    <?php
break;

            case '1':
                ?>
        <label>Internship:</label>&nbsp;&nbsp;Yes<br>
                <?php
break;

        }
        ?>


<label>Exams Required:</label><br>
        <?php
$exams = getExams($data[0]->exam_marks);
        foreach ($exams as $exam_name => $sub_arr) {
            echo "<label>" . $exam_name . "</label>";
            foreach ($sub_arr as $subject => $marks) {
                echo "<p>" . $subject . " " . $marks . " marks </p>";
            }
        }
        ?>

<label>Course Image:</label><br>;<img src="<?=course_asset_url . "/images/" . $data[0]->image?>" width="200px" heigth="200px"><br>

<label>Intakes:</label><br>




        <?php
$intakes = getCourseIntakes($data[0]->course_id);

        if (empty($intakes)) {
            echo "No course intake defined.";
        } else {
            echo "<table style='border:2px solid black; width:100%;'><thead><th>Month Name</th><th>Course Start Date</th>
            <th>Course End Date</th><th>Application Deadline</th></thead>";
            foreach ($intakes as $key => $obj) {
                echo "<tr><td>" . $obj->name . "</td><td>" . $obj->start_date . "</td>";
                echo "<td>" . $obj->end_date . "</td><td>" . $obj->deadline . "</td></tr>";
            }
            echo "</table>";
        }
        ?>
        </div>
<?php
// echo "<pre>";
        //         print_r($data);
    }
    ?>
<script src="<?=staff_asset_url . '/js/ApplicationDetail.js'?>"></script>

<?php
}

add_shortcode('application_detail', 'applicationDetail')
?>

