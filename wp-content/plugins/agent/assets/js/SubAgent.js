$(document).ready(function () {
    viewSubAgents();
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
            { "orderable": false, "targets": [2,3,4] }
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
                console.log(response);
            } else {
                agentRedirectLogin();
            }
        }
    })
}

