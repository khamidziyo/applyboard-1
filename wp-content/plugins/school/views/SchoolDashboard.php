<?php

function schoolDashboard()
{
    ?>
        <a href="<?=base_url?>school-profile/">Profile</a></br>
        <a href="<?=base_url?>add-course/">Add Course</a></br>
        <a href="<?=base_url?>view-all-course/">View All Course</a></br>

        <a href="<?=base_url?>messages">My Messages</a></br>
        <a href="<?=base_url?>view-applications-by-school/">Applications</a></br>
        <a href="<?=base_url?>notification-detail/">Notifications</a></br>


        <div class="row">
    <div class="col-md-4">

    <div class="card text-white bg-dark mb-3" style="width: 200px;height:150px">
  <div class="card-header">Students</div>
  <div class="card-body">
    <h5 class="card-title">Total Students</h5>
    <p class="card-text" id="students"></p>
  </div>
</div>
</div>

<div class="col-md-4">
<div class="card text-white bg-secondary mb-3" style="width: 200px;height:150px">
  <div class="card-header">Courses</div>
  <div class="card-body">
    <h5 class="card-title">Total Courses</h5>
    <p class="card-text" id="courses"></p>
  </div>
</div>
</div>


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

        <script src = '<?=school_asset_url?>js/SchoolDashboard.js'></script>

    <?php
}

add_shortcode('school_dashboard', 'schoolDashboard');
?>