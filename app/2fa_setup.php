<?php require 'config.php'; require 'auth.php'; require_login(); render_header('2FA');
$stmt=$pdo->prepare('SELECT twofa_enabled,twofa_secret FROM users WHERE id=?');
$stmt->execute([$_SESSION['user_id']]);
$user=$stmt->fetch();
$secret=$user['twofa_secret'] ?: generate_totp_secret();
if($_SERVER['REQUEST_METHOD']==='POST'){
verify_csrf();
if(verify_totp($secret,$_POST['code'])){
$stmt=$pdo->prepare('UPDATE users SET twofa_enabled=true,twofa_secret=? WHERE id=?');
$stmt->execute([$secret,$_SESSION['user_id']]);
echo "<div class='alert alert-success'>2FA aktiviert</div>";
$user['twofa_enabled']=true;
}}
$issuer='3DPrintTracker';
$otpauth='otpauth://totp/'.$issuer.':'.rawurlencode($_SESSION['username']).'?secret='.$secret.'&issuer='.$issuer;
?>
<div class='card shadow'><div class='card-body'>
<h3>2FA Setup</h3>
<p>Secret:</p><code><?= e($secret) ?></code>
<p class='mt-3'>QR URL (Authenticator App):</p>
<textarea class='form-control mb-3' readonly><?= e($otpauth) ?></textarea>
<form method='post'><?= csrf_field() ?>
<input class='form-control mb-3' name='code' maxlength='6' placeholder='6-stelliger Code'>
<button class='btn btn-success'>2FA aktivieren</button>
</form>
</div></div><?php render_footer(); ?>