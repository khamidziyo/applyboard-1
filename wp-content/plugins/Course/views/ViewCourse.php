<?php

// function to view a particular course detail...
function viewCourse()
{
    if (!empty($_GET['c_id'])) {

        $course_id = base64_decode($_GET['c_id']);

        // function to get the course detail by course id defined in server/functions.php file...
        $course_data = getCourseDetailById($course_id);

        if (!empty($course_data[0]->intake)) {
            $intakes = json_decode($course_data[0]->intake, true);

            $course_intake = getIntakes($intakes);
        }

        ?>
  <div class="container-fluid">
  <?php
if (!empty($course_data)) {
            $duration = json_decode($course_data[0]->duration, true);

            $process_time = json_decode($course_data[0]->process_time, true);
            ?>

    <h2>Course Detail of <b><?=$course_data[0]->name?></b></h2><br>

    <p>Course Name: <b><?=$course_data[0]->name?></b></p>

    <p>Course Code: <b><?=$course_data[0]->code?></b></p>

    <p>Course Description: <b><?=$course_data[0]->description?></b></p>

    <p>Course Type: <b><?=$course_data[0]->type_name?></b></p>

    <p>Course Category: <b><?=$course_data[0]->category_name?></b></p>

    <p>Course Language: <b><?=$course_data[0]->language_name?></b></p>

    <p>Course Duration: <b><?=$duration['day_span'] . " " . $duration['time_span']?></b></p>

    <p>Course Start Date: <b><?=date('d/m/Y', strtotime($course_data[0]->start_date))?></b></p>

    <p>Course End Date: <b><?=date('d/m/Y', strtotime($course_data[0]->end_date))?></b></p>
    <p>Course Intake : <b>
    <?php
            echo "<ul>";
            foreach ($course_intake as $key => $obj) {
                echo "<li>" . $obj->name . "</li>";
            }
            echo "</ul>";
            ?>
    </b></p>

    <p>Processing Time: <b><?=$process_time['day_span'] . " " . $process_time['time_span']?></b></p>

    <p>Internship: <b><?php if ($course_data[0]->internship == 0) {echo "No";} else {echo "Yes";}?></b></p>

    <p>Fees

    <p>Application Fee: <b><?=$course_data[0]->application_fee?></b></p>

    <p>International Tution Fee(<small>per semester</small>): <b><?=$course_data[0]->int_tution_fee?></b></p>

    <p>International Total Fee(<small>full Course</small>):<b><?=$course_data[0]->int_total_fee?></b></p>

    <p>Domestic Tution Fee(<small>per semester</small>):<b><?=$course_data[0]->dom_tution_fee?></b></p>

    <p>Domestic Total Fee(<small>Full Course</small>):<b><?=$course_data[0]->dom_total_fee?></b></p>

    </p>

    <?php
if (!empty($course_data[0]->exam_marks)) {
                ?>
      <p>Requirements for Course
      <?php
$exam_marks = json_decode($course_data[0]->exam_marks, true);
                foreach ($exam_marks as $exam_id => $marks_arr) {

                    // get the exam name by exam id...
                    $exam_name = getExamNameById($exam_id);

                    echo "<p><b>" . $exam_name[0]->name . "</b>";
                    foreach ($marks_arr as $sub_name => $marks) {
                        ?>
          <p><?=$sub_name?>&nbsp;&nbsp; <?=$marks?> marks
          <?php
}
                }
                ?>
      </p>
      <?php
}
            ?>
  <p>Course Image:</p>
   <p><img src="<?=constant('course_asset_url') . "images/" . $course_data[0]->image?>" width="200px" height="200px"></p>

  </div>
  <?php
}

    }
}

// shortcode to view a specific course in detail..
add_shortcode('view_course', 'viewCourse');
?>