


    <tr>

        <td class="Title" colspan="<?php echo $actual_columns_count ?>" height="33" valign="top" >


            <?php if (!empty($custom_logo)) { ?>
              <div class="logo-img">
                 <div>
                     <img src="<?php echo '../shared/config/' . $custom_logo ?>" alt="company-name">
                 </div>
                 <div>
              <span class="Title" style="float:left;"><?php echo"<b>$title</b>"; ?></span>
                 </div>
             </div>
            <?php } else { ?>
             <div class="logo-img">
                 
              <span class="Title" style="float:left;"><?php echo"<b>$title</b>"; ?></span>
                 
             </div>

            <?php } ?>

            <?php if ($_print_option !== 1 && $_print_option !== 2) { ?>
                <div class="action-menu">
                    <div class="theme layout-menu">
                        <a href="#"
                           class="menu_hvr" target_class=".sub1" title="Print">
                           <!-- <img src="./TEST REPORT_files/printer.png"
                           style="vertical-align: middle;"> -->
                            <div class="print-icon icon-container"></div>
                            <span><?php echo escape($print_lang); ?></span>
                        </a>
                        <ul class="sub-menu sub1 first-sub-menu">
                            <li class="menu-item-li"><a
                                    href="<?php echo $link_print2; ?>"><?php echo escape($all_pages_lang); ?></a></li>
                            <li class="menu-item-li"><a
                                    href="<?php echo $link_print1; ?>"><?php echo escape($current_page_lang); ?></a></li>
                        </ul>
                    </div>
                   
                    <div class="theme layout-menu">
                        <a href="#"
                           class="menu_hvr" target_class=".sub2" title="Export">
                           <!-- <img src="./TEST REPORT_files/export.png" style="vertical-align: middle;"> -->
                            <div class="export-icon icon-container"></div>
                            <span><?php echo escape($export_lang); ?></span>
                        </a>

                        <ul class="sub-menu sub2 first-sub-menu">
                            <li class="menu-item-li"><a class="menu_hvr_sub menu-item-a" target_class=".sub4"
                                                        href="#">CSV</a>
                                <ul class="sub-menu sub4 sub_sub menu-item-ul">
                                    <li class="menu-item-li"><a
                                            href="<?php echo $link_csv_current; ?>"
                                            download=""><?php echo escape($current_page_lang); ?></a></li>
                                    <li class="menu-item-li"><a
                                            href="<?php echo $link_csv_all; ?>"
                                            download=""><?php echo escape($all_pages_lang); ?></a></li>
                                </ul>

                            </li>

                     
                            <li class="menu-item-li"><a class="menu_hvr_sub menu-item-a" target_class=".sub5"
                                                        href="#">XML</a>

                                <ul class="sub-menu sub5 sub_sub menu-item-ul">
                                    <li class="menu-item-li"><a
                                            href="<?php echo $link_xml_current; ?>" 
                                            download=""><?php echo escape($current_page_lang); ?></a></li>
                                    <li class="menu-item-li"><a
                                            href="<?php echo $link_xml_all; ?>" 
                                            download=""><?php echo escape($all_pages_lang); ?></a></li>
                                </ul>
                            </li>


                        </ul>
                    </div>
                </div>
            <?php } ?>
        </td>

    </tr>

