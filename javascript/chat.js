const form = document.querySelector(".typing-area");
const inputField = form.querySelector(".input-field");
const sendBtn = form.querySelector("button");
const chatBox = document.querySelector(".chat-box");

console.log("entered chat.js");

form.onsubmit = (e) => {
  e.preventDefault();
};

inputField.onkeyup = () => {
  // Check if the input field value is not empty
  if (inputField.value.trim() !== "") {
    sendBtn.classList.add("active");
  } else {
    sendBtn.classList.remove("active");
  }
};

sendBtn.onclick = () => {
  console.log("clicked");

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/insert-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        console.log("hello");
        inputField.value = ""; // Clear the input field
        sendBtn.classList.remove("active"); // Remove the "active" class from sendBtn
      }
    }
  };

  // This sends form data through AJAX to PHP
  let formData = new FormData(form);
  xhr.send(formData);
};

setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/get-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        chatBox.innerHTML = data;
        // if(!chatBox.classList.contains("active")){
        //     scrollToBottom();
        // }
      }
    }
  };

  // This sends form data through AJAX to PHP
  let formData = new FormData(form);
  xhr.send(formData);
}, 50);
