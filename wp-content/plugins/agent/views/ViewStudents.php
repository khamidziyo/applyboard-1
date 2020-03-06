<?php

function viewStudents()
{
    ?>

<style>
/* Full-width textarea */
.form-container textarea {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 30px;
}

.image-upload > input
{
    display: none;
}

.image-upload img
{
    width: 80px;
    cursor: pointer;
}
</style>

<div class = 'modal fade' id = 'chat_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h4 class = 'modal-title'>Send Message</h4>
    </div>
    <div class = 'modal-body'>
    <form class="form-container" id="chatForm">

    <h3 id="user_name"></h3>

    <span id='previous_messages'></span><br>

        <label for="msg"><b>Message</b></label>
        <textarea placeholder="Type message.." name="message" required></textarea>

        <div class="image-upload">
        <label for="file_input">
        <img src="https://goo.gl/pB9rpQ"/>
        </label>

        <input id="file_input" name="file_input[]" type="file" multiple/>Attach File
        </div>

        <button type="submit" class="btn btn-success">Send</button>
        </form>

    </div>
    <div class = 'modal-footer'>
    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>


   <table id="view_student_table" border="2px solid black">
   <thead>
   <th>Id</th>
   <th>First Name</th>
   <th>Last Name</th>
   <th>Email</th>
   <th>Date Of Birth</th>
   <th>Nationality</th>
   <th>Gender</th>
   <th>Total Applications</th>
   <th>Image</th>
   <th>Action</th>
   <th>Chat</th>


   </thead>
   </table>

   <script src="<?=agent_asset_url?>js/ViewStudent.js"></script>
   <?php
}

add_shortcode('view_students', 'viewStudents')
?>
