// DermAve IMS - login page behavior

const form = document.getElementById('loginForm');
const username = document.getElementById('username');
const password = document.getElementById('password');
const toggleBtn = document.getElementById('togglePassword');
const forgotBtn = document.getElementById('forgotBtn');

// Show / hide password
toggleBtn.addEventListener('click', () => {
  const hidden = password.type === 'password';
  password.type = hidden ? 'text' : 'password';
  toggleBtn.textContent = hidden ? 'Hide' : 'Show';
});

// Basic client-side validation (the server validates again in PHP)
form.addEventListener('submit', (e) => {
  if (!username.value.trim() || !password.value) {
    e.preventDefault();
    alert('Please enter your username and password.');
  }
});

// TODO: replace with a real "forgot password" flow
forgotBtn.addEventListener('click', () => {
  alert('Please contact the system administrator to reset your password.');
});
