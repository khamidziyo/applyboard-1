$(document).ready(function () {
    viewApplicationTable();
})

function viewApplicationTable() {

    $("#applications_table").DataTable({
        "lengthMenu": [5, 10, 20, 30, 40],
        "pageLength": 5,
        "order":[0,'desc'],
        "processing": true,
        "serverSide": true,
        "language": {
            "emptyTable": "No application available"
        },

        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [3, 5] }

        ],
        "ajax": ({
            url: school_server_url + "GetApplications.php",
            data: { val: "getSchoolApplications" },
            dataType: "json",

            // appending token in the request...
            beforeSend: function (request) {

                // calling function that appends the token defined in token.js file 
                // inside common directory of plugins.
                if (!appendToken(request)) {
                    redirectLogin();
                }
            }
        }),
        "initComplete": function (seting, response) {

            // calling function that verifies the token defined in token .js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {
                console.log(response);
            } else {
                redirectLogin();
            }
        }
    });
}

// when user clicks on view button to view the user...
$(document).on('click', '.view', function () {
    var id = $(this).attr('user_id');
    var app_id = $(this).attr('app_id');

    window.location.href = base_url + "user-detail?id=" + id + "&app_id=" + app_id;
})

// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = base_url + "school-login/";
    }, 2000)
}