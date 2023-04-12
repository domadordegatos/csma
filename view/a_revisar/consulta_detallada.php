<?php
session_start();
 ?>
            <table class="table table-bordered table-sm text-white mt-4">
                <tr class="table-success text-dark">
                    <td># Factu</td>
                    <td>Vendedor</td>
                    <td>Cant</td>
                    <td>Tipo</td>
                    <td>Tipo 2</td>
                    <td>Val. Anterior</td>
                    <td>Val. Nuevo</td>
                    <td>Fecha</td>
                    <td>Hora</td>
                </tr>
            
      <?php
      if (isset($_SESSION['consulta_temp_detallada_almuerzos'])):
        foreach (@$_SESSION['consulta_temp_detallada_almuerzos'] as $key) {
        $dat=explode("||", $key);
       ?>
       <tr style="font-size: 0.7rem;" class="table-sm">
                <td><?php echo $dat[0]; ?></td>
                <td><?php echo $dat[1]; ?></td>
                <td class="text-center"><?php echo $dat[2]; ?></td>
                <td><?php echo $dat[7]; ?></td>
                <td><?php echo $dat[8]; ?></td>
                <td><?php echo $dat[3]; ?></td>
                <td><?php echo $dat[4]; ?></td>
                <td class="bg-success"><?php echo $dat[5]; ?></td>
                <td class="bg-success"><?php echo $dat[6]; ?></td>
        </tr>

<?php }  endif;?>
</table>