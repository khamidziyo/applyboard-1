<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function verifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Agent::verifyUser($payload);
}

if (!empty($_POST['val'])) {

    if (verifyUser()) {
        switch ($_POST['val']) {

            case 'agentProfile':

                $id = $payload->userId;

                $sql = "select id,email,image from agents where id=" . $id . " && role='3'";

                $user = $wpdb->get_results($sql);

                if (!empty($user)) {
                    $response = ['status' => Success_Code, 'message' => "Profile fetched Successfully", 'data' => $user[0]];
                } else {
                    throw new Exception("No user found");
                }
                break;

            case 'adminDashboard':
                $id = $payload->userId;

                $total_students = $wpdb->get_results("select count(id) as total_students from users where role='1' && agent_id=" . $id);
                $students = $total_students[0]->total_students;

                $total_application = $wpdb->get_results("select count(id) as total_application from applications where agent_id=" . $id);
                $applications = $total_application[0]->total_application;

                $app_application = $wpdb->get_results("select count(id) as approve_application from applications where status='1' && agent_id=" . $id);
                $approved_application = $app_application[0]->approve_application;

                $dec_application = $wpdb->get_results("select count(id) as decline_application from applications where status='2' && agent_id=" . $id);
                $declined_application = $dec_application[0]->decline_application;

                $pen_application = $wpdb->get_results("select count(id) as pending_application from applications where status='0' && agent_id=" . $id);
                $pending_application = $pen_application[0]->pending_application;

                $sub_agents = $wpdb->get_results("select count(id) as sub_agents from agents where created_by=" . $id);
                $subagents = $sub_agents[0]->sub_agents;

                $response = ['status' => Success_Code, 'message' => 'All data fetched successfully',
                    'total_students' => $students, 'total_applications' => $applications,
                    'total_subagents' => $subagents, 'application_approved' => $approved_application,
                    'application_decline' => $declined_application, 'application_pending' => $pending_application];

                break;

            default:
                throw new Exception("No match Found");
                break;
        }
    }
} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is required"];
}
echo json_encode($response);
