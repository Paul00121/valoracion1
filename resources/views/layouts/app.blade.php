<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background: linear-gradient(135deg, #6c5ce7, #00b894);
            display: flex;
            justify-content: center; /* centra horizontal */
            align-items: center;     /* centra vertical */
            color: white;
            text-align: center;
        }

        .container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .card {
            border-radius: 1rem;
            background-color: rgba(255, 255, 255, 0.95);
            color: #000;
        }

        .btn-primary {
            background-color: #6c5ce7;
            border-color: #6c5ce7;
            padding: 10px 30px;
            font-size: 1.2rem;
        }

        .btn-primary:hover {
            background-color: #5a4ccf;
            border-color: #5a4ccf;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column justify-content-center align-items-center">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
