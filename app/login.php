<?php require 'config.php'; require 'auth.php'; ensure_default_admin($pdo);
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
$stmt=$pdo->prepare('SELECT * FROM users WHERE username=?');
$stmt->execute([$_POST['username']]);
$user=$stmt->fetch();
if($user && password_verify($_POST['password'],$user['password_hash'])){
$_SESSION['user_id']=$user['id'];
$_SESSION['username']=$user['username'];
header('Location:index.php');exit;
}else{$error='Login fehlgeschlagen';}}
?>
<h1>Login</h1>
<?php if($error): ?><p><?= $error ?></p><?php endif; ?>
<form method='post'>
<input name='username' placeholder='Benutzer'><br><br>
<input name='password' type='password' placeholder='Passwort'><br><br>
<button>Login</button>
</form>