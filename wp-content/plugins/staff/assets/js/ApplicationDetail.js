
// get the initial selected status value...
var status_val = $("#app_status").children("option:selected").val();

let searchParams = new URLSearchParams(window.location.search)
if (searchParams.has('a_id')) {
    let app_id = searchParams.get('a_id');
    // console.log(app_id);
    var data = { app_id: app_id };

} else {
    swal({
        title: "Application id is required",
        icon: 'error'
    })
}
$("#app_status").change(function () {

    var value = $(this).val();
    data.status = value;
    data.val = "updateStatus";
    updateApplicationStatus(data);
})


function updateApplicationStatus(data) {
    // console.log(data);

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
            $("#app_status").val(status_val);
        }
    })
}
