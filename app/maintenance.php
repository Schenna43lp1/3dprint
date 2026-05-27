<?php require 'config.php'; require 'auth.php'; require_login(); render_header('Wartung');
if($_SERVER['REQUEST_METHOD']==='POST'){
verify_csrf();
if(isset($_POST['add'])){
$stmt=$pdo->prepare('INSERT INTO maintenance_tasks (task_name,interval_days,last_done_at,notes) VALUES (?,?,?,?)');
$stmt->execute([trim($_POST['task_name']),(int)$_POST['interval_days'],$_POST['last_done_at'],trim($_POST['notes'])]);}
if(isset($_POST['done'])){
$stmt=$pdo->prepare('UPDATE maintenance_tasks SET last_done_at=CURRENT_DATE WHERE id=?');
$stmt->execute([$_POST['task_id']]);}
header('Location:maintenance.php');exit;}
$tasks=$pdo->query("SELECT *, CASE WHEN last_done_at IS NULL THEN true WHEN last_done_at + interval_days < CURRENT_DATE THEN true ELSE false END AS overdue FROM maintenance_tasks ORDER BY task_name")->fetchAll();
?>
<div class='card shadow mb-4'><div class='card-body'>
<h3>Neue Wartung</h3>
<form method='post'><?= csrf_field() ?><input type='hidden' name='add' value='1'>
<input class='form-control mb-3' name='task_name' maxlength='150' required placeholder='Aufgabe'>
<input class='form-control mb-3' name='interval_days' type='number' min='1' value='30'>
<input class='form-control mb-3' name='last_done_at' type='date'>
<textarea class='form-control mb-3' name='notes'></textarea>
<button class='btn btn-success'>Speichern</button>
</form></div></div>
<div class='card shadow'><div class='card-body'><table class='table table-striped'>
<tr><th>Aufgabe</th><th>Status</th><th>Aktion</th></tr>
<?php foreach($tasks as $t): ?>
<tr><td><?= e($t['task_name']) ?></td><td><?= $t['overdue'] ? '⚠️ Überfällig':'OK' ?></td><td><form method='post'><?= csrf_field() ?><input type='hidden' name='done' value='1'><input type='hidden' name='task_id' value='<?= $t['id'] ?>'><button class='btn btn-sm btn-primary'>Erledigt</button></form></td></tr>
<?php endforeach; ?>
</table></div></div><?php render_footer(); ?>