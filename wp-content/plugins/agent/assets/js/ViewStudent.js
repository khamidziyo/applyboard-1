viewStudents();


function viewStudents() {
    $("#view_student_table").DataTable({
        "lengthMenu": [1, 2, 3, 4],
        "pageLength": 1,
        "processing": true,
        "serverSide": true,
        "order": [0, 'desc'],
        "language": {
            "emptyTable": "No student available"
        },
        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [6, 7] }

        ],
        "ajax": ({
            url: agent_server_url + "Students.php",
            data: { val: "getStudents" },
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


$(document).on('click', '.view_application', function () {
    var id = $(this).attr('data_id');
    window.location.href = base_url + "/view-applications?id=" + id;
})

$(document).on('click', '.edit_user', function () {
    var id = $(this).attr('data_id');
    window.location.href = base_url + "/add-student?id=" + id;
})

$(document).on('click', '.create_application', function () {
    var id = $(this).attr('data_id');

    window.location.href = base_url + "eligible-programs?id=" + id;
});






$("#application_form").submit(function (e) {
    e.preventDefault();

    $.ajax({
        url: agent_server_url + "AddApplication.php",
        type: "post",
        dataType: "json",
        data: $("#application_form").serializeArray(),

        beforeSend: function (request) {
            if (!appendToken(request)) {
                agentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);
            } else {
                agentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})