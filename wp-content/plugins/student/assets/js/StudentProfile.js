// function that loads when script is loaded..
$(document).ready(function() {
    getUserProfile();
})

// function to get the user profile...
function getUserProfile() {
    $.ajax({
        url: student_server_url + "GetStudentprofile.php",
        type: "get",
        dataType: "json",
        data: { val: "getProfile" },

        // appending token in request...
        beforeSend: function(request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },

        // if success response from server...
        success: function(response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if status is 200...
                if (response.status == 200) {
                    $("#profile_div").show();
                    if (response.data.image != null) {
                        $("#image").attr('src', student_assets_url + "images/" + response.data.image);
                    } else {
                        $("#image").attr('src', student_assets_url + "images/default_image.png");

                    }
                    $("#email").val(response.data.email);
                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            }

            //if token not verified...
            else {
                redirectLogin();
            }
        },

        // if error response from server...
        error: function(error) {
            console.error(error);
        }
    })
}

// when user selects the profile image...
$("#profile_image").change(function(e) {
    e.preventDefault();
    if (this.files && this.files[0]) {

        // creating file reader object...
        var reader = new FileReader();

        reader.onload = function(e) {

            // setting the profile image...
            $('#image').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    }
});

// when user clicks on update profile button to update profile...
$("#student_update_profile").submit(function(e) {
    e.preventDefault();

    var form = document.getElementById('student_update_profile');
    var form_data = new FormData(form);
    form_data.append('val', 'updateProfile');

    $.ajax({
        url: student_server_url + "UpdateProfile.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,
        // appending token in request...
        beforeSend: function(request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },

        // if success response from server...
        success: function(response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if status is 200...
                if (response.status == 200) {
                    swal({
                        title: response.message,
                        icon: 'success'
                    })
                    setTimeout(function() {
                        location.reload();
                    }, 1500);

                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            }

            //if token not verified...
            else {
                redirectLogin();
            }
        },

        // if error response from server...
        error: function(error) {
            swal({
                title: 'Internal server while updating profile.',
                icon: 'error'
            })
            console.error(error);
        }
    })
})


// to open the modal when user click on change password link...
$("#change_password").click(function() {
    $("#password_modal").modal('show');
})

// when user enters the old password and click on check button...
$("#check_password").click(function() {
    var old_password = $("#password").val();
    if (old_password != "") {
        $.ajax({
            url: student_server_url + "UpdateProfile.php",
            type: "post",
            dataType: "json",
            data: { password: old_password, val: "validateOldPassword" },

            // appending token in request...
            beforeSend: function(request) {

                // calling function that appends the token defined in token.js file 
                // inside common directory of plugins.
                if (!appendToken(request)) {

                    // if the token is not in the localStorage...
                    redirectLogin();
                }
            },

            // if success response from server...
            success: function(response) {

                // calling function that verifies the token defined in token.js file 
                // inside common directory of plugins.
                if (verifyToken(response)) {

                    // if status is 200...
                    if (response.status == 200) {
                        window.location.href = "http://localhost/wordpress/wordpress/index.php/change-password/?tok=" + response.data.token;
                    } else {
                        swal({
                            title: response.message,
                            icon: 'error'
                        })
                    }
                }

                //if token not verified...
                else {
                    redirectLogin();
                }
            },

            // if error response from server...
            error: function(error) {
                console.error(error);
            }
        })
    }
})

// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function() {
        window.location.href = "http://localhost/wordpress/wordpress/index.php/student-login/";
    }, 1500)
}