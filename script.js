document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  const nazwaInput = document.querySelector('input[name="nazwa_odbiorcy"]');
  const kontoInput = document.querySelector('input[name="numer_konta"]');
  const tytulInput = document.querySelector('input[name="tytul_przelewu"]');
  const kwotaInput = document.querySelector('input[name="kwota"]');

  const modal = document.getElementById('modalPodglad');
  const podgladOdbiorca = document.getElementById('podgladOdbiorca');
  const podgladKonto = document.getElementById('podgladKonto');
  const podgladTytul = document.getElementById('podgladTytul');
  const podgladKwota = document.getElementById('podgladKwota');

  const potwierdzBtn = document.getElementById('potwierdzBtn');
  const anulujBtn = document.getElementById('anulujBtn');

  const errorMsg = document.createElement('div');
  errorMsg.style.color = 'red';
  kontoInput.parentNode.appendChild(errorMsg);

  function sprawdzNumerKonta(numer) {
    const czysty = numer.replace(/\s+/g, '');
    if (!/^\d{26}$/.test(czysty)) {
      return 'Numer konta musi zawierać dokładnie 26 cyfr.';
    }
    return '';
  }

  function formatujNumer(numer) {
    const cyfry = numer.replace(/\D/g, '');
    const grupy = [];
    if (cyfry.length >= 2) grupy.push(cyfry.slice(0, 2));
    if (cyfry.length >= 6) grupy.push(cyfry.slice(2, 6));
    if (cyfry.length >= 10) grupy.push(cyfry.slice(6, 10));
    if (cyfry.length >= 14) grupy.push(cyfry.slice(10, 14));
    if (cyfry.length >= 18) grupy.push(cyfry.slice(14, 18));
    if (cyfry.length >= 22) grupy.push(cyfry.slice(18, 22));
    if (cyfry.length >= 26) grupy.push(cyfry.slice(22, 26));
    return grupy.join(' ');
  }

  kontoInput.addEventListener('input', (e) => {
    const el = e.target;
    const rawValue = el.value.replace(/\D/g, '');
    const cursorPosition = el.selectionStart;

    let formatted = '';
    const blocks = [2, 4, 4, 4, 4, 4, 4];
    let index = 0;

    for (let block of blocks) {
      if (rawValue.length > index) {
        formatted += rawValue.substr(index, block) + ' ';
        index += block;
      }
    }

    formatted = formatted.trim();
    el.value = formatted;

    el.setSelectionRange(el.value.length, el.value.length);
  });

  form.addEventListener('submit', e => {
    e.preventDefault();

    const blad = sprawdzNumerKonta(kontoInput.value);
    if (blad) {
      errorMsg.textContent = blad;
      return;
    }

    podgladOdbiorca.textContent = nazwaInput.value || '-';
    podgladKonto.textContent = kontoInput.value || '-';
    podgladTytul.textContent = tytulInput.value || '-';
    const kwota = parseFloat(kwotaInput.value).toFixed(2);
    podgladKwota.textContent = isNaN(kwota) ? '-' : kwota;

    modal.style.display = 'flex';
  });

  potwierdzBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    form.submit();
  });

  anulujBtn.addEventListener('click', () => {
    modal.style.display = 'none';
  });
});
