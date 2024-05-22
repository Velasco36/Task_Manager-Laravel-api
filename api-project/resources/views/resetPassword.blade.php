<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Reestablecer Contraseña</title>
</head>

<body class="bg-slate-200 text-slate-900">
    <div class="grid h-screen place-content-center">
        <div class="border border-white bg-white p-10 rounded-lg shadow-lg max-w-lg w-full">
            <div class="mb-10 text-center text-indigo-600">
                <h1 class="text-3xl font-bold">Reestablecer Contraseña</h1>
            </div>
            <form id="resetForm" method="POST" action="{{ route('reset') }}">
                @csrf
                <div class="flex flex-col items-center justify-center space-y-6">
                    <div class="relative w-full">
                        <input type="password" id="password" required name="password" placeholder="Contraseña" class="w-full appearance-none rounded-full border border-gray-300 bg-white p-2 px-4 focus:bg-blue-50 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <span id="togglePassword" class="absolute inset-y-0 right-4 flex items-center cursor-pointer text-gray-500 hover:text-indigo-500">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="relative w-full">
                        <input type="password" id="confirm_password" required name="confirm_password" placeholder="Confirmar Contraseña" class="w-full appearance-none rounded-full border border-gray-300 bg-white p-2 px-4 focus:bg-blue-50 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <span id="toggleConfirmPassword" class="absolute inset-y-0 right-4 flex items-center cursor-pointer text-gray-500 hover:text-indigo-500">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <p id="validation" class="text-center text-red-500 italic text-sm"></p>
                    <button type="submit" class="rounded-full bg-indigo-500 p-2 px-4 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
            const confirmPassword = document.querySelector('#confirm_password');
            const validationMessage = document.querySelector('#validation');
            const form = document.querySelector('#resetForm');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            confirmPassword.addEventListener('keyup', function() {
                if (password.value !== confirmPassword.value) {
                    validationMessage.textContent = "Las contraseñas no coinciden!";
                } else {
                    validationMessage.textContent = "";
                }
            });

            form.addEventListener('submit', function(e) {

                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las contraseñas no coinciden!',
                    });
                } else {
                    e.preventDefault(); // Prevent the form from submitting immediately
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'La contraseña se ha reestablecido correctamente!',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.href = 'http://localhost:5173/login';
                    });
                }
            });
        });
    </script>
</body>

</html>
