
var user_id;
var app_id;

$(document).ready(function () {
    var doc_html = "";

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
                    schoolRedirectLogin();
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
                        // console.log(data);
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
                        var grade = JSON.parse(data.grade_name);
                        var qualification = Object.keys(grade);

                        $(".qualification").html(qualification[0])

                        $("#score").html(data.score + " marks");
                        // $("#sub_marks").html(sub_mark_html);

                        var exam = JSON.parse(data.exam);


                        $(".exam").html(response.exam.name)

                        for (var key in response.exam) {
                            sub_mark_html += "<label>Marks scored In " + response.exam[key].name + "</label>"
                            for (subject in exam[response.exam[key].id]) {
                                sub_mark_html += "<p>" + subject + "&nbsp;&nbsp;" + exam[response.exam[key].id][subject] + " marks</p>";
                            }
                        }


                        $("#sub_marks").html(sub_mark_html);

                        $("#image").attr('src', student_assets_url + "images/" + data.image);

                        if (response.documents.length > 0) {
                            doc_html += "<ul>"
                            $.each(response.documents, function (k, obj) {
                                doc_html += "<li><a href='" + student_assets_url + "documents/" + obj.document + "' download='" + obj.document + "'>" + obj.document + "</a></li>";
                            })
                            doc_html += "</ul>"
                            $("#documents").html(doc_html);
                        } else {
                            $("#documents").html("No document uploaded<br><br>");
                        }

                    } else {
                        errorSwal(response);
                    }
                } else {
                    schoolRedirectLogin();
                }
            },
            error: function (error) {
                var response = { status: 400, message: 'Internal Server Error' };
                errorSwal(response);
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
                schoolRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);
                if (response.status == 200) {

                    setTimeout(function () {
                        location.reload();
                    }, 1500)
                }
            } else {
                schoolRedirectLogin();
            }
        },
        error: function (error) {
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
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
                schoolRedirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response)
                if (response.status == 200) {
                    document.getElementById('message_form').reset();
                    $("#message_modal").modal("hide");
                }
            } else {
                schoolRedirectLogin();
            }
        },
        error: function (error) {
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
        }
    })
}
