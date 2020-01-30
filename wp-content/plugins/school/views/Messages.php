<?php

function myMessages()
{
    ?>
    <span id="messages"></span>

    <div class = 'modal fade' id = 'message_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h4 class = 'modal-title' id="sender_name"></h4>
    </div>
    <div class = 'modal-body'>
    <div id="all_messages"></div>
    </div><br><br><br><br><br>
    <div  class = 'modal-footer'>

    <span id="send_messages">
    <button class = 'btn btn-default' id = 'reply'>Reply</button>

    <button class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </span>

    </div>
    </div>
    </div>
    </div>


    <div class="container-fluid">

<div class = 'modal fade' id = 'send_message_modal'>
<div class = 'modal-dialog'>
<div class = 'modal-content'>
<div class = 'modal-header'>
<button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
</button>
<h4 class = 'modal-title'>Send Message</h4>
</div>
<div class = 'modal-body'>

<form name="message_form" id="message_form" method="Post">
<p>Subject:
<input type = 'text' name = 'subject' id = 'subject' required>
</p>
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

<script src = '<?=school_asset_url?>js/message.js'></script>
<?php
}

add_shortcode('messages', 'myMessages')
?>