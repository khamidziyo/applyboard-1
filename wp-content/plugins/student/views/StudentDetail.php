<?php
function studentDetail()
{
    ?>
    <div class="container">
    <p>First Name: <b id='first_name'></b></p>

    <p>Last Name: <b id="last_name"></b></p>

    <p>Email: <b id="stu_email"></b></p>

    <p>Date of Birth: <b id="dob"></b></p>

    <p>Gender: <b id="gender"></b></p>

    <p>Visa: <b id="visa"></b></p>


    <p>Passport Number: <b id="passport"></b></p>


    <p>Nationality: <b id="nationality"></b></p>

    <p>Highest Qualification: <b id="qualification"></b></p>

    <p>Grade Scheme: <b id="grade_scheme"></b></p>

    <p>Marks Scored In Highest Qualification: <b id="marks_score"></b></p>

    <p>Intake <b id="stu_intake"></b></p>


    <p>Language Prior: <b id="language"></b></p>

    <h3>Exams Given:</h3>
     <span id="exams"></span>
    
    <h3>Profile Image</h3>
    <img src="" id="profile_image" width="200px" height="200px">
    
    <h3>Documents Uploaded</h3>
    <span id="documents"></span>

    </div>
    <script src = '<?=student_asset_url?>/js/StudentDetail.js'></script>
    <?php
}
add_shortcode('student_detail', 'studentDetail')
?>
