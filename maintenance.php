<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Dalam Perbaikan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .maintenance-container {
            position: relative;
            text-align: center;
            padding: 40px;
            background: rgba(30, 30, 30, 0.8);
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
            z-index: 2;
            max-width: 500px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .maintenance-container h1 {
            font-size: 36px;
            margin-bottom: 20px;
            animation: slideIn 0.5s ease-in-out;
            font-weight: bold;
        }

        .maintenance-container p {
            font-size: 18px;
            margin: 20px 0;
            color: #b0bec5;
            line-height: 1.5;
        }

        .maintenance-container i {
            font-size: 80px;
            color: #ffdd57;
            margin-bottom: 20px;
            animation: bounce 1s infinite;
        }

        .maintenance-container a {
            display: inline-block;
            padding: 12px 24px;
            background-color: #ff6b6b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            font-weight: bold;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .maintenance-container a:hover {
            background-color: #e64e4e;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        /* Media Queries */
        @media (max-width: 576px) {
            .maintenance-container h1 {
                font-size: 28px; /* Ukuran font untuk perangkat kecil */
            }
            .maintenance-container p {
                font-size: 16px; /* Ukuran font untuk perangkat kecil */
            }
            .maintenance-container i {
                font-size: 60px; /* Ukuran ikon untuk perangkat kecil */
            }
        }

        @media (min-width: 576px) {
            .maintenance-container h1 {
                font-size: 36px; /* Ukuran font untuk perangkat menengah */
            }
            .maintenance-container p {
                font-size: 18px; /* Ukuran font untuk perangkat menengah */
            }
            .maintenance-container i {
                font-size: 70px; /* Ukuran ikon untuk perangkat menengah */
            }
        }

        @media (min-width: 768px) {
            .maintenance-container {
                max-width: 800px; /* Lebar maksimum untuk tablet dan komputer */
            }
            .maintenance-container h1 {
                font-size: 48px; /* Ukuran font untuk perangkat besar */
            }
            .maintenance-container p {
                font-size: 20px; /* Ukuran font untuk perangkat besar */
            }
            .maintenance-container i {
                font-size: 80px; /* Ukuran ikon untuk perangkat besar */
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="maintenance-container">
        <i class="fas fa-sad-tear"></i> <!-- Ikon wajah tersenyum dengan air mata -->
        <h2>Sedang Dalam Perbaikan</h1><br>
        <a href="login.php">Kembali ke Beranda</a>
    </div>
</body>
</html>
