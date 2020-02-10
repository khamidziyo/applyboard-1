<?php
function subAgentDashboard()
{
    ?>
    <a href="<?=base_url?>sub-agent-profile/">Profile</a><br>
    <a href="<?=base_url?>add-student/">Add Student</a><br>
    <a href="<?=base_url?>view-students/">View Students</a><br>
    <a href="#">View Applications</a><br>

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

    <script src="<?=constant('agent_asset_url') . "/js/SubAgentDashboard.js"?>"></script>

<?php
}
add_shortcode('sub_agent_dashboard', 'subAgentDashboard');
?>