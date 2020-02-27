<?php
function staffProfile()
{
    ?>
    <a style = 'float:right' id = 'change_password'>Change Password</a>

<div class="container">
  <form name="update_staff" id="update_staff">

    <div class="form-group">
      <label for="name"> Name:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required>
    </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="stu_email" placeholder="Enter email " name="email" required>
    </div>

    <div class="form-group">
      <label for="image">Image:</label><br>
      <img src="" id="image" width="200px" height="200px" style="display:none">

      <input type="hidden" name="cur_image" id="cur_image">

      <input type="file" class="form-control" id="img_input" name="img_input">
    </div>

    <input type="submit" value="Update Profile" class="btn btn-success" id="update_staff_btn" name="update_staff_btn">

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
    <input type = 'password' name = 'password' id = 'previous_password' required>
    </p>
    </div>
    <div class = 'modal-footer'>
    <button type = 'button' class = 'btn btn-default' id = 'check_password'>Check</button>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>
    
<script src="<?=staff_asset_url?>js/StaffProfile.js"></script>

    </div>



   <?php
}

add_shortcode('staff_profile', 'staffProfile');
?>