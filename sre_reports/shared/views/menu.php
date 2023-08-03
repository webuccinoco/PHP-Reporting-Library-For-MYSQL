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
if ($layout == "horizontal") {
    $span = "colspan='" . 2 . "'";
    $actual_columns_count = 2;
    $align = ($language == "ar" || $language == "iw" ) ? "right" : "left";
} else {
    $span = "colspan='" . count($fields) . "'";
    $align = ($language == "ar" || $language == "iw" ) ? "right" : "left";
}


$dataty = array(
    'varchar',
    'char',
    'text',
    'int',
    'decimal',
    'double',
    'smallint',
    'float',
    'datetime',
    'date',
    'time',
    'year',
    'bit',
    'bool'
);
$dataStr = array(
    'varchar',
    'char',
    'text'
);
$dataInt = array(
    'int',
    'decimal',
    'double',
    'smallint',
    'float'
);
$dataDate = array(
    'datetime',
    'date',
    'time',
    'year',
    'timestamp'
);
$dataBool = array(
    'bit',
    'bool',
    'tinyint'
);

$cond = "";
$params = array();
$types = "";
foreach ($table as $value) {
    $cond .= "table_name = '$value' or ";
// array_push($params, $value);
// $types .="s";
}
$cond .= ")";
$cond = str_replace("or )", " ", $cond);
$flush = true; // Last request in the process
// $resultcon = query( "SELECT table_name,COLUMN_NAME ,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS"
// ." WHERE $cond","Menu: Get Data Types",$params,$types);

$resultcon = query("SELECT table_name,COLUMN_NAME ,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS" . " WHERE  TABLE_SCHEMA = '" . $DB_NAME . "' and ( $cond )", "Menu: Get Data Types");

function lower(&$string) {
    $string = strtolower($string);
}

/**
 * * apply the lower function to the array **
 */
array_walk($fields2, 'lower');

$data = array();

if (is_array($resultcon)) {
    foreach ($resultcon as $row) {
        $fild = array();

// if(in_array($row['DATA_TYPE'],$dataty) && in_array(((count($table)==1)?"":strtolower($row['table_name']).".").strtolower($row['COLUMN_NAME']),$fields2) )
// {

        foreach ($row as $k => $v) {
            $fild [] = $v;
        }
        if (!in_array($fild, $data)) {
            $data [] = $fild;
        }
// }
    }
}

function printOption() {

    global $data, $table, $labels, $fields, $_CLEANED;

    $posted_field = isset($_CLEANED["SearchField"]) ? $fields[$_CLEANED["SearchField"] - 1] : "";
    foreach ($data as $val) {
      
        if(in_array(strtolower($val [1]),array_map("strtolower",$fields)))  
          $fild =   strtolower($val [1]);
        else
          continue;
            


        if ($posted_field == $fild)
            echo " <option value='" . get_numeric_index($fild, array_map("strtolower",$fields)) . "' dat='" . map_datatype($val [2]) . "' selected>" . query_associative_array_by_key_insenstive($labels, $fild) . "</option>\n ";
        else
            echo " <option value='" . get_numeric_index($fild, array_map("strtolower",$fields)) . "' dat='" . map_datatype($val [2]) . "' >" . query_associative_array_by_key_insenstive($labels, $fild) . "</option>\n ";
       
    }
}
?>
<?php
if (!($_startRecord_index + $records_per_page < $nRecords))
    $link_next = '#';
if ($_startRecord_index <= 0)
    $link_prev = '#';
?>
<?php
if ($_print_option == 0) {
    ?>

    <div lang="<?php echo escape($language); ?>" class="menu"
         style="z-index: 1; text-align: center;" >
        <div class="menu-container" style="position: relative; width: 100%; margin-right: auto; margin-left: auto;">
            <div class="nav-holder">



                <ul class="nav-menu clear">
                    <?php
                    if ($allow_change_style === "yes") {
                        ?>

                        <li class="theme theme-icon">
                            <a   href="#" class="menu_hvr changeTheme" target_class=".sub6" title="Change theme">                            
                                <img src="../shared/images/menu/settings.png"  style="vertical-align: middle;margin-bottom: 5px;" />
                                <span class="themesTxt">

                                    <?php echo $change_theme_lang; ?>
                                </span>
                            </a>
                            <ul class="sub-menu sub6 first-sub-menu special-dropdown">
                                <li class="menu-item-li">

                                    <a
                                        href='<?php echo "ChangeStyle.php?setStyle=default" . "&&RequestToken=$request_token_value"; ?>'>
                                        <span class="style-one square-theme"></span>
                                        <?php echo escape($default_lang); ?>
                                    </a>
                                </li>
                                <li class="menu-item-li">

                                    <a
                                        href='<?php echo "ChangeStyle.php?setStyle=blue" . "&&RequestToken=$request_token_value"; ?>'>
                                        <span class="style-two square-theme"></span>
                                        <?php echo escape($blue_lang); ?>
                                    </a>
                                </li>
                                <li class="menu-item-li">

                                    <a
                                        href='<?php echo "ChangeStyle.php?setStyle=grey" . "&&RequestToken=$request_token_value"; ?>'>
                                        <span class="style-three square-theme"></span>
                                        <?php echo escape($grey_lang); ?>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <?php
                    }
                   
                    if (!empty($chkSearch) && strtolower($chkSearch) == "yes" && $datasource == 'table') {
                        
                        ?>
                        <li class="header-search">
                            <form action="<?php echo basename($link_home); ?>" method="post">

                                <input type="hidden" name="RequestToken"  value=<?php echo $request_token_value; ?> />
                                <input type="text" class="search-txtbox" name="txtordnarySearch"
                                       value="<?php echo get_default_value('txtordnarySearch'); ?>"
                                       id="txtordnarySearch"
                                       placeholder="<?php echo escape($Enter_your_search_lang); ?>" /> <input
                                       type="submit" class="srch-btn" name="btnordnarySearch"
                                       value="<?php echo escape($search_lang); ?>"
                                       id="txtordnarySearch" /> <a href="#" id="SearchAdvanced"
                                       class="srch-btn advanced-link"
                                       title="<?php $advanced_search_lang; ?>"><img
                                        src="../shared/images/icons/tridown.gif" alt=""></a> <input
                                    type="submit" class="srch-btn"
                                    value="<?php echo escape($show_all_lang); ?> " id="btnShowAll"
                                    name="btnShowAll" />


                            </form>
                            <?php
                            if (!empty($chkSearch) && strtolower($chkSearch) == "yes") {
                                require_once ("search.php");
                            }
                            ?>

                        </li>
                    <?php } ?>


                </ul>




                <!----2 level sub--->

            </div>


        </div>

    </div>



    <br />



    <script type="text/javascript">
        //fix ie menu
        if (navigator.appName == 'Microsoft Internet Explorer')
        {
            $('.search').css('margin-top', '0px');
            $('.search').css('z-index', '-1');
        }

        var close = false;
        $(function () {
            $('.menu_hvr').mouseover(function () {
                $('.sub-menu').not($($(this).attr('target_class'))).hide();
                $($(this).attr('target_class')).slideDown();
                close = false;
            });

            $('.menu_hvr').mouseleave(function () {
                close = true;
                setTimeout(function () {
                    if (close)
                    {
                        $('.sub-menu').not($($(this).attr('target_class'))).hide();
                    }

                }, 500);
            });
            $('.sub-menu').mouseover(function () {
                close = false;
            });
            $('.sub-menu').mouseleave(function () {
                $('.menu_hvr').mouseleave();
            });

            $('.menu_hvr_sub').mouseover(function () {
                $('.sub_sub').not($($(this).attr('target_class'))).hide();
                $($(this).attr('target_class')).slideDown();
            });
        });
    </script>

    <?php
}
?>

