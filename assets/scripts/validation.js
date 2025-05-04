// Define the regular expressions for password validation
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@%^&*]).{8,}$/;

const form = document.getElementById("myForm");
const username = document.getElementById("username");
const password = document.getElementById("password");

const validation = (event) => {
    var usernameValue = username.value.trim();
    var passwordValue = password.value.trim();

    if(usernameValue.length <= 2){
        event.preventDefault();
        window.alert("username is too short!");
        username.focus();
        username.style.borderColor = "#f00";
        return false;
    }

    if(!passwordRegex.test(passwordValue)){
        event.preventDefault();
        window.alert("password is wrong");
        password.focus();
        return false;
    }

    return true;
}

form.onsubmit = validation;