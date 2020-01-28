<?php

function eligibleProgram()
{
    ?>
<table id="eligible_course_table" border="2px solidblack">
<thead>
<th>Id</th>
<th>Name</th>
<th>Code</th>
<th>Type</th>
<th>Category</th>
<th>Action</th>

</thead>
</table>


<div class = 'modal fade' id = 'profile_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h4 class = 'modal-title'>Update Profile</h4>
    </div>
    <div class = 'modal-body'>

    <form name="user_profile" id="user_profile">
    <p>First Name:
    <input type = 'text' name = 'f_name' id = 'f_name' required>
    </p>

    <p>Last Name:
    <input type = 'text' name = 'l_name' id = 'l_name' required>
    </p>

    <p>Date Of Birth:
    <input type = 'text' name = 'dob' id = 'dob' required>
    </p>

    <p>Passport Number: <input type = 'text' name = 'passport' id = 'passport' required></p>

    <p>Language Prior:
    <select class="form-group" name="lang_prior" id="lang_prior" required>
    <option selected disabled>Select Your Prior Language</option>
    </select></p>


    <p>Choose Gender</p>
    <p><input type = 'radio' name = 'gender' id = 'gender' value="1">Male
    <input type = 'radio' name = 'gender' id = 'gender' value="2">Female</p>

    <img src="" width="200px" height="200px" id="image" style="display:none" required> 
    <input type='file' name='image' id="image_input">


    </div>
    <div class = 'modal-footer'>
    <input type = 'submit' class = 'btn btn-primary' value="Update" id = 'update'>
    </form>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>

<script src="<?=student_asset_url?>/js/eligibleCourse.js"></script>
<?php
}

add_shortcode('eligible_program', 'eligibleProgram');
?>