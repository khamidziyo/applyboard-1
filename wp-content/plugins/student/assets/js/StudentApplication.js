
viewApplications();

function viewApplications() {

    $("#student_application").DataTable({
        "lengthMenu": [1, 2, 3, 4],
        "pageLength": 1,
        "processing": true,
        "serverSide": true,
        "language": {
            "emptyTable": "No application available"
        },

        "destroy": true,
        "columnDefs": [
            // { targets: '_all', visible: true },
            { "orderable": false, "targets": [3,5] }

        ],
        "ajax": ({
            url: student_server_url + "GetApplications.php",
            data: { val: "getStudentApplications" },
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
            if (verifyToken(response)) { } else {
                redirectLogin();
            }
        }
    });

}

// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function() {
        window.location.href = "http://localhost/wordpress/wordpress/index.php/student-login/";
    }, 2000)
}
