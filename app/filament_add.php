<?php require 'config.php'; require 'auth.php'; require_login(); render_header('Filament');
if($_SERVER['REQUEST_METHOD']==='POST'){
verify_csrf();
$brand=trim($_POST['brand']);
$color=trim($_POST['color']);
$weight=(int)$_POST['weight'];
$price=(float)$_POST['price'];
if($brand && $weight>0){
$stmt=$pdo->prepare('INSERT INTO filament_spools (brand,material,color,initial_weight_g,remaining_weight_g,price_eur,storage_location) VALUES (?,?,?,?,?,?,?)');
$stmt->execute([$brand,$_POST['material'],$color,$weight,$weight,$price,trim($_POST['location'])]);
header('Location:index.php');exit;}}
?>
<div class='card shadow'><div class='card-body'>
<h3>Filament hinzufügen</h3>
<form method='post'><?= csrf_field() ?>
<input class='form-control mb-3' name='brand' maxlength='100' required placeholder='Marke'>
<select class='form-select mb-3' name='material'><option>PLA</option><option>PETG</option><option>ASA</option><option>ABS</option></select>
<input class='form-control mb-3' name='color' maxlength='50' placeholder='Farbe'>
<input class='form-control mb-3' name='weight' type='number' min='1' value='1000'>
<input class='form-control mb-3' name='price' type='number' min='0' step='0.01' placeholder='Preis'>
<input class='form-control mb-3' name='location' maxlength='100' placeholder='Lagerort'>
<button class='btn btn-success'>Speichern</button>
</form></div></div>
<?php render_footer(); ?>