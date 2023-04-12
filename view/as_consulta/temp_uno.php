<?php
session_start();
 ?>
            <table class="table table-bordered table-sm text-white mt-4">
                <tr class="table-light text-dark">
                    <td>#Id</td>
                    <td>Usuario</td>
                    <td>Rol</td>
                    <td>Fecha</td>
                    <td>Hora</td>
                </tr>
            
      <?php
      if (isset($_SESSION['datos_uno_varios'])):
        foreach (@$_SESSION['datos_uno_varios'] as $key) {
        $dat=explode("||", $key);
       ?>
       <tr class="table-sm">
                <td><?php echo $dat[0]; ?></td>
                <td><?php echo $dat[1].' '.$dat[2]; ?></td>
                <td><?php echo $dat[3]; ?></td>
                <td><?php echo $dat[4]; ?></td>
                <td><?php echo $dat[5]; ?></td>
        </tr>

<?php }  endif;?>
</table>