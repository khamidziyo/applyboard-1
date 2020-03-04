<?php
function subAgentProfile()
{
    ?>

    <div class="container">

    <form name="sub_agent_profile" id="sub_agent_profile">
    <a id="change_password" style="float:right">Change Password</a><br><br>

    <div class="form-group" style="float:right">
    <label for="created_user">Created By :</label>
    <span id="created_user"></span>
  </div>


    <div class="form-group">
    <label for="person_name">Name:</label>
    <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required >
  </div>

  <div class="form-group">
    <label for="u_mail">Email:</label>
    <input type="email" class="form-control" id="u_mail" placeholder="Enter email" name="email" required >
  </div>

  <div class="form-group">
    <label for="person_name">Image:</label>
    <input type="hidden" name="cur_image" id="cur_image">

    <img src="" width="200px" height="200px" id="image" style="display:none">
    <input type="file" class="form-control" id="img_input" name="img_input" >
  </div>

  <div class="form-group">
    <label for="person_name">Contact Number:</label>
    <input type="number" class="form-control" id="number" name="number" required>
  </div>

    <input type="submit" value="Update Profile" id ="update_btn" class="btn btn-success">
    </form>

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

  <script src="<?=agent_asset_url?>js/SubAgentProfile.js"></script>

  <?php
}

add_shortcode('sub_agent_profile', 'subAgentProfile');

