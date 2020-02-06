<?php

function viewAllCourse()
{
    ?>
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <div class="container-fluid">
  <table id="view_course_table" border="2px solid black">
  <thead>
  <th>ID</th>
  <th>Name</th>
  <th>Code</th>
  <th>Type</th>
  <th>Category</th>
  <th>Start Date</th>
  <th>End Date</th>
  <th>Created On</th>
  <th>Action</th>
  </thead>
  </table>
  </div>

<script src="<?=constant('course_asset_url')?>js/ViewCourse.js"></script>

  <?php
}

// shortcode to view all courses...
add_shortcode('view_all_course', 'viewAllCourse');

?>