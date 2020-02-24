// $(document).ready(function () {
viewStudentApplication();
// })

// function to get the applications assigned to staff...
function viewStudentApplication() {

    $("#student_application").DataTable({
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
            data: { val: 'getStudentApplications' },
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

// when staff clicks on the mark review button...

$(document).on('click', '.mark_review', function () {
    $(this).attr('disabled', true);

    var app_id = $(this).attr('data_id');
    var data = { app_id: app_id, val: 'markApplicationReview' };

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
            $(this).attr('disabled', false);

            if (verifyToken(response)) {
                if (response.status == 200) {
                    $(this).attr('class', 'btn btn-success');
                    sweetalert(response);

                    setTimeout(function () {
                        window.location.href = base_url + "review-applications";
                    }, 1500);
                }
                // console.log(response);
            } else {
                staffRedirectLogin();
            }
        }, error: function (error) {
            $(this).attr('disabled', false);
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})

var previous_status;
var application_id;
var this_status;

$(document).on('focus', '.update_status', function () {
    previous_status = this.value;
    application_id = $(this).attr('app_id');

})

$(document).on('change', '.update_status', function () {
    var status = $(this).children("option:selected").val();
    this_status = $(this);

    var data = { status: status, app_id: application_id, val: 'updateStatus' };

    swal({
        title: "Are you sure you want to update the application status",
        icon: "warning",
        buttons: ['Cancel', 'Yes,Update']
    }).then(function (val) {
        if (val) {

            $.ajax({
                url: staff_server_url + "UpdateApplication.php",
                type: "post",
                data: data,
                dataType: "json",
                beforeSend: function (request) {

                    // if token not found in the local Storage...
                    if (!appendToken(request)) {
                        staffRedirectLogin();
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
                        staffRedirectLogin();
                    }
                }, error: function (error) {

                    // if any error occurs on internal server error...
                    console.error(error);
                    var response = { status: 400, message: 'Internal Server Error' };
                    errorSwal(response);
                }
            })
        } else {
            this_status.val(previous_status);
        }
    })

});   
