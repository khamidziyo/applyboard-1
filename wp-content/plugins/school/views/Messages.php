<?php

function myMessages()
{
    ?>
    <style>
    .sent_messages {
   max-width:50%;
   min-width:150px;
   background: lightblue;
   padding:2px;
   margin:3px;
   border-radius: 2px;
   border:1px solid black;
   float:  left;
   clear: left;
}

.receive_messages {
   max-width:50%;
   min-width:150px;
   background: #ffeec0;
   padding:2px;
   margin:3px;
   border-radius: 2px;
   border:1px solid black;
   float:  left;
   clear: left;
}

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


    <form name="message_form" id="message_form" method="post">
    <input type="file" name="documents[]" multiple>
    <input type="hidden" name="val" value="sendMessage">
    <input type="text" name="message" id="message" placeholder="Enter Your Message" required>
    <input type="submit" class="btn btn-success" value="Reply" id = 'reply'>
        </form>

    <button class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </span>

    </div>
    </div>
    </div>
    </div>



<script src = '<?=school_asset_url?>js/message.js'></script>
<?php
}

add_shortcode('messages', 'myMessages')
?>