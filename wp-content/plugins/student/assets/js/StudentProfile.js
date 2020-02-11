
var doc_html = "";

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
                console.log(response);
                // if status is 200...
                if (response.status == 200) {
                    var data = response.data;

                    var get_data = { val: 'getDataByStudent' };
                    getStudentData(get_data);

                    $("#image").show();

                    if (data.f_name != null || data.f_name != '') {
                        $("#first_name").val(data.f_name)
                    }

                    if (data.l_name != null || data.l_name != '') {
                        $("#last_name").val(data.l_name)
                    }

                    $("#stu_email").val(data.email);

                    if (data.dob != null || data.dob != '') {
                        $("#dob").val(data.dob)
                    }
                    if (data.passport_no != null || data.passport_no != '') {
                        $("#pass_number").val(data.passport_no)
                    }

                    if (data.gender == "1") {
                        $("#gender").val("male").prop('checked', true);
                    } else {
                        $("#gender").val("female").prop('checked', true);
                    }

                    if (data.score != null || data.score != '') {
                        $("#marks").val(data.score)
                    }

                    if (data.language_prior != null || data.language_prior != '') {
                        $("#lang_prior").val(data.language_prior);
                    }


                    var data = { 'val': 'getExams', 'id': data.language_prior };
                    getStudentData(data);



                    if (data.nationality != null || data.nationality != '') {
                        $("#nationality").val(data.nationality);
                    }


                    if (data.grade_id != null || data.grade_id != '') {
                        $("#qualification").val(data.grade_id);
                    }


                    if (data.has_visa != null || data.has_visa != '') {
                        $("#visa").val(data.has_visa);
                    }

                    if (data.image != null || data.image != '') {
                        $("#image").attr('src', student_assets_url + "images/" + data.image);
                    } else {
                        $("#image").attr('src', student_assets_url + "images/default_image.png");
                    }

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
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
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
        }
    })
}

$(document).on('click', '.remove_doc', function () {
    var doc_id = $(this).attr('data_id');
    console.log(doc_id);
})


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

                            // decoding the json...
                            grades.push(JSON.parse(obj.grade_scheme));

                            // loop to get all the classess extracted from json...
                            $.each(JSON.parse(obj.grade_scheme), function (grade) {
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

                // if status is 200...
                if (response.status == 200) {
                    swal({
                        title: response.message,
                        icon: 'success'
                    })
                    setTimeout(function () {
                        location.reload();
                    }, 1500);

                } else {
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            }

            //if token not verified...
            else {
                studentRedirectLogin();
            }
        },

        // if error response from server...
        error: function (error) {
            swal({
                title: 'Internal server while updating profile.',
                icon: 'error'
            })
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
                        swal({
                            title: response.message,
                            icon: 'error'
                        })
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
            }
        })
    }
})
