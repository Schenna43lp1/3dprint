<?php require 'config.php'; require 'auth.php'; require_login(); render_header('User Admin');
if($_SERVER['REQUEST_METHOD']==='POST'){
verify_csrf();
$stmt=$pdo->prepare('INSERT INTO users (username,password_hash,is_admin) VALUES (?,?,?)');
$stmt->execute([
trim($_POST['username']),
password_hash($_POST['password'], PASSWORD_DEFAULT),
isset($_POST['is_admin'])
]);
header('Location:admin_users.php');exit;}
$users=$pdo->query('SELECT id,username,is_admin,created_at FROM users ORDER BY id')->fetchAll();
?>
<div class='card shadow mb-4'><div class='card-body'><h3>Benutzer anlegen</h3>
<form method='post'><?= csrf_field() ?>
<input class='form-control mb-3' name='username' required maxlength='100' placeholder='Benutzername'>
<input class='form-control mb-3' name='password' type='password' required placeholder='Passwort'>
<div class='form-check mb-3'><input class='form-check-input' type='checkbox' name='is_admin'><label class='form-check-label'>Admin</label></div>
<button class='btn btn-success'>Erstellen</button>
</form></div></div>
<div class='card shadow'><div class='card-body'><table class='table'><tr><th>User</th><th>Admin</th><th>Erstellt</th></tr>
<?php foreach($users as $u): ?><tr><td><?= e($u['username']) ?></td><td><?= $u['is_admin']?'Ja':'Nein' ?></td><td><?= e($u['created_at']) ?></td></tr><?php endforeach; ?>
</table></div></div><?php render_footer(); ?>