// when user clicks on button to change password...
$("#reset_password_form").submit(function(e) {

    e.preventDefault();

    $("#load_img").show();

    $("#reset").hide();

    var myform = document.getElementById('reset_password_form');
    var data = new FormData(myform);

    // call to ajax to reset the password...
    $.ajax({
        url: student_server_url + "ResetPassword.php",
        type: "post",
        data: $("#reset_password_form").serializeArray(),
        dataType: "json",

        // if success ajax response...
        success: function(response) {
            $("#load_img").hide();
            $("#reset").show();

            if (response.status == 200) {
                swal({
                    title: response.message,
                    icon: "success"
                })
                $("#reset_password_form").get(0).reset();
            } else {
                swal({
                    title: response.message,
                    icon: "error"
                })
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