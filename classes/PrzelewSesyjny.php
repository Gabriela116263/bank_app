<?php
require_once 'Przelew.php';

class PrzelewSesyjny extends Przelew {
    public function zapisz() {
        if (!isset($_SESSION['historia_przelewow'])) {
            $_SESSION['historia_przelewow'] = [];
        }

        $_SESSION['historia_przelewow'][] = [
            'nazwa' => $this->nazwa,
            'konto' => $this->formatujNumer(),
            'tytul' => $this->tytul,
            'kwota' => $this->getKwota()
        ];

        setcookie('ostatni_odbiorca', $this->nazwa, time() + (7 * 24 * 60 * 60));
    }
}