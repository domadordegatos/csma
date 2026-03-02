<?php
session_start();
 ?>
            <table class="table table-bordered table-sm text-white mt-4">
                <tr class="table-success text-dark">
                    <td># Factu</td>
                    <td>Vendedor</td>
                    <td>Producto</td>
                    <td>Total</td>
                    <td>Valor anterior</td>
                    <td>Valor nuevo</td>
                    <td>Fecha</td>
                    <td>Hora</td>
                    <td>Detalle</td>
                </tr>
            
      <?php
      if (isset($_SESSION['consulta_temp_detallada'])):
        foreach (@$_SESSION['consulta_temp_detallada'] as $key) {
        $dat=explode("||", $key);
       ?>
       <tr style="font-size: 0.7rem;" class="table-sm">
                <td><?php echo $dat[0]; ?></td>
                <td><?php echo $dat[1]; ?></td>
                <td><?php echo $dat[2]; ?></td>
                <td><?php echo $dat[3]; ?></td>
                <td><?php echo $dat[4]; ?></td>
                <td><?php echo $dat[5]; ?></td>
                <td class="bg-success"><?php echo $dat[6]; ?></td>
                <td class="bg-success"><?php echo $dat[7]; ?></td>
                <td class="d-flex justify-content-center"><button class="btn btn-warning" onclick="consulta_factura('<?php echo $dat[0]; ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="7" height="7" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                      </svg>
                </button></td>
        </tr>

<?php }  endif;?>
</table>