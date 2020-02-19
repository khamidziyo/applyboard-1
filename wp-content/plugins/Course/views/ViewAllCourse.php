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
  <th>Created On</th>
  <th>Intake </th>
  <th>Action</th>
  </thead>
  </table>


  <!-- <div class = 'modal fade' id = 'intake_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h4 class = 'modal-title'>Available Intakes</h4>
    </div>
    <form name="courseIntake" id="courseIntake">

    <div class = 'modal-body'>
    <span id="intakes"></span>
    </div>
    <div class = 'modal-footer'>
    <button type = 'submit' class = 'btn btn-success' id = 'apply_btn'>Add Intake</button>
    </form>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div> -->

  </div>

<script src="<?=constant('course_asset_url')?>js/ViewCourse.js"></script>

  <?php
}

// shortcode to view all courses...
add_shortcode('view_all_course', 'viewAllCourse');

?>