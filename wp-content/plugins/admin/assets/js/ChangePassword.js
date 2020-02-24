
var search_params = new URLSearchParams(window.location.search);

console.log(search_params);

var type;
var agent_id;

if (search_params.has('type')) {
    type = search_params.get('type');
    agent_id = search_params.get('agent_id');
    console.log(type);
}

// when user clicks on button to change password...
$("#change_password_form").submit(function (e) {

    e.preventDefault();
    var role;

    if (localStorage.getItem('data') != null) {
        var data = localStorage.getItem('data');
        var local_data = JSON.parse(data);
        role = local_data.role;
    }

    $("#reset").attr('disabled', true);

    var myform = document.getElementById('change_password_form');
    var form_data = new FormData(myform);

    switch (role) {

        // if looged in user is admin...
        case '2':
            switch (type) {

                // if admin updates the password of agent...
                case 'agentPassword':
                    form_data.append('type', 'updateAgentPassword');
                    form_data.append('agent_id', agent_id);
                    break;

            }

    }
    form_data.append('role', role);

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
                adminRedirectLogin();
            }
        },
        // if success ajax response...
        success: function (response) {
            if (verifyToken(response)) {
                switch (window.role) {
                    case '1':
                        break;

                    case '2':
                        break;

                    case '3':
                        window.location.href = base_url + "agent-dashboard/";
                        break;

                    case '4':
                        window.location.href = base_url + "sub-agent-dashboard/";
                        break;
                }
                $("#reset").attr('disabled', false);

                sweetalert(response);
            } else {
                adminRedirectLogin();
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

