<?php
    require 'db.php';
    session_start();

    $db = getDb();

    // Query to get company details and employee count with +1 for every company
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
            background-color: #ddd;
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

        .selected-column {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Egyesületek</h1>
    <p>Séra Dávid U7JZ93</p>

    <!-- Search Bar -->
    <div class="search-bar">
        <label for="search">Név:</label>
        <input type="text" id="search" placeholder="Keresés név szerint...">
        <button onclick="searchTable()">Keres</button>
    </div>

    <!-- Associations Table -->
    <table id="associationTable">
        <thead>
            <tr>
                <th id="header-0" onclick="sortTable(0)">Név</th>
                <th id="header-1" onclick="sortTable(1)">Alapítás éve</th>
                <th id="header-2" onclick="sortTable(2)">Alapító</th>
                <th id="header-3" onclick="sortTable(3)">Tagok száma</th>
                <th id="header-4" onclick="sortTable(4)">Tagsági díj</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetchObject()): ?>
                <tr>
                    <td> <?= $row->egyesulet ?></td>
                    <td> <?= $row->alapitasev ?></td>
                    <td> <?= $row->alapito ?></td>
                    <td> <?= $row->employee_count ?></td>
                    <td> <?= $row->tagsagidij ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        let lastSortedColumn = -1; // To keep track of the last clicked column for sorting

        // Function to search the table based on the name
        function searchTable() {
            var input = document.getElementById("search");
            var filter = input.value.toLowerCase();
            var table = document.getElementById("associationTable");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                var td = tr[i].getElementsByTagName("td")[0]; // search in the "Név" column (index 0)
                if (td) {
                    var textValue = td.textContent || td.innerText;
                    if (textValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // Function to sort the table by a given column index
        function sortTable(n) {
            var table = document.getElementById("associationTable");
            var rows = table.rows;
            var switching = true;
            var dir = "asc"; 
            var switchcount = 0;

            // Toggle sorting direction based on last column sorted
            if (n === lastSortedColumn) {
                dir = (dir === "asc") ? "desc" : "asc"; // Reverse direction if same column clicked again
            }
            
            while (switching) {
                switching = false;
                var rowsArray = Array.from(rows).slice(1); // Skip the header row
                for (var i = 0; i < rowsArray.length - 1; i++) {
                    var x = rowsArray[i].getElementsByTagName("TD")[n];
                    var y = rowsArray[i + 1].getElementsByTagName("TD")[n];
                    var shouldSwitch = false;
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rowsArray[i].parentNode.insertBefore(rowsArray[i + 1], rowsArray[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }

            lastSortedColumn = n; // Update the last sorted column

            // Apply the underline style to the sorted column
            document.querySelectorAll('th').forEach((header, index) => {
                if (index === n) {
                    header.classList.add("selected-column");
                } else {
                    header.classList.remove("selected-column");
                }
            });
        }

        // Function to show an alert with all company names when the page loads
        window.onload = function() {
            var table = document.getElementById("associationTable");
            var rows = table.getElementsByTagName("tr");
            var companyNames = [];

            // Loop through the rows and collect all company names
            for (var i = 1; i < rows.length; i++) {
                var companyName = rows[i].getElementsByTagName("td")[0].textContent;
                companyNames.push(companyName);
            }

            // Show the company names in an alert
            alert("Company names:\n" + companyNames.join("\n"));
        }
    </script>
</body>
</html>
