<?php
// echo dirname(__DIR__,1).'/server/functions.php';
if(file_exists(dirname(__DIR__,1).'/server/functions.php')){
    include_once dirname(__DIR__,1).'/server/functions.php';
}

function applicationDetail()
{

    if (!empty($_GET['a_id'])) {
        $app_id = base64_decode($_GET['a_id']);
        $data=getApplicationDetail($app_id);
        echo "<pre>";
        print_r($data);
        die;
    }
}

add_shortcode('application_detail', 'applicationDetail')
?>
