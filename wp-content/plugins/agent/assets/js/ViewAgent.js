viewAllAgents();

function viewAllAgents() {
    alert();
    $("#view_agent_table").DataTable({
        "lengthMenu": [5, 10, 20, 30, 40],
        "pageLength": 5,
        "processing": true,
        "serverSide": true,
        "order": [0, 'desc'],
        "language": {
            "emptyTable": "No agent available"
        },
        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [4, 5, 6, 7] }
        ],
        "ajax": ({
            url: agent_server_url + "agents.php",
            data: { val: "getAgents" },
            dataType: "json",
            beforeSend: function (request) {
                if (!appendToken(request)) {
                    adminRedirectLogin();
                }
            }
        }),
        "initComplete": function (seting, response) {
            //Make your callback here.
            if (verifyToken(response)) {
                console.log(response);
            } else {
                adminRedirectLogin();
            }
        }
    })
}

$(document).on('click', '.view_profile', function () {
    var agent_id = $(this).attr('data_id');

    window.location.href = base_url + "agent-profile?agent_id=" + agent_id;
})

var this_status;
var previous_status;

$(document).on('focus', '.update_status', function () {
    previous_status = $(this).val();
})


$(document).on('change', '.update_status', function () {
    this_status = $(this);

    var agent_id = $(this).attr('agent_id');
    var status = $(this).val();
    var data = { agent_id: agent_id, status: status, val: 'updateAgentStatus' };

    var status_txt = $(this).children("option:selected").text();

    swal({
        title: "Are you sure you want to " + status_txt + " this user",
        icon: "warning",
        buttons: ['Cancel', 'Yes,sure']
    }).then(function (val) {
        if (val) {
            updateAgentStatus(data);
        } else {
            this_status.val(previous_status);
        }
    })
})

function updateAgentStatus(data) {


    $.ajax({
        url: admin_server_url + "UpdateAgent.php",
        type: "post",
        data: data,
        dataType: "json",
        beforeSend: function (request) {

            // if token not found in the local Storage...
            if (!appendToken(request)) {
                adminRedirectLogin();
            }
        }, success: function (response) {

            // if token verified successfully...
            if (verifyToken(response)) {
                sweetalert(response);

                if (response.status == 200) {

                    setTimeout(function () {
                        location.reload();
                    }, 1500);

                }
            } else {
                adminRedirectLogin();
            }
        }, error: function (error) {

            // if any error occurs on internal server error...
            console.error(error);
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
        }
    })
}

var agent_id;

$(document).on('click', '.change_password', function () {
    $("#password_modal").modal('show');
    agent_id = $(this).attr('data_id');
})

// when user enters the old password and click on check button...
$("#password_form").submit(function (e) {
    e.preventDefault();


    var form = document.getElementById('password_form');
    var form_data = new FormData(form);
    form_data.append('agent_id', agent_id);
    form_data.append('val', 'validateAgentOldPasswordByAdmin');

    $.ajax({
        url: agent_server_url + "UpdateProfile.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,

        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                adminRedirectLogin();
            }
        },

        // if success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if status is 200...
                if (response.status == 200) {
                    var url = base_url + "change-password/?tok=" + response.data.token + " &&agent_id=" + agent_id;
                    window.location.href = url;

                } else {
                    sweetalert(response);
                }
            }

            //if token not verified...
            else {
                adminRedirectLogin();
            }
        },

        // if error response from server...
        error: function (error) {
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
            console.error(error);
        }
    })
})