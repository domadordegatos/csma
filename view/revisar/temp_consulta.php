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
        <td>Imprimir</td> <td>Revisar</td>
    </tr>
            
    <?php
    if (isset($_SESSION['consulta_temp'])):
        foreach (@$_SESSION['consulta_temp'] as $key) {
            $dat=explode("||", $key);
    ?>
    <tr>
        <td class="text-uppercase"><?php echo $dat[1]; ?></td>
        <td class="text-uppercase"><?php echo $dat[2]; ?></td>
        <td><?php echo $dat[3]; ?></td>
        <td><?php echo $dat[5]; ?></td>
        <td class="<?php if($dat[4]<0){ echo 'bg-light text-dark'; } ?>"><?php echo $dat[4]; ?></td>
        
        <td class="text-center">
            <button class="btn btn-danger btn-sm" onclick="imprimirReporteDetallado('<?php echo $dat[0]; ?>')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
  <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
  <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>
</svg>
            </button>
        </td>

        <td class="d-flex justify-content-center">
            <button class="btn btn-warning" onclick="consulta('<?php echo $dat[0]; ?>')">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
            </button>
        </td>
    </tr>
<?php } endif; ?>
</table>