<?php require 'config.php';
$spools = $pdo->query('SELECT * FROM filament_spools ORDER BY id DESC')->fetchAll();
$jobs = $pdo->query('SELECT COUNT(*) FROM print_jobs')->fetchColumn();
?>
<h1>3D Print Dashboard</h1>
<a href='filament_add.php'>Filament hinzufügen</a> |
<a href='job_add.php'>Druckjob</a> |
<a href='maintenance.php'>Wartung</a>
<p>Druckjobs: <?= $jobs ?></p>
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