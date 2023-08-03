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

function render($val, $type, $column, $is_conditional_formatting = true, $rating = true, $country = true) {
    global $images_path, $_CLEANED, $output_escaping, $cells,$calculated_columns;
          
    if ($val === "&nbsp;" || $val === "" || !isset($cells))
        return $val;
    // output escaping
    if (isset($output_escaping) && $output_escaping == "Yes")
        $val = escape($val);

    if ($type == "stars" && $rating == true)
        return render_stars($val);
    else if (strstr($type, "append"))
        return parse_conditional_formatting(render_append($val, $type), $column, $is_conditional_formatting); // the typr contins the currency

    else if ($type == "link")
        return render_link($val);
    else if ($type == "image")
        return render_image($val, $images_path);
    else if ($type == "country" && $country == true)
        return render_country($val);

    else if ($type == "bit")
        return render_bit($val);
    else
        return parse_conditional_formatting($val, $column, $is_conditional_formatting);
}

function parse_conditional_formatting($val, $column, $is_conditional_formatting = true) {

    if ($is_conditional_formatting == false)
        return $val;
    global $conditional_formating;
    if (!isset($conditional_formating) || empty($conditional_formating))
        return $val;

    // check if the column is in the conditional formatting rray
    if (!isset($conditional_formating) || !is_array($conditional_formating) || empty($conditional_formating)) {
        return $val;
    }
    $filtered_conditional_formating = arr_formatting_filter($conditional_formating, $column);
    if (empty($filtered_conditional_formating))
        return $val;

    foreach ($filtered_conditional_formating as $rule) {
        // $font_color = $rule ['color'];
        $font_color = $rule ['color'];
        $target = $rule ['filterValue1'];
        $end_target = isset($rule ['filterValue2']) ? $rule ['filterValue2'] : "";
        $operator = $rule ['filter'];

        switch ($operator) {
            case "less" :
                if (floatval(filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) < $target)
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                // return "<div style='background-color:" . $font_color . " ; height:100%; width:100%; display: block; overflow:auto;' >" . $val . "</div>";
                    return "<div style='color:#ffffff; background-color:" . $font_color . " ; padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "lessorequal" :
                if (floatval(filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) <= $target)
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                    return "<div style='color:#ffffff;background-color:" . $font_color . " ; padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "more" :
                if (floatval(filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) > $target)
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                    return "<div style='color:#ffffff;background-color:" . $font_color . " ;padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "moreorequal" :
                if (floatval(filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) >= $target)
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                    return "<div style='color:#ffffff;background-color:" . $font_color . " ; padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "equal" :
                if (floatval(filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) == $target)
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                    return "<div style='color:#ffffff;background-color:" . $font_color . " ; padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "notequal" :
                if (floatval(filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) != $target)
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                    return "<div style='color:#ffffff;background-color:" . $font_color . " ; padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "contain" :
                if (stristr($val, $target))
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                    return "<div style='color:#ffffff;background-color:" . $font_color . " ;padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "notcontain" :
                if (!stristr($val, $target))
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                    return "<div style='color:#ffffff;background-color:" . $font_color . " ;padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "beginwith" :
                if (substr(strtolower($val), 0, strlen($target)) == strtolower($target))
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                    return "<div style='color:#ffffff;background-color:" . $font_color . " ; padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "endwith" :
                if (EndsWith($val, $target))
                // return "<font color='" . $font_color . "' >" . $val . "</font>";
                    return "<div style='color:#ffffff;background-color:" . $font_color . " ;padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;
            case "between" :
                if (floatval(filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) > $target && floatval(filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) < $end_target)
                    return "<div style='color:#ffffff; background-color:" . $font_color . " ;padding: 0px 3px; box-sizing: content-box;' >" . $val . "</div>";
                break;

            default :
                return $val;
        }
    }
    return $val;
}

function render_stars($val) {
    if ($val <= 5 && $val >= 0)
        return '<span title="' . $val . ' out of 5" class="stars">' . $val . '</span> ';
    else
        return $val;
}

function render_link($val) {
    return "<a class='linkcell' href='" . $val . "' target='_blank' >" . $val . "</a>";
}

function render_append($val, $type) {
    if ($val == "&nbsp;")
        return $val;
    if (strstr($type, "-")) {
        $temp = explode("-", $type);
        $appended_value = $temp [2];
        if ($temp [1] == "r")
            return $val . " " . $appended_value;
        else
            return $appended_value . " " . $val;
    } else {
        return $val;
    }
}

