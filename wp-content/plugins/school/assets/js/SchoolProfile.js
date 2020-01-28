getSchoolProfile();

// function to get user profile...
function getSchoolProfile() {
    $.ajax({
        url: school_server_url + "GetSchoolProfile.php",
        type: "post",
        data: { val: "schoolProfile" },
        dataType: "json",
        // appending token in the request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {
                redirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {

                    $("#profile_div").show();

                    $("#school_id").html(response.data.id);
                    $("#school_name").val(response.data.name);
                    $("#email").val(response.data.email);
                    $("#description").html(response.data.description);
                    $("#address").val(response.data.address);
                    $("#number").val(response.data.number);
                    $("#pin_code").val(response.data.postal_code);

                    if (response.data.profile_image != null) {
                        $("#profile_image").attr('src', school_assets_url + "images/" + response.data.profile_image);
                    } else {
                        $("#profile_image").attr('src', school_assets_url + "images/default_image.png");
                    }
                    if (response.data.cover_image != null) {
                        $("#cover_image").attr('src', school_assets_url + "images/" + response.data.cover_image);
                    } else {
                        $("#cover_image").attr('src', school_assets_url + "images/default_image.png");
                    }

                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            } else {
                redirectLogin();
            }
        },
        error: function (error) {
            console.error(error);
            swal({
                title: "Internal Server Error",
                icon: 'error'
            })
        }
    })
}

// when user changes the profile image...
$("#profile_image_input").change(function () {
    previewImage(this, 'profile_image');
})

// when user changes the profile image...
$("#cover_image_input").change(function () {
    previewImage(this, 'cover_image');
})

function previewImage(file_obj, id) {
    if (file_obj.files && file_obj.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#' + id).attr('src', e.target.result);
        }
        reader.readAsDataURL(file_obj.files[0]);
    }
}

// when user enters the old password and click on check button...
$("#check_password").click(function () {
    var old_password = $("#password").val();
    if (old_password != "") {
        $.ajax({
            url: school_server_url + "UpdateProfile.php",
            type: "post",
            dataType: "json",
            data: { password: old_password, val: "validateOldPassword" },

            // appending token in request...
            beforeSend: function (request) {

                // calling function that appends the token defined in token.js file 
                // inside common directory of plugins.
                if (!appendToken(request)) {

                    // if the token is not in the localStorage...
                    redirectLogin();
                }
            },

            // if success response from server...
            success: function (response) {

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
            error: function (error) {
                console.error(error);
            }
        })
    }
})


$("#school_update_profile").submit(function (e) {
    e.preventDefault();
    var form = document.getElementById('school_update_profile');

    var form_data = new FormData(form);
    form_data.append('val', 'updateProfile');

    $.ajax({
        url: school_server_url + "UpdateProfile.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,
        // appending token in the request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {
                redirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    console.log(response);
                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            } else {
                redirectLogin();
            }
        },
        error: function (error) {
            console.error(error);
            swal({
                title: "Internal Server Error",
                icon: 'error'
            })
        }
    })
})

// when user clicks on change password link...
$("#change_password").click(function () {
    $("#password_modal").modal('show');
})


// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = "http://localhost/wordpress/wordpress/index.php/school-login/";
    }, 2000)
}