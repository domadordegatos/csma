<?php
session_start();
$total_pagar=0;
?>
<h3 class="text-white">Productos a comprar</h3><?php
if (isset($_SESSION['carrito_temp_almuerzos'])):
  $i=0;  foreach (@$_SESSION['carrito_temp_almuerzos'] as $key) {
  $dat=explode("||", $key)  ?>
        <button class="btn btn-outline-light" onclick="quitarproducto('<?php echo $i; ?>')">
          <img src="../media/recursos/productos/<?php echo $dat[0]; ?>.png" alt="" width="75px" height="75px">
        </button>
<?php  
$total_pagar=(int)$total_pagar + (int)$dat[2]; 

    $i++;   
    }  ?>
    
    <table class="table table-bordered table-sm">
    <tr>
      <td>Total pagar</td>
      <td class="bg-success"><?php echo "$".$total_pagar; ?></td>
    </tr>
  </table>
    <?php
    endif;   ?>