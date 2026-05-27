<?php require 'config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
if(isset($_POST['add'])){
$stmt=$pdo->prepare('INSERT INTO maintenance_tasks (task_name,interval_days,last_done_at,notes) VALUES (?,?,?,?)');
$stmt->execute([$_POST['task_name'],$_POST['interval_days'],$_POST['last_done_at'],$_POST['notes']]);}
if(isset($_POST['done'])){
$stmt=$pdo->prepare('UPDATE maintenance_tasks SET last_done_at=CURRENT_DATE WHERE id=?');
$stmt->execute([$_POST['task_id']]);}
header('Location:maintenance.php');exit;}
$tasks=$pdo->query("SELECT *, CASE WHEN last_done_at IS NULL THEN true WHEN last_done_at + interval_days < CURRENT_DATE THEN true ELSE false END AS overdue FROM maintenance_tasks ORDER BY task_name")->fetchAll();
?>
<h1>Wartung</h1>
<form method='post'>
<input type='hidden' name='add' value='1'>
<input name='task_name' placeholder='Aufgabe'><br><br>
<input name='interval_days' type='number' value='30'><br><br>
<input name='last_done_at' type='date'><br><br>
<textarea name='notes'></textarea><br><br>
<button>Speichern</button>
</form>
<table border='1'>
<tr><th>Aufgabe</th><th>Status</th><th>Aktion</th></tr>
<?php foreach($tasks as $t): ?>
<tr>
<td><?= htmlspecialchars($t['task_name']) ?></td>
<td><?= $t['overdue'] ? '⚠️ Überfällig' : 'OK' ?></td>
<td>
<form method='post'>
<input type='hidden' name='done' value='1'>
<input type='hidden' name='task_id' value='<?= $t['id'] ?>'>
<button>Erledigt</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>