
viewStaffMembers();

function viewStaffMembers() {

    $("#staff_table").DataTable({
        "lengthMenu": [5, 10, 20, 30, 40],
        "pageLength": 5,
        "processing": true,
        "serverSide": true,
        "order": [0, 'desc'],
        "language": {
            "emptyTable": "No staff available"
        },

        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [3, 4, 5, 6] }

        ],
        "ajax": ({
            url: staff_server_url + "GetStaffMember.php",
            data: { val: "getStaffMemberByAdmin" },
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

$(document).on('focus', '.update_status', function () {
    previous_status = $(this).val();
})


$(document).on('change', '.update_status', function () {
    this_status = $(this);

    var staff_id = $(this).attr('staff_id');
    var status = $(this).val();
    var data = { staff_id: staff_id, status: status, val: 'updateStaffStatusByAdmin' };

    var status_txt = $(this).children("option:selected").text();

    swal({
        title: "Are you sure you want to " + status_txt + " this user",
        icon: "warning",
        buttons: ['Cancel', 'Yes,sure']
    }).then(function (val) {
        if (val) {
            updateStaffStatus(data);
        } else {
            this_status.val(previous_status);
        }
    })
})

function updateStaffStatus(data) {

    $.ajax({
        url: admin_server_url + "UpdateStaff.php",
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

$(document).on('click', '.view_profile', function () {
    var staff_id = $(this).attr('s_id');
    window.location.href = base_url + "staff-profile?staff=" + staff_id;
})