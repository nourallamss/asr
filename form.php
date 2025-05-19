<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.24/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .container h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .button-group button {
            background-color: #3b82f6;
            color: #ffffff;
            padding: 10px 20px;
            margin: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .button-group button:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Select Your Role</h2>
    <div class="button-group">
        <button onclick="window.location.href='buyer.php'">Buyer</button>
        <button onclick="window.location.href='seller.php'">Seller</button>
    </div>
</div>

</body>
</html>
