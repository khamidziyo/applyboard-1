<?php
function staffProfile()
{
    ?>

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

<script src="<?=staff_asset_url?>js/StaffProfile.js"></script>

    </div>



   <?php
}

add_shortcode('staff_profile', 'staffProfile');
?>