<?php

function addCourse()
{

    if (!empty($_GET['c_id'])) {

        // to get the course data by course id ...
        $course_id = base64_decode($_GET['c_id']);

        $course_data = getCourseDetailById($course_id);
        // echo "<pre>";
        // print_r($course_data);

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

	<p>Course Name (required) <br/>
	 <input type="text"  name="course_name" value="<?=!empty($course_data) ? $course_data[0]->name : ''?>" size="40" />
	 </p>

	 <p>
	  Course Code (required) <br/>
	 <input type="text"  name="course_code" value="<?=!empty($course_data) ? $course_data[0]->code : ''?>" size="40" required/>
	 </p>

   <p>Course Description (required) <br/>
	 <textarea rows="10" cols="35" name="course_description" id="course_description" required><?=!empty($course_data) ? $course_data[0]->description : ''?></textarea>
	 </p>

   <p>Course Type  (required) <br/>

   <select name="course_type" id="course_type" required>
   <option selected disabled> Select Course Type</option>
   </select>
   </p>


   <p>
   Course Category  (required) <br/>
   <select name="category" id="course_category" required>
   <option selected disabled> Select Course Category</option>
   </select>
	 </p>

	 <p>
	 Course Start Date (required) <br/>

	 <input type="text"  name="c_st_date" id="c_st_date"  value="<?=!empty($course_data) ? $course_data[0]->start_date : ''?>" size="40" required/>
	 </p>

	 <p>
	 Course End Date (required) <br/>
	 <input type="text"   name="c_end_date" id="c_end_date"  value="<?=!empty($course_data) ? $course_data[0]->end_date : ''?>" size="40" required/>
	 </p>

<?php
if (!empty($course_data)) {
        $duration = json_decode($course_data[0]->duration, true);
    }
    ?>
   <p>
	 Course Duration (required) <br/>
   <select name="c_duration[time_span]" id="c_duration">
   <option selected disabled>Select Duration</option>
   <option <?php if (!empty($duration)) {if ($duration['time_span'] == "day") {?>selected="selected"<?php }}?>value="day">Days</option>
   <option <?php if (!empty($duration)) {if ($duration['time_span'] == "week") {?>selected="selected"<?php }}?>value="week">Week</option>
   <option <?php if (!empty($duration)) {if ($duration['time_span'] == "month") {?>selected="selected"<?php }}?>value="month">Month</option>
   <option <?php if (!empty($duration)) {if ($duration['time_span'] == "year") {?>selected="selected"<?php }}?> value="year">Year</option>
   </select></br>
	 <input type="text"  name="c_duration[day_span]"  value="<?=!empty($duration) ? $duration['day_span'] : ''?>" size="40" required/>
	 </p>

	 <p> Application Fee  (required) <br/>
	 <input type="text"  name="app_fee" value="<?=!empty($course_data) ? $course_data[0]->application_fee : ''?>" size="40" required/>
	 </p>

	 <p><h2>International Fees</h2><br/>
   <p> Tution Fee  per semester(required)
   <input type="text"  name="int_tution_fee" value="<?=!empty($course_data) ? $course_data[0]->int_tution_fee : ''?>" size="40" required/>
    </p>

    <p> Total Fee  (required)
   <input type="text"  name="int_total_fee" value="<?=!empty($course_data) ? $course_data[0]->int_total_fee : ''?>" size="40" required/>
    </p>
    </p>

	 <p><h2>Domestic Fees</h2><br/>
   <p> Tution Fee per semester (required)
   <input type="text"  name="dom_tution_fee" value="<?=!empty($course_data) ? $course_data[0]->dom_tution_fee : ''?>" size="40" required/>
    </p>

    <p> Total Fee  (required)
   <input type="text"  name="dom_total_fee" value="<?=!empty($course_data) ? $course_data[0]->dom_total_fee : ''?>" size="40" required/>
    </p>
	 </p>

    <?php

    if (!empty($course_data)) {
        ?>
      <input type="hidden" name="course_id" value="<?=$course_id?>">
      <?php
}
    ?>

    <p> Internship :
		  Yes <input type="radio" name="internship" <?php if (!empty($course_data)) {if ($course_data[0]->internship == 1) {?>checked<?php }}?> value="1" id="inerternship-yes" required>
		  No <input type="radio" name="internship" <?php if (!empty($course_data)) {if ($course_data[0]->internship == 0) {?>checked<?php }}?> value="0" id="inerternship-no" required>
	 </p>

   <p>Language of instruction  (required) <br/>
      <select name="language_of_instruction" id="language_of_instruction">
      <option selected disabled> Select Language</option>
      </select>
      </p>

      <p>Processing Time <br>
      <?php
if (!empty($course_data)) {

        $process_time = json_decode($course_data[0]->process_time, true);
        // echo "<pre>";
        // print_r($process_time);
    }
    ?>
      <select name="process_time[time_span]" id="process_time">
   <option selected disabled>Select Time span</option>
   <option <?php if (!empty($process_time)) {if ($process_time['time_span'] == "day") {?>selected="selected"<?php }}?>value="day">Days</option>
   <option <?php if (!empty($process_time)) {if ($process_time['time_span'] == "week") {?>selected="selected"<?php }}?>value="week">Week</option>
   <option <?php if (!empty($process_time)) {if ($process_time['time_span'] == "month") {?>selected="selected"<?php }}?>value="month">Month</option>
   <option <?php if (!empty($process_time)) {if ($process_time['time_span'] == "year") {?>selected="selected"<?php }}?> value="year">Year</option>
   </select></br>
	 <input type="text"  name="process_time[day_span]"  value="<?=!empty($process_time) ? $process_time['day_span'] : ''?>" size="40" required/>      </p>

   <p>Do you allow student for english proficiency test
   <input type="checkbox" id="eng_prof_test">
   </p>



   <?php
if (!empty($course_data)) {
        ?>
     <span id="exam_test">


      <script>
   setTimeout(function(){
      $('#language_of_instruction').children("option[value="+<?=$course_data[0]->language_id?>+"]").attr('selected',true)
      $('#course_category').children("option[value="+<?=$course_data[0]->category_id?>+"]").attr('selected',true)
      $('#course_type').children("option[value="+<?=$course_data[0]->type_id?>+"]").attr('selected',true)

    },3000);
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