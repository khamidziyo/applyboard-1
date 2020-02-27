<?php

function studentProfile()
{
    ?>
    <a style = 'float:right' id = 'change_password'>Change Password</a>

    <form name="student_update_profile" id="student_update_profile">

    <div class="form-group">
      <label for="first_name">First Name:</label>
      <input type="text" class="form-control" id="first_name" placeholder="Enter first name" name="first_name" required>
    </div>

    <div class="form-group">
      <label for="last_name">Last Name:</label>
      <input type="text" class="form-control" id="last_name" placeholder="Enter last name" name="last_name" required>
    </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="stu_email" placeholder="Enter email " name="email" required>
    </div>


    <div class="form-group">
      <label for="dob">Date Of Birth:</label>
      <input type="text" class="form-control" id="dob" name="dob" required>
    </div>

    <div class="form-group">
      <label for="language">Select Language Prior</label>

      <select class="form-control" id="lang_prior" name="lang_prior" required>
        <option selected disabled>Select Language</option>
      </select>
    </div>


    <div class="form-group">
    <span id="exams"></span>
    </div>

    <div class="form-group">
    <span id="sub_marks"></span>
    </div>


    <div class="form-group">
      <label for="nationality">Select Nationality</label>

      <select class="form-control" id="nationality" name="nationality" required>
        <option selected disabled>Select Nationality</option>
      </select>
    </div>


    <div class="form-group">
      <label for="pass_number">Passport Number:</label>
      <input type="text" class="form-control" id="pass_number" name="pass_number" required>
    </div>


    <div class="form-group">
      <label for="gender">Gender:</label>
      <input type="radio" class="form-control" id="gender" name="gender" value="male" required>Male
      <input type="radio" class="form-control" id="gender" name="gender" value="female" required>Female
    </div>


    <div class="form-group">
      <label for="qualification">Select Highest Qualification</label>

      <select class="form-control" id="qualification" name="qualification" required>
        <option selected disabled>Select Highest Qualification</option>
      </select>
    </div>

    <div class="form-group">
      <label for="grade_scheme">Grade Scheme</label>

      <select class="form-control" id="grade_scheme" name="grade_scheme" required>
        <option selected disabled>Select Grade Scheme </option>
      </select>
    </div>



    <div class="form-group">
      <label for="marks">Average Marks Scored In Highest Qualification:</label>
      <input type="text" class="form-control" id="marks" placeholder="Enter marks scored in highest qualification" name="marks" required>
    </div>

    <div class="form-group">
      <label for="visa">Visa</label>

      <select class="form-control" id="visa" name="visa" required>
        <option selected disabled>Select Visa</option>
        <option value="0">No I don't have this.</option>
        <option value="1">USA F1 Visa</option>
        <option value="2">Canadian study Permit or Visitor Visa</option>
      </select>
    </div>


    <div class="form-group">
      <label for="image">Profile Image:</label><br>
      <img src="" id="image" width="200px" height="200px" style="display:none">

      <input type="hidden" name="cur_image" id="cur_image">
      <input type="file" class="form-control" id="img_input" name="img_input">
    </div>

    <div class="form-group">
    <span id="documents"></span>
    </div>

    <div class="form-group">
      <label for="documents">Documents:</label>
      <input type="file" class="form-control" class="documents" name="documents[]"><br>
      <div id="add_more"></div>

      <button type="button" class="btn btn-primary" id="add_more_btn">Add More Documents</button>
    </div>


    <input type="submit" class="btn btn-success" id="submit_btn" value="Update Profile">

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

    <script src = '<?=student_asset_url?>/js/StudentProfile.js'></script>
    <?php
}

add_shortcode('student_profile', 'studentProfile');
?>