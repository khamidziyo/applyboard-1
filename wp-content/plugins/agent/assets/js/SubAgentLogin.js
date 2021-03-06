$(document).ready(function () {
})

$("#sub_agent_login").submit(function (e) {
    e.preventDefault();
    $("#sign_in_btn").attr('disabled', true);

    $.ajax({
        url: agent_server_url + "SubAgentLogin.php",
        type: "post",
        dataType: "json",
        data: $("#sub_agent_login").serializeArray(),
        success: function (response) {
            $("#sign_in_btn").attr('disabled', false);

            if (response.status == 200) {

                // storing user data in local storage...
                localStorage.setItem('data', JSON.stringify(response.data));
                // loading the admin dashboard page...
                setTimeout(function () {
                    window.location.href = base_url + "sub-agent-dashboard/";
                }, 1500);
            }

            sweetalert(response);

        }, error: function (error) {
            $("#sign_in_btn").attr('disabled', false);

            var response = { 'status': 400, 'message': 'Internal server error' };
            errorSwal(response);
        }
    })
})