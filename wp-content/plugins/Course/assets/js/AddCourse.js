$(document).ready(function () {
    ajaxToGetCourseData();
})


function ajaxToGetCourseData() {

    $.ajax({
        url: course_server_url + "GetCoursesType.php",
        dataType: "json",
        async: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                schoolRedirectLogin();
            }
        },
        data: { data: "getCourseTypeCategoryLanguage" },
        success: function (response) {

            if (verifyToken(response)) {

                if (response.status == 200) {
                    if (response.hasOwnProperty('c_type')) {
                        var html = "<option value='' selected disabled> Select Course Type</option>";
                        $.each(response.c_type, function (k, obj) {
                            html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        })
                        $("#course_type").html(html);
                    }

                    if (response.hasOwnProperty('c_category')) {
                        var html = "<option value='' selected disabled> Select Course Category</option>";
                        $.each(response.c_category, function (k, obj) {
                            html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        })
                        $("#course_category").html(html);
                    }

                    if (response.hasOwnProperty('c_language')) {
                        var html = "<option value='' selected disabled> Select Course Language</option>";
                        $.each(response.c_language, function (k, obj) {
                            html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        })
                        $("#language_of_instruction").html(html);
                    }

                } else {
                    errorSwal(response);
                }
            } else {
                schoolRedirectLogin();
            }

        },
        error: function (err) {
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
        }
    });
}

$("#eng_prof_test").click(function () {
    var chk = $(this).prop('checked');
    if (chk) {
        getExams();

    } else {
        $("#exams").html('');
    }
})

function getExams() {
    var lang_id = $("#language_of_instruction").find("option:selected").val();
    var html = "";

    $.ajax({
        url: course_server_url + "GetExams.php",
        type: "get",
        dataType: "json",
        data: { lang_id: lang_id, val: "getAllExams" },
        beforeSend: function (request) {

            if (!appendToken(request)) {
                schoolRedirectLogin();
            }
        },
        success: function (response) {

            if (verifyToken(response)) {

                if (response.status == 200) {
                    $.each(response.data, function (k, obj) {

                        html += "<input type='checkbox' class='english_exam_input' name=" + obj.name + " value=" + obj.id + ">" + obj.name + "<br>"
                        html += "<span id=" + obj.name + "></span>";
                    })
                    $("#exams").html(html);
                } else {
                    $("#eng_prof_test").prop('checked', false);
                    errorSwal(response);

                }
            } else {
                schoolRedirectLogin();
            }
        },
        error: function (error) {
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);
        }
    });
}

$(document).on('click', '.english_exam_input', function () {
    var chk = $(this).prop('checked');
    var exam_name = $(this).attr('name');
    if (chk) {
        var val = $(this).val();
        var exams_sub = "<br><h2>Marks scored in " + exam_name + "</h2>";

        exams_sub += "<p>Reading:</p><p><input type='text' name=exams[" + val + "][reading] class=" + exam_name + "></p>";
        exams_sub += "<p>Writing:</p><p><input type='text' name=exams[" + val + "][writing] class=" + exam_name + "></p>";
        exams_sub += "<p>Listening:</p><p><input type='text' name=exams[" + val + "][listening] class=" + exam_name + "></p>";
        exams_sub += "<p>Speaking:</p><p><input type='text' name=exams[" + val + "][speaking] class=" + exam_name + "></p>";

        $("#" + exam_name).html(exams_sub);
    } else {

        $("#" + exam_name).html('');
    }

});


$("#image_input").change(function () {
    $("#image").show();
    if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    }
})


$("#course_form").submit(function (e) {
    e.preventDefault();


    $("#loading_gif").show();
    $(".submit_course").hide();

    // getting the object of course form...
    var form = document.getElementById("course_form");

    // creating the instance of formdata...
    var form_data = new FormData(form);

    $.ajax({
        url: course_server_url + "AddCourse.php",
        type: "post",
        dataType: "json",
        data: form_data,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                schoolRedirectLogin();
            }

        },
        contentType: false,
        processData: false,
        success: function (response) {
            $("#loading_gif").hide();
            $(".submit_course").show();

            if (verifyToken(response)) {
                sweetalert(response);

                if (response.status == 200) {

                    setTimeout(function () {
                        window.location.href = base_url + "view-all-course/";
                    }, 1500);

                }
            } else {
                schoolRedirectLogin();
            }
        },
        error: function (error) {
            $("#loading_gif").hide();
            $(".submit_course").show();
            var response = { status: 400, message: 'Internal Server Error' };
            errorSwal(response);

        }
    })
});