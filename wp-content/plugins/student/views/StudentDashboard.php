<?php

// file to show the  dashboard page...

function studentDashboard() {
    ?>
    <div class = 'container'>
    <style>
    .card {
        /* Add shadows to create the 'card' effect */
        box-shadow: 0 4px 8px 0 rgba( 0, 0, 0, 0.2 );
        transition: 0.3s;
    }

    /* On mouse-over, add a deeper shadow */
    .card:hover {
        box-shadow: 0 8px 16px 0 rgba( 0, 0, 0, 0.2 );
    }

    /* Add some padding inside the card container */
    .container {
        padding: 2px 16px;
    }
    .card {
        box-shadow: 0 4px 8px 0 rgba( 0, 0, 0, 0.2 );
        transition: 0.3s;
        border-radius: 5px;
        /* 5px rounded corners */
    }

    /* Add rounded corners to the top left and the top right corner of the image */
    #c_image {
        border-radius: 5px 5px 0 0;
    }

    * {
        box-sizing: border-box;
    }

    body {
        background-color: #f1f1f1;
    }

    #eligibilityForm {
        background-color: #ffffff;
        margin: 100px auto;
        font-family: Raleway;
        padding: 40px;
        width: 70%;
        min-width: 300px;
    }

    h1 {
        text-align: center;

    }

    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
    }

    /* Hide all steps by default: */
    .tab {
        display: none;
    }

    button {
        background-color: #4CAF50;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 17px;
        font-family: Raleway;
        cursor: pointer;
    }

    button:hover {
        opacity: 0.8;
    }

    #prevBtn {
        background-color: #bbbbbb;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
        height: 15px;
        width: 350px;
        margin: 100px;
        background-color: #bbbbbb;
        border: none;

        display: inline;
        opacity: 0.5;
    }

    .step.active {
        opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #4CAF50;
    }

    </style>

    <a href="<?=base_url?>student-profile/">Profile</a><br><br>
    <a href="<?=base_url?>messages/">My Messages</a><br><br>
    <a href="<?=base_url?>student-applications/">My Applications</a><br><br>

    <div class = 'modal fade' id = 'eligibilty_modal'>
    <div class = 'modal-dialog' style = 'width:90%'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    </div>

    <div class = 'modal-body'>
    <!-- Circles which indicates the steps of the form: -->
    <div>
    <span class = 'step'>Start</span>
    <span class = 'step'>Step 1</span>
    <span class = 'step'>Step 2</span>
    <span class = 'step'>Finish</span>
    </div>
    <div class = 'progress'>
    <div class = 'progress-bar' id = 'progress_bar' aria-valuemin = '0' aria-valuemax = '100' style = 'width:0%'>
    </div>
    </div>

    <form name = 'eligibilityForm' id = 'eligibilityForm'>

    <div class = 'tab'>

    <h1>Discover programs you can apply for </br>
    Get a list of eligible programs ... in just 60 seconds</h1>

    <p><center>Start by telling us about yourself.</center></p>

    <h3>Nationality</h3>

    <select name = 'nationality' id = 'nationality' class = 'form-control' required>
    <option selected = 'selected' disabled>Select</option>
    </select><br>

    </div>

    <div class = 'tab'>

    <h2 style = 'float:left'>How were your most recent grades?</h2></br>

    <label for = 'country'>Country Of Education:</label>
    <select name = 'country' id = 'country' class = 'form-control'>
    <option selected = 'selected' disabled>Select</option>
    </select><br>

    <label for = 'grade'>Highest Education:</label>
    <select id = 'grade' name = 'grade' style="height: 50px;overflow:hidden;" class = 'form-control'>
    <option selected = 'selected' disabled>Select grade</option>
    </select>

    <label for = 'scheme'>Grading Scheme:</label>
    <select id = 'scheme' name = 'scheme' class = 'form-control'>
    <option selected = 'selected' disabled>Select Scheme</option>
    </select>

    <label for = 'education'>Grade Average:</label>
    <input type = 'text' name = 'average' id = 'average' placeholder = 'Enter grade average'>

    <label>Do you have a valid Study Permit / Visa?</label>
    <select id = 'visa' name = 'visa' class = 'form-control'>
    <option selected = 'selected' disabled>Select</option>
    <option value="0">No I don't have this.</option>
    <option value="1">USA F1 Visa</option>
    <option value="2">Canadian study Permit or Visitor Visa</option>

    </select>

    </div><br>

    <div class = 'tab'>
    <label>English Exam Type</label>
    <select id = 'exams' name = 'exams' class = 'form-control'>
    <option selected = 'selected' disabled>Select</option>
    <option value = '0'>I dont have this</option>
    </select>
    <span id="marks_html"></span>
    </div><br>

    <div class = 'tab'>
    <center>
    <h2>Last Step!</h2>
    <h3>What disciplines do you want to study?</h3>
    <p>or leave blank to see everything.</p>
    <span id = 'course_category'></span>
    </center>

    </div><br>

    <input type = 'button' id = 'prevBtn' value = 'Previous' onclick = 'nextPrev(-1)'>
    <input type = 'button'  style = 'float:right' value = 'Next' id = 'nextBtn' onclick = 'nextPrev(1)'>
    </form>

    </div>
    <div class = 'modal-footer'>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>
    </div>
        
        <span id="eligible_program_btn"></span>

    <form name = 'search_form' id = 'search_form'>
    <p>Search Program
    <input type = 'text' class = 'form-control' name = 'program' id = 'program' placeholder = 'What would you like to study' required>
    <input type = 'text' class = 'form-control' name = 'sch_name' id = 'sch_name' placeholder = 'Where? eg. school name' required>
    <input type = 'hidden' name = 'val' value = 'searchProgram'>
    <input type = 'submit' class = 'btn btn-primary form-control' value = 'Search' id = 'search'>
    </p>
    </form>

    <div id = 'course_div' style = 'display:none'>
    <div class = 'card'>
    <img src = '' id = 'c_image' alt = 'Course' style = 'width:200px;height:200px'>
    <div class = 'container'>
    <h4><b><p id = 'c_name'></p></b></h4>
    <p id = 's_name'></p>
    <input type = 'button' width = '20px' value = 'View Detail' id = 'view_detail'>

    </div>
    </div>
    </div>
    <span id = 'c_empty' style = 'display:none'>No Course Found</span>
    </div>
    <script src = '<?=student_asset_url?>/js/StudentDashboard.js'></script>
    <?php
}
add_shortcode( 'student_dashboard', 'studentDashboard' );
