<?php

function myMessages()
{
    ?>
    <style>

/* Important part */
.modal-dialog{
    overflow-y: initial !important
}

.modal-body{
    height: 250px;
    overflow-y: auto;
}
</style>

    <span id="messages"></span>


    <div class="collapse" id="messageDiv">
    
  <div class="card card-body" id="messageContainer">

  <span id="all_messages"></span>

  <div class="chat-popup" id="chatContainer">

<form class="form-container" id="chatForm">

    <h3 id="user_name"></h3>

    <span id='previous_messages'>
    </span><br>


<div class="form-group">
<label for="msg"><b>Message</b></label>
<textarea placeholder="Type message.." class="form-control" name="message" id="msg" required></textarea>
</div>


<div class="image-upload">
<label for="file_input">
    <img src="https://goo.gl/pB9rpQ"/>
</label>

<input id="file_input" name="file_input[]" type="file" multiple/>Attach File
</div>

<button type="submit" class="btn btn-success">Send</button>
</form>
</div>

  </div>

</div>

<script src = '<?=school_asset_url?>js/message.js'></script>
<?php
}

add_shortcode('messages', 'myMessages')
?>