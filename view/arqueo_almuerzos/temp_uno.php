<?php
session_start();
?>
<table class="table table-bordered table-sm text-white mt-4">
    <tr class="table-light text-dark text-uppercase">
        <td>#Id</td>
        <td>Nombre</td>
        <td>Movimiento</td>
        <td>Admin</td>
        <td>Valor</td>
        <td>Medio Pago</td>
        <td>Fecha</td>
        <td>Hora</td>
    </tr>

<?php
if (isset($_SESSION['datos_uno_varios_arqueo_almuerzos'])):
    $transferencia = 0; 
    $efectivo = 0; 
    $abonos = 0; 
    $cargas = 0;
    $adminsEfectivo = []; // Array para almacenar los administradores y el total recibido en efectivo

    foreach (@$_SESSION['datos_uno_varios_arqueo_almuerzos'] as $key) {
        $dat = explode("||", $key);
?>
    <tr class="table-sm text-dark">
        <td><?php echo $dat[0]; ?></td>
        <td class="text-uppercase"><?php echo $dat[1] . ' ' . $dat[2]; ?></td>
        <td class="<?php if (in_array($dat[3], ['carga'])) { echo 'table-success'; }else{ echo 'table-primary'; }?> "><?php echo $dat[3]; ?></td>
        <td class="text-center"><?php echo $dat[8]; ?></td>
        <td class="<?php if (in_array($dat[3], ['carga'])) { echo 'table-success'; }else{ echo 'table-primary'; }?> "><?php echo number_format($dat[4]); ?></td>
        <td class="<?php if($dat[5] == 'Efectivo'){ echo 'table-info';} else if($dat[5] == 'Transferencia'){ echo 'table-warning';} ?>"><?php echo $dat[5]; ?></td>
        <td><?php echo $dat[6]; ?></td>
        <td><?php echo $dat[7]; ?></td>

        <?php 
            if ($dat[3] == 'abono') { 
                $abonos += $dat[4]; 
            } else if ($dat[3] == 'carga') { 
                $cargas += $dat[4];
            }

            if (in_array($dat[3], ['carga', 'abono']) && $dat[5] == 'Efectivo') { 
                $efectivo += $dat[4]; 

                // Almacenar el administrador y sumar el total recibido en efectivo
                if (!isset($adminsEfectivo[$dat[8]])) {
                    $adminsEfectivo[$dat[8]] = 0;
                }
                $adminsEfectivo[$dat[8]] += $dat[4];
            }

            if (in_array($dat[3], ['carga', 'abono']) && $dat[5] == 'Transferencia') { 
                $transferencia += $dat[4];
            }
        ?>
    </tr>

<?php } ?>

    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Abonos</td>
        <td class="table-light" colspan="2"><?php echo '$'.number_format($abonos); ?></td>
    </tr>
    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Cargas</td>
        <td class="table-light" colspan="2"><?php echo '$'.number_format($cargas); ?></td>
    </tr>
    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Total + Efectivo</td>
        <td class="table-light" colspan="2"><?php echo '$'.number_format($efectivo); ?></td>
    </tr>
    <tr class="text-dark">
        <td class="table-secondary" colspan="4"></td>
        <td class="table-secondary">Total + Transferencias</td>
        <td class="table-light" colspan="2"><?php echo '$'.number_format($transferencia); ?></td>
    </tr>

    <!-- Retroalimentación: Administradores que realizaron cargas en efectivo -->
    <tr class="text-dark">
        <td class="table-secondary" colspan="8">
            <strong>Administradores que realizaron cargas en efectivo hoy:</strong>
            <ul>
                <?php foreach ($adminsEfectivo as $admin => $total) { ?>
                    <li><?php echo $admin . ' - Total: $' . number_format($total); ?></li>
                <?php } ?>
            </ul>
        </td>
    </tr>

<?php endif; ?>
</table>
