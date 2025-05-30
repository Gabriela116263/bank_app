<?php
session_start();
require_once 'classes/PrzelewSesyjny.php';

$conn = new mysqli("localhost", "root", "admin", "bank_app");
if ($conn->connect_error) {
  die("Błąd połączenia z MySQL: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nazwa = $_POST['nazwa_odbiorcy'];
  $numer = $_POST['numer_konta'];
  $tytul = $_POST['tytul_przelewu'];
  $kwota = $_POST['kwota'];

  $stmt = $conn->prepare("INSERT INTO transfers (nazwa_odbiorcy, numer_konta, tytul_przelewu, kwota) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sssd", $nazwa, $numer, $tytul, $kwota);

  if (!$stmt->execute()) {
    echo "<p style='color:red;'>❌ Błąd zapisu: " . $stmt->error . "</p>";
  }

  $stmt->close();
}

$conn->close();

$daneWyslane = false;
$przelew = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $przelew = new PrzelewSesyjny(
    $_POST['nazwa_odbiorcy'],
    $_POST['numer_konta'],
    $_POST['tytul_przelewu'],
    (float) $_POST['kwota']
  );

  if ($przelew->waliduj()) {
    $przelew->zapisz();
    $daneWyslane = true;
  } else {
    $blad = $_SESSION['blad'] ?? "Nieprawidłowe dane przelewu.";
  }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <title>Przelew bankowy</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <?php if ($daneWyslane): ?>
    <div class="container">
      <h2>✅ Przelew zrealizowany!</h2>
      <p><strong>Odbiorca:</strong> <?= htmlspecialchars($przelew->nazwa) ?></p>
      <p><strong>Numer konta:</strong> <?= $przelew->formatujNumer() ?></p>
      <p><strong>Tytuł przelewu:</strong> <?= htmlspecialchars($przelew->tytul) ?></p>
      <p><strong>Kwota:</strong> <?= number_format($przelew->kwota, 2, ',', ' ') ?> zł</p>


      <form method="GET" action="index.php">
        <button type="submit">Powrót do formularza</button>
      </form>
    </div>

  <?php else: ?>
    <div class="container">
      <h2>Formularz przelewu</h2>
      <form method="POST" action="index.php">
        <label for="nazwa">Nazwa odbiorcy:</label>
        <input type="text" name="nazwa_odbiorcy" required>

        <label for="numer">Numer konta:</label>
        <input type="text" name="numer_konta" maxlength="40" required>

        <label for="tytul">Tytuł przelewu:</label>
        <input type="text" name="tytul_przelewu" required>

        <label for="kwota">Kwota (PLN):</label>
        <input type="number" name="kwota" step="0.01" min="0.01" required>

        <button type="submit">Wyślij przelew</button>
        <p><a href="transfer_history.php">Zobacz historię przelewów</a></p>
      </form>
    </div>

    <div id="modalPodglad">
      <div class="modal-content">
        <h3>Podgląd przelewu</h3>
        <p><strong>Odbiorca:</strong> <span id="podgladOdbiorca"></span></p>
        <p><strong>Numer konta:</strong> <span id="podgladKonto"></span></p>
        <p><strong>Tytuł:</strong> <span id="podgladTytul"></span></p>
        <p><strong>Kwota:</strong> <span id="podgladKwota"></span> zł</p>

        <div class="modal-buttons">
          <button type="button" class="anuluj" id="anulujBtn">Anuluj</button>
          <button type="button" class="potwierdz" id="potwierdzBtn">Potwierdź przelew</button>
        </div>
      </div>
    </div>

    <script src="script.js"></script>
  <?php endif; ?>

</body>

</html>