
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

$("#add_agent").submit(function (e) {
    e.preventDefault();
    $("#add_agent_btn").attr('disabled', true)

    var form = document.getElementById('add_agent');
    var form_data = new FormData(form);
    form_data.append('val', 'addAgent');

    $.ajax({
        url: agent_server_url + "AddAgent.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                adminRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                $("#add_agent_btn").attr('disabled', false)

                sweetalert(response);

                if (response.status == 200) {

                    form.reset();

                    setTimeout(function () {
                        window.location.href = base_url + "view-agents/";
                    }, 1500);
                }
            } else {
                adminRedirectLogin();
            }
        },
        error: function (error) {
            $("#add_agent_btn").attr('disabled', false)

            var response = { status: 400, message: "Internal Server Error" };
            errorSwal(response);
        }
    })
})


