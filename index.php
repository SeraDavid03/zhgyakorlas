<?php
	require 'db.php';
    session_start();

    $db = getDb();

    $result = $db->query("
    SELECT 
        adatbazis.id, 
        adatbazis.egyesulet, 
        adatbazis.alapitasev, 
        adatbazis.alapito, 
        adatbazis.alapitoszul, 
        adatbazis.tagsagidij, 
        COUNT(DISTINCT tagok.id) + 1 AS employee_count
    FROM adatbazis
    LEFT JOIN tagok ON adatbazis.id = tagok.cegid
    GROUP BY adatbazis.id
    ORDER BY adatbazis.egyesulet;
");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egyesületek</title>
    <style>
        /* General Table Styling */
        table {
            border-collapse: collapse;
            width: 75%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
            cursor: pointer;
        }

        th:hover {
            color: darkgray;
        }

        tr:nth-child(even) {
            background-color: #e7f4f4;
        }

        tr:hover {
            background-color: #ddd;
        }

        .highlight {
            background-color: #b0e0e6 !important;
        }

        .search-bar {
            margin: 20px 0;
        }

        .search-bar input {
            padding: 8px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 8px 16px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Egyesületek</h1>
	<p>Séra Dávid U7JZ93</p>
    <!-- Search Bar -->
    <div class="search-bar">
        <label for="search">Név:</label>
        <input type="text" id="search" placeholder="">
        <button onclick="search()">Keres</button>
    </div>

    <!-- Associations Table -->
    <table>
        <tr>
            <th onclick="sortTable(0)">Név</th>
            <th onclick="sortTable(1)">Alapítás éve</th>
            <th onclick="sortTable(2)">Alapító</th>
            <th onclick="sortTable(3)">Tagok száma</th>
            <th onclick="sortTable(4)">Tagsági díj</th>
        </tr>

        <?php while ($row = $result->fetchObject()): ?>
        <tr>
            <td> <?= $row->egyesulet ?></td>
            <td> <?= $row->alapitasev ?></td>
            <td> <?= $row->alapito ?></td>
            <td> <?= $row->employee_count ?></td>
            <td> <?= $row->tagsagidij ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
