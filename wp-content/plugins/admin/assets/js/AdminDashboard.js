
$(document).ready(function () {
    adminDashboard();
})

function adminDashboard() {
    $.ajax({
        url: admin_server_url + "AdminDashboard.php",
        dataType: "json",
        data: { val: "adminDashboard" },
        beforeSend: function (request) {
            if (!appendToken(request)) {
                adminRedirectLogin();
            }
        },
        success: function (response) {
            // $(".loader").hide();

            if (verifyToken(response)) {
                if (response.status == 200) {
                    $("#agents").html(response.agents.total_agent);
                    $("#sub_agents").html(response.sub_agents.total_sub_agent);
                    $("#schools").html(response.schools.total_school);
                    $("#courses").html(response.courses.total_courses);
                    $("#staff").html(response.staff.total_staff);

                } else {
                    errorSwal(response)
                }
            } else {
                adminRedirectLogin();
            }
        },
        error: function (response) {
            // $(".loader").hide();

            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response)
        }
    })
}