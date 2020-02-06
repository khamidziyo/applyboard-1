<?php

function studentProfile() {
    ?>
    <a style = 'float:right' id = 'change_password'>Change Password</a>
    
    <form name="student_update_profile" id="student_update_profile">
    <div class = 'container-fluid' id = 'profile_div' style = 'display:none'>
    <img src = '' width = '200px' height = '200px' id="image">
    <input type = 'file' name = 'profile_image' id = 'profile_image'>

    <p>Email:
    <input type = 'email' name = 'email' id = 'email' required>
    </p>

    <p>Title: <short>Student</short></p>

    <input type="submit" value="Update" class="btn btn-success" id="update_profile" name="update_profile">
    </div>
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
    <input type = 'password' name = 'password' id = 'password' required>
    </p>
    </div>
    <div class = 'modal-footer'>
    <button type = 'button' class = 'btn btn-default' id = 'check_password'>Check</button>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>

    <script src = '<?=student_asset_url?>/js/StudentProfile.js'></script>
    <?php
}

add_shortcode( 'student_profile', 'studentProfile' );
?>