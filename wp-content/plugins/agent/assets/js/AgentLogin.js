$(document).ready(function () {
})

$("#agent_login_form").submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: agent_server_url + "AgentLogin.php",
        type: "post",
        dataType: "json",
        data: $("#agent_login_form").serializeArray(),
        success: function (response) {
            if (response.status == 200) {

                // storing user data in local storage...
                localStorage.setItem('data', JSON.stringify(response.data));

                // loading the admin dashboard page...
                setTimeout(function () {
                    window.location.href = base_url + "agent-dashboard/";
                }, 1500);
            }
            
            sweetalert(response);

        }, error: function (error) {
            errorSwal();
        }
    })
})
