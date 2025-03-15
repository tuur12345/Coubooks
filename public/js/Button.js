document.addEventListener("DOMContentLoaded", function () {
    const authorInput = document.getElementById("form_author");
    const feedbackInput = document.getElementById("form_text");
    const submitButton = document.getElementById("form_save");

    function checkButton() {
        submitButton.disabled = !(authorInput.value.trim() && feedbackInput.value.trim());
    }
    authorInput.addEventListener("input", checkButton);
    feedbackInput.addEventListener("input", checkButton);
    checkButton()
});
