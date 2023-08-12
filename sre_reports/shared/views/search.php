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

?>
<style>
    .error_border{border:  1px solid red !important ;}
    .error_span{font-size: 12px; color: red; font-size: tahoma; cursor:default;}
    /* css for timepicker */
    .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
    .ui-timepicker-div dl { text-align: left; }
    .ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
    .ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
    .ui-timepicker-div td { font-size: 90%; }
    .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
    .ui-slider { position: relative; text-align: left; }
    .ui-slider .ui-slider-handle { position: absolute; z-index: 2; width: 1.2em; height: 1.2em; cursor: default; }
    .ui-slider .ui-slider-range { position: absolute; z-index: 1; font-size: .7em; display: block; border: 0; background-position: 0 0; }

    .ui-slider-horizontal { height: .8em; }
    .ui-slider-horizontal .ui-slider-handle { top: -.3em; margin-left: -.6em; }
    .ui-slider-horizontal .ui-slider-range { top: 0; height: 100%; }
    .ui-slider-horizontal .ui-slider-range-min { left: 0; }
    .ui-slider-horizontal .ui-slider-range-max { right: 0; }

    .ui-slider-vertical { width: .8em; height: 100px; }
    .ui-slider-vertical .ui-slider-handle { left: -.3em; margin-left: 0; margin-bottom: -.6em; }
    .ui-slider-vertical .ui-slider-range { left: 0; width: 100%; }
    .ui-slider-vertical .ui-slider-range-min { bottom: 0; }
    .ui-slider-vertical .ui-slider-range-max { top: 0; }

</style>

<script src="../shared/Js/jquery-1.7.2.js"></script>
<script src="../shared/Js/jquery.ui.core.min.js"></script>
<script src="../shared/Js/jquery.ui.widget.min.js"></script>
<script src="../shared/Js/jquery.ui.datepicker.min.js"></script>

<link rel="stylesheet" type="text/css" href="../shared/Js/ui-lightness/jquery.ui.all.css" />
<link rel="stylesheet" type="text/css" href="../shared/Js/ui-lightness/jquery.ui.datepicker.css" />
<style type="text/css">
    .ui-datepicker{ font-size: 13px;}

</style>



<?php
$display_search = 'none';
if (isset($_CLEANED['btnSearch']))
    $display_search = 'block';
?>
<div style="position: absolute; left: 25.6%;">
    <div class="search" style="display: <?php echo $display_search; ?>;">
        <img src="../shared/images/icons/close.png" style="cursor: pointer; float: right;" id="CloseSearch" />
        <form action="<?php echo basename($_SERVER["PHP_SELF"]); ?>" method="post">
          
            <input type="hidden" name="RequestToken"  value=<?php echo $request_token_value; ?> />
            <table border="0" cellpadding="0" cellspacing="0">

                <tr>
                    <td class="small-lbl">
                        <?php echo escape($field_lang) ?>: 
                    </td>
                    <td>
                        <select id="SearchField" name="SearchField" class="search-txtbox" style="width: 110px;padding-right: 5px;">
                            <?php printOption(); ?>
                        </select>   
                    </td> 
                    <td class="small-lbl">
                        <?php echo escape($kayword_lang) ?>:  
                    </td>
                    <td>
                        <div id="search_feild_div"></div>
                    </td>

                    <td style="width: 200px; text-align: right;">
                        <input type="submit" class="srch-btn" value="<?php echo escape($search_lang); ?>" id="btnSearch" name="btnSearch" />

                        <input type="submit" class="srch-btn" value="<?php echo escape($show_all_lang); ?>" id="btnShowAll2" name="btnShowAll" />

                    </td> 

                </tr>

            </table>
            <input type="hidden"   id="HdSearchval" name="HdSearchval"  />
        </form>
    </div>
</div>

<script language="JavaScript" type="text/javascript">
    var dataStr = new Array("text", "time");
    var dataInt = new Array("number");
    var dataDate = new Array("date", 'datetime');
    var dataBool = new Array("YesNo");
    var run = false;


    if (!Array.prototype.indexOf)
    {
        Array.prototype.indexOf = function (elt /*, from*/)
        {
            var len = this.length >>> 0;
            var from = Number(arguments[1]) || 0;
            from = (from < 0)
                    ? Math.ceil(from)
                    : Math.floor(from);
            if (from < 0)
                from += len;
            for (; from < len; from++)
            {
                if (from in this &&
                        this[from] === elt)
                    return from;
            }
            return -1;
        };
    }

    $(function () {


        var def_value = '<?php echo get_default_value('keyWord') ?>';
        var def_value2 = '<?php echo get_default_value('keyWord2') ?>';
        // var show_advanced = <?php
                        if (!empty($btnSearch))
                            echo 'true';
                        else
                            echo 'false';
                        ?>;
        $('#SearchAdvanced').click(function () {
            $('.search').show();
        });
        $('#CloseSearch').click(function () {
            $('.search').hide();
        });
        $("#SearchField").change(function () {
            reconstruct_search();
            $("#keyWord").val("");
            $("#keyWord2").val("");
            $('#txtordnarySearch').val('');
        });
        $("#btnShowAll,#btnShowAll2").click(function () {
            $("#keyWord").val("");
            $("#keyWord2").val("");
            $('#txtordnarySearch').val('');
            $("#SearchField").val(0);
        });
        $("#btnSearch").click(function () {


            if (dataInt.indexOf($("#SearchField option:selected").attr("dat")) > -1)
            {
                var Return = true;
                if (!TryParseInt($("#keyWord").val()) && $("#keyWord").val() != '')
                {
                    if (!($("#keyWord").next().is('span')))
                        $("#keyWord").after($('<span class="error_span" title="Please enter a valid numeric value !!">*</span>'));
                    Return = false;
                }
                if (!TryParseInt($("#keyWord2").val()) && $("#keyWord2").val() != '')
                {
                    if (!($("#keyWord2").next().is('span')))
                        $("#keyWord2").after($('<span class="error_span" title="Please enter a valid numeric value !!">*</span>'));
                    Return = false;
                }
                if (!Return)
                    return false;
                $("#HdSearchval").val("int");
            } else if (dataBool.indexOf($("#SearchField option:selected").attr("dat")) > -1)
            {
                //		
                $("#HdSearchval").val("bool");
                //		}
            } else if (dataDate.indexOf($("#SearchField option:selected").attr("dat")) > -1)
            {
                $("#HdSearchval").val("date");
            } else
            {

                $("#HdSearchval").val("string");
            }
        });

        //$("#SearchField").change();
        function reconstruct_search() {
            
            $('#search_feild_div').empty();

            var dataty = $("#SearchField option:selected").attr("dat");
            //$('#keyWord').timepicker('disable');

            if (dataBool.indexOf(dataty) > -1)
            {
                var selected = '';
                if (def_value == '0' && !run)
                    selected = 'selected';
                $('#search_feild_div').append('<select style="width:100px; margin:2px;" class="search-txtbox" name="keyWord" typ="' + dataty + '" id="keyWord"><option value="1">True</option><option value="0" ' + selected + '>False</option></select>');
            } else if (dataInt.indexOf(dataty) > -1)
            {
                var val = '';
                var val2 = '';
                if (!run)
                {
                    val = def_value;
                    val2 = def_value2;
                }
                $('#search_feild_div').append('<input style="width:70px; margin:2px;" placeholder="<?php echo escape($from_lang); ?>" name="keyWord" value="' + val + '" type="text" typ="' + dataty + '" id="keyWord" class="search-txtbox" /><input  style="width:70px; margin:2px;" placeholder="<?php echo escape($To_lang); ?>"  name="keyWord2" value="' + val2 + '" type="text" typ="' + dataty + '" id="keyWord2" class="search-txtbox" />');
                $(':text').blur();
            } else if (dataDate.indexOf(dataty) > -1)
            {

                var val = '';
                var val2 = '';
                if (!run)
                {
                    val = def_value;
                    val2 = def_value2;
                }
                $('#search_feild_div').append('<div class="i-date-1"><input class="input-field input-date-1 datepicker" style="width:123px;" placeholder="<?php echo escape($from_lang); ?>"  name="keyWord" value="' + val + '" type="text" typ="' + dataty + '" id="keyWord" /></div><div class="i-date-1"><input class="input-field input-date-2 datepicker" style="width:123px;" placeholder="<?php echo escape($To_lang); ?>"  name="keyWord2" value="' + val2 + '" type="text" typ="' + dataty + '" id="keyWord2"  /></div>');
                $(':text').blur();


            } else {
                var val = '';
                if (!run)
                    val = def_value;
                $('#search_feild_div').append('<input class="search-txtbox" style="width:140px; margin:2px;" name="keyWord" value="' + val + '" type="text" typ="' + dataty + '" id="keyWord" />');
            }


            if (dataty == 'datetime' || dataty == 'date')
                //      $(this).datetimepicker({showSecond: true, timeFormat: 'hh:mm:ss', changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd'});
                run = true;
            $(".datepicker").datepicker(
                    {
                        dateFormat: "yy-mm-dd"
                    });
            $('.input-date-1').focus();


        }
        reconstruct_search();
    });

    function TryParseInt(str)
    {
        var retValue = false;
        if (str != null)
        {
            if (str.length > 0)
            {
                if (!isNaN(str))
                {
                    str = parseInt(str);
                    retValue = true;
                    if (isNaN(str))
                    {
                        retValue = false;
                    }
                }
            }
        }
        return retValue;
    }

    $(function () {
        if (!$.support.placeholder) {
            var active = document.activeElement;
            $('#search_feild_div').on('focus', ':text', function () {

                if ($(this).attr('placeholder') != '' && $(this).val() == $(this).attr('placeholder')) {

                    $(this).val('').removeClass('hasPlaceholder');
                }
            });
            $('#search_feild_div').on('blur', ':text', function () {
                if ($(this).attr('placeholder') != '' && ($(this).val() == '' || $(this).val() == $(this).attr('placeholder'))) {

                    $(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
                }
            });
            $(':text').blur();
            $(active).focus();
        }
    });
</script>
<div class="clear" ></div>
