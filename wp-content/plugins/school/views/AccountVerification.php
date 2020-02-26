<?php
function accountVerification($wpdb)
{
    try {
        if (!empty($_GET['type'])) {

            switch ($_GET['type']) {

                case 'student':

                    $user_id = base64_decode($_GET['student']);
                    $token = $_GET['tok'];

                    $update_res = $wpdb->update('users', ['status' => 1, 'verify_at' => date('Y-m-d h:i:s')], ['verify_token' => $token, 'id' => $user_id]);

                    if ($update_res) {
                        ?>
                        <script>
                            swal({
                                title: "Account Verified Successfully.Please login",
                                icon: "success"
                            }).then(function(){
                                window.location = "<?=get_home_url()?>/index.php/student-login/";
                            });
                    </script>

                    <?php
} else {
                        throw new Exception('Account not verified..');
                    }
                    break;

                // case for school verification...
                case 'school':

                    $school_id = base64_decode($_GET['school']);
                    $token = $_GET['tok'];

                    $update_res = $wpdb->update('school', ['status' => 1, 'verified_at' => date('Y-m-d h:i:s')], ['verify_token' => $token, 'id' => $school_id]);
                    if ($update_res) {
                        ?>
                        <script>
                        localStorage.removeItem('data');
                            swal({
                                title: "Account Verified Successfully.Please login",
                                icon: "success"
                            }).then(function(){
                                window.location = "<?=get_home_url()?>/index.php/school-login/";
                            });
                    </script>

                    <?php
} else {
                        throw new Exception('Account not verified..');
                    }
                    break;
            }
        } else {
            throw new Exception('Unauthorized Access');
        }
    } catch (Exception $e) {
        echo "<script>swal({icon:'error',title:'" . $e->getMessage() . "'})</script>";
    }
}

add_shortcode('account_verification', function () use ($wpdb) {
    accountVerification($wpdb);
});

?>