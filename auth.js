document.addEventListener('DOMContentLoaded', function() {
    // Formulario de Login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                remember: document.querySelector('input[name="remember"]').checked
            };
            
            // Aquí iría la llamada al backend para autenticar
            console.log('Datos de login:', formData);
            
            // Simulación de login exitoso
            alert('Inicio de sesión exitoso');
            window.location.href = 'dashboard.html';
        });
    }

    // Formulario de Registro
    const registroForm = document.getElementById('registroForm');
    if (registroForm) {
        registroForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden');
                return;
            }
            
            const formData = {
                nombre: document.getElementById('nombre').value,
                email: document.getElementById('email').value,
                telefono: document.getElementById('telefono').value,
                password: password,
                terms: document.querySelector('input[name="terms"]').checked
            };
            
            // Aquí iría la llamada al backend para registrar
            console.log('Datos de registro:', formData);
            
            // Simulación de registro exitoso
            alert('Registro exitoso');
            window.location.href = 'login.html';
        });
    }
});