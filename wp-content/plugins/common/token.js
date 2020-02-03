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
    var arr = [109, 110, 111, 112, 113, 114, 115, 117];

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