function render_image($val, $image_path = "") {
    if ($val == "" || $val == "&nbsp;")
        return "&nbsp;";
    global $thumnail_max_width, $thumnail_max_height, $show_real_size_image, $show_realsize_in_popup;
    $val = $image_path . $val;
    // make sure image exists, it s an image get its width and height, if image not exists return value
    @$source_details = getimagesize($val);
    if ($source_details == false || !is_array($source_details)) {
        // no image with that dimension
        return $val;
    } else {
        if ($show_real_size_image == false) {

            $thumnail_spec = array(
                'width' => $thumnail_max_width,
                'height' => $thumnail_max_height,
                'identifier' => "tz"
            );
            $result = createThumbnail($val, $thumnail_spec);
            if ($result == false) {
                // couldn't create a thumnail .
                return render_image_css($val);
            } else {
                $uniqueKey = substr($val, - 4);
                $uniqueKey .= rand(1, 1000000);
                $thum_code = "";
                if ($show_realsize_in_popup)
                    $thum_code .= "<a href='" . $val . "' data-lightbox=image" . $uniqueKey . " data-title='Smart Report Maker' >";
                $thum_code .= "<img  src='" . $result . "' />";
                if ($show_realsize_in_popup)
                    $thum_code .= "</a>";
                return $thum_code;
            }
        } else {

            return render_image_css($val);
        }
    }
}

function render_image_css($val) {
    if ($val == "" || $val == "&nbsp;")
        return "&nbsp;";
    global $thumnail_max_width, $thumnail_max_height, $show_realsize_in_popup;
    $uniqueKey = substr($val, - 4);
    $uniqueKey .= rand(1, 1000000);
    if ($show_realsize_in_popup)
        $original_image = "<a href='" . $val . "' data-lightbox=image" . $uniqueKey . " data-title='Smart Report Maker' >";
    $original_image .= "<img      src='" . $val . "'";
    if ($thumnail_max_width != 0)
        $original_image .= 'style= "max-width:' . $thumnail_max_width . 'px;" ';
    if ($thumnail_max_height != 0)
        $original_image .= ' style= "max-height:' . $thumnail_max_height . 'px;"';
    $original_image .= ' />';
    if ($show_realsize_in_popup)
        $original_image .= "</a>";
    return $original_image;
}

function render_country($val) {
    global $language, $layout;
    if (!empty($val)) {
        $code = get_country_code($val);
        if ($code != false) {
            $align = ($language == "ar" || $language == "iw" ) ? "right" : "left";
            if (strtolower($layout) != "mobile") {

                return '<span style="float:' . $align . ';text-align:' . $align . ';white-space: nowrap;padding 0;margin 0;"><img style="max-width:20px;margin: 0;padding: 0;" src="../shared/images/flags/' . $code . '.png" /> ' . $val . "</span>";
            } else {
                return '<span style="text-align:' . $align . ';padding 0;margin 0;"><img style="max-width:20px;margin: 0;padding: 0;" src="../shared/images/flags/' . $code . '.png" /> ' . $val . "</span>";
            }
        } else {
            return $val;
        }
    } else {
        return $val;
    }
}

