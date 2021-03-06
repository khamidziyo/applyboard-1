$(document).ready(function () {
    agentDashboard();
})

function agentDashboard() {

    $.ajax({
        url: agent_server_url + "AgentDashboard.php",
        type: "post",
        data: { val: "subAgentDashboard" },
        dataType: "json",
        beforeSend: function (request) {
            if (!appendToken(request)) {
                subAgentRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    $("#students").html(response.total_students);
                    $("#applications").html(response.total_applications);
                    $("#application_approve").html(response.application_approved);
                    $("#application_decline").html(response.application_decline);
                    $("#application_pending").html(response.application_pending);
                }
                errorSwal(response);
            } else {
                subAgentRedirectLogin();
            }
        },
        error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}