<?php

function schoolLogin(){
?>
<div class="container">
<form name="school_login_form" id="school_login_form">
<h2>School Login Form</h2>
<label for="email">Email:</label>
<input type="email" name="email" id="email" placeholder="Enter email" required><br>

<label for="password">Password:</label>
<input type="password" name="password" id="password" placeholder="Enter password" required><br>

<img src="<?=school_asset_url?>images/loading.gif" id="loading_gif" width="200px" height="200px" style="display:none">
<input type="submit" value="Login" id="login" class="btn btn-primary" name="login">

</form>
<script src="<?=school_asset_url?>js/SchoolLogin.js"></script>
</div>

<?php
}
add_shortcode('school_login','schoolLogin');

?>