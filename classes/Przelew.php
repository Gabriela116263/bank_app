<?php
require_once 'PrzelewInterface.php';

class Przelew implements PrzelewInterface {
    public string $nazwa;
    public string $konto;
    public string $tytul;
    public float $kwota;

    public function __construct(string $nazwa, string $konto, string $tytul, float $kwota) {
        $this->nazwa = htmlspecialchars($nazwa);
        $this->konto = preg_replace('/\D/', '', $konto);
        $this->tytul = htmlspecialchars($tytul);
        $this->kwota = $kwota;
    }

    public function waliduj(): bool {
    if (strlen($this->konto) !== 26 || !ctype_digit($this->konto)) {
        $_SESSION['blad'] = "Numer konta musi zawierać dokładnie 26 cyfr.";
        return false;
    }

    if ($this->kwota <= 0) {
        $_SESSION['blad'] = "Kwota przelewu musi być większa od zera.";
        return false;
    }

    return true;
}


    public function formatujNumer(): string {
        return substr($this->konto, 0, 2) . ' ' .
               substr($this->konto, 2, 4) . ' ' .
               substr($this->konto, 6, 4) . ' ' .
               substr($this->konto, 10, 4) . ' ' .
               substr($this->konto, 14, 4) . ' ' .
               substr($this->konto, 18, 4) . ' ' .
               substr($this->konto, 22, 4);
    }

    public function getNazwa(): string {
        return $this->nazwa;
    }

    public function getTytul(): string {
        return $this->tytul;
    }

    public function getKwota(): string {
        return number_format($this->kwota, 2, '.', '');
    }

    public function zapisz() {
    }
}
