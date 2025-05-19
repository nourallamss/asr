<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rooms = $_POST['rooms'];
    $bathrooms = $_POST['bathrooms'];
    $kitchen = $_POST['kitchen'];
    $floor = $_POST['floor'];
    $reception = $_POST['reception'];
    $view = $_POST['view'];
    $location = $_POST['location'];
    $space = $_POST['space'];
    $sale = $_POST['sale'];

    echo "<div class='result-container'>";
    echo "<h1 class='text-2xl font-bold mb-4'>Buyer - Apartment Details</h1>";
    echo "<p class='mb-2'>Rooms: $rooms</p>";
    echo "<p class='mb-2'>Bathrooms: $bathrooms</p>";
    echo "<p class='mb-2'>Kitchen: $kitchen</p>";
    echo "<p class='mb-2'>Floor: $floor</p>";
    echo "<p class='mb-2'>Reception: $reception</p>";
    echo "<p class='mb-2'>View: $view</p>";
    echo "<p class='mb-2'>Location: $location</p>";
    echo "<p class='mb-2'>Space: $space m²</p>";
    echo "<p class='mb-2'>Sale: $sale</p>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.24/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2563eb;
        }
        .form-container label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        .form-container input, .form-container select, .form-container button {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }
        .form-container button {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-container button:hover {
            background-color: #1d4ed8;
        }
        .result-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #e5e7eb;
            border-radius: 12px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Enter Apartment Details for Buyer</h2>
        <form method="POST" action="">
             <label>Rooms:</label>
            <select name="rooms" required>
                <option value="1">1</option>
                <option value="2">2</option>
                  <option value="3">3</option>
                <option value="4">4</option>
            </select>

            <label>Bathrooms:</label>
            <input type="number" name="bathrooms" required>

            <label>Kitchen:</label>
            <input type="number" name="kitchen" required>

            <label>Floor:</label>
            <select name="floor" required>
                <option value="1">1</option>
                <option value="2">2</option>
                  <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                  <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>

            </select>

            <label>Reception:</label>
            <input type="number" name="reception" required>

            <label>View (Internal/External):</label>
            <select name="view" required>
                <option value="Internal">Internal</option>
                <option value="External">External</option>
            </select>

            <label>Location:</label>
            <input type="text" name="location" required>

            <label>Space (m²):</label>
            <input type="number" name="space" required>

            <label>Sale Price:</label>
            <input type="number" name="sale" required>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>