<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - WORLD TRAVELS en Boyacá</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: 'Instrument Sans', sans-serif; }
            .bg-blue-600 { background-color: #2563eb; }
            .text-white { color: white; }
            .p-4 { padding: 1rem; }
            .mb-4 { margin-bottom: 1rem; }
            .max-w-md { max-width: 28rem; }
            .mx-auto { margin-left: auto; margin-right: auto; }
            .rounded { border-radius: 0.25rem; }
            .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
            .bg-white { background-color: white; }
            .p-6 { padding: 1.5rem; }
            .text-center { text-align: center; }
            .text-2xl { font-size: 1.5rem; }
            .font-bold { font-weight: 700; }
            .mb-6 { margin-bottom: 1.5rem; }
            .block { display: block; }
            .w-full { width: 100%; }
            .border { border: 1px solid #d1d5db; }
            .rounded-md { border-radius: 0.375rem; }
            .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
            .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
            .mb-2 { margin-bottom: 0.5rem; }
            .bg-blue-500 { background-color: #3b82f6; }
            .hover\:bg-blue-700:hover { background-color: #1d4ed8; }
            .text-blue-600 { color: #2563eb; }
            .mt-4 { margin-top: 1rem; }
        </style>
    @endif
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow max-w-md w-full">
            <h2 class="text-2xl font-bold text-center mb-6">Registrarse</h2>
            <form id="register-form">
                <!-- Campos dinámicos basados en el rol -->
                <div id="common-fields">
                    <div class="mb-4">
                        <label for="email" class="block mb-2">Correo Electrónico</label>
                        <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-md" required>
                    </div>
                </div>

                <!-- Campos específicos para turista -->
                <div id="turista-fields" style="display: none;">
                    <div class="mb-4">
                        <label for="name" class="block mb-2">Nombre</label>
                        <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="apellido" class="block mb-2">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="telefono" class="block mb-2">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="nacionalidad" class="block mb-2">Nacionalidad</label>
                        <input type="text" id="nacionalidad" name="nacionalidad" class="w-full px-3 py-2 border rounded-md">
                    </div>
                </div>

                <!-- Campos específicos para empresa -->
                <div id="empresa-fields" style="display: none;">
                    <div class="mb-4">
                        <label for="numero" class="block mb-2">Número (NIT)</label>
                        <input type="text" id="numero" name="numero" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="name" class="block mb-2">Nombre de la Empresa</label>
                        <input type="text" id="empresa_name" name="name" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="direccion" class="block mb-2">Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="ciudad" class="block mb-2">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" class="w-full px-3 py-2 border rounded-md">
                    </div>
                </div>

                <!-- Campos específicos para administrador -->
                <div id="admin-fields" style="display: none;">
                    <div class="mb-4">
                        <label for="name" class="block mb-2">Nombre</label>
                        <input type="text" id="admin_name" name="name" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="apellido" class="block mb-2">Apellido</label>
                        <input type="text" id="admin_apellido" name="apellido" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="telefono" class="block mb-2">Teléfono</label>
                        <input type="text" id="admin_telefono" name="telefono" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="documento" class="block mb-2">Documento</label>
                        <input type="text" id="documento" name="documento" class="w-full px-3 py-2 border rounded-md">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-2">Contraseña</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="block mb-2">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="role" class="block mb-2">Rol</label>
                    <select id="role" name="role" class="w-full px-3 py-2 border rounded-md" required>
                        <option value="">Selecciona un rol</option>
                        <option value="turista">Turista</option>
                        <option value="empresa">Empresa de Viajes</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
                <!-- Sección de código de verificación para administrador -->
                <div class="mb-4" id="admin-verification-section" style="display: none;">
                    <label class="block mb-2 text-sm text-gray-600">Código de Verificación (solo para administradores)</label>
                    <p class="text-xs text-gray-500 mb-2">El código de verificación se mostrará después del registro para fines administrativos.</p>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Registrarse</button>
            </form>
            <p class="text-center mt-4">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-blue-600">Inicia Sesión</a></p>
            <div id="message" class="mt-4 text-center"></div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar campos basados en el rol seleccionado
        document.getElementById('role').addEventListener('change', function() {
            const role = this.value;

            // Ocultar todos los campos específicos
            document.getElementById('turista-fields').style.display = 'none';
            document.getElementById('empresa-fields').style.display = 'none';
            document.getElementById('admin-fields').style.display = 'none';
            document.getElementById('admin-verification-section').style.display = 'none';

            // Mostrar campos específicos según el rol
            if (role === 'turista') {
                document.getElementById('turista-fields').style.display = 'block';
                // Hacer campos requeridos
                setRequiredFields(['name', 'apellido', 'telefono', 'nacionalidad'], true);
                setRequiredFields(['numero', 'empresa_name', 'direccion', 'ciudad', 'admin_name', 'admin_apellido', 'admin_telefono', 'documento'], false);
            } else if (role === 'empresa') {
                document.getElementById('empresa-fields').style.display = 'block';
                // Hacer campos requeridos
                setRequiredFields(['numero', 'empresa_name', 'direccion', 'ciudad'], true);
                setRequiredFields(['name', 'apellido', 'telefono', 'nacionalidad', 'admin_name', 'admin_apellido', 'admin_telefono', 'documento'], false);
            } else if (role === 'administrador') {
                document.getElementById('admin-fields').style.display = 'block';
                document.getElementById('admin-verification-section').style.display = 'block';
                // Hacer campos requeridos
                setRequiredFields(['admin_name', 'admin_apellido', 'admin_telefono', 'documento'], true);
                setRequiredFields(['name', 'apellido', 'telefono', 'nacionalidad', 'numero', 'empresa_name', 'direccion', 'ciudad'], false);
            }
        });

        // Función para establecer campos como requeridos o no
        function setRequiredFields(fieldIds, required) {
            fieldIds.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.required = required;
                }
            });
        }

        document.getElementById('register-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            const role = document.getElementById('role').value;

            if (password !== password_confirmation) {
                alert('Las contraseñas no coinciden');
                document.getElementById('message').innerText = 'Las contraseñas no coinciden';
                return;
            }

            if (!role) {
                alert('Por favor selecciona un rol');
                document.getElementById('message').innerText = 'Por favor selecciona un rol';
                return;
            }

            let requestData = {
                rol: role,
                email: email,
                contraseña: password,
                password_confirmation: password_confirmation
            };

            // Agregar campos específicos según el rol
            if (role === 'turista') {
                requestData.nombre = document.getElementById('name').value;
                requestData.apellido = document.getElementById('apellido').value;
                requestData.telefono = document.getElementById('telefono').value;
                requestData.nacionalidad = document.getElementById('nacionalidad').value;
            } else if (role === 'empresa') {
                requestData.numero = document.getElementById('numero').value;
                requestData.nombre = document.getElementById('empresa_name').value;
                requestData.direccion = document.getElementById('direccion').value;
                requestData.ciudad = document.getElementById('ciudad').value;
                requestData.correo = email; // Para empresas, el email es el correo de la empresa
            } else if (role === 'administrador') {
                requestData.admin_name = document.getElementById('admin_name').value;
                requestData.admin_apellido = document.getElementById('admin_apellido').value;
                requestData.admin_telefono = document.getElementById('admin_telefono').value;
                requestData.documento = document.getElementById('documento').value;
            }

            fetch('http://127.0.0.1:8000/api/registrar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let successMessage = data.message;
                    if (role === 'administrador' && data.codigo_verificacion) {
                        successMessage += `\n\nCódigo de verificación: ${data.codigo_verificacion}\n\nGuarda este código para fines administrativos.`;
                    }
                    alert(successMessage);
                    document.getElementById('message').innerText = successMessage;
                    // Redirigir después de un breve delay
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                } else {
                    let errorMessage = 'Error en el registro';
                    if (data.message) {
                        errorMessage = data.message;
                    } else if (data.errors) {
                        // Mostrar errores de validación
                        const errors = Object.values(data.errors).flat();
                        errorMessage = errors.join('\n');
                    }
                    alert(errorMessage);
                    document.getElementById('message').innerText = errorMessage;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al registrarse. Revisa la consola para más detalles.');
                document.getElementById('message').innerText = 'Error al registrarse';
            });
        });
    </script>
</body>
</html>