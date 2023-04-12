<?php
session_start();
 ?>
            <table class="table table-bordered table-sm text-white mt-4">
                <tr class="table-success text-dark">
                    <td>Nombre</td>
                    <td>Apellido</td>
                    <td>Tarjeta</td>
                    <td>Grado</td>
                    <td>Saldo</td>
                    <td>Revisar</td>
                </tr>
            
      <?php
      if (isset($_SESSION['consulta_temp_almuerzos'])):
        foreach (@$_SESSION['consulta_temp_almuerzos'] as $key) {
        $dat=explode("||", $key);
       ?>
       <tr>
                <td><?php echo $dat[1]; ?></td>
                <td><?php echo $dat[2]; ?></td>
                <td><?php echo $dat[3]; ?></td>
                <td><?php echo $dat[4]; ?></td>
                <td class="<?php if($dat[5]<0){ echo 'bg-light text-dark'; } ?>"><?php echo $dat[5]; ?></td>
                <td class="d-flex justify-content-center"><button class="btn btn-warning" onclick="consulta('<?php echo $dat[0]; ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                      </svg>
                </button></td>
        </tr>

<?php }  endif;?>
</table>
    