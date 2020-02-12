<?php
function staffLogin()
{
    ?>
<div class="container">

<form name="login_form" id="login_form">

<div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="stu_email" placeholder="Enter email " name="email" required>
    </div>

    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password " name="password" required>
    </div>

    <input type="submit" value="Login" class="btn btn-success" id="login_btn" name="login_btn">

    </form>

    <script src="<?=staff_asset_url?>js/StaffLogin.js"></script>

    </div>

   <?php
}

add_shortcode('staff_login', 'staffLogin')
?>