<?php

function eligibleProgram()
{
    ?>
    <!-- <div class="container" style="width:500px"> -->

    <div class="row">
    <div class="col-md-3">

<div class="form-group">
  <label for="country">Select Country</label>
  <select class="form-control" id="countries" name="country[]" multiple required>
  </select>
</div>
</div>

<div class="col-md-3">
<div class="form-group">
  <label for="category">Select Category of Course interested </label>
  <select class="form-control" id="categories" name="category[]" multiple required>
  </select>
</div>
</div>

<div class="col-md-3">
<div class="form-group">
  <label for="discipline">Select The Discipline of course</label>
  <select class="form-control" id="discipline" name="disciplines[]" multiple required>
  </select>
</div>
</div>

<div class="col-md-3">
<div class="form-group">
  <label for="schools">Select School</label>
  <select class="form-control" id="schools" name="school[]" multiple required>
  </select>
</div>

<input type="button" value="Filter Course" class="btn btn-primary">
</div>

</div>

<table id="eligible_course_table" border="2px solidblack">
<thead>
<th>Id</th>
<th>Name</th>
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