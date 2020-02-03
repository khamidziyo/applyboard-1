<?php

function adminProfile(){
    ?>
    <div class="container-fluid" id="profile_container" style="display:none">
    <a style="float:right" id="change_password">Change Password</a>

    <form name="update_profile_form" id="update_profile_form">
    <img src="" name="image" id="image" width="200px" height="200px">
    <input type="file" name="admin_image" id="image_input">

    <p>Email: 
    <input type="email"  name="email" id="admin_email" required>
    </p>
    <p>Title: <short>Administrtator</short></p>

    <input type="submit" class="btn btn-success" id="update" name="update" value="Update">
    </form>

    <div class="modal fade" id="password_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button> 
				<h4 class="modal-title">Change Password</h4>                                                             
			</div> 
			<div class="modal-body">
			<p>Old Password: 
            <input type="password" name="password" id="password" required>
            </p>
			</div>   
			<div class="modal-footer">
            <button type="button" class="btn btn-default" id="check_password">Check</button>                               

			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                               
			</div>
		</div>                                                                       
	</div>                                          
</div>
</div>
<script src="<?=admin_asset_url."js/AdminProfile.js"?>"></script>


    <?php
}

add_shortcode('admin_profile','adminProfile');

