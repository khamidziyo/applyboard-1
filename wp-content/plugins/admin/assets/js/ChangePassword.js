// when user clicks on button to change password...
$("#change_password_form").submit(function(e) {

    e.preventDefault();

    $("#load_img").show();

    $("#reset").hide();

    var myform = document.getElementById('change_password_form');
    var data = new FormData(myform);

    // call to ajax to reset the password...
    $.ajax({
        url: admin_server_url + "ChangePassword.php",
        type: "post",
        data: $("#change_password_form").serializeArray(),
        dataType: "json",
        beforeSend: function(request) {
            if (!appendToken(request)) {
                adminRedirectLogin();
            }
        },
        // if success ajax response...
        success: function(response) {
            if (verifyToken(response)) {

                $("#load_img").hide();
                $("#reset").show();

                if (response.status == 200) {
                    swal({
                            title: response.message,
                            icon: "success"
                        })
                        // $("#change_password_form").get(0).reset();
                } else {
                    swal({
                        title: response.message,
                        icon: "error"
                    })
                }
            } else {
                adminRedirectLogin();
            }
        },

        // if error ajax response...
        error: function(error) {
            $("#load_img").hide();
            $("#reset").show();

            swal({
                title: "Internal Server Error",
                icon: "error"
            })
        }
    })

})

