<?php

function resetPassword() {
    if ( !empty( $_GET['tok']) && !empty($_GET['student'] )) {

        ?>

        <form name = 'reset_password_form' id = 'reset_password_form'>
        <p>Password
        <input type = 'password' id = 'pwd' name = 'password' required>
        </p>

        <p>Confirm Password
        <input type = 'password' id = 'con_pwd' name = 'confirm_password' required>
        </p>

        <input type = 'hidden' name = 'token' value = <?= $_GET['tok']?>>
        <input type = 'hidden' name = 'student' value = <?= $_GET['student']?>>

        <input type = 'submit' class = 'btn btn-primary' name = 'reset' value = 'Reset' id = 'reset'>

        <img src = "<?=content_url('plugins/student/assets/images/loading.gif')?>" id = 'load_img' width = '200px' height = '200px' style = 'display:none'>
        <script src = '<?=student_asset_url?>/js/ResetPassword.js'></script>
        <?php
    }else{
        ?>
        <script>
        swal({
            title:"Internal Server error.Please try again",
            icon:"error"
        })
        </script>
        <?php
    }
}

add_shortcode( 'reset_password', 'resetPassword' );
?>