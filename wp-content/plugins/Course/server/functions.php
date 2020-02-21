<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

function getCourseDetailById($c_id)
{
    global $wpdb;

    $sql = "select *,c.name,type.name as type_name, category.name as category_name,language.name as
    language_name,type.id as type_id,category.id as category_id,language.id as language_id
    from courses as c join type on type.id=c.type_id join category on c.category_id=category.id join
    language on c.language_id=language.id where c.id=" . $c_id;
    $result = $wpdb->get_results($sql);
    return $result;
}

function getCourseIntake($c_id)
{
    global $wpdb;

    $sql = "select intakes.name from course_intake as c_intake left join intakes on intakes.id=c_intake.intake_id where c_intake.course_id=" . $c_id;
    $result = $wpdb->get_results($sql);
    return $result;
}

function getAllExams($language_id)
{
    global $wpdb;

    $sql = "select * from exams where language_id =" . $language_id;
    $result = $wpdb->get_results($sql);

    return $result;
}

function getExamNameById($exam_id)
{
    global $wpdb;

    $sql = "select name from exams where id=" . $exam_id;

    $data = $wpdb->get_results($sql);
    return $data;
}
