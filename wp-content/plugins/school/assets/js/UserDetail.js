
var user_id;
var app_id;

$(document).ready(function () {
    let searchParams = new URLSearchParams(window.location.search);
    if (searchParams.has('id')) {
        if (searchParams.has('app_id')) {
            app_id = searchParams.get('app_id')
        }
        user_id = searchParams.get('id');
        $.ajax({
            url: school_server_url + "GetUserDetail.php",
            type: "get",
            dataType: "json",
            data: { val: "getUserDetail", id: user_id, app_id: app_id },
            beforeSend: function (req) {
                // calling function that appends the token defined in token.js file 
                // inside common directory of plugins.
                if (!appendToken(req)) {
                    redirectLogin();
                }
            },
            success: function (response) {

                // calling function that verifies the token defined in token .js file 
                // inside common directory of plugins.
                if (verifyToken(response)) {
                    if (response.status == 200) {
                        var data = response.user;
                        var sub_mark_html = "";
                        // console.log(response.application.status);
                        $('#status_dropdown').val(response.application.status).attr("selected", "selected");
                        console.log(data);
                        $("#f_name").html(data.f_name)
                        $("#l_name").html(data.l_name)
                        if (data.gender == '1') {
                            $("#gender").html('Male')
                        } else {
                            $("#gender").html('Female')
                        }
                        $("#nationality").html(data.cntry_name)
                        $("#dob").html(data.dob)
                        $("#passport").html(data.passport_no);
                        $("#lang_prof").html(data.lang_name);
                        var grade = JSON.parse(data.grade_scheme);
                        var qualification = Object.keys(grade);
                        $(".qualification").html(qualification[0])
                        $("#score").html(data.score + " marks");
                        var exam = JSON.parse(data.exam, true);
                        $(".exam").html(response.exam.name)

                        for (var key in exam) {
                            for (subject in exam[key]) {
                                sub_mark_html += "<p>" + subject + "&nbsp;&nbsp;" + exam[key][subject] + " marks</p>";
                            }
                        }
                        $("#sub_marks").html(sub_mark_html);

                        $("#image").attr('src', student_assets_url + "images/" + data.image);
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
                swal({
                    title: "Internal Server Error",
                    icon: 'error'
                })
                console.error(error);
            }
        })
    }
})

$("#update_status").click(function () {
    var id = window.app_id;
    var status = $("#status_dropdown").val();

    var data = { id: id, val: "updateStatus", status: status };
    $.ajax({
        url: school_server_url + "UpdateApplication.php",
        type: "post",
        dataType: "json",
        data: data,

        // function to append the token in the request...
        beforeSend: function (req) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(req)) {
                redirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    swal({
                        title: response.message,
                        icon: 'success'
                    })
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


$("#contact").click(function () {
    $("#message_modal").modal("show");
});

$("#message").keyup(function () {
    var len = this.value.length;
    if (len >= 500) {
        this.value = this.value.substring(0, 500);
    } else {
        $('#char_left').html(500 - len + " characters left");
    }
})

$("#message_form").submit(function (e) {
    e.preventDefault();
    var user = window.user_id;
    var form = document.getElementById('message_form');
    var form_data = new FormData(form);

    form_data.append('id', user);

    sendMessage(form_data);
});

// function to send the message...
function sendMessage(data) {
    $.ajax({
        url: school_server_url + "SendMessage.php",
        type: "post",
        dataType: "json",
        data: data,
        contentType: false,
        processData: false,

        // function to append the token in the request...
        beforeSend: function (req) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(req)) {
                redirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    swal({
                        title: response.message,
                        icon: 'success'
                    })
                    document.getElementById('message_form').reset();
                    $("#message_modal").modal("hide");
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

// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = "http://localhost/wordpress/wordpress/index.php/school-login/";
    }, 2000)
}