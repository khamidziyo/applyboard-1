<?php

// file to show the  dashboard page...

function studentDashboard() {
    ?>
    <div class = 'container'>

<div class="row">
    <div class="col-md-4">
<div class="card text-white bg-primary mb-3" style="width: 200px;height:150px">
  <div class="card-header">Applications</div>
  <div class="card-body">
    <h5 class="card-title">Total Applications</h5>
    <p class="card-text" id="applications"></p>
  </div>
</div>
</div>

<div class="col-md-4">

<div class="card text-white bg-success mb-3" style="width: 200px;height:150px">
<div class="card-header">Applications Approved</div>
<div class="card-body">
<h5 class="card-title">Total Applications Approved</h5>
<p class="card-text" id="application_approve"></p>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card text-white bg-warning mb-3" style="width: 200px;height:150px">
  <div class="card-header">Applications Declined</div>
  <div class="card-body">
    <h5 class="card-title">Total Applications Declined</h5>
    <p class="card-text"  id="application_decline"></p>
  </div>
</div>
</div>


<div class="col-md-4">
<div class="card text-white bg-info mb-3" style="width: 200px;height:150px">
  <div class="card-header">Applications Pending</div>
  <div class="card-body">
    <h5 class="card-title">Total Applications Pending</h5>
    <p class="card-text"  id="application_pending"></p>
  </div>
</div>
</div>

</div>

   
    <!-- <form name = 'search_form' id = 'search_form'>
    <p>Search Program
    <input type = 'text' class = 'form-control' name = 'program' id = 'program' placeholder = 'What would you like to study' required>
    <input type = 'text' class = 'form-control' name = 'sch_name' id = 'sch_name' placeholder = 'Where? eg. school name' required>
    <input type = 'hidden' name = 'val' value = 'searchProgram'>
    <input type = 'submit' class = 'btn btn-primary form-control' value = 'Search' id = 'search'>
    </p>
    </form>

    <div id = 'course_div' style = 'display:none'>
    <div class = 'card'>
    <img src = '' id = 'c_image' alt = 'Course' style = 'width:200px;height:200px'>
    <div class = 'container'>
    <h4><b><p id = 'c_name'></p></b></h4>
    <p id = 's_name'></p>
    <input type = 'button' width = '20px' value = 'View Detail' id = 'view_detail'>

    </div>
    </div>
    </div>
    <span id = 'c_empty' style = 'display:none'>No Course Found</span> -->
    </div>
    <script src = '<?=student_asset_url?>/js/StudentDashboard.js'></script>
    <?php
}
add_shortcode( 'student_dashboard', 'studentDashboard' );
