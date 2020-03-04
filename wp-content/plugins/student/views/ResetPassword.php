<?php

function resetPassword() {

    if ( !empty( $_GET['tok']) && !empty($_GET['case'] )) {
        ?>

        <form name = 'reset_password_form' id = 'reset_password_form'>
        <label>New Password</label>
        <input type = 'password' id = 'pwd' name = 'password' placeholder="Enter new password" required>

        <label>Confirm New Password</label>
        <input type = 'password' id = 'con_pwd' name = 'confirm_password' placeholder="Confirm the new password" required>

        <input type = 'hidden' name = 'token' value = <?= $_GET['tok']?>>
        <input type = 'hidden' name = 'case' value = <?= $_GET['case']?>>
        <input type = 'hidden' name = 'user' value = <?= $_GET['user']?>>


        <input type = 'submit' class = 'btn btn-primary' name = 'reset' value = 'Reset Password' id = 'reset_password_btn'>

        <script src = '<?=student_asset_url?>/js/ResetPassword.js'></script>
        <?php
    }else{
        ?>
        <script>
        swal({
            title:"Required Parameters missing in the request",
            icon:"error"
        })
        </script>
        <?php
    }
}

add_shortcode( 'reset_password', 'resetPassword' );
?>