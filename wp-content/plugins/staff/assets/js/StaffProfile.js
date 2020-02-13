
$(document).ready(function () {

    getStaffProfile();
})

function getStaffProfile() {

    $("#image").show();
    $.ajax({
        url: staff_server_url + "StaffProfile.php",
        type: "post",
        data: { val: 'getStaffProfile' },
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
    var form = document.getElementById('update_staff');
    var form_data = new FormData(form);

    form_data.append('val', 'updateProfile');

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