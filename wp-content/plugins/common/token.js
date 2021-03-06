// appending the token in every api request...
function appendToken(req) {
    // get the token from local storage...
    if (localStorage.getItem('data') != null) {

        // parsing the stringify to object...
        var data = JSON.parse(localStorage.getItem('data'));
        // set headers...
        req.setRequestHeader("Authorization", "Bearer " + data.token);
        return true;
    } else {
        swal({
            title: "Unauthorized Access",
            icon: "error"
        });
        return false;

    }
}


// function called there is any error from server regarding token...
function verifyToken(response) {
    $(".loader").hide();

    // 120 code if usera ccount deactivated...
    var arr = [109, 110, 111, 112, 113, 114, 115, 117, 120];

    // if response.status matches with the array...
    if ($.inArray(response.status, arr) != -1) {
        swal({
            title: response.message,
            icon: 'error'
        })

        return false;
    }

    // if all good then return true...
    else {
        return true;
    }
}

function redirect(role) {

    switch (role) {

        // if a logged in user is student...
        case '1':
            studentRedirectLogin();
            break;

        // if a logged in user is admin...
        case '2':
            adminRedirectLogin();
            break;

        // if a logged in user is agent...
        case '3':
            agentRedirectLogin();
            break;

        // if a logged in user is sub agent...
        case '4':
            subAgentRedirectLogin();
            break;

        // if a logged in user is staff...
        case '5':
            staffRedirectLogin();
            break;
    }
}

function adminRedirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = base_url + "admin-login/";
    }, 1000);
}

function agentRedirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = base_url + "agent-login/";
    }, 1000);
}

function subAgentRedirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = base_url + "sub-agent-login/";
    }, 1000);
}


// function that redirects to login page...
function studentRedirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = base_url + "student-login/";
    }, 2000)
}

function schoolRedirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = base_url + "school-login/";
    }, 1000);
}

function staffRedirectLogin() {
    localStorage.removeItem('data');
    setTimeout(function () {
        window.location.href = base_url + "staff-login/";
    }, 1000);
}

