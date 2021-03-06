

var agent_id;

$(document).ready(function () {

    var search_params = new URLSearchParams(window.location.search);
    if (search_params.has('agent_id')) {
        agent_id = search_params.get('agent_id');

        var data = { agent_id: agent_id, 'val': 'getAgentProfileByAdmin' };
        getAgentProfile(data);

    } else {
        var data = { 'val': 'getAgentProfile' };
        getAgentProfile(data);
    }
})


function getAgentProfile(data) {
    $.ajax({
        url: agent_server_url + "AgentProfile.php",
        dataType: "json",
        data: data,
        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                agentRedirectLogin();
            }
        },
        // if the response is success
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {
                if (response.status == 200) {
                    if (response.hasOwnProperty('data')) {
                        $("#image").show();
                        // console.log(response.data);
                        $("#business_name").val(response.data.business_name)
                        $("#business_email").val(response.data.business_email)
                        $("#business_address").val(response.data.business_address)
                        $("#business_phone").val(response.data.business_phone)
                        $("#business_site").val(response.data.business_website)
                        $("#image").attr('src', agent_assets_url + "images/" + response.data.image)
                        $("#bus_image").val(response.data.image);
                        $("#person_name").val(response.data.name)
                        $("#person_mail").val(response.data.email)
                        $("#person_number").val(response.data.contact_number)
                        $("#person_address").val(response.data.address)
                    }
                }
            } else {
                agentRedirectLogin();
            }
        },

        // if the response is error...
        error: function (err) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}


$("#change_password").click(function () {
    $("#password_modal").modal('show');
})

// when user enters the old password and click on check button...
$("#check_password").click(function () {
    var old_password = $("#agent_password").val();
    // alert(old_password);
    if (old_password != "") {
        $.ajax({
            url: agent_server_url + "UpdateProfile.php",
            type: "post",
            dataType: "json",
            data: { password: old_password, val: "validateOldPassword" },

            // appending token in request...
            beforeSend: function (request) {

                // calling function that appends the token defined in token.js file 
                // inside common directory of plugins.
                if (!appendToken(request)) {

                    // if the token is not in the localStorage...
                    agentRedirectLogin();
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
                    agentRedirectLogin();
                }
            },

            // if error response from server...
            error: function (error) {
                console.error(error);
            }
        })
    }
})

// when user selects the image...
$("#business_img").change(function () {

    previewImage(this);
})

// preview image function to preview the image before uploading...
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



$("#update_agent").submit(function (e) {
    e.preventDefault();
    $("#update_btn").attr('disabled', true);

    var form = document.getElementById('update_agent');
    var form_data = new FormData(form);

    // if data is not present in the local storage...
    if (localStorage.getItem('data') != null) {

        var local_data = JSON.parse(localStorage.getItem('data'));
        switch (local_data.role) {

            // if the looged in user is admin...
            case '2':
                form_data.append('agent_id', agent_id);

                form_data.append('val', 'updateProfileByAdmin');

                break;

            // if the looged in user is agent...
            case '3':
                form_data.append('val', 'updateProfileByAgent');
                break;

            default:
                swal({
                    title: "No match Found",
                    icon: 'error'
                })
                break;
        }
    } else {
        swal({
            title: "Session Expired.Please Login again",
            icon: 'error'
        })
        redirect();
    }

    $.ajax({
        url: agent_server_url + "UpdateProfile.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,
        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                agentRedirectLogin();
            }
        },
        // if the response is success
        success: function (response) {
            $("#update_btn").attr('disabled', false);
            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {
                sweetalert(response);
                if (response.status == 200) {
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
            } else {
                agentRedirectLogin();
            }
        },

        // if the response is error...
        error: function (err) {
            $("#update_btn").attr('disabled', false);
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
})


function redirect() {
    var local_data = JSON.parse(localStorage.getItem('data'));

    switch (local_data.role) {

        case '2':
            adminRedirectLogin();
            break;

        case '3':
            agentRedirectLogin();
            break;
    }
}