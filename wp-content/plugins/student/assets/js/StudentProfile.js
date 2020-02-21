
var doc_html = "";
var sub_marks_html = "";

$("#dob").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: "1980:2014"
});

getUserProfile();

// function to get the user profile...
function getUserProfile() {
    $.ajax({
        url: student_server_url + "GetStudentprofile.php",
        type: "get",
        dataType: "json",
        async: false,
        data: { val: "getProfile" },

        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                studentRedirectLogin();
            }
        },

        // if success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {
                // console.log(response);

                // if status is 200...
                if (response.status == 200) {
                    var data = response.data;

                    // calling function to get languages countries and grades...
                    var get_data = { val: 'getDataByStudent' };
                    getStudentData(get_data);

                    $("#image").show();

                    if (data.f_name != null || data.f_name == '') {
                        $("#first_name").val(data.f_name)
                    }

                    if (data.l_name != null || data.l_name == '') {
                        $("#last_name").val(data.l_name)
                    }

                    $("#stu_email").val(data.email);

                    if (data.dob != null || data.dob == '') {
                        $("#dob").val(data.dob)
                    }
                    if (data.passport_no != null || data.passport_no == '') {
                        $("#pass_number").val(data.passport_no)
                    }

                    if (data.gender == "1") {
                        $("#gender").val("male").prop('checked', true);
                    } else {
                        $("#gender").val("female").prop('checked', true);
                    }

                    if (data.score != null || data.score == '') {
                        $("#marks").val(data.score)
                    }

                    // console.log(data.language_prior);

                    if (data.language_prior != null || data.language_prior == '') {
                        $("#lang_prior").val(data.language_prior);
                        // alert();
                        var get_exam_data = { 'val': 'getExams', 'id': data.language_prior };
                        getStudentData(get_exam_data);
                    }



                    if (data.exam != null) {
                        // decoding json to get the exams...
                        var exams = JSON.parse(data.exam);
                        // console.log(exams);

                        var sub_arr = Object.values(exams);
                        // console.log(sub_arr);

                        // loop over subject array to get the exams and marks stored in array...
                        $.each(sub_arr, function (k, obj) {

                            var exam_id = Object.keys(exams)[k];

                            $(".exam_input[value=" + exam_id + "]").prop("checked", "true");

                            var exam_name = $(".exam_input[value=" + exam_id + "]").attr('name');

                            sub_marks_html += "<span id='" + exam_name + "'><h2>Marks Scored In " + exam_name + " </h2>";

                            for (arr_key in Object.keys(obj)) {

                                // to get the subject... 
                                sub_marks_html += "<label>" + Object.keys(obj)[arr_key] + "</label>&nbsp;&nbsp;&nbsp"

                                // to get the marks...
                                sub_marks_html += "<input type='text' name='exams[" + exam_id + "][" + Object.keys(obj)[arr_key] + "]' value=" + Object.values(obj)[arr_key] + "><br><br>"
                            }
                            sub_marks_html += "</span>"
                        })

                        $("#sub_marks").html(sub_marks_html);
                    }


                    if (data.nationality != null || data.nationality == '') {
                        $("#nationality").val(data.nationality);
                    }

                    // console.log(data.grade_id);
                    if (data.grade_id != null || data.grade_id == '') {
                        $("#qualification").val(data.grade_id);

                        var grade_scheme_data = { 'val': 'getGradeScheme', 'id': data.grade_id };

                        // calling function to get grades...
                        getStudentData(grade_scheme_data);

                        $("#grade_scheme").val(data.grade_scheme);
                    }


                    if (data.has_visa != null || data.has_visa == '') {
                        $("#visa").val(data.has_visa);
                    }

                    if (data.image.startsWith("https")) {
                        $("#image").attr('src', data.image);
                    } else if (data.image != null || data.image == '') {
                        $("#image").attr('src', student_assets_url + "images/" + data.image);
                        $("#cur_image").val(data.image);
                    } else {
                        $("#image").attr('src', student_assets_url + "images/default_image.png");
                    }

                    console.log(response.documents);

                    if (response.documents.length > 0) {
                        // console.log(response.documents);
                        $.each(response.documents, function (k, obj) {
                            doc_html += "<li>" + obj.document + "<button type='button' class='btn btn-danger remove_doc'";
                            doc_html += "data_id=" + obj.id + ">Remove</button></li><br>";
                        })
                        doc_html += "</ul>";
                        $("#documents").html(doc_html);
                    }
                } else {
                    errorSwal(response);
                }
            }

            //if token not verified...
            else {
                studentRedirectLogin();
            }
        },

        // if error response from server...
        error: function (error) {
            console.error(error);
            var response = { status: 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
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

$("#qualification").change(function () {

    var grade_id = $(this).val();

    var data = { 'val': 'getGradeScheme', 'id': grade_id };

    getStudentData(data);
})

// when user changes the language based on language exam will appear...
$("#lang_prior").change(function () {
    var lang_id = $(this).val();
    $("#exams").html('');
    $("#sub_marks").html('');

    var data = { 'val': 'getExams', 'id': lang_id };

    // calling function to get exams of specific language...
    getStudentData(data);
})

$("#add_more_btn").click(function () {
    var html = "<span><input type='file' class='form-control' class='documents' name='documents[]'><br>";
    html += "<input type='button' name='remove' Value='Remove' class='remove'></span>"
    $("#add_more").append(html);
})
$(document).on('click', '.remove', function () {
    $(this).parent().remove();
})

$(document).on('click', '.exam_input', function () {
    var exam = $(this).attr('name');

    var exam_id = $(this).val();

    if ($(this).prop('checked')) {
        // when user selects the exam...
        var marks_html = "";

        // then defining the text fields to enter marks in different subjects...
        marks_html += "<span id='" + exam + "'><h2>Marks Scored In " + exam + "</h2>"
        marks_html += "<p>Reading&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][reading] id='reading'></p>";
        marks_html += "<p>Writing&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][writing] id='writing'></p>";
        marks_html += "<p>Listening&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][listening] id='listening'></p>";
        marks_html += "<p>Speaking&nbsp;&nbsp;<input type='text' name=exams[" + exam_id + "][speaking] id='speaking'></p>";
        marks_html += "</span>";
        // setting the marks html...
        $("#sub_marks").append(marks_html);
    } else {
        $("#" + exam).remove();
    }

})


$(document).on('click', '.remove_doc', function () {
    var doc_id = $(this).attr('data_id');
    swal({
        title: "Are you sure you want to remove this document",
        icon: "warning",
        buttons: ['Cancel', 'Yes,remove it'],
    }).then(function (val) {
        if (val) {
            removeDoc(doc_id);
        }
    })
})

function removeDoc(doc_id) {

    var data = { doc_id: doc_id, val: 'removeDocument' };

    $.ajax({
        url: student_server_url + "RemoveDocument.php",
        type: "post",
        data: data,
        dataType: "json",
        beforeSend: function (request) {
            if (!appendToken(request)) {
                studentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                sweetalert(response);

                if (response.status == 200) {
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
            } else {
                studentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}


// function to get nationality,language and highest qualification...
function getStudentData(data) {
    var cntry_html = "";
    var grade_html = "";
    var language_html = "";
    var exam_html = "";
    var grade_scheme_html = "";
    var grades = [];

    $.ajax({
        url: student_server_url + "GetData.php",
        dataType: "json",
        data: data,
        async: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {
                studentRedirectLogin();
            }
        }, success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {
                    if (response.hasOwnProperty('cntry_data')) {
                        // country html...
                        cntry_html += "<option selected='selected' disabled>Select Country</option>";

                        // each loop to display countries in drop down...
                        $.each(response.cntry_data, function (k, obj) {
                            cntry_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });

                        // displaying countries in the dropdown...
                        $("#nationality").html(cntry_html);
                    }

                    if (response.hasOwnProperty('grade')) {
                        grade_html += "<option selected='selected' disabled>Select Grade</option>";

                        // each loop to dynamically display all classes in drop down...
                        $.each(response.grade, function (k, obj) {

                            // loop to get all the classess extracted from json...
                            $.each(JSON.parse(obj.name), function (grade) {
                                grade_html += "<option value=" + obj.id + ">" + grade + "</option>";
                            })

                            //set the html of grade drop down...
                            $("#qualification").html(grade_html);
                        });
                    }

                    if (response.hasOwnProperty('languages')) {

                        // language html...
                        language_html += "<option selected='selected' disabled>Select</option>";

                        // each loop to display countries in drop down...
                        $.each(response.languages, function (k, obj) {
                            language_html += "<option value=" + obj.id + ">" + obj.name + "</option>";
                        });

                        // displaying countries in the dropdown...
                        $("#lang_prior").html(language_html);
                    }


                    if (response.hasOwnProperty('exam_data')) {

                        // each loop to display exams as radio buttons...
                        $.each(response.exam_data, function (k, obj) {
                            exam_html += "<input type='checkbox' class='exam_input' value='" + obj.id + "' name='" + obj.name + "'>" + obj.name + "<br>";
                        });

                        // displaying exams in the ...
                        $("#exams").html(exam_html);
                    }

                    if (response.hasOwnProperty('grade_data')) {

                        // each loop to display grade schemes in drop down...
                        grade_scheme_html += "<option selected disabled>Select Grade Scheme</option>";
                        $.each(response.grade_data, function (grade, scheme_arr) {
                            $.each(scheme_arr, function (k, scheme) {
                                grade_scheme_html += "<option value='" + scheme + "'>" + scheme + "</option>";
                            })
                        });
                        $("#grade_scheme").html(grade_scheme_html);
                    }

                }
                errorSwal(response);

            } else {
                studentRedirectLogin();
            }
        }, error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
        }
    })
}


// when user selects the profile image...
$("#profile_image").change(function (e) {
    e.preventDefault();
    if (this.files && this.files[0]) {

        // creating file reader object...
        var reader = new FileReader();

        reader.onload = function (e) {

            // setting the profile image...
            $('#image').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    }
});

// when user clicks on update profile button to update profile...
$("#student_update_profile").submit(function (e) {
    e.preventDefault();

    var form = document.getElementById('student_update_profile');
    var form_data = new FormData(form);
    form_data.append('val', 'updateProfile');

    $.ajax({
        url: student_server_url + "UpdateProfile.php",
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
                studentRedirectLogin();
            }
        },

        // if success response from server...
        success: function (response) {

            // calling function that verifies the token defined in token.js file 
            // inside common directory of plugins.
            if (verifyToken(response)) {

                sweetalert(response);

                // if status is 200...
                if (response.status == 200) {

                    setTimeout(function () {
                        location.reload();
                    }, 1500);

                }
            }

            //if token not verified...
            else {
                studentRedirectLogin();
            }
        },

        // if error response from server...
        error: function (error) {
            var response = { 'status': 400, 'message': 'Internal Server Error' };
            errorSwal(response);
            console.error(error);
        }
    })
})


// to open the modal when user click on change password link...
$("#change_password").click(function () {
    $("#password_modal").modal('show');
})

// when user enters the old password and click on check button...
$("#check_password").click(function () {
    var old_password = $("#password").val();
    if (old_password != "") {
        $.ajax({
            url: student_server_url + "UpdateProfile.php",
            type: "post",
            dataType: "json",
            data: { password: old_password, val: "validateOldPassword" },

            // appending token in request...
            beforeSend: function (request) {

                // calling function that appends the token defined in token.js file 
                // inside common directory of plugins.
                if (!appendToken(request)) {

                    // if the token is not in the localStorage...
                    studentRedirectLogin();
                }
            },

            // if success response from server...
            success: function (response) {

                // calling function that verifies the token defined in token.js file 
                // inside common directory of plugins.
                if (verifyToken(response)) {

                    // if status is 200...
                    if (response.status == 200) {

                        // change password file in admin plugin...
                        window.location.href = base_url + "change-password/?tok=" + response.data.token;
                    } else {
                        errorSwal(response);
                    }
                }

                //if token not verified...
                else {
                    studentRedirectLogin();
                }
            },

            // if error response from server...
            error: function (error) {
                console.error(error);
                var response = { 'status': 400, 'message': 'Internal Server Error' };
                errorSwal(response);
            }
        })
    }
})
