<?php require 'config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
$stmt=$pdo->prepare('INSERT INTO filament_spools (brand,material,color,initial_weight_g,remaining_weight_g,price_eur,storage_location) VALUES (?,?,?,?,?,?,?)');
$stmt->execute([$_POST['brand'],$_POST['material'],$_POST['color'],$_POST['weight'],$_POST['weight'],$_POST['price'],$_POST['location']]);
header('Location:index.php');exit;}
?>
<h1>Filament hinzufügen</h1>
<form method='post'>
<input name='brand' placeholder='Marke'><br><br>
<select name='material'><option>PLA</option><option>PETG</option><option>ASA</option><option>ABS</option></select><br><br>
<input name='color' placeholder='Farbe'><br><br>
<input name='weight' type='number' value='1000'><br><br>
<input name='price' type='number' step='0.01' placeholder='Preis'><br><br>
<input name='location' placeholder='Lagerort'><br><br>
<button>Speichern</button>
</form>