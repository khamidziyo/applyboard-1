<?php

// function to view agents by admin...
function viewAgents()
{
    ?>
    <div class="container">
    <table id="view_agent_table" border="2px solid black">
    <thead>
    <th>Id</th>
    <th>Created By</th>
    <th>Name</th>
    <th>Email</th>
    <th>Contact Number</th>
    <th>Address</th>
    <th>Image</th>
    <th>Status</th>
    <th>Action</th>
    </thead>
    </table>


    <div class = 'modal fade' id = 'password_modal'>
    <div class = 'modal-dialog'>
    <div class = 'modal-content'>
    <div class = 'modal-header'>
    <button type = 'button' class = 'close' data-dismiss = 'modal'>&times;
    </button>
    <h4 class = 'modal-title'>Change Password</h4>
    </div>
    <form name="password_form" id="password_form">
    <div class = 'modal-body'>
    <p>Old Password:
    <input type = 'password' name = 'password' id = 'password' required>
    </p>
    </div>
    <div class = 'modal-footer'>
    <button type = 'submit' class = 'btn btn-default' id = 'check_password'>Check</button>
    </form>

    <button type = 'button' class = 'btn btn-default' data-dismiss = 'modal'>Close</button>
    </div>
    </div>
    </div>
    </div>


    </div>
     <script src="<?=agent_asset_url?>js/ViewAgent.js"></script>
<?php
}

add_shortcode('view_agents', 'viewAgents');
?>