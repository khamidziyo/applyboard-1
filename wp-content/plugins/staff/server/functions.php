<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

function getApplicationDetail($application_id)
{
    global $wpdb;
    $sql = "select a.*,u.*,c.* from applications as a join users as u on u.id=a.student_id join courses
     as c on c.id=a.course_id where a.id=" . $application_id;
    $result = $wpdb->get_results($sql);
    return $result;
    // echo $application_id;
}
