<?php require 'config.php'; require 'auth.php'; ensure_default_admin($pdo);
render_header('Login');
$error='';
$_SESSION['login_attempts']=$_SESSION['login_attempts'] ?? 0;
$_SESSION['login_block_until']=$_SESSION['login_block_until'] ?? 0;

if($_SERVER['REQUEST_METHOD']==='POST'){
verify_csrf();
if(time() < $_SESSION['login_block_until']){
$error='Zu viele Loginversuche. Bitte später erneut versuchen.';
}else{
$stmt=$pdo->prepare('SELECT * FROM users WHERE username=?');
$stmt->execute([trim($_POST['username'])]);
$user=$stmt->fetch();
if($user && password_verify($_POST['password'],$user['password_hash'])){
session_regenerate_id(true);
$_SESSION['user_id']=$user['id'];
$_SESSION['username']=$user['username'];
$_SESSION['login_attempts']=0;
header('Location:index.php');exit;
}else{
$_SESSION['login_attempts']++;
if($_SESSION['login_attempts']>=5){
$_SESSION['login_block_until']=time()+300;
}
$error='Login fehlgeschlagen';
}}}
?>
<div class='row justify-content-center'><div class='col-md-4'><div class='card shadow'><div class='card-body'>
<h3 class='mb-3'>Login</h3>
<?php if($error): ?><div class='alert alert-danger'><?= e($error) ?></div><?php endif; ?>
<form method='post'><?= csrf_field() ?>
<input class='form-control mb-3' name='username' maxlength='50' required placeholder='Benutzer'>
<input class='form-control mb-3' name='password' type='password' required placeholder='Passwort'>
<button class='btn btn-primary w-100'>Login</button>
</form></div></div></div></div>
<?php render_footer(); ?>