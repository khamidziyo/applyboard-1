$(document).ready(function () {
    schoolDashboard();
})

function schoolDashboard() {
    $.ajax({
        url: school_server_url + "SchoolDashboard.php",
        data: { val: "schoolDashboard" },
        dataType: "json",
        beforeSend: function (request) {
            if (!appendToken(request)) {
                schoolRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    $("#students").html(response.students.total_students);
                    $("#courses").html(response.courses.total_courses);
                    $("#applications").html(response.applications.total_application);
                    $("#application_approve").html(response.approve_application.total_app_application);
                    $("#application_decline").html(response.decline_application.total_dec_application);
                    $("#application_pending").html(response.pending_application.total_pen_application);





                }
                console.log(response);
            } else {
                schoolRedirectLogin();
            }

        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}



