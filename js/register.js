document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    // Construct the form data array
    var formData = {};
    formData['email'] = document.getElementById('email').value;
    formData['password'] = document.getElementById('password').value;

    // Display form validation errors
    var validationErrors = validateForm();
    if (validationErrors.length > 0) {
        displayError(validationErrors);
        return;
    } else {
        clearError();
    }

    // Send form data to PHP for processing
    submitForm(formData);
});

// Function to validate the form
function validateForm() {
    var errors = [];
    // Check password complexity
    var password = document.getElementById('password').value;
    if (password && !isPasswordValid(password)) {
        errors.push("Password must contain at least 6 characters including letters, numbers, and special characters");
    }

    return errors;
}

// Function to display error message
function displayError(errors) {
    var errorSpan = document.getElementById('passwordError');
    errorSpan.textContent = errors.join(', ');
    errorSpan.style.display = 'block';
}

// Function to clear error message
function clearError() {
    var errorSpan = document.getElementById('passwordError');
    errorSpan.textContent = '';
    errorSpan.style.display = 'none';
}

// Function to validate password complexity
function isPasswordValid(password) {
    // Password must contain at least one letter, one number, one special character, and have a minimum length of 6
    var regex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[.@$!%*?&])[A-Za-z\d.@$!%*?&]{6,}$/;
    return regex.test(password);
}

function submitForm(formData) {
    // Send form data to server
    $.ajax({    
        type: "POST",
        url: "http://localhost/nutrientandhealth/mediplus-lite/register.php",
        data: formData,
        success: function(response) {
            // Handle success
            console.log("Form submitted successfully!");
            console.log("Response from server: ", response);
            window.location.href = '#contact'; // Redirect to login page
        },
        error: function(xhr, status, error) {
            // Handle errors
            console.error("Error:", error);
        }
    });
}

