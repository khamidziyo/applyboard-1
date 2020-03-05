$(".loader").hide();

if (localStorage.getItem('data') != null) {

    swal({
        title: "You are already logged in.",
        icon: 'warning'
    })
    
    setTimeout(function () {
        window.location.href = base_url + "staff-dashboard";
    }, 1000);
}


$("#login_form").submit(function (e) {
    e.preventDefault();
    $("#login_btn").attr('disabled', true);

    $.ajax({
        url: staff_server_url + "StaffLogin.php",
        type: "post",
        data: $("#login_form").serializeArray(),
        dataType: "json",
        success: function (response) {
            sweetalert(response);
            $("#login_btn").attr('disabled', false);

            if (response.status == 200) {
                localStorage.setItem('data', JSON.stringify(response.data));
                setTimeout(function () {
                    window.location.href = base_url + "staff-dashboard";
                }, 1500);
            }
        }, error: function (error) {
            $("#login_btn").attr('disabled', false);

            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response)
        }
    })
})