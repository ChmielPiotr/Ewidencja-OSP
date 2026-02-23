<?php include 'views/header.php'; ?>

<div class="card shadow-sm border-0" style="max-width: 1000px; margin: 0 auto;">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-person-plus"></i> Dodaj nowego strażaka</h5>
    </div>
    <div class="card-body p-4">
        
        <?php if (isset($blad)): ?>
            <div class="alert alert-danger"><?= $blad ?></div>
        <?php endif; ?>

        <form action="index.php?action=add" method="POST">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Imię</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nazwisko</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Login w systemie (wymagany)</label>
                    <input type="text" name="login" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Hasło startowe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">E-mail (opcjonalny)</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>

            <div class="mb-3 border border-warning p-3 rounded bg-light">
                <label class="form-label fw-bold">Uprawnienia w systemie</label>
                <select name="role" class="form-select border-warning">
                    <option value="user" selected>Zwykły Strażak</option>
                    <option value="admin">Administrator (Naczelnik)</option>
                </select>
                <div class="form-text">Administrator ma dostęp do edycji wszystkich danych. Zwykły strażak widzi tylko podgląd.</div>
            </div>
            
            <hr>
            
            <div class="mb-4 border border-danger p-3 rounded bg-white shadow-sm">
                <label class="form-label fw-bold text-danger"><i class="bi bi-bank"></i> Przynależność do Zarządu OSP (Opcjonalnie)</label>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Funkcja w Zarządzie</label>
                        <select name="funkcja_zarzad" class="form-select border-danger">
                            <option value="">-- Brak (Nie jest w zarządzie) --</option>
                            <option value="PREZES">PREZES</option>
                            <option value="WICEPREZES">WICEPREZES</option>
                            <option value="NACZELNIK">NACZELNIK</option>
                            <option value="ZASTĘPCA NACZELNIKA">ZASTĘPCA NACZELNIKA</option>
                            <option value="SKARBNIK">SKARBNIK</option>
                            <option value="SEKRETARZ">SEKRETARZ</option>
                            <option value="GOSPODARZ">GOSPODARZ</option>
                            <option value="CZŁONEK ZARZĄDU">CZŁONEK ZARZĄDU</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Data powołania</label>
                        <input type="date" name="data_powolania_zarzad" class="form-control">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="index.php?action=index" class="btn btn-secondary">Anuluj i Wróć</a>
                <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-person-check"></i> Dodaj strażaka do bazy</button>
            </div>
        </form>

    </div>
</div>

<?php include 'views/footer.php'; ?>