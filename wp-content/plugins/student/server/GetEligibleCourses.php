<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

// function to verify user...

function verifyUser()
{
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser($payload);
}

if (!empty($_GET)) {
    try {
        if (verifyUser()) {
            switch ($_GET['val']) {

                // case to view eligible courses...
                case 'getEligibleCourses':

                    $cmplt_eligible_arr = [];
                    $partial_eligible_arr = [];

                    $start = $_GET['start'];
                    $length = $_GET['length'];
                    $limit = 'limit ' . $start . ',' . $length;

                    $sort_arr = ['id', 'name'];

                    $order_by = 'order by ' . $sort_arr[$_GET['order'][0][column]] . ' ' . $_GET['order'][0][dir];

                    $srch_arr = ['c.id', 'c.name', 'c.code', 'type.name', 'category.name'];

                    $id = $payload->userId;

                    $category_where;

                    $interest_course_sql = 'select category_id from user_interest where user_id=' . $id;
                    $interest_course_data = $wpdb->get_results($interest_course_sql);

                    if (!empty($interest_course_data)) {
                        foreach ($interest_course_data as $key => $obj) {
                            $categories[] = $obj->category_id;
                            $category_where = ' where c.category_id in (' . implode(',', $categories) . ')';

                            if (!empty($_GET['search'][value])) {
                                $where = '&& ';
                            }
                        }
                    } else if (!empty($_GET['search'][value])) {
                        $where = 'where ';
                    }

                    if (!empty($_GET['search'][value])) {

                        $srch_val = $_GET['search'][value];
                        foreach ($srch_arr as $col_name) {
                            $where .= $col_name . " like '%" . $srch_val . "%' or ";
                        }

                        $where = substr_replace($where, '', -3);
                    }

                    $sql = "select c.id,c.name,c.code,c.exam_marks,type.name as type_name,category.name as category_name
                from courses as c join type on type.id=c.type_id join category on
                category.id=c.category_id" . $category_where;

                    // all the courses that matches with user interest in user_interest table...
                    $total_courses = $wpdb->get_results($sql . $where);

                    //
                    $sql = $sql . ' ' . $where . ' ' . $order_by . ' ' . $limit;
                    // echo $sql;die;
                    $display_courses = $wpdb->get_results($sql);

                    // query to get the exam given by user...
                    $user_exam_sql = 'select exam from users where id=' . $id;
                    $user_exam_data = $wpdb->get_results($user_exam_sql);

                    // if user has already given the exam...
                    if (!empty($user_exam_data)) {
                        $i = 0;

                        // decoding the user exam array...
                        $user_exam = json_decode($user_exam_data[0]->exam, true);

                        // loop on total courses of user interested...
                        foreach ($display_courses as $key => $obj) {

                            if (empty($obj->exam_marks)) {
                                $cmplt_eligible_arr[] = $obj;
                            }

                            // decoding the course exam array...
                            $course_exams = json_decode($obj->exam_marks, true);

                            // loop on course exam to check whether that exam is given by user...
                            foreach ($course_exams as $exam_id => $sub_arr) {
                                // $exams = $wpdb->get_results( 'select id,name from exams where id='.$exam_id );
                                // $obj->exam_name = $exams;

                                if (array_key_exists($exam_id, $user_exam)) {
                                    // echo $user_exam[$exam_id]['reading'];
                                    // echo $course_exams[$exam_id]['reading'];

                                    // subarray and marks to compare the course marks with user marks...
                                    foreach ($sub_arr as $subject => $marks) {
                                        if ($user_exam[$exam_id][$subject] >= $course_exams[$exam_id][$subject]) {

                                            // incrementing i variable to check whether in all exams user has
                                            // narks higher as defined by user...
                                            $i++;

                                        } else {
                                            $i--;
                                        }
                                    }

                                    if ($i == 4) {
                                        $cmplt_eligible_arr[] = $obj;
                                        $i = 0;
                                    } else {
                                        $partial_eligible_arr[] = $obj;
                                    }
                                    break;
                                } else {
                                    $partial_eligible_arr[] = $obj;
                                }
                                break;
                            }
                        }
                    } else {
                        // to be not eligible ...
                    }
//                     echo "<pre>";
                    //                     print_r($cmplt_eligible_arr);
                    //                     print_r($partial_eligible_arr);
                    //                      die;

                    if (!empty($cmplt_eligible_arr)) {

                        foreach ($cmplt_eligible_arr as $key => $obj) {
                            $record = [];
                            $record[] = $obj->id;
                            $record[] = $obj->name;
                            $record[] = $obj->code;
                            $record[] = $obj->type_name;
                            $record[] = $obj->category_name;
                            $applications = $wpdb->get_results("select id from applications where course_id=" . $obj->id . " && user_id=" . $payload->userId);

                            if (!empty($applications[0])) {
                                $record[] = "<input type='button' class='btn btn-success' value='Already Applied' c_id=" . base64_encode($obj->id) . ' disabled>';
                            } else {
                                $record[] = "<input type='button' class='btn btn-success apply' value='Apply' c_id=" . base64_encode($obj->id) . '>';
                            }
                            $output['aaData'][] = $record;
                        }
                    }

                    if (!empty($partial_eligible_arr)) {

                        foreach ($partial_eligible_arr as $key => $obj) {
                            $record = [];
                            $record[] = $obj->id;
                            $record[] = $obj->name;
                            $record[] = $obj->code;
                            $record[] = $obj->type_name;
                            $record[] = $obj->category_name;
                            $record[] = "<input type='button' name='not_eligible' value='Not Eligible' class='not_eligible_btn btn btn-danger' c_id=" . base64_encode($obj->id) . ">";

                            $output['aaData'][] = $record;
                        }
                    }if (empty($cmplt_eligible_arr) && empty($partial_eligible_arr)) {
                        $output['aaData'] = [];
                    }

                    $output['iTotalDisplayRecords'] = count($total_courses);
                    $output['iTotalRecords'] = count($display_courses);

                    // echo '<pre>';
                    // print_r( $output );
                    // die;

                    echo json_encode($output);
                    exit;

                    break;

                // if no case matches...
                default:
                    throw new Exception('No match found');
                    break;

            }
        }

    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'unauthorized Access'];
}
echo json_encode($response);
