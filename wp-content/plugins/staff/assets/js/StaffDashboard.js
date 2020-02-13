
$(document).ready(function () {
    staffDashboard();
});

function staffDashboard() {
    $.ajax({
        url: staff_server_url + "StaffDashboard.php",
        data: { val: 'staffDashboard' },
        dataType: "json",
        beforeSend: function (request) {
            if (!appendToken(request)) {
                staffRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    $("#applications").html(response.application.total_application)
                    $("#review_applications").html(response.review_application.review_application)
                    $("#application_approve").html(response.approve_application.approve_application)
                    $("#application_decline").html(response.decline_application.decline_application)
                    $("#application_pending").html(response.pending_application.pending_application)
                    // console.log(response);
                } else {
                    errorSwal(response);
                }
            } else {
                staffRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}