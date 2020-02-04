<?php

function agentDashboard()
{
    ?>


<div class="container">
<a href="#">Profile</a><br>
<a href="<?=base_url?>add-student/">Add Student</a><br>
<a href="<?=base_url?>view-students/">View Students</a><br>
<a class="btn btn-primary" id="create_sublogin">Create Sub agents</a><br><br>


<div class = 'modal fade' id = 'sub_login_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h2>Create Sub Agent Profile</h2>
    </div>

    <div class = 'modal-body'>
    <!-- Circles which indicates the steps of the form: -->
    <div class="container" style="width:500px">

    <form name="sub_agent_form" id="sub_agent_form">

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
    </div>


    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
    </div>


    <div class="form-group">
      <label for="con_password">Confirm Password:</label>
      <input type="password" class="form-control" id="con_password" placeholder="Enter email" name="con_password" required>
    </div>

    <input type="hidden" name="val" value="addSubAgent">
    <input type="submit" class="btn btn-success" value="Create profile" id="sub_agent">
    </form>

    </div>
    <div class = 'modal-footer'>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>
    </div>


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
<div class="card text-white bg-secondary mb-3" style="width: 200px;height:150px">
  <div class="card-header">Sub Agents</div>
  <div class="card-body">
    <h5 class="card-title">Total Sub agents</h5>
    <p class="card-text" id="sub_agents"></p>
    <a href="#">View Sub Agents</a>
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
</div>

<script src="<?=agent_asset_url?>js/AgentDashboard.js"></script>
<?php
}

add_shortcode('agent_dashboard', 'agentDashboard');
