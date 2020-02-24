<?php

function viewCourses(){
?>
<p>Select the country of which you want to view schools
<select class="form-control" name="countries" id="countries">
<option selected="true" disabled>Select Country</option>
</select>
</p>

<p>Select the school of which you want to view courses
<select class="form-control" name="schools" id="schools">
<option selected="true" disabled>Select School</option>
</select>
</p>


<div class="container-fluid">
<table id="course_table" style="display:none">
<thead>
<th>Id</th>
<th>Name</th>
<th>Code</th>
<th>Type</th>
<th>Category</th>
<th>Action</th>
</thead>
</table>
</div>

<script src="<?=admin_asset_url?>js/ViewCourses.js"></script>
<?php
}
add_shortcode('view_course_by_admin','viewCourses');
