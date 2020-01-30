

// common function to get country state and city according to the set value by ajax...
function getDataBYAjax(data) {
    // alert(data);
    var cntry_html = "";
    var state_html = "";
    var city_html = "";

    // ajax call here...
    $.ajax({
        url: school_server_url + 'GetProfileData.php',
        type: 'get',
        dataType: 'json',
        async: false,
        beforeSend: function (request) {
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
            }
        },
        data: data,

        // if there is a success response...
        success: function (response) {
            if (verifyToken(response)) {

                // if success response with status code 200... 
                if (response.status == 200) {

                    // to set the country drop down if response has country object...
                    if (response.hasOwnProperty('countries')) {

                        $.each(response.countries, function (k, obj) {
                            cntry_html += '<option value="' + obj.id + '">' + obj.name + '</option>';
                        })

                        $("#country").html(cntry_html)
                    }

                    // to set the state drop down if response has states object...
                    if (response.hasOwnProperty('states')) {
                        state_html += '<option selected disabled>Select State</option>';

                        $.each(response.states, function (k, obj) {
                            state_html += '<option value="' + obj.id + '">' + obj.name + '</option>';
                        })

                        $("#state").html(state_html)
                    }

                    // to set the city drop down if response has cities object...
                    if (response.hasOwnProperty('cities')) {
                        city_html += '<option selected disabled>Select City</option>';

                        $.each(response.cities, function (k, obj) {
                            city_html += '<option value="' + obj.id + '" data_code="' + obj.postal_code + '">' + obj.name + '</option>';
                        })

                        $("#city").html(city_html)

                    }

                    // to set the pincode if response has pincode object...
                    if (response.hasOwnProperty('pincode')) {
                        $("#pin_code").val(response.pincode.postal_code)
                    }

                }
                // if any error response code 400...
                else {
                    // $("#" + val).html(html);
                    swal({
                        title: response.message,
                        icon: 'error'
                    })
                }
            } else {
                redirectLogin();
            }
        },

        // if not a success response...
        error: function (err) {
            console.error(err);
            swal({
                title: "Internal Server Error",
                icon: 'error'
            })
        }
    })
}

getSchoolProfile();

