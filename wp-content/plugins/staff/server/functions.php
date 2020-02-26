<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

//function to get the student and the course detail...
function getApplicationDetail($application_id)
{
    global $wpdb;
    $sql = "select a.*,a.created_at as created_on,a.status as app_status,l.name as u_l_name,u.*,u.image
     as u_image,type.name as type_name,category.name as category_name,cntry.name as cntry_name,grade.name as
     u_grade_name,c.* from applications as a join users as u on u.id=a.student_id join courses as c on
     c.id=a.course_id join language as l on l.id=u.language_prior join countries as cntry on
      cntry.id = u.nationality join grade on grade.id = u.grade_id join type on type.id=c.type_id
      join category on category.id=c.category_id where a.id=" . $application_id;

    $result = $wpdb->get_results($sql);
    return $result;
    // echo $application_id;
}

function getExams($exam_json)
{
    global $wpdb;

    $exam_mark_arr = json_decode($exam_json, true);
    foreach ($exam_mark_arr as $exam_id => $sub_arr) {
        $exam = $wpdb->get_results("select id,name from  exams where id=" . $exam_id);
        $exams[$exam[0]->name] = $sub_arr;
    }
    return $exams;
}

function getStudentDocuments($student_id)
{
    global $wpdb;

   $documents=$wpdb->get_results("select * from user_documents where user_id=".$student_id);
    return $documents;
}

function getCourseIntakes($course_id)
{
    global $wpdb;

    $sql = "select *,intakes.name from course_intake join intakes on intakes.id=course_intake.intake_id
     where course_id=" . $course_id;

    $result = $wpdb->get_results($sql);
    return $result;
}

// function to get the names of language,country,grade on basis of ids...
// function getStudentData($student_data)
// {
//     global $wpdb;

//     $language_id = $student_data->language_prior;
//     $country_id = $student_data->nationality;
//     $grade_id = $student_data->grade_id;

//     $sql = "select l.*,c.*,g.* from users as u join language as l on l.id=a.student_id join courses
//      as c on c.id=a.course_id where a.id=" . $application_id;
//     $result = $wpdb->get_results($sql);

//     return $result;
// }