function render_bit($val) {

    $true_values = array(
        "1",
        "true",
        "yes",
        "y",
        "checked",
        "on"
    );
    $false_values = array(
        "0",
        "no",
        "n",
        "unchecked",
        "off"
    );

    if (in_array($val, $false_values) || $val === 0 || (Boolean) $val === false)
        return "<img src='../shared/images/icons/false.gif' alt=$val />";
    elseif (in_array($val, $true_values) || $val === 1 || (Boolean) $val === true || (Boolean) $val == 1)
        return "<img src='../shared/images/icons/true.gif' alt=$val />";
    else
        return $val;
}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function createThumbnail($img, $specs) {
    $w = $specs ['width'];
    $h = $specs ['height'];
    $id = $specs ['identifier'];
    $path = dirname($img);

    // image doesn't exist or inaccessible?
    if (!$size = @getimagesize($img))
        return FALSE;

    // calculate thumbnail size to maintain aspect ratio
    $ow = $size [0]; // original image width
    $oh = $size [1]; // original image height
    $twbh = $h / $oh * $ow; // calculated thumbnail width based on given height
    $thbw = $w / $ow * $oh; // calculated thumbnail height based on given width
    if ($w && $h) {
        if ($twbh > $w)
            $h = $thbw;
        if ($thbw > $h)
            $w = $twbh;
    } elseif ($w) {
        $h = $thbw;
    } elseif ($h) {
        $w = $twbh;
    } else {
        return FALSE;
    }

    // dir not writeable?
    if (!is_writable($path))
        return FALSE;

    // GD lib not loaded?
    if (!function_exists('gd_info'))
        return FALSE;
    $gd = gd_info();

    // GD lib older than 2.0?
    preg_match('/\d/', $gd ['GD Version'], $gdm);
    if ($gdm [0] < 2)
        return FALSE;

    // get file extension
    preg_match('/\.[a-zA-Z]{3,4}$/U', $img, $matches);
    $ext = strtolower($matches [0]);

    // check if supplied image is supported and specify actions based on file type
    if ($ext == '.gif') {
        if (!$gd ['GIF Create Support'])
            return FALSE;
        $thumbFunc = 'imagegif';
    } elseif ($ext == '.png') {
        if (!$gd ['PNG Support'])
            return FALSE;
        $thumbFunc = 'imagepng';
    } elseif ($ext == '.jpg' || $ext == '.jpe' || $ext == '.jpeg') {
        if (!$gd ['JPG Support'] && !$gd ['JPEG Support'])
            return FALSE;
        $thumbFunc = 'imagejpeg';
    } else {
        return FALSE;
    }

    // determine thumbnail file name
    $ext = $matches [0];
    $thumb = substr($img, 0, - 5) . str_replace($ext, $id . $ext, substr($img, - 5));

    // if the original image smaller than thumb, then just copy it to thumb
    if ($h > $oh && $w > $ow) {
        return (@copy($img, $thumb) ? TRUE : FALSE);
    }

    // get image data
    if (!$imgData = imagecreatefromstring(implode('', file($img))))
        return FALSE;

    // finally, create thumbnail
    $thumbData = imagecreatetruecolor($w, $h);

    // preserve transparency of png and gif images
    if ($thumbFunc == 'imagepng') {
        if (($clr = @imagecolorallocate($thumbData, 0, 0, 0)) != - 1) {
            @imagecolortransparent($thumbData, $clr);
            @imagealphablending($thumbData, false);
            @imagesavealpha($thumbData, true);
        }
    } elseif ($thumbFunc == 'imagegif') {
        @imagealphablending($thumbData, false);
        $transIndex = imagecolortransparent($imgData);
        if ($transIndex >= 0) {
            $transClr = imagecolorsforindex($imgData, $transIndex);
            $transIndex = imagecolorallocatealpha($thumbData, $transClr ['red'], $transClr ['green'], $transClr ['blue'], 127);
            imagefill($thumbData, 0, 0, $transIndex);
        }
    }

    // resize original image into thumbnail
    if (!imagecopyresampled($thumbData, $imgData, 0, 0, 0, 0, $w, $h, $ow, $oh))
        return FALSE;
    unset($imgData);

    // gif transparency
    if ($thumbFunc == 'imagegif' && $transIndex >= 0) {
        imagecolortransparent($thumbData, $transIndex);
        for ($y = 0; $y < $h; ++$y)
            for ($x = 0; $x < $w; ++$x)
                if (((imagecolorat($thumbData, $x, $y) >> 24) & 0x7F) >= 100)
                    imagesetpixel($thumbData, $x, $y, $transIndex);
        imagetruecolortopalette($thumbData, true, 255);
        imagesavealpha($thumbData, false);
    }

    if (!$thumbFunc($thumbData, $thumb))
        return FALSE;
    unset($thumbData);

    return $thumb;
}

function EndsWith($whole, $end) {
    $whole = strtolower($whole);
    $end = strtolower($end);
    return (strpos($whole, $end, strlen($whole) - strlen($end)) !== false);
}

