

$("#login_form").submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: staff_server_url + "StaffLogin.php",
        type: "post",
        data: $("#login_form").serializeArray(),
        dataType: "json",
        success: function (response) {
            sweetalert(response);

            if (response.status == 200) {
                localStorage.setItem('data', JSON.stringify(response.data));
                setTimeout(function () {
                    window.location.href = base_url + "staff-dashboard";
                }, 1500);
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response)
        }
    })
})