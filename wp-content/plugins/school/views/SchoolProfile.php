<?php
function schoolProfile()
{
    ?>
     <a style = 'float:right' id = 'change_password'>Change Password</a>
    <form name="school_update_profile" id="school_update_profile">
    <div class = 'container-fluid' id = 'profile_div' style = 'display:none'>

    <label>School Id:&nbsp;&nbsp;<span id="school_id"></span></label><br>

    <p>School Name:&nbsp;&nbsp;<input type="text" name="school_name" id="school_name" required></p>

    <p>Email:&nbsp;&nbsp;<input type = 'email' name = 'email' id = 'email' required></p>

    <label>Description:</label></br><textarea name="description" id="description" required></textarea>


    <p>Address:&nbsp;&nbsp;<input type="text" name="address" id="address" required></p>

    <p>Contact Number:&nbsp;&nbsp;<input type="text" name="number" id="number" required></p>

    <p>Pin-Code:&nbsp;&nbsp;<input type="text" name="pin_code" id="pin_code" disabled></p>

    <label>Profile Image</label><br>
    <img src = '' width = '200px' height = '200px' id="profile_image">
    <input type = 'file' name = 'profile_image_input' id = 'profile_image_input'>

    <label>Cover Image</label><br>
    <img src = '' width = '200px' height = '200px' id="cover_image">
    <input type = 'file' name = 'cover_image_input' id = 'cover_image_input'>

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

    <script src = '<?=school_asset_url?>/js/SchoolProfile.js'></script>
    <?php
}

add_shortcode('school_profile', 'schoolProfile')
?>