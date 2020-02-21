<?php

function agentProfile()
{
    ?>
<div class="container">
  <a href="<?=base_url?>/view-sub-agents/" class="btn btn-primary" style="float:right">View sub agents</a>
  <br><br>
  <a id="change_password" style="float:right">Change Password</a>

  <h2>Contact Detail of Business</h2>
  <form name="update_agent" id="update_agent">

    <div class="form-group">
      <label for="business_name">Business Name:</label>
      <input type="text" class="form-control" id="business_name" placeholder="Enter business name" name="business_name" required>
    </div>

    <div class="form-group">
      <label for="business_email">Business Email:</label>
      <input type="email" class="form-control" id="business_email" placeholder="Enter business email" name="business_email" required email>
    </div>


    <div class="form-group">
      <label for="business_address">Business Address:</label>
      <input type="text" class="form-control" id="business_address" placeholder="Enter business address" name="business_address" required>
    </div>

    <div class="form-group">
      <label for="business_phone">Business Phone:</label>
      <input type="text" class="form-control" id="business_phone" placeholder="Enter business contact number" name="business_phone" required number>
    </div>

    <div class="form-group">
      <label for="business_site">Business Website:</label>
      <input type="url" class="form-control" id="business_site" placeholder="Enter business website url" name="business_site" required >
    </div>

    <div class="form-group">
      <label for="business_img">Business Image:</label><br>
      <img src="" id="image" name="image" style="display:none" width="200px" height="200px">
        <input type="hidden" name="bus_image" id="bus_image">
      <input type="file" class="form-control" id="business_img" name="business_img" >
    </div>

    <h2>Contact Detail Of Authorized Person of Business</h2>

    <div class="form-group">
      <label for="person_name">Name:</label>
      <input type="text" class="form-control" id="person_name" placeholder="Enter name" name="person_name" required >
    </div>

    <div class="form-group">
      <label for="person_mail">Email:</label>
      <input type="email" class="form-control" id="person_mail" placeholder="Enter email address" name="person_mail" required email>
    </div>

    <div class="form-group">
      <label for="person_number">Contact Number:</label>
      <input type="text" class="form-control" id="person_number" placeholder="Enter contact number" name="person_number" required number>
    </div>

    <div class="form-group">
      <label for="person_address">Address:</label>
      <input type="text" class="form-control" id="person_address" placeholder="Enter address" name="person_address" required >
    </div>

    <input type="submit" class="btn btn-success" id="update_btn" value="Update Profile">
     </form>

     <div class = 'modal fade' id = 'password_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h4 class = 'modal-title'>Change Password</h4>
    </div>
    <div class = 'modal-body'>
    <p>Old Password:
    <input type = 'password' name = 'password' id = 'agent_password' required>
    </p>
    </div>
    <div class = 'modal-footer'>
    <button type = 'button' class = 'btn btn-default' id = 'check_password'>Check</button>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>
    
     <script src="<?=agent_asset_url?>js/AgentProfile.js"></script>
</div>
    <?php
}
add_shortcode('agent_profile', 'agentProfile');
?>