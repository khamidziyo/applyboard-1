$(document).ready(function () {

    getAgentProfile();
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

function getAgentProfile() {
    $.ajax({
        url: agent_server_url + "AgentDashboard.php",
        type: "post",
        data: { val: "subAgentProfile" },
        dataType: "json",
        beforeSend: function (request) {
            $("#image").show();
            if (!appendToken(request)) {
                subAgentRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    $("#created_user").html(response.created_user.name);
                    var data = response.data;
                    // console.log(data)

                    if (data.name != null) {
                        $("#name").val(data.name);
                    }
                    console.log(data.email);
                    $("#email").val(data.email);

                    if (data.image != null) {
                        $("#cur_image").val(data.image);
                        $("#image").attr('src', agent_assets_url + "images/" + data.image)
                    } else {
                        $("#image").attr('src', agent_assets_url + "images/default_image.png")
                    }
                    if (data.contact_number != 0) {
                        $("#number").val(data.contact_number);
                    }
                } else {
                    sweetalert(response);
                }
            } else {
                subAgentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}

$("#change_password").click(function () {
    $("#password_modal").modal('show');
})

// when user enters the old password and click on check button...
$("#validate_old_password").submit(function (e) {
    e.preventDefault();
    var old_password=$("#password").val();

    $.ajax({
        url: agent_server_url + "UpdateSubAgentProfile.php",
        type: "post",
        dataType: "json",
        data: { password: old_password, val: "validateOldPassword" },

        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                subAgentRedirectLogin();
            }
        },

        // if success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                // if status is 200...
                if (response.status == 200) {
                    window.location.href = base_url + "change-password/?tok=" + response.data.token;
                } else {
                    sweetalert(response);
                }
            }

            //if token not verified...
            else {
                subAgentRedirectLogin();
            }
        },

        // if error response from server...
        error: function (error) {
            console.error(error);
        }
    })
})

$("#sub_agent_profile").submit(function (e) {
    e.preventDefault();
    var form = document.getElementById('sub_agent_profile');
    var form_data = new FormData(form);
    form_data.append('val', 'updateSubAgentProfile');
    $.ajax({
        url: agent_server_url + "UpdateSubAgentProfile.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                subAgentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {

                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }else{
                    sweetalert(response);
                }
            } else {
                subAgentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})