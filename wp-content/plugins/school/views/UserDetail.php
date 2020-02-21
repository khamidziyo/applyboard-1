<?php
function userDetail()
{
    ?>
    <div class="container-fluid">

    <div class = 'modal fade' id = 'message_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h4 class = 'modal-title'>Send Message</h4>
    </div>
    <div class = 'modal-body'>

    <form name="message_form" id="message_form" method="Post">

    <p>Message<br>
    <textarea name="message" id="message" rows="10" cols="40" maxlength="500" required></textarea><br>
    <span style="color:red" id="char_left"></span>
    </p>
    <input type="hidden" name="val" value="sendMessage">

    <p>Attachment <short>if any</short>
    <input type='file' name='document_input[]' class="document_input"></p>

    </div>
    <div class = 'modal-footer'>
    <input type = 'submit' class = 'btn btn-default' value="Send" id = 'send_message'>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </form>
    </div>
    </div>
    </div>
    </div>

    <span style="float:right">
    <p> Application Status</p>
        <select id="status_dropdown">
        <option selected disabled>Select Status</option>
        <option value="0">No Action Taken</option>
        <option value="1">Approve</option>
        <option value="2">Decline</option>
        </select><br>
        <input type='button' class='btn btn-primary' value="Update Status" id="update_status">
    </span>

    <label>First Name: &nbsp;&nbsp;<span id="f_name"></span></label><br>
    <label>Last Name: &nbsp;&nbsp;<span id="l_name"></span></label><br>
    <label>Gender: &nbsp;&nbsp;<span id="gender"></span></label><br>
    <label>Nationality: &nbsp;&nbsp;<span id="nationality"></span></label><br>
    <label>Date Of Birth: &nbsp;&nbsp;<span id="dob"></span></label><br>
    <label>Passport Number: &nbsp;&nbsp;<span id="passport"></span></label><br>

    <label>Proficient Language: &nbsp;&nbsp;<span id="lang_prof"></span></label><br>
    <label>Highest Qualification: &nbsp;&nbsp;<span class="qualification"></span></label><br>
    <label>Average score in <span class="qualification"></span>: &nbsp;&nbsp;<span id="score"></span></label><br>
    <label>Exam Given:</label><br>

    <span id="sub_marks"></span>

    <label>Profile Image</label><br>
    <img src="" id="image" width="200px" height="200px"><br>

    <h3>Documents Uploaded</h3>
    <span id="documents"></span>

    <input type="button" value="Contact Here" id="contact" class="btn btn-primary">
    </div>
    <script src="<?=school_asset_url?>/js/UserDetail.js"></script>
    <?php
}

add_shortcode('user_detail', 'userDetail')
?>