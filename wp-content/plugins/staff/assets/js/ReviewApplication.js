viewReviewedApplication();
// })

// function to get the applications assigned to staff...
function viewReviewedApplication() {

    $("#reviewed_application").DataTable({
        "lengthMenu": [10, 20, 30, 40],
        "pageLength": 10,
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
            url: staff_server_url + "GetApplications.php",
            data: { val: 'getReviewApplications' },
            dataType: "json",
            beforeSend: function (request) {
                if (!appendToken(request)) {
                    staffRedirectLogin();
                }
            }
        }),
        "initComplete": function (seting, response) {
            //Make your callback here.
            if (verifyToken(response)) {
                // console.log(response);
            } else {
                staffRedirectLogin();
            }
        }
    });
}


$(document).on('change', '.update_status', function () {
    var status_val = $(this).val();
    var app_id = $(this).attr('app_id');
    var data = { app_id: app_id, status: status_val, val: "updateStatus" };

    swal({
        title: "Are you sure you want to update the application status",
        icon: 'warning',
        buttons: ['Cancel', 'Yes,I am sure']
    }).then(function (val) {
        if (val) {
            updateApplicationStatus(data);
        }
    })
})

function updateApplicationStatus(data) {
    $.ajax({
        url: staff_server_url + "UpdateApplication.php",
        type: "post",
        data: data,
        dataType: "json",
        beforeSend: function (request) {

            if (!appendToken(request)) {
                staffRedirectLogin();
            }
        }, success: function (response) {
            
            if (verifyToken(response)) {
                sweetalert(response);

            } else {
                staffRedirectLogin();
            }
        }, error: function (error) {
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
        }
    })
    console.log(data);
}
