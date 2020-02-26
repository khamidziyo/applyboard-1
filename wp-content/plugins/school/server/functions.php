<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

function getSchoolDetailById($id)
{

    global $wpdb;
    $sql = "select s.*,s.id as s_id,s.name as sch_name,s.created_at as sch_created_at,countries.name as cntry_name,state.name as state_name,cities.name as city_name
        FROM school as s join countries on countries.id=s.countries_id join state on state.id=s.state_id
        join cities on cities.id=s.city_id where s.id=" . $id;
    $data = $wpdb->get_results($sql);
    return $data;
}

function getSchoolCertificates($school_id)
{

    global $wpdb;
    $sql = "select * from school_certificate  where school_certificate.school_id=" . $school_id;
    $data = $wpdb->get_results($sql);
    return $data;
}
