<?php

function adminDashboard()
{
    ?>
 <div class="container-fluid" >

    <div class="row">

    <div class="col-md-4">
    <div class="card text-white bg-warning mb-3" style="width: 200px;height:150px">
  <div class="card-header">Agents</div>
  <div class="card-body">
    <h5 class="card-title">Total Agents</h5>
    <p class="card-text" id="agents"></p>
  </div>
</div>
</div>


<div class="col-md-4">
<div class="card text-white bg-primary mb-3" style="width: 200px;height:150px">
  <div class="card-header">Sub Agents</div>
  <div class="card-body">
    <h5 class="card-title">Total Sub Agents</h5>
    <p class="card-text" id="sub_agents"></p>
  </div>
</div>
</div>


<div class="col-md-4">
<div class="card text-white bg-success mb-3" style="width: 200px;height:150px">
  <div class="card-header">Schools</div>
  <div class="card-body">
    <h5 class="card-title">Total Schools</h5>
    <p class="card-text" id="schools"></p>
  </div>
</div>
</div>


<div class="col-md-4">
<div class="card text-white bg-success mb-3" style="width: 200px;height:150px">
  <div class="card-header">Courses</div>
  <div class="card-body">
    <h5 class="card-title">Total Courses</h5>
    <p class="card-text" id="courses"></p>
  </div>
</div>
</div>


<div class="col-md-4">
<div class="card text-white bg-info mb-3" style="width: 200px;height:150px">
  <div class="card-header">Staff</div>
  <div class="card-body">
    <h5 class="card-title">Total Staff</h5>
    <p class="card-text"  id="staff"></p>
  </div>
</div>
</div>


</div>
</div>






    <!-- </div>
    </div>

</div>
</div>
</div>
</div>
</div>
</div> -->

    <script src="<?=admin_asset_url?>js/AdminDashboard.js"></script>
<?php
}

add_shortcode('admin_dashboard', 'adminDashboard');
?>