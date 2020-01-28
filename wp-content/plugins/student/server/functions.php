<?php


global $wpdb;

if(!isset($wpdb)){
    include_once '../../../../wp-config.php';
}

function checkMail($email){
    global $wpdb;

    $sql="select email from users where email='".$email."'";
    $result=$wpdb->get_results($sql);
    return $result;
}
?>