<?php require 'config.php'; require 'auth.php'; require_login(); render_header('Druckjob');
$spools=$pdo->query('SELECT * FROM filament_spools')->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){
verify_csrf();
$used=(int)$_POST['material_used_g'];
$pdo->beginTransaction();
$stmt=$pdo->prepare('INSERT INTO print_jobs (spool_id,model_name,material_used_g,print_time_minutes,sale_price_eur) VALUES (?,?,?,?,?)');
$stmt->execute([$_POST['spool_id'],trim($_POST['model_name']),$used,(int)$_POST['print_time_minutes'],(float)$_POST['sale_price_eur']]);
$upd=$pdo->prepare('UPDATE filament_spools SET remaining_weight_g=GREATEST(remaining_weight_g-?,0) WHERE id=?');
$upd->execute([$used,$_POST['spool_id']]);
$pdo->commit();
header('Location:index.php');exit;}
?>
<div class='card shadow'><div class='card-body'>
<h3>Druckjob eintragen</h3>
<form method='post'><?= csrf_field() ?>
<select class='form-select mb-3' name='spool_id'>
<?php foreach($spools as $s): ?>
<option value='<?= $s['id'] ?>'><?= e($s['brand']) ?> <?= e($s['material']) ?> (<?= $s['remaining_weight_g'] ?>g)</option>
<?php endforeach; ?>
</select>
<input class='form-control mb-3' name='model_name' required maxlength='150' placeholder='Modellname'>
<input class='form-control mb-3' name='material_used_g' type='number' min='1' required placeholder='Materialverbrauch g'>
<input class='form-control mb-3' name='print_time_minutes' type='number' min='0' placeholder='Druckzeit Minuten'>
<input class='form-control mb-3' name='sale_price_eur' type='number' min='0' step='0.01' placeholder='Verkaufspreis €'>
<button class='btn btn-primary'>Speichern</button>
</form></div></div><?php render_footer(); ?>