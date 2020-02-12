<?php
function addStaff()
{
    ?>
<div class="container">
  <form name="add_staff" id="add_staff">

    <div class="form-group">
      <label for="name"> Name:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required>
    </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="stu_email" placeholder="Enter email " name="email" required>
    </div>

    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password " name="password" required>
    </div>

    <div class="form-group">
      <label for="confirm_password">Confirm Password:</label>
      <input type="password" class="form-control" id="confirm_password" placeholder="Enter password " name="confirm_password" required>
    </div>
    

    <div class="form-group">
      <label for="image">Image:</label><br>
      <img src="" id="image" width="200px" height="200px" style="display:none">
      <input type="file" class="form-control" id="img_input" name="img_input">
    </div>

    <input type="submit" value="Add Staff" class="btn btn-success" id="add_staff_btn" name="add_staff_btn">

</form>
<script src="<?=admin_asset_url?>js/AddStaff.js"></script>

    </div>

<?php
}

add_shortcode('add_staff', 'addStaff');
?>