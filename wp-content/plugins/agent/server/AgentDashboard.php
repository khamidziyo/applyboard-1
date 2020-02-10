<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function agentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Agent::verifyUser($payload);
}

function subAgentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return SubAgent::verifyUser($payload);
}

if (!empty($_POST['val'])) {

    switch ($_POST['val']) {

        case 'agentProfile':

            if (agentVerifyUser()) {
                $id = $payload->userId;

                $sql = "select id,email,image,status from agents where id=" . $id . " && role='3'";

                $user = $wpdb->get_results($sql);

                if (!empty($user)) {

                    switch ($user[0]->status) {

                        // is an active user...
                        case '1':
                            $response = ['status' => Success_Code, 'message' => "Profile fetched Successfully", 'data' => $user[0]];
                            break;

                        // is account deactivated...
                        case '2':
                            $response = ['status' => Account_Deactive, 'message' => "Account Deactivated by admin.Please contact to site admin"];
                            echo json_encode($response);
                            exit;
                            break;

                    }
                } else {
                    throw new Exception("No user found");
                }
            }
            break;

        case 'subAgentProfile':

            if (subAgentVerifyUser()) {
                $id = $payload->userId;

                $sql = "select id,name,created_by,email,contact_number,status,image from agents where id=" . $id . " && role='4'";

                $user = $wpdb->get_results($sql);

                $created_user = $wpdb->get_results("select name from agents where id=" . $user[0]->created_by);

                if (!empty($user)) {

                    switch ($user[0]->status) {

                        // is an active user...
                        case '1':
                            $response = ['status' => Success_Code,
                                'message' => "Profile fetched Successfully",
                                'data' => $user[0], 'created_user' => $created_user[0]];

                            break;

                        // is account deactivated...
                        case '2':
                            $response = ['status' => Account_Deactive, 'message' => "Account Deactivated by admin.Please contact to site admin"];
                            echo json_encode($response);
                            exit;
                            break;

                    }
                } else {
                    throw new Exception("No user found");
                }

            }
            break;

        case 'agentDashboard':
            if (agentVerifyUser()) {
                $id = $payload->userId;
                $agents = $wpdb->get_results("select id from agents where created_by=" . $id);

                $agent_id[] = $id;
                foreach ($agents as $key => $obj) {
                    $agent_id[] = $obj->id;
                }
                $agent_ids = implode(",", $agent_id);

                $student_sql = "select count(id) as total_students from users where role='1' && agent_id
                 in (" . $agent_ids . ")";

                $total_students = $wpdb->get_results($student_sql);

                $students = $total_students[0]->total_students;

                $application_sql = "select count(id) as total_application from applications where
                agent_id in (" . $agent_ids . ")";

                $total_application = $wpdb->get_results($application_sql);

                $applications = $total_application[0]->total_application;

                $app_application_sql = "select count(id) as approve_application from applications where
                status='1' && agent_id in (" . $agent_ids . ")";

                $app_application = $wpdb->get_results($app_application_sql);

                $approved_application = $app_application[0]->approve_application;

                $dec_application_sql = "select count(id) as decline_application from applications where
                status='2' && agent_id in (" . $agent_ids . ")";

                $dec_application = $wpdb->get_results($dec_application_sql);
                $declined_application = $dec_application[0]->decline_application;

                $pen_application_sql = "select count(id) as pending_application from applications where
                status='0' && agent_id in (" . $agent_ids . ")";

                $pen_application = $wpdb->get_results($pen_application_sql);
                $pending_application = $pen_application[0]->pending_application;

                $sub_agents = $wpdb->get_results("select count(id) as sub_agents from agents where created_by=" . $id);
                $subagents = $sub_agents[0]->sub_agents;

                $response = ['status' => Success_Code, 'message' => 'All data fetched successfully',
                    'total_students' => $students, 'total_applications' => $applications,
                    'total_subagents' => $subagents, 'application_approved' => $approved_application,
                    'application_decline' => $declined_application, 'application_pending' => $pending_application];
            }
            break;

        case 'subAgentDashboard':
            if (subAgentVerifyUser()) {
                $id = $payload->userId;

                $user = $wpdb->get_results("select created_by,permission from agents where id=" . $id);
                $access_level = $user[0]->permission;

                $user_id = $id;
                if ($access_level) {
                    $agent_id = $user[0]->created_by;
                    $where = "agent_id in (" . $user_id . "," . $agent_id . ")";
                } else {
                    $where = "agent_id=" . $user_id;
                }

                $total_students = $wpdb->get_results("select count(id) as total_students from users where role='1' && " . $where);
                $students = $total_students[0]->total_students;

                $total_application = $wpdb->get_results("select count(id) as total_application from applications where " . $where);
                $applications = $total_application[0]->total_application;

                $app_application = $wpdb->get_results("select count(id) as approve_application from applications where status='1' && " . $where);
                $approved_application = $app_application[0]->approve_application;

                $dec_application = $wpdb->get_results("select count(id) as decline_application from applications where status='2' && " . $where);
                $declined_application = $dec_application[0]->decline_application;

                $pen_application = $wpdb->get_results("select count(id) as pending_application from applications where status='0' && " . $where);
                $pending_application = $pen_application[0]->pending_application;

                $response = ['status' => Success_Code, 'message' => 'All data fetched successfully',
                    'total_students' => $students, 'total_applications' => $applications, 'application_approved' => $approved_application,
                    'application_decline' => $declined_application, 'application_pending' => $pending_application];
            }
            break;

        default:
            throw new Exception("No match Found");
            break;
    }
} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is required"];
}
echo json_encode($response);