function get_country_code($country) {
    if (empty($country))
        return "";
    $country = strtolower($country);
    $countries_Formal_names_List = array(
        'af' => 'afghanistan',
        'ax' => 'aland islands',
        'al' => 'albania',
        'dz' => 'algeria',
        'as' => 'american samoa',
        'ad' => 'andorra',
        'ao' => 'angola',
        'ai' => 'anguilla',
        'aq' => 'antarctica',
        'ag' => 'antigua and barbuda',
        'ar' => 'argentina',
        'am' => 'armenia',
        'aw' => 'aruba',
        'au' => 'australia',
        'at' => 'austria',
        'az' => 'azerbaijan',
        'bs' => 'bahamas the',
        'bh' => 'bahrain',
        'bd' => 'bangladesh',
        'bb' => 'barbados',
        'by' => 'belarus',
        'be' => 'belgium',
        'bz' => 'belize',
        'bj' => 'benin',
        'bm' => 'bermuda',
        'bt' => 'bhutan',
        'bo' => 'bolivia',
        'ba' => 'bosnia and herzegovina',
        'bw' => 'botswana',
        'bv' => 'bouvet island (bouvetoya)',
        'br' => 'brazil',
        'io' => 'british indian ocean territory (chagos archipelago)',
        'vg' => 'british virgin islands',
        'bn' => 'brunei darussalam',
        'bg' => 'bulgaria',
        'bf' => 'burkina faso',
        'bi' => 'burundi',
        'kh' => 'cambodia',
        'cm' => 'cameroon',
        'ca' => 'canada',
        'cv' => 'cape verde',
        'ky' => 'cayman islands',
        'cf' => 'central african republic',
        'td' => 'chad',
        'cl' => 'chile',
        'cn' => 'china',
        'cx' => 'christmas island',
        'cc' => 'cocos (keeling) islands',
        'co' => 'colombia',
        'km' => 'comoros the',
        'cd' => 'congo',
        'cg' => 'congo the',
        'ck' => 'cook islands',
        'cr' => 'costa rica',
        'ci' => 'cote d\'ivoire',
        'hr' => 'croatia',
        'cu' => 'cuba',
        'cy' => 'cyprus',
        'cz' => 'czech republic',
        'dk' => 'denmark',
        'dj' => 'djibouti',
        'dm' => 'dominica',
        'do' => 'dominican republic',
        'ec' => 'ecuador',
        'eg' => 'egypt',
        'sv' => 'el salvador',
        'gq' => 'equatorial guinea',
        'er' => 'eritrea',
        'ee' => 'estonia',
        'et' => 'ethiopia',
        'fo' => 'faroe islands',
        'fk' => 'falkland islands (malvinas)',
        'fj' => 'fiji the fiji islands',
        'fi' => 'finland',
        'fr' => 'france, french republic',
        'gf' => 'french guiana',
        'pf' => 'french polynesia',
        'tf' => 'french southern territories',
        'ga' => 'gabon',
        'gm' => 'gambia the',
        'ge' => 'georgia',
        'de' => 'germany',
        'gh' => 'ghana',
        'gi' => 'gibraltar',
        'gr' => 'greece',
        'gl' => 'greenland',
        'gd' => 'grenada',
        'gp' => 'guadeloupe',
        'gu' => 'guam',
        'gt' => 'guatemala',
        'gg' => 'guernsey',
        'gn' => 'guinea',
        'gw' => 'guinea-bissau',
        'gy' => 'guyana',
        'ht' => 'haiti',
        'hm' => 'heard island and mcdonald islands',
        'va' => 'holy see (vatican city state)',
        'hn' => 'honduras',
        'hk' => 'hong kong',
        'hu' => 'hungary',
        'is' => 'iceland',
        'in' => 'india',
        'id' => 'indonesia',
        'ir' => 'iran',
        'iq' => 'iraq',
        'ie' => 'ireland',
        'im' => 'isle of man',
        'il' => 'israel',
        'it' => 'italy',
        'jm' => 'jamaica',
        'jp' => 'japan',
        'je' => 'jersey',
        'jo' => 'jordan',
        'kz' => 'kazakhstan',
        'ke' => 'kenya',
        'ki' => 'kiribati',
        'kp' => 'korea',
        'kr' => 'korea',
        'kw' => 'kuwait',
        'kg' => 'kyrgyz republic',
        'la' => 'lao',
        'lv' => 'latvia',
        'lb' => 'lebanon',
        'ls' => 'lesotho',
        'lr' => 'liberia',
        'ly' => 'libyan arab jamahiriya',
        'li' => 'liechtenstein',
        'lt' => 'lithuania',
        'lu' => 'luxembourg',
        'mo' => 'macao',
        'mk' => 'macedonia',
        'mg' => 'madagascar',
        'mw' => 'malawi',
        'my' => 'malaysia',
        'mv' => 'maldives',
        'ml' => 'mali',
        'mt' => 'malta',
        'mh' => 'marshall islands',
        'mq' => 'martinique',
        'mr' => 'mauritania',
        'mu' => 'mauritius',
        'yt' => 'mayotte',
        'mx' => 'mexico',
        'fm' => 'micronesia',
        'md' => 'moldova',
        'mc' => 'monaco',
        'mn' => 'mongolia',
        'me' => 'montenegro',
        'ms' => 'montserrat',
        'ma' => 'morocco',
        'mz' => 'mozambique',
        'mm' => 'myanmar',
        'na' => 'namibia',
        'nr' => 'nauru',
        'np' => 'nepal',
        'an' => 'netherlands antilles',
        'nl' => 'netherlands the',
        'nc' => 'new caledonia',
        'nz' => 'new zealand',
        'ni' => 'nicaragua',
        'ne' => 'niger',
        'ng' => 'nigeria',
        'nu' => 'niue',
        'nf' => 'norfolk island',
        'mp' => 'northern mariana islands',
        'no' => 'norway',
        'om' => 'oman',
        'pk' => 'pakistan',
        'pw' => 'palau',
        'ps' => 'palestinian territory',
        'pa' => 'panama',
        'pg' => 'papua new guinea',
        'py' => 'paraguay',
        'pe' => 'peru',
        'ph' => 'philippines',
        'pn' => 'pitcairn islands',
        'pl' => 'poland',
        'pt' => 'portugal, portuguese republic',
        'pr' => 'puerto rico',
        'qa' => 'qatar',
        're' => 'reunion',
        'ro' => 'romania',
        'ru' => 'russian federation',
        'rw' => 'rwanda',
        'bl' => 'saint barthelemy',
        'sh' => 'saint helena',
        'kn' => 'saint kitts and nevis',
        'lc' => 'saint lucia',
        'mf' => 'saint martin',
        'pm' => 'saint pierre and miquelon',
        'vc' => 'saint vincent and the grenadines',
        'ws' => 'samoa',
        'sm' => 'san marino',
        'st' => 'sao tome and principe',
        'sa' => 'saudi arabia, ksa',
        'sn' => 'senegal',
        'rs' => 'serbia',
        'sc' => 'seychelles',
        'sl' => 'sierra leone',
        'sg' => 'singapore',
        'sk' => 'slovakia (slovak republic)',
        'si' => 'slovenia',
        'sb' => 'solomon islands',
        'so' => 'somalia, somali republic',
        'za' => 'south africa',
        'gs' => 'south georgia and the south sandwich islands',
        'es' => 'spain',
        'lk' => 'sri lanka',
        'sd' => 'sudan',
        'sr' => 'suriname',
        'sj' => 'svalbard & jan mayen islands',
        'sz' => 'swaziland',
        'se' => 'sweden',
        'ch' => 'switzerland, swiss confederation',
        'sy' => 'syrian arab republic',
        'tw' => 'taiwan',
        'tj' => 'tajikistan',
        'tz' => 'tanzania',
        'th' => 'thailand',
        'tl' => 'timor-leste',
        'tg' => 'togo',
        'tk' => 'tokelau',
        'to' => 'tonga',
        'tt' => 'trinidad and tobago',
        'tn' => 'tunisia',
        'tr' => 'turkey',
        'tm' => 'turkmenistan',
        'tc' => 'turks and caicos islands',
        'tv' => 'tuvalu',
        'ug' => 'uganda',
        'gb' => 'united kingdom , uk',
        'ua' => 'ukraine',
        'ae' => 'united arab emirates, uae',
        'us' => 'united states of america , usa',
        'um' => 'united states minor outlying islands',
        'vi' => 'united states virgin islands',
        'uy' => 'uruguay, eastern republic of',
        'uz' => 'uzbekistan',
        'vu' => 'vanuatu',
        've' => 'venezuela',
        'vn' => 'vietnam',
        'wf' => 'wallis and futuna',
        'eh' => 'western sahara',
        'ye' => 'yemen',
        'zm' => 'zambia',
        'zw' => 'zimbabwe'
    );

    foreach ($countries_Formal_names_List as $key => $val) {
        if (strstr($val, $country)) {
            $code = $key;
            return $code;
        }
    }

    return false;
}

function arr_formatting_filter($arr, $col) {
    $array = array();
    foreach ($arr as $rule) {
        if (stristr($rule ["column"], ".")) {
            $column = explode(".", $rule ["column"])[1];
        } else {
            $column = $rule ["column"];
        }
        if (strtolower($column) == strtolower($col)) {
            $array [] = $rule;
        }
    }
    return $array;
}

?>