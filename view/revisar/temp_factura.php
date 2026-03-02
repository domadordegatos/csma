<?php
session_start();
 ?>
            <table class="table table-bordered table-sm text-white mt-4">
                <tr class="table-success text-dark">
                    <td>Vendedor</td>
                    <td>Producto</td>
                    <td>Total</td>
                    <td>Valor anterior</td>
                    <td>Valor nuevo</td>
                </tr>
            
      <?php
      $total=0;
      if (isset($_SESSION['consulta_temp_factura'])):
        foreach (@$_SESSION['consulta_temp_factura'] as $key) {
        $dat=explode("||", $key);
       ?>
       <tr>
                <td><?php echo $dat[1]; ?></td>
                <td><?php echo $dat[2]; ?></td>
                <td><?php echo $dat[3]; ?></td>
                <td><?php echo $dat[4]; ?></td>
                <td class="bg-info"><?php echo $dat[5]; ?></td>
        </tr>

<?php $total=$total+$dat[3]; } ?> 
    <tr>
        <td class="bg-success text-align-right" colspan="5">Total: $<?php echo $total; ?> </td>
    </tr>

<?php endif;?>
</table>
    