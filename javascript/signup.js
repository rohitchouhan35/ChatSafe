const form = document.querySelector(".signup form"),
continueBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-text");

form.onsubmit = (e)=>{
    e.preventDefault();
}

continueBtn.onclick = ()=>{
    console.log("continur");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/signup.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                // location.href = "users.php";

                console.log(data);
                if (data == "success") {
                    location.href = "users.php";
                } else {
                    errorText.style.display = "block";
                    errorText.textContent = data;
                }
            }
        }
    }; // Closing parenthesis added
    
    // this send form data through ajax to php
    let formData = new FormData(form);
    xhr.send(formData);
}


// A password is considered strong if the below conditions are all met:

// It has at least 6 characters and at most 20 characters.
// It contains at least one lowercase letter, at least one uppercase letter, and at least one digit.
// It does not contain three repeating characters in a row (i.e., "Baaabb0" is weak, but "Baaba0" is strong).

// use eventlistnere "keypress"
