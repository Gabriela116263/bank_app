<?php
$conn = new mysqli("localhost", "root", "admin", "bank_app");
if ($conn->connect_error) {
    die("Błąd połączenia z MySQL: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM transfers ORDER BY data DESC");

echo "<h2>Historia przelewów</h2>";
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8'><tr><th>Odbiorca</th><th>Numer konta</th><th>Tytuł</th><th>Kwota</th><th>Data</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['nazwa_odbiorcy']}</td>
            <td>{$row['numer_konta']}</td>
            <td>{$row['tytul_przelewu']}</td>
            <td>{$row['kwota']}</td>
            <td>{$row['data']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "Brak przelewów w historii.";
}

$conn->close();
?>
