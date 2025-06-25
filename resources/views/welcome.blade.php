
<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <title>Bienvenido</title>
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <!-- Bootstrap 5 CDN -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
 <div class="text-center">
 <h1 class="mb-4">Bienvenido Marco a tu Proyecto Laravel</h1>
 <div class="d-flex justify-content-center gap-3">
 <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
 <a href="{{ route('register') }}" class="btn btn-outline-primary">Registrarse</a>
 </div>
 </div>
 <!-- Bootstrap JS (opcional si quieres funcionalidades como modals, dropdowns, etc.) -->
 <script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
