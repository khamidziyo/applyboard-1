<?php

function agentDashboard()
{
    ?>


<div class="container">
<div class="ph-item">
    <div class="ph-col-12">
    <div class="ph-row">
            <div class="ph-col-6 big"></div>
            <div class="ph-col-4 empty big"></div>
            <div class="ph-col-2 big"></div>
            <div class="ph-col-4"></div>
            <div class="ph-col-8 empty"></div>
            <div class="ph-col-6"></div>
            <div class="ph-col-6 empty"></div>
            <div class="ph-col-12"></div>
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
    <a href="<?=base_url?>view-sub-agents/">View Sub Agents</a>
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
