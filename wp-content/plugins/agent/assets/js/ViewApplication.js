$(document).ready(function () {
    const queryString = window.location.search;

    const urlParams = new URLSearchParams(queryString);

    var stu_id = urlParams.get('id');
    var data = { student: stu_id, 'val': 'getApplications' };

    viewApplications(data);
})

function viewApplications(data) {
    $("#view_application_table").DataTable({
        "lengthMenu": [1, 2, 3, 4],
        "pageLength": 1,
        "processing": true,
        "serverSide": true,
        "order": [0, 'desc'],
        "language": {
            "emptyTable": "No application available"
        },
        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [6, 7] }

        ],
        "ajax": ({
            url: agent_server_url + "Applications.php",
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
    });
}