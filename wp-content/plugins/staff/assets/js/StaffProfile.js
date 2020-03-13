
var url_params = new URLSearchParams(window.location.search);

if (url_params.has('staff')) {
    var staff_id = url_params.get('staff');
    var data = { staff: staff_id, val: 'getStaffProfileByAdmin' };
    getStaffProfile(data);
} else {
    var data = { val: 'getStaffProfile' };
    getStaffProfile(data);
}
// return false;


function getStaffProfile(data) {

    $("#image").show();
    $.ajax({
        url: staff_server_url + "StaffProfile.php",
        type: "post",
        data: data,
        dataType: "json",
        beforeSend: function (request) {

            if (!appendToken(request)) {
                staffRedirectLogin();
            }
        }, success: function (response) {

            if (verifyToken(response)) {

                if (response.status == 200) {
                    $("#name").val(response.data.name);
                    $("#stu_email").val(response.data.email);
                    $("#cur_image").val(response.data.image);
                    $("#image").attr('src', staff_assets_url + 'images/' + response.data.image);
                } else {
                    errorSwal(response);
                }
            } else {
                staffRedirectLogin();
            }
        }, error: function (error) {
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
        }
    });
}


// to open the modal when user click on change password link...
$("#change_password").click(function () {
    $("#password_modal").modal('show');
})


// when user enters the old password and click on check button...
$("#check_password").click(function () {
    var old_password = $("#previous_password").val();
    if (old_password != "") {
        $.ajax({
            url: staff_server_url + "UpdateProfile.php",
            type: "post",
            dataType: "json",
            data: { password: old_password, val: "validateOldPassword" },

            // appending token in request...
            beforeSend: function (request) {

                // calling function that appends the token defined in token.js file 
                // inside common directory of plugins.
                if (!appendToken(request)) {

                    // if the token is not in the localStorage...
                    studentRedirectLogin();
                }
            },

            // if success response from server...
            success: function (response) {

                // calling function that verifies the token defined in token.js file 
                // inside common directory of plugins.
                if (verifyToken(response)) {

                    // if status is 200...
                    if (response.status == 200) {

                        // change password file in admin plugin...
                        window.location.href = base_url + "change-password/?tok=" + response.data.token;
                    } else {
                        errorSwal(response);
                    }
                }

                //if token not verified...
                else {
                    studentRedirectLogin();
                }
            },

            // if error response from server...
            error: function (error) {
                console.error(error);
                var response = { 'status': 400, 'message': 'Internal Server Error' };
                errorSwal(response);
            }
        })
    }
})


$("#img_input").change(function () {
    previewImage(this);
})

function previewImage(file_input) {
    $("#image").show();
    if (file_input.files && file_input.files[0]) {

        // creating file reader object...
        var reader = new FileReader();

        reader.onload = function (e) {

            // setting the source of document file
            $('#image').attr('src', e.target.result);
        }
        reader.readAsDataURL(file_input.files[0]);
    }
}


$("#update_staff").submit(function (e) {
    e.preventDefault();


    // 

    var form = document.getElementById('update_staff');
    var form_data = new FormData(form);


    if (url_params.has('staff')) {
        var staff_id = url_params.get('staff');
        form_data.append('val', 'updateStaffProfileByAdmin');
        form_data.append('staff_id', staff_id);

    } else {
        form_data.append('val', 'updateProfile');
    }

    $.ajax({
        url: staff_server_url + "UpdateProfile.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function (request) {
            $("#update_staff_btn").attr('disabled', true);

            if (!appendToken(request)) {
                staffRedirectLogin();
            }
        }, success: function (response) {
            $("#update_staff_btn").attr('disabled', false);
            if (verifyToken(response)) {
                sweetalert(response)
                if (response.status == 200) {
                    form.reset();

                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
            } else {

            }
        }, error: function (error) {
            $("#update_staff_btn").attr('disabled', false);
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
        }
    });

})