<?php
function formatujDate($data_z_bazy) {
    if (empty($data_z_bazy)) return '<span class="text-muted">Brak wpisu</span>';
    
    $dzisiaj = new DateTime();
    $dzisiaj->setTime(0, 0, 0);
    $waznosc = new DateTime($data_z_bazy);
    $waznosc->setTime(0, 0, 0);
    
    $roznica = $dzisiaj->diff($waznosc);
    $zostalo_dni = $roznica->days;
    $czy_po_terminie = $roznica->invert;
    
    $wyswietlanaData = date('d.m.Y', strtotime($data_z_bazy));

    if ($czy_po_terminie == 1) return '<span class="badge bg-danger fs-6 shadow-sm">❌ ' . $wyswietlanaData . '</span>';
    elseif ($zostalo_dni <= 30) return '<span class="badge bg-warning text-dark fs-6 shadow-sm">⚠️ ' . $wyswietlanaData . '</span>';
    else return '<span class="badge bg-success fs-6 shadow-sm">✅ ' . $wyswietlanaData . '</span>';
}
function dataPolska($data_z_bazy) {
    if (empty($data_z_bazy)) return '';
    
    $miesiace = ['stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia'];
    $timestamp = strtotime($data_z_bazy);
    
    $dzien = date('j', $timestamp);
    $miesiac = $miesiace[date('n', $timestamp) - 1];
    $rok = date('Y', $timestamp);
    
    return "$dzien $miesiac $rok r.";
}
?>
