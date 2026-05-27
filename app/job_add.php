<?php require 'config.php'; require 'auth.php'; require_login();
$spools=$pdo->query('SELECT * FROM filament_spools')->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){
$used=(int)$_POST['material_used_g'];
$pdo->beginTransaction();
$stmt=$pdo->prepare('INSERT INTO print_jobs (spool_id,model_name,material_used_g,print_time_minutes,sale_price_eur) VALUES (?,?,?,?,?)');
$stmt->execute([$_POST['spool_id'],trim($_POST['model_name']),$used,$_POST['print_time_minutes'],$_POST['sale_price_eur']]);
$upd=$pdo->prepare('UPDATE filament_spools SET remaining_weight_g=GREATEST(remaining_weight_g-?,0) WHERE id=?');
$upd->execute([$used,$_POST['spool_id']]);
$pdo->commit();
header('Location:index.php');exit;}
?>
<h1>Druckjob</h1>
<form method='post'>
<select name='spool_id'>
<?php foreach($spools as $s): ?>
<option value='<?= $s['id'] ?>'><?= htmlspecialchars($s['brand']) ?> <?= htmlspecialchars($s['material']) ?> <?= $s['remaining_weight_g'] ?>g</option>
<?php endforeach; ?>
</select><br><br>
<input name='model_name' placeholder='Modell' required><br><br>
<input name='material_used_g' type='number' min='1' required placeholder='Verbrauch g'><br><br>
<input name='print_time_minutes' type='number' min='0' placeholder='Zeit'><br><br>
<input name='sale_price_eur' type='number' min='0' step='0.01' placeholder='Preis'><br><br>
<button>Speichern</button>
</form>