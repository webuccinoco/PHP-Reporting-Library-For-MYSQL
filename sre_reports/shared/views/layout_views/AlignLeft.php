<?php
$current_value = "intial";


foreach ($result as $row) {

 



   
        $sub_total_group_by =  "";
   

    



    foreach ($group_by as $key => $val) {


        $cur_group_ar [$val] = query_associative_array_by_key_insenstive($row,$val);
    }

    // print group by fields in case of grouping values variation



    if (count($last_group_ar) != 0) {

        $diff_index = grouping_diff_index($cur_group_ar, $last_group_ar);
    } else {

        $diff_index = 0;
    }

    if ($diff_index != - 1) {

        for ($i = $diff_index; $i < count($group_by_source); $i ++) {



            if ($i == 0 && $diff_index == 0) {

                echo "<tr><td class='MainGroup'  colspan=" . $actual_columns_count . " ><span style='float:" . $align . "'>" . $group_by_source [$i] . ":&nbsp;</span>" .
                       query_associative_array_by_key_insenstive($row,$group_by [$i]) . " </td></tr>";
            } else {
                echo "<tr><td class='SubGroup'  colspan=" . $actual_columns_count . " ><span style='float:" . $align . ";'>" . $group_by_source [$i] . ":&nbsp;</span> " . 
                        query_associative_array_by_key_insenstive($row,$group_by [$i])  . "</td></tr>";
            }
        }

        // echo"<tr><td height='15' $span class='TableHeader'></td></tr>";

        if ($cur_row == 0) {
            ?>



            <tr>

                <td>
                    <table <?php
            // width of report

            if ($_print_option != 0)
                echo "width='700'";
            else
                echo "width ='100%'";
            ?> cellspacing="0" cellpadding="2" align='center' class="inner-data-table">

                        <?php
                    }
                }
                ?>



                <?php
                // print table columns
                draw_table_headers($group_by_count, $diff_index, $cur_row, $actual_fields_source, $group_by, $fields, false, array("stock_value" => ""));

                // print row data

                echo "<tr class='data-row'>";

                foreach ($actual_fields as $key => $val) {

                    if ($toggle_row == 0)
                        if (query_associative_array_by_key_insenstive($row,$val) === "")
                            echo "<td class='AlternateTableCell'>" . "&nbsp;". "</td>";
                        else
                            echo "<td class='AlternateTableCell'>" . query_associative_array_by_key_insenstive($row,$val). "</td>";

                    else

                    if (query_associative_array_by_key_insenstive($row,$val) === "")
                        echo "<td class='AlternateTableCell'>" . "&nbsp;" . "</td>";
                    else
                        echo "<td class='AlternateTableCell'>" . query_associative_array_by_key_insenstive($row,$val). "</td>";
                }
                /* if (get_primary_key_column()) {
                  $Primary = get_record_primary_key_value($row);
                  echo '<td valign="middle" class="TableCell" ><a href="' . "Detailed-view.php" . '?detail=' . $Primary . '" title="' . $detail_view_lang . '" ><img src="../shared/images/icons/row-print.png" alt="Detail View"></a></td>';

                  } */
                echo "</tr>";

                // change toggling of rows

                if ($toggle_row == 0)
                    $toggle_row = 1;
                else
                    $toggle_row = 0;

                // update new grouping

                if ($diff_index != - 1) {

                    $last_group_ar = array();

                    foreach ($group_by as $key => $val) {

                        $last_group_ar [$val] = query_associative_array_by_key_insenstive($row,$val);
                    }
                }
             

                $cur_row ++;
            }
            ?>



        </table>
    </td>

</tr>

<!-- ******************** start custom footer ******************** !-->

<?php
if (!empty($footer)) {

    echo "<tr><td class='headerfooter'> $footer</td></tr>";
}

function draw_table_headers($group_by_count, $diff_index, $cur_row, $actual_fields, $group_by, $fields, $is_grand_total = false) {
    if ($is_grand_total || (($group_by_count > 0 && $diff_index != - 1) || $cur_row == 0)) { // if there is a change in grouping
        echo "<tr>";
        foreach ($actual_fields as $key => $val) {
            if (strstr($val, ".")) {
                $temp = explode('.', $val);
                $field_ = $temp [1];
            } else {
                $field_ = $val;
            }


            if (in_array($field_, $group_by))
                continue;




            echo "<td align='center' class='ColumnHeader'>$val</td>";
        }

        echo "</tr>";
    }
}
?>



