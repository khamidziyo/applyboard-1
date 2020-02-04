$(document).ready(function () {
    agentDashboard();
})

function agentDashboard() {

    $.ajax({
        url: agent_server_url + "AgentDashboard.php",
        type: "post",
        data: { val: "adminDashboard" },
        dataType: "json",
        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    $("#students").html(response.total_students);
                    $("#applications").html(response.total_applications);
                    $("#sub_agents").html(response.total_subagents);
                    $("#application_approve").html(response.application_approved);
                    $("#application_decline").html(response.application_decline);
                    $("#application_pending").html(response.application_pending);


                }
                errorSwal(response);
            } else {
                agentRedirectLogin();
            }
        },
        error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}

$("#create_sublogin").click(function () {
    $("#sub_login_modal").modal('show');
})

// when agent creates the profile of sub agent...
$("#sub_agent_form").submit(function (e) {
    e.preventDefault();

    var form = document.getElementById('sub_agent_form');
    var form_data = new FormData(form);
    $.ajax({
        url: agent_server_url + "AddSubAgent.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    $("#sub_login_modal").modal('hide');

                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
                sweetalert(response);
            } else {
                agentRedirectLogin();
            }
        },
        error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})