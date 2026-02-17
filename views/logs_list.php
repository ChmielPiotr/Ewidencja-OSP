<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="text-danger"><i class="bi bi-shield-lock-fill"></i> Dziennik Zdarzeń (SuperAdmin)</h3>
</div>

<div class="card shadow-sm border-0 mb-3 bg-light">
    <div class="card-body py-2">
        <form action="index.php" method="GET" class="row align-items-center g-3">
            <input type="hidden" name="action" value="export_logs">
            
            <div class="col-auto">
                <label class="col-form-label fw-bold"><i class="bi bi-download"></i> Eksportuj logi:</label>
            </div>
            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white">Od</span>
                    <input type="date" name="date_from" class="form-control">
                </div>
            </div>
            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white">Do</span>
                    <input type="date" name="date_to" class="form-control">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-success shadow-sm"><i class="bi bi-filetype-csv"></i> Pobierz plik CSV</button>
            </div>
            <div class="col-auto">
                <small class="text-muted">(Zostaw puste, by pobrać cały dziennik)</small>
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-hover table-sm align-middle mb-0">
                <thead class="table-dark" style="position: sticky; top: 0;">
                    <tr>
                        <th class="ps-3">Data</th>
                        <th>Użytkownik</th>
                        <th>Zdarzenie</th>
                        <th>Adres IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($logi) > 0): ?>
                        <?php foreach ($logi as $log): ?>
                            <tr>
                                <td class="ps-3 text-muted small"><?= date('d.m.Y H:i:s', strtotime($log['data_zdarzenia'])) ?></td>
                                <td>
                                    <?php if ($log['first_name']): ?>
                                        <strong><?= htmlspecialchars($log['first_name'] . ' ' . $log['last_name']) ?></strong> 
                                        <span class="text-muted small">(<?= htmlspecialchars($log['login']) ?>)</span>
                                    <?php else: ?>
                                        <span class="text-secondary">System / Usunięty</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($log['akcja']) ?></span></td>
                                <td class="font-monospace text-muted small"><?= htmlspecialchars($log['adres_ip']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted">Brak logów w systemie.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>