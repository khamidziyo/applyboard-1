
var search_params = new URLSearchParams(window.location.search);

// console.log(search_params);

var type;

var agent_id;
var role;


if (localStorage.getItem('data') != null) {
    var data = localStorage.getItem('data');
    var local_data = JSON.parse(data);
    role = local_data.role;
} else {
    redirect(role);
}

// when user clicks on button to change password...
$("#change_password_form").submit(function (e) {

    e.preventDefault();

    $("#reset").attr('disabled', true);

    var myform = document.getElementById('change_password_form');
    var form_data = new FormData(myform);

    form_data.append('role', role);


    switch (role) {

        // if looged in user is admin...
        case '2':

            if (search_params.has('agent_id')) {
                agent_id = search_params.get('agent_id');

                form_data.append('agent_id', agent_id);
                form_data.append('type', 'updateAgentPasswordByAdmin');
            } else {
                form_data.append('type', 'updateAdminPassword');
            }

            break;


    }

    // call to ajax to reset the password...
    $.ajax({
        url: admin_server_url + "ChangePassword.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                redirect(role);
            }
        },
        // if success ajax response...
        success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);
                $("#reset").attr('disabled', false);

                if (response.status == 200) {

                    setTimeout(function () {

                        switch (role) {

                            // if the logged in user is student...
                            case '1':
                                window.location.href = base_url + "student-dashboard/";
                                break;

                            // if the logged in user is admin...
                            case '2':
                                window.location.href = base_url + "admin-dashboard/";
                                break;

                            // if the logged in user is agent...
                            case '3':
                                window.location.href = base_url + "agent-dashboard/";
                                break;

                            // if the logged in user is sub agent...
                            case '4':
                                window.location.href = base_url + "sub-agent-dashboard/";
                                break;

                            // if the logged in user is staff...
                            case '5':
                                window.location.href = base_url + "staff-dashboard/";
                                break;

                            default:
                                var response = { status: 400, message: 'No role matches.' };
                                errorSwal(response);
                                break;
                        }
                    }, 1500);
                }

            } else {
                redirect(role);
            }
        },

        // if error ajax response...
        error: function (error) {
            $("#reset").attr('disabled', false);

            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);

        }
    })

})

