function showError(message) {
    const errorContainer = document.getElementById('error-message');
    if (errorContainer) {
        errorContainer.textContent = message;
        errorContainer.style.display = 'block';
    } else {
        alert(message);
    }
}

function clearError() {
    const errorContainer = document.getElementById('error-message');
    if (errorContainer) {
        errorContainer.textContent = '';
        errorContainer.style.display = 'none';
    }
}

function validateLoginForm(event) {
    clearError();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    if (!email || !password) {
        showError('Please fill in all fields.');
        event.preventDefault();
        return false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showError('Please enter a valid email address.');
        event.preventDefault();
        return false;
    }

    return true;
}

function validateRegisterForm(event) {
    clearError();
    const email = document.getElementById('email').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (!email || !username || !password || !confirmPassword) {
        showError('All fields are required.');
        event.preventDefault();
        return false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showError('Please enter a valid email address.');
        event.preventDefault();
        return false;
    }

    if (username.length < 5) {
        showError('Username must be at least 5 characters long.');
        event.preventDefault();
        return false;
    }

    if (password.length < 8) {
        showError('Password must be at least 8 characters long.');
        event.preventDefault();
        return false;
    }

    if (password !== confirmPassword) {
        showError('Passwords do not match.');
        event.preventDefault();
        return false;
    }

    return true;
}

function validateTodoForm(event) {
    clearError();
    const title = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const dueDate = document.getElementById('due_date').value;

    if (!title || !description || !dueDate) {
        showError('Title, Description, and Due Date are required.');
        event.preventDefault();
        return false;
    }

    return true;
}