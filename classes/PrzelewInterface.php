<?php
interface PrzelewInterface {
    public function waliduj(): bool;
    public function formatujNumer(): string;
    public function zapisz();
}