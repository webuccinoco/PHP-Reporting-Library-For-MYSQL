<?php
/**
 * Smart Report Engine
 * Community Edition
 * Author : Webuccino 
 * All copyrights are preserved to Webuccino
 * URL : https://mysqlreports.com/
 *
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");
if (isset($calculated_columns) && is_array($calculated_columns)) {
    foreach ($calculated_columns as $key => $value) {
        $fields[] = $key;
        $labels[$key] = $key;
        $cells[$key] = "value";
    }
}
$actual_fields = array_diff($fields, $group_by); // actual columns which will be shown without group by fields

$actual_columns_count = count($actual_fields); // number of columns to be shown

$group_by_count = count($group_by);
$group_by_source = $group_by;

$fields_source = $fields;
$actual_fields_source = array_values(array_diff($fields, $group_by));
?>
<!DOCTYPE HTML>

<html  <?php
if ($language == "he" || $language == "ar") {
    echo "dir = 'rtl'";
}
?>>

    <head>

        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../shared/Js/lightbox/css/lightbox.min.css" />

        <script type="text/javascript" src="../shared/Js/jquery-2.2.3.min.js"></script>

        <title><?php echo escape(strtoupper($title)) ?></title>
        <link href="<?php echo "../shared/styles/common.css"; ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo "../shared/styles/" . $style_name . ".css"; ?>" rel="stylesheet" type="text/css" />
        <?php if ($_print_option != 0) echo "<link href='../shared/styles/print.css'  rel='stylesheet' type='text/css' />"; ?>

    </head>



    <body class="MainPage">
        <?php
        require_once 'menu.php';
        echo ' <div class="container" ';
        echo_width($_print_option);
        echo ">";
        echo '<table border="0"';
        echo_width($_print_option);
        echo ' align="center" cellpadding="2" cellspacing="0" class="MainTable">';

        require_once 'actions.php';
        require_once 'header.php';
        require_once "../shared/views/layout_views/AlignLeft.php";
        require_once "pagger.php";

        echo "</table>";
        echo "</div>";


        if ($_print_option == 3) {
            ?>

            <script>

                window.print();

            </script>

            <?php
        }
        ?>

        <!-- ************************* End Of Show print Dialog ********************* !-->
        <script type="text/javascript" src="../shared/Js/lightbox/js/lightbox.min.js"></script>
        <script>
            $(document).ready(function () {



                $.fn.stars = function () {
                    return $(this).each(function () {
                        // Get the value
                        var val = parseFloat($(this).html());
                        // Make sure that the value is in 0 - 5 range, multiply to get width
                        var size = Math.max(0, (Math.min(5, val))) * 16;
                        // Create stars holder
                        var $span = $('<span />').width(size);
                        // Replace the numerical value with stars
                        $(this).html($span);
                    });
                }


                var datasource = <?php echo "'" . escape($datasource) . "';"; ?>
                //    if (datasource == 'sql') {
                //        $("#txtordnarySearch").css('visibility', 'hidden');
                //        $(".srch-btn").css('visibility', 'hidden');
                //        $("#search_advanced").css('visibility', 'hidden');

                //    }
                $('span.stars').stars();



            }
            );

        </script>


    </script>

</body>

</html>

