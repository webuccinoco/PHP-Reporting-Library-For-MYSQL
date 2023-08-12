<?php
if (!defined('DIRECTACESS'))
    exit('No direct script access allowed');

?>



    <!-- ******************** start custom header ******************** !-->

    <?php
    if (!empty($header)) {
        ?>

        <tr>

            <td class="headerfooter" colspan="<?php echo $actual_columns_count ?>" valign="top"><?php echo($header); ?></td>

        </tr>



        <?php
    }
    ?>


    <!-- ******************** end custom header ******************** !-->

    

        
        
 
    <tr>

        <td class="Separator" colspan="<?php echo $actual_columns_count ?>"></td>

    </tr>

    <?php
    if ($empty_search_parameters) {
        if (check_debug_mode() == 1) {
            send_log_info($maintainance_email);
        }
        die("<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='MainGroup'>$empty_search_parameters_lang</td></tr>");
    }

    if ($possible_attack == true) {
        if (check_debug_mode() == 1) {
            send_log_info($maintainance_email);
        }
        die("<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='MainGroup'>$no_specials_lang</td></tr>");
        exit();
    }

    if ($nRecords === 0 || $empty_Report) {
        if (check_debug_mode() == 1) {
            send_log_info($maintainance_email);
        }
        die("<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='MainGroup'>$empty_report_lang</td></tr>");
    }


   