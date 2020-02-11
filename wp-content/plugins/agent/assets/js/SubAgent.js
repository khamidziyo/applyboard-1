$(document).ready(function () {

    // function that invoked when agent view the sub agents...
    viewSubAgents();
    var sub_agent_id;

})

function viewSubAgents() {
    $("#view_sub_agent").DataTable({
        "lengthMenu": [1, 2, 3, 4],
        "pageLength": 1,
        "processing": true,
        "serverSide": true,
        "order": [0, 'desc'],
        "language": {
            "emptyTable": "No agent available"
        },
        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [2, 3, 4] }
        ],
        "ajax": ({
            url: agent_server_url + "subAgents.php",
            data: { val: "getAgents" },
            dataType: "json",
            beforeSend: function (request) {
                if (!appendToken(request)) {
                    agentRedirectLogin();
                }
            }
        }),
        "initComplete": function (seting, response) {
            //Make your callback here.
            if (verifyToken(response)) {
                // console.log(response);
            } else {
                agentRedirectLogin();
            }
        }
    })
}

$(document).on('click', '.change_password', function () {
    sub_agent_id = $(this).attr('data_id');
    $("#password_modal").modal('show');
    $("#sub_agent_id").val(sub_agent_id);
})

// when agent changes the status of subagent...
$(document).on('change', '.sub_agent_status', function () {
    var id = $(this).attr('data_id');
    var status = $(this).children("option:selected").val();
    var data = { 'id': id, 'status': status, 'val': 'updateStatus' };

    switch (status) {

        case '1':
            swal({
                title: "Are you sure you want to active this user",
                icon: "warning",
                buttons: ['Cancel', 'Yes,Activate.']
            }).then(function (val) {
                if (val) {
                    updateSubaAgentStatus(data);
                } else {
                    $(this).val('2');
                }
            })
            break;

        case '2':
            swal({
                title: "Are you sure you want to deactivate the user",
                icon: "warning",
                buttons: ['Cancel', 'Yes,Deactivate.']
            }).then(function (val) {
                if (val) {
                    updateSubaAgentStatus(data);
                } else {
                    $(this).val('1');
                }
            })
            break;



        default:
            swal({
                title: "No match Found",
                icon: 'error'
            })
            break;
    }
});

function updateSubaAgentStatus(data) {
    $.ajax({
        url: agent_server_url + "UpdateSubAgentStatus.php",
        type: "post",
        data: data,
        dataType: "json",
        beforeSend: function (request) {

            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);
                if (response.status == 200) {

                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
            } else {
                agentRedirectLogin();
            }
        }, error: function (err) {
            var response = { title: "Internal Server Error", status: 400 };
            errorSwal(response);
        }
    })
    console.log(data);

}


// when user enters the old password and click on check button...
$("#validate_old_password").submit(function (e) {
    e.preventDefault();
    var old_password = $("#password").val();

    $.ajax({
        url: agent_server_url + "UpdateSubAgentPassword.php",
        type: "post",
        dataType: "json",
        data: $("#validate_old_password").serializeArray(),

        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                agentRedirectLogin();
            }
        },

        // if success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if status is 200...
                if (response.status == 200) {
                    var url = base_url + "change-password/?tok=" + response.data.token + "&& sub_id=" + btoa(window.sub_agent_id);
                    window.location.href = url;

                } else {
                    sweetalert(response);
                }
            }

            //if token not verified...
            else {
                agentRedirectLogin();
            }
        },

        // if error response from server...
        error: function (error) {
            console.error(error);
        }
    })
})

