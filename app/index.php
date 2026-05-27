<?php require 'config.php'; require 'auth.php'; require_login();
$spools=$pdo->query('SELECT * FROM filament_spools ORDER BY id DESC')->fetchAll();
$jobs=$pdo->query('SELECT COUNT(*) FROM print_jobs')->fetchColumn();
$revenue=$pdo->query('SELECT COALESCE(SUM(sale_price_eur),0) FROM print_jobs')->fetchColumn();
$cost=$pdo->query('SELECT COALESCE(SUM((pj.material_used_g::numeric/fs.initial_weight_g)*fs.price_eur),0) FROM print_jobs pj JOIN filament_spools fs ON pj.spool_id=fs.id')->fetchColumn();
$profit=$revenue-$cost;
render_header('Dashboard'); ?>
<div class='row mb-4'>
<div class='col-md-3'><div class='card shadow-sm'><div class='card-body'><h6>Druckjobs</h6><h3><?= $jobs ?></h3></div></div></div>
<div class='col-md-3'><div class='card shadow-sm'><div class='card-body'><h6>Umsatz</h6><h3><?= number_format($revenue,2) ?> €</h3></div></div></div>
<div class='col-md-3'><div class='card shadow-sm'><div class='card-body'><h6>Kosten</h6><h3><?= number_format($cost,2) ?> €</h3></div></div></div>
<div class='col-md-3'><div class='card shadow-sm'><div class='card-body'><h6>Gewinn</h6><h3><?= number_format($profit,2) ?> €</h3></div></div></div>
</div>
<div class='card shadow-sm'><div class='card-body'>
<h5>Filament Lager</h5>
<table class='table table-striped'>
<tr><th>Marke</th><th>Material</th><th>Farbe</th><th>Rest</th><th>Status</th></tr>
<?php foreach($spools as $s): ?>
<tr><td><?= e($s['brand']) ?></td><td><?= e($s['material']) ?></td><td><?= e($s['color']) ?></td><td><?= $s['remaining_weight_g'] ?> g</td><td><?= $s['remaining_weight_g']<150 ? '⚠️ Niedrig':'OK' ?></td></tr>
<?php endforeach; ?>
</table></div></div><?php render_footer(); ?>