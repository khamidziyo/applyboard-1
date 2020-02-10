<?php
function addStudent()
{
    ?>

<div class="container">
  <form name="add_student" id="add_student">

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
      <input type="email" class="form-control" id="email" placeholder="Enter email " name="email" required>
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
      <label for="image">Profile Image:</label>
      <img src="" id="image" width="200px" height="200px" style="display:none">
      <input type="hidden" name="cur_image" id="cur_image">
      <input type="file" class="form-control" id="img_input" name="img_input" required>
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


    <input type="submit" class="btn btn-success" id="submit_btn" value="Create Student">

    </form>
       <script src="<?=agent_asset_url?>js/AddStudent.js"></script>
    </div>
<?php
}

add_shortcode('add_student', 'addStudent');
?>