<?php

function addCourse()
{

    if (!empty($_GET['c_id'])) {

        // to get the course data by course id ...
        $course_id = base64_decode($_GET['c_id']);

        $course_data = getCourseDetailById($course_id);

        $intakes = json_decode($course_data[0]->intake, true);
        // echo "<pre>";
        // print_r($intakes);
        ?>

    <h2>Update Course !</h2>
    <?php

    } else {
        ?>
  <h2>Add Course !</h2>

  <?php
}
    ?>
  <div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
		<style>
 input[type="text"] {
  height: 41px;
}
 </style>

<div class="container-fluid">

    <form method="post" id="course_form">

    <div class="form-group">
      <label for="course_name">Course Name (required)</label>
      <input type="text"  name="course_name" id="course_name" class="form-control" placeholder="Enter Course name" value="<?=!empty($course_data) ? $course_data[0]->name : ''?>" size="40" />
      </div>


      <div class="form-group">
      <label for="course_code">Course Code (required)</label>
      <input type="text"  name="course_code" class="form-control" placeholder="Enter Course code" value="<?=!empty($course_data) ? $course_data[0]->code : ''?>" size="40" />
      </div>

      <div class="form-group">
      <label for="course_description">Course Description (required)</label>
	 <textarea rows="10" cols="35" class="form-control" placeholder="Give a short description about course" name="course_description" id="course_description" required><?=!empty($course_data) ? $course_data[0]->description : ''?></textarea>
  </div>


  <div class="form-group">
      <label for="course_type">Course Type  (required)</label>
      <select class="form-control" name="course_type" id="course_type" required>
   <option selected disabled> Select Course Type</option>
   </select>
      </div>

      <div class="form-group">
      <label for="course_category">Course Category  (required)</label>
     <select class="form-control" name="category" id="course_category" required>
   <option selected disabled> Select Course Category</option>
   </select>
    </div>


<?php
if (!empty($course_data)) {
        $duration = json_decode($course_data[0]->duration, true);
    }
    ?>

   <div class="form-group">
      <label for="c_duration">Course Duration (required) </label>
   <select name="c_duration[time_span]" id="c_duration" class="form-control">
   <option selected disabled>Select Duration</option>
   <option <?php if (!empty($duration)) {if ($duration['time_span'] == "day") {?>selected="selected"<?php }}?>value="day">Days</option>
   <option <?php if (!empty($duration)) {if ($duration['time_span'] == "week") {?>selected="selected"<?php }}?>value="week">Week</option>
   <option <?php if (!empty($duration)) {if ($duration['time_span'] == "month") {?>selected="selected"<?php }}?>value="month">Month</option>
   <option <?php if (!empty($duration)) {if ($duration['time_span'] == "year") {?>selected="selected"<?php }}?> value="year">Year</option>
   </select></br>
   </div>

   <div class="form-group">
   <label for="days">Enter Day</label>
	 <input type="text" id="days" name="c_duration[day_span]" class="form-control" value="<?=!empty($duration) ? $duration['day_span'] : ''?>" size="40" required/>
    </div>

    <div class="form-group">
    <label for="app_fee">Application Fee  (required)</label>
	 <input type="text"  name="app_fee" class="form-control" placeholder="Enter Application fees" value="<?=!empty($course_data) ? $course_data[0]->application_fee : ''?>" size="40" required/>
    </div>

    <p><h2>International Fees</h2><br/>

    <div class="form-group">
    <label for="int_tution_fee">Tution Fee  per semester(required)</label>
    <input type="text"  name="int_tution_fee" class="form-control" placeholder="Enter international Course fee" value="<?=!empty($course_data) ? $course_data[0]->int_tution_fee : ''?>" size="40" required/>
    </div>

    <div class="form-group">
    <label for="int_total_fee">Tution Fee  per semester(required)</label>
    <input type="text" class="form-control" name="int_total_fee" placeholder="Enter total Course fee" value="<?=!empty($course_data) ? $course_data[0]->int_total_fee : ''?>" size="40" required/>
    </div>


	 <p><h2>Domestic Fees</h2><br/>

   <div class="form-group">
   <label for="dom_tution_fee">Tution Fee  per semester(required)</label>
   <input type="text"  name="dom_tution_fee" class="form-control" placeholder="Enter domestic Course fee" value="<?=!empty($course_data) ? $course_data[0]->dom_tution_fee : ''?>" size="40" required/>
   </div>

   <div class="form-group">
   <label for="dom_total_fee">Total Fee  (required)</label>
   <input type="text"  name="dom_total_fee" class="form-control" placeholder="Enter domestic total Course fee" value="<?=!empty($course_data) ? $course_data[0]->dom_total_fee : ''?>" size="40" required/>
    </div>

    <?php

    if (!empty($course_data)) {
        ?>
      <input type="hidden" name="course_id" value="<?=$course_id?>">
      <?php
}
    ?>

    <div class="form-group">
    <label for="internship">Internship:</label>
    Yes <input type="radio" name="internship" class="form-control" <?php if (!empty($course_data)) {if ($course_data[0]->internship == 1) {?>checked<?php }}?> value="1" id="inerternship-yes" required>
		  No <input type="radio" name="internship" class="form-control" <?php if (!empty($course_data)) {if ($course_data[0]->internship == 0) {?>checked<?php }}?> value="0" id="inerternship-no" required>
    </div>

    <div class="form-group">
    <label for="language_of_instruction">Language of instruction  (required)</label>

    <select class="form-control" name="language_of_instruction" id="language_of_instruction">
      <option selected disabled> Select Language</option>
      </select>
    </div>

    <?php
if (!empty($course_data)) {

        $process_time = json_decode($course_data[0]->process_time, true);
    }
    ?>

    <div class="form-group">
    <label for="process_time">Processing Time</label>
    <select name="process_time[time_span]" id="process_time" class="form-control">
   <option selected disabled>Select Time span</option>
   <option <?php if (!empty($process_time)) {if ($process_time['time_span'] == "day") {?>selected="selected"<?php }}?>value="day">Days</option>
   <option <?php if (!empty($process_time)) {if ($process_time['time_span'] == "week") {?>selected="selected"<?php }}?>value="week">Week</option>
   <option <?php if (!empty($process_time)) {if ($process_time['time_span'] == "month") {?>selected="selected"<?php }}?>value="month">Month</option>
   <option <?php if (!empty($process_time)) {if ($process_time['time_span'] == "year") {?>selected="selected"<?php }}?> value="year">Year</option>
   </select></br>
    </div>

    <div class="form-group">
    <input type="text"  name="process_time[day_span]"  class="form-control" placeholder="Enter days" value="<?=!empty($process_time) ? $process_time['day_span'] : ''?>" size="40" required/>      </p>
    </div>

    <div class="form-group">
    <label for="eng_prof_test">Do you allow student for english proficiency test</label>
    <input type="checkbox" id="eng_prof_test">
    </div>




   <?php
if (!empty($course_data)) {
        ?>
     <span id="exam_test">

     <?php

        ?>

      <script>
   setTimeout(function(){
      $('#language_of_instruction').children("option[value="+<?=$course_data[0]->language_id?>+"]").attr('selected',true)
      $('#course_category').children("option[value="+<?=$course_data[0]->category_id?>+"]").attr('selected',true)
      $('#course_type').children("option[value="+<?=$course_data[0]->type_id?>+"]").attr('selected',true)

    },1000);
   </script>



        </span>
        <?php
}
    echo "</span>";
    ?>

  <span id="exams">
  <?php
if (isset($course_data[0]->exam_marks)) {
        ?>

  <script>
    $("#eng_prof_test").prop('checked',true);
    </script>

    <?php
// to get the languages by language id ...
        $exams = getAllExams($course_data[0]->language_id);

        //  to decode the exam and marks that is stored in json format..
        $exams_marks = json_decode($course_data[0]->exam_marks, true);

        foreach ($exams as $key => $obj) {
            ?>
           <h2><input type='checkbox' class='english_exam_input' value=<?=$obj->id?> name=<?=$obj->name?>><?=$obj->name?> </h2>
           <span id="<?=$obj->name?>">
           <?php

            foreach ($exams_marks as $exam_id => $marks_arr) {

                if ($exam_id == $obj->id) {
                    echo "<script>$('.english_exam_input[type=checkbox][value=" . $exam_id . "]').prop('checked',true)</script>";

                    foreach ($marks_arr as $sub_name => $marks) {

                        echo "<p>" . $sub_name . ":<input type='text' name=exam_marks[" . $exam_id . "][" . $sub_name . "] id=" . $sub_name . " value=" . $marks . "></p>";
                    }
                }
            }
            echo "</span>";
        }
    }

    ?>
  </span>




  <p>Course Image  <br/><div>

    <input type="file" name="image" id="image_input" class="regular-text">
    <?php
if (!empty($course_data)) {
        ?>
  <input type="hidden" name="image" value="<?=$course_data[0]->image?>">
    <img src="<?=constant('course_asset_url')?>images/<?=$course_data[0]->image?>" id="image" name="image" width="100px" height="100px">
<?php
} else {
        ?>
      <img src="" id="image" name="image" width="100px" height="100px" style="display:none">
<?php
}
    ?>
     </div><br></p>

  <p><img src="<?=constant('asset_url')?>images/loading.gif" id="loading_gif" width="200px" height="200px" style="display:none"></p>

<?php
if (!empty($_GET['c_id'])) {
        ?>
	<p><input type="submit" id="update_course" class="btn btn-primary submit_course" c_type="update_course" name="update_course" value="Update Course"></p>

<?php
} else {
        ?>
	<p><input type="submit" id="add_course" class="btn btn-primary submit_course" c_type="add_course" name="add_course" value="Add Course"></p>
<?php
}
    ?>
	</form>
  </div>

  <script src="<?=constant('course_asset_url')?>js/AddCourse.js"></script>

	<?php
}

// shortcode to add course...
add_shortcode('add_course', 'addCourse');
?>