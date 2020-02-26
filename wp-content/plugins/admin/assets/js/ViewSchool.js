$(document).ready(function () {

    viewSchools();
})

// function to view all the school using datatble plugin...
function viewSchools() {

    $("#school_table").DataTable({
        "lengthMenu": [5,10, 20, 30, 40],
        "pageLength": 5,
        "processing": true,
        "serverSide": true,
        "order":[0,'desc'],
        "language": {
            "emptyTable": "No school available"
        },

        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [2, 3, 4, 5, 6, 7, 8] }

        ],
        "ajax": ({
            url: admin_server_url + "GetSchools.php",
            data: { val: "getSchools" },
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

$(document).on('click', '.delete', function () {
    var s_id = $(this).attr('s_id');
    var data_obj = { val: "delete_school", s_id: s_id };

    swal({
        title: "Are you sure you want to delete this school",
        icon: 'warning',
        buttons: [
            'Cancel',
            'Yes I am sure'
        ]
    }).then(function (val) {
        if (val) {
            deleteSchool(data_obj);
        }
    })
})


// function to delete a particular school by admin...
function deleteSchool(data) {
    $.ajax({
        url: admin_server_url + "DeleteSchool.php",
        type: "post",
        dataType: "json",
        data: data,
        beforeSend: function (request) {

            if (!appendToken(request)) {
                adminRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {

                if (response.status == 200) {
                    swal({
                        title: response.message,
                        icon: 'success'
                    })
                    viewSchools();
                } else {
                    swal({
                        title: response.message,
                        icon: 'error',
                    })
                }
            } else {
                adminRedirectLogin();
            }
        },
        error: function (error) {
            var response = { status: 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}

// when admin clicks on view button to view the school...
$(document).on('click', '.view', function () {
    var sch_id = $(this).attr('s_id');

    window.location.href = base_url + "view-school?sch=" + sch_id;
})

// when admin clicks on edit button to edit the school...
$(document).on('click', '.edit', function () {
    var sch_id = $(this).attr('s_id');

    window.location.href = base_url + "add-school?sch=" + sch_id;
})

