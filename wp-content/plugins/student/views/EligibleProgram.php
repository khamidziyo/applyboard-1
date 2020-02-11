<?php

function eligibleProgram()
{
    ?>
    <!-- <div class="container" style="width:500px"> -->

  <form name="filter_applications" id="filter_applications">
    <div class="row">
    <div class="col-md-3">

<div class="form-group">
  <label for="country">Select Country</label>
  <select class="form-control" id="countries" name="country" multiple>
  </select>
</div>
</div>

<div class="col-md-3">
<div class="form-group">
  <label for="category">Select Category of Course interested </label>
  <select class="form-control" id="categories" name="category" multiple>
  </select>
</div>
</div>

<div class="col-md-3">
<div class="form-group">
  <label for="discipline">Select The Discipline of course</label>
  <select class="form-control" id="discipline" name="disciplines" multiple>
  </select>
</div>
</div>

<div class="col-md-3">
<div class="form-group">
  <label for="schools">Select School</label>
  <select class="form-control" id="schools" name="school" multiple>
  </select>
</div>

<input type="submit" value="Filter Course" id="filter_course" class="btn btn-primary">
</div>

</div>
</form>


<div class = 'modal fade' id = 'intake_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h4 class = 'modal-title'>Available Intakes</h4>
    </div>
    <form name="course_intake" id="course_intake">

    <div class = 'modal-body'>
    <span id="intakes"></span>
    </div>
    <div class = 'modal-footer'>
    <button type = 'submit' class = 'btn btn-success' id = 'check_password'>Apply Now</button>
    </form>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>


<table id="eligible_course_table" border="2px solidblack">
<thead>
<th>Id</th>
<th>Course Name</th>
<th>School Name</th>
<th>Code</th>
<th>Type</th>
<th>Category</th>
<th>Action</th>

</thead>
</table>




<script src="<?=student_asset_url?>/js/eligibleCourse.js"></script>
<?php
}

add_shortcode('eligible_program', 'eligibleProgram');
?>