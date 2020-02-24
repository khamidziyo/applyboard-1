
var sub_agent_id;

$(document).ready(function () {

    var search_params = new URLSearchParams(window.location.search);
    if (search_params.has('agent_id')) {
        var agent_id = search_params.get('agent_id');
        var data = { agent_id: agent_id, val: "getSubAgentsByAdmin" };

        viewSubAgents(data)
    } else {
        var data = { val: "getSubAgentsByAgent" };
        // function that invoked when agent view the sub agents...
        viewSubAgents(data);
    }
})

function viewSubAgents(data) {
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
            data: data,
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

var previous_status;
var this_status;

// function to get the previous status...
$(document).on('focus', '.sub_agent_status', function () {
    previous_status = $(this).val();
})

// when agent changes the status of subagent...
$(document).on('change', '.sub_agent_status', function () {
    this_status = $(this);

    var id = $(this).attr('data_id');
    var status = $(this).children("option:selected").val();

    // if data is not present in the local storage...
    if (localStorage.getItem('data') != null) {
        var local_data = JSON.parse(localStorage.getItem('data'));

        switch (local_data.role) {

            case '2':
                var data = { 'id': id, 'status': status, 'val': 'updateStatusByAdmin' };

                break;

            case '3':
                var data = { 'id': id, 'status': status, 'val': 'updateStatusByAgent' };
                break;

            default:
                swal({
                    title: "No match Found",
                    icon: 'error'
                })
                break;
        }
    } else {
        swal({
            title: "Session Expired.Please Login again",
            icon: 'error'
        })
        redirect();
    }

    var status_txt = $(this).children("option:selected").text();

    swal({
        title: "Are you sure you want to " + status_txt + " this user",
        icon: "warning",
        buttons: ['Cancel', "Yes, " + status_txt + " ."]
    }).then(function (val) {
        if (val) {
            updateSubaAgentStatus(data);
        } else {
            this_status.val(previous_status);
        }
    })
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
    // console.log(data);

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



function redirect() {
    var local_data = JSON.parse(localStorage.getItem('data'));

    switch (local_data.role) {

        case '2':
            adminRedirectLogin();
            break;

        case '3':
            agentRedirectLogin();
            break;
    }
}