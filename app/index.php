<?php require 'config.php'; require 'auth.php'; require_login();
$spools = $pdo->query('SELECT * FROM filament_spools ORDER BY id DESC')->fetchAll();
$jobs = $pdo->query('SELECT COUNT(*) FROM print_jobs')->fetchColumn();
$revenue = $pdo->query('SELECT COALESCE(SUM(sale_price_eur),0) FROM print_jobs')->fetchColumn();
$cost = $pdo->query('SELECT COALESCE(SUM((pj.material_used_g::numeric/fs.initial_weight_g)*fs.price_eur),0) FROM print_jobs pj JOIN filament_spools fs ON pj.spool_id=fs.id')->fetchColumn();
$profit = $revenue - $cost;
?>
<h1>3D Print Dashboard</h1>
<p>Hallo <?= htmlspecialchars(current_user_name()) ?> | <a href='logout.php'>Logout</a></p>
<a href='filament_add.php'>Filament hinzufügen</a> |
<a href='job_add.php'>Druckjob</a> |
<a href='maintenance.php'>Wartung</a>
<h2>Business</h2>
<p>Druckjobs: <?= $jobs ?></p>
<p>Umsatz: <?= number_format($revenue,2) ?> €</p>
<p>Materialkosten: <?= number_format($cost,2) ?> €</p>
<p>Gewinn: <strong><?= number_format($profit,2) ?> €</strong></p>
<table border='1' cellpadding='8'>
<tr><th>Marke</th><th>Material</th><th>Farbe</th><th>Rest</th><th>Status</th></tr>
<?php foreach($spools as $s): ?>
<tr>
<td><?= htmlspecialchars($s['brand']) ?></td>
<td><?= htmlspecialchars($s['material']) ?></td>
<td><?= htmlspecialchars($s['color']) ?></td>
<td><?= $s['remaining_weight_g'] ?> g</td>
<td><?= $s['remaining_weight_g'] < 150 ? '⚠️ Niedrig' : 'OK' ?></td>
</tr>
<?php endforeach; ?>
</table>