<?php
session_start();
 ?>
            <table class="table table-bordered table-sm text-white mt-4">
                <tr class="table-success text-dark">
                    <td>Id</td>
                    <td>Nombre</td>
                    <td>Tarjeta</td>
                    <td>Grado</td>
                </tr>
            
      <?php
      if (isset($_SESSION['consulta_users'])):
        foreach (@$_SESSION['consulta_users'] as $key) {
        $dat=explode("||", $key);
       ?>
       <tr>
                <td><?php echo $dat[0]; ?></td>
                <td><?php echo $dat[1]." ".$dat[2]; ?></td>
                <td class="bg-info"><?php echo $dat[3]; ?></td>
                <td class="bg-success"><?php echo $dat[4]; ?></td>
        </tr>

<?php }  endif;?>
</table>
    