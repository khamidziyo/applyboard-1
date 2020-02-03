viewAgents();


function viewAgents() {
    $("#view_agent_table").DataTable({
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

