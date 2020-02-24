<?php
function viewSubAgents()
{
    ?>
    <div class="container">
    <table id="view_sub_agent" border="2px solid black">
    <thead>
    <th>Id</th>
    <th>Email</th>
    <th>Status</th>
    <th>Created On</th>
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
    <form name="validate_old_password" id="validate_old_password">
    <div class = 'modal-body'>
    <p>Old Password:
    <input type = 'password' name = 'password' id = 'password' required>
    <input type="hidden" name="sub_agent_id"  id="sub_agent_id">
    <input type="hidden" name="val"  value="validateOldPassword">


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
<script src="<?=agent_asset_url?>js/SubAgent.js"></script>
<?php
}

add_shortcode('view_sub_agents', 'viewSubAgents');
