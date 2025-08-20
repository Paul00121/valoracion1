<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Recuperación de contraseña</h2>
        <p>Tu código de verificación es: <strong>{{ $codigo }}</strong></p>
        <p>O haz clic en el siguiente enlace para cambiar tu contraseña:</p>
        <a href="{{ $enlace }}" style="background:#4e73df;color:white;padding:10px 20px;text-decoration:none;">Restablecer contraseña</a>
        <p>Este enlace expirará en 1 hora.</p>
        <p>Si no solicitaste este cambio, ignora este mensaje.</p>
</body>
</html>