// function to get user profile...
function getSchoolProfile() {
    $.ajax({
        url: school_server_url + "GetSchoolProfile.php",
        type: "post",
        data: { val: "schoolProfile" },
        dataType: "json",
        // appending token in the request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {
                redirectLogin();
            }
        },
        success: function (response) {
            if (verifyToken(response)) {
                if (response.status == 200) {

                    var data = {
                        'cntry_id': response.data.countries_id,
                        'state_id': response.data.state_id, val: "getData"
                    };

                    // calling function to get the countries states and cities...
                    getDataBYAjax(data);


                    $("#profile_div").show();

                    $("#school_id").html(response.data.id);
                    $("#school_name").val(response.data.name);
                    $("#email").val(response.data.email);
                    $("#description").html(response.data.description);
                    $("#address").val(response.data.address);
                    $("#number").val(response.data.number);
                    $(".pin_code").val(response.data.postal_code);

                    $('#country').val(response.data.countries_id);
                    $('#state').val(response.data.state_id);
                    $('#city').val(response.data.city_id);

                    $('#school_type').val(response.data.type);

                    if (response.data.accomodation == 1) {
                        $("#accomodation").prop('checked', true);
                        let cost_html = "<p>Living Cost&nbsp;&nbsp;";
                        cost_html += "<input type='text' name='living_cost' value=" + response.data.living_cost + "></p>";
                        $("#living_cost").html(cost_html);
                    } else {
                        $("#accomodation").prop('checked', false);
                    }

                    if (response.data.work_studying == 1) {
                        $("#work_study").prop('checked', true);
                    } else {
                        $("#work_study").prop('checked', false);
                    }

                    if (response.data.offer_letter == 1) {
                        $("#offer_leter").prop('checked', true);
                    } else {
                        $("#offer_leter").prop('checked', false);
                    }

                    if (response.data.profile_image != null) {
                        $("#profile_image").attr('src', school_assets_url + "images/" + response.data.profile_image);
                    } else {
                        $("#profile_image").attr('src', school_assets_url + "images/default_image.png");
                    }
                    if (response.data.cover_image != null) {
                        $("#cover_image").attr('src', school_assets_url + "images/" + response.data.cover_image);
                    } else {
                        $("#cover_image").attr('src', school_assets_url + "images/default_image.png");
                    }

                    if (response.hasOwnProperty('certificates')) {
                        var certificate_arr = response.certificates;
                        var certificate_html = "";

                        if (certificate_arr.length > 0) {
                            $.each(certificate_arr, function (k, obj) {
                                certificate_html += "<img src='" + school_assets_url + "certificates/" + obj.document + "' width='200px' height='200px'><br>"
                                certificate_html += "<button type='button' class='btn btn-primary delete' data_id=" + obj.id + ">Delete</button><br>";
                            })
                            $("#certificates").html(certificate_html);
                        }

                    }

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

$("#accomodation").click(function () {
    var chk = $(this).prop('checked');
    if (chk) {
        let cost_html = "<p>Living Cost&nbsp;&nbsp;";
        cost_html += "<input type='text' name='living_cost'></p>";
        $("#living_cost").html(cost_html);
    } else {
        $("#living_cost").html('');
    }
})

// to get the states when user changes the country...
$("#country").change(function () {
    var cntry_id = $(this).val();
    var data = { 'cntry_id': cntry_id, 'val': "getStates" };

    // calling function that gets the states by country id...
    getDataBYAjax(data);
})

// to get the cities when user changes the state...
$("#state").change(function () {
    var state_id = $(this).val();
    var data = { 'state_id': state_id, 'val': "getCities" };

    // calling function that gets the cities by state id...
    getDataBYAjax(data);
})

// to get the postal code when user changes the city...
$("#city").change(function () {
    var city_id = $(this).val();
    var data = { 'city_id': city_id, 'val': "getPostalCode" };

    // calling function that gets the postal code by city id...
    getDataBYAjax(data);
})

// when user click on add certificate button to to add any more certificate...
$("#add_certificate").click(function (e) {
    e.preventDefault();

    // set the html...
    var cert_inp_html = "<p><input type='file' name='certificates[]'>&nbsp;<button class='remove'>Remove</button></p>";
    $("#new_certificate_span").append(cert_inp_html);
})

// when user clicks on remove button after clicking on add certificate button...
$(document).on('click', '.remove', function () {
    $(this).parent().remove();
})

// when user click on delete button to delete any certificate...
$(document).on('click', '.delete', function () {

    // get the certificate id...
    var cert_id = $(this).attr('data_id');

    var data = { val: "deleteCertificate", id: cert_id };

    // show warning message before deleting any certificate...
    swal({
        title: "Are you sure you want to delete this certificate",
        icon: 'warning',
        buttons: [
            'Cancel',
            'Yes Delete'
        ]
    }).then(function (val) {
        if (val) {
            deleteCertificate(data);
        }
    })
})

// function to delete the certificate...
function deleteCertificate(data) {
    $.ajax({
        url: school_server_url + "DeleteCertificate.php",
        type: "post",
        dataType: "json",
        data: data,

        // appending token in request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {

                // if the token is not in the localStorage...
                redirectLogin();
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
                redirectLogin();
            }
        },

        // if error response from server...
        error: function (error) {
            swal({
                title: "Internal Server Error",
                icon: "error"
            })
            console.error(error);
        }
    })
}

// when user changes the profile image...
$("#profile_image_input").change(function () {
    previewImage(this, 'profile_image');
})

// when user changes the profile image...
$("#cover_image_input").change(function () {
    previewImage(this, 'cover_image');
})

function previewImage(file_obj, id) {
    if (file_obj.files && file_obj.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#' + id).attr('src', e.target.result);
        }
        reader.readAsDataURL(file_obj.files[0]);
    }
}

// when user enters the old password and click on check button...
$("#check_password").click(function () {
    var old_password = $("#password").val();
    if (old_password != "") {
        $.ajax({
            url: school_server_url + "UpdateProfile.php",
            type: "post",
            dataType: "json",
            data: { password: old_password, val: "validateOldPassword" },

            // appending token in request...
            beforeSend: function (request) {

                // calling function that appends the token defined in token.js file 
                // inside common directory of plugins.
                if (!appendToken(request)) {

                    // if the token is not in the localStorage...
                    redirectLogin();
                }
            },

            // if success response from server...
            success: function (response) {

                // calling function that verifies the token defined in token.js file 
                // inside common directory of plugins.
                if (verifyToken(response)) {

                    // if status is 200...
                    if (response.status == 200) {
                        window.location.href = base_url+"change-password/?tok=" + response.data.token;
                    } else {
                        swal({
                            title: response.message,
                            icon: 'error'
                        })
                    }
                }

                //if token not verified...
                else {
                    redirectLogin();
                }
            },

            // if error response from server...
            error: function (error) {
                console.error(error);
            }
        })
    }
})


$("#school_update_profile").submit(function (e) {
    e.preventDefault();
    var form = document.getElementById('school_update_profile');

    var form_data = new FormData(form);
    form_data.append('val', 'updateProfile');

    $.ajax({
        url: school_server_url + "UpdateProfile.php",
        type: "post",
        dataType: "json",
        data: form_data,
        contentType: false,
        processData: false,
        // appending token in the request...
        beforeSend: function (request) {

            // calling function that appends the token defined in token.js file 
            // inside common directory of plugins.
            if (!appendToken(request)) {
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
                    setTimeout(function () {
                        location.reload();
                    }, 1500);

                    console.log(response);
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

// when user clicks on change password link...
$("#change_password").click(function () {
    $("#password_modal").modal('show');
})


// function that redirects to login page...
function redirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = base_url+"school-login/";
    }, 2000)
}