
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

$("#add_staff").submit(function (e) {
    e.preventDefault();
    var form = document.getElementById('add_staff');
    var form_data = new FormData(form);
    form_data.append('val', 'addStaff');

    $.ajax({
        url: admin_server_url + "AddStaff.php",
        type: "post",
        data: form_data,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                adminRedirectLogin();
            }
        },
        success: function (response) {

            if (verifyToken(response)) {
                sweetalert(response);

                if (response.status == 200) {
                    form.reset();
                    setTimeout(function () {
                        location.reload();
                    }, 1500);

                }
            } else {
                adminRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response)
        }
    })
})