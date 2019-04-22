<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2011-2019 TZ Portfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

# Family website: http://www.templaza.com

# Family Support: Forum - https://www.templaza.com/Forums.html

-------------------------------------------------------------------------*/

namespace tp\lib\Form\Field;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\Loader;
use tp\lib\Form\FormField;
use tp\lib\HTML\HTMLHelper;
use tp\lib\Language\TPLanguage;
use tp\lib\Utilities\Registry;

if ( ! class_exists( 'tp\lib\Form\Field\TZMultipleFieldField' ) ) {
    class TZMultipleFieldField extends FormField
    {
        protected $type = 'TZMultipleField';
        protected $head = false;
        protected $multiple = true;

        protected function getName($fieldName)
        {
            return parent::getName($fieldName);
        }

        protected function getInput()
        {

            wp_enqueue_script('jquery-ui-sortable');

            if (!is_array($this->value) && preg_match_all('/(\{.*?\})/', $this->value, $match)) {
                $this->setValue($match[1]);
            }

            $id = $this->id;
            $element = $this->element;
            $this->__set('multiple', 'true');

            // Initialize some field attributes.
            $class = !empty($this->class) ? ' class="' . $this->class . '"' : '';
            $disabled = $this->disabled ? ' disabled' : '';

            // Initialize JavaScript field attributes.
            $onchange = $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

            // Get children fields from xml file
            $tzfields = $element->children();

            // Get field with tzfield tags
            $xml = array();
            $html = array();
            $thead = array();
            $tbody_col_require = array();
            $tbody_row_id = array();
            $tbody_row_html = array();
            $tzform_control_id = array();
            $form_control = array();

            $tbody_row_html[] = '<td style="text-align: center;">'
                . '<span class="dashicons dashicons-move icon-move hasTooltip" title="' . TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_MOVE') . '"
             style="cursor: move;"></span></td>';

            ob_start();
            ?>
            <div id="<?php echo $id; ?>-content">
                <div class="control-group">
                    <button type="button" class="button-primary js-tp-btn__add">
                        <span class="dashicons dashicons-plus"
                              title="<?php echo TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_UPDATE'); ?>"></span>
                        <?php echo TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_UPDATE'); ?>
                    </button>
                    <button type="button" class="button js-tp-btn__reset">
                        <span class="dashicons dashicons-no"
                              title="<?php echo TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_RESET'); ?>"></span>
                        <?php echo TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_RESET'); ?>
                    </button>
                </div>
                <?php

                // Generate children fields from xml file
                if ($tzfields) {
                    $i = 0;
                    foreach ($tzfields as $xmlElement) {
                        $type = $xmlElement['type'];
                        if (!$type) {
                            $type = 'text';
                        }
                        $tz_class = 'tp\lib\Form\Field\\' . ucfirst($type) . 'Field';

                        if (!class_exists($tz_class)) {
                            Loader::register($tz_class, TP_PLUGIN_LIBRARY_PATH . '/form/field/' . $type . '.php');
                        }

                        // Check formfield class of children field
                        if (class_exists($tz_class)) {

                            // Create formfield class of children field
                            $tz_class = new $tz_class();
                            $tz_class->setForm($this->form);
                            $tz_class->formControl = $this -> formControl.'_child';
                            // Init children field for children class
                            $tz_class->setup($xmlElement, '');
                            $tz_class->value = $xmlElement['default'];
                            $tz_name = (string)$xmlElement['name'];
                            $tz_tbl_require = (bool)$xmlElement['table_required'];

                            $tzform_control_id[$i] = array();
                            $tzform_control_id[$i]["id"] = $tz_class->id;
                            $tzform_control_id[$i]["type"] = $tz_class->type;
                            $tzform_control_id[$i]["fieldname"] = $tz_class->fieldname;
                            $tzform_control_id[$i]["table_required"] = 0;
                            $tzform_control_id[$i]["name"] = $tz_class->name;
                            $tzform_control_id[$i]["default"] = $tz_class->default;
                            $tzform_control_id[$i]["field_required"] = (bool)$xmlElement['field_required'];
                            $tzform_control_id[$i]["value_validate"] = (string)$xmlElement['value_validate'];
                            $tzform_control_id[$i]["label"] = $tz_class->getTitle();

                            // Create table's head column (check attribute table_required of children field from xml file)
                            if ($tz_tbl_require) {
                                $tbody_row_id[] = $tz_class->id;
                                $tbody_col_require[] = $tz_class->fieldname;
                                $tzform_control_id[$i]["table_required"] = 1;

                                ob_start();
                                ?>
                                <th><?php echo $tz_class->getTitle(); ?></th>
                                <?php
                                $thead[] = ob_get_clean();
                                ob_start();

                                ?>
                                <td>{<?php echo $tz_class->id; ?>}

                                    <?php if ($i == 0) { ?>
                                        <div class="row-actions">
                                    <span class="move">
                                        <a class="js-tp-btn__edit"
                                           href="javacript:void();"><?php echo TPLanguage::_('JACTION_EDIT'); ?></a>
                                    </span> |
                                            <span class="delete">
                                        <a class="js-tp-btn__remove"
                                           href="javacript:void();"><?php echo TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_REMOVE'); ?></a>
                                    </span>
                                            <input type="hidden" name="<?php echo $this->getName($this->fieldname); ?>"
                                                   value="<?php echo '{' . $this->id . '}'; ?>" <?php echo $class . $disabled . $onchange ?>/>
                                        </div>
                                    <?php } ?>
                                </td>
                                <?php
                                $tbody_row_html[] = ob_get_clean();
                            }
                            ob_start();
                            // Generate children field from xml file
                            echo $tz_class->renderField();

                            $form_control[] = ob_get_clean();
                        }
                        $i++;
                    }
                }

                echo implode("\n", $form_control);

                // Generate table
                if (count($thead)) {
                    ?>
                    <table class="widefat plugins js-tp-table__field mt-1">
                        <thead>
                        <tr>
                            <th style="width: 3%; text-align: center;">#</th>
                            <?php echo implode("\n", $thead); ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($values = $this->value) {
                            if (count($values)) {
                                foreach ($values as $value) {
                                    $value  = str_replace('\\', '', $value);
                                    $j_value = json_decode($value);
                                    ?>
                                    <tr class="inactive">
                                        <td style="text-align: center;"><span
                                                    class="dashicons dashicons-move icon-move hasTooltip"
                                                    style="cursor: move;"
                                                    title="<?php echo TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_MOVE') ?>"></span>
                                        </td>
                                        <?php
                                        if ($j_value && !empty($j_value)) {
                                            $j = 0;
                                            foreach ($j_value as $key => $_j_value) {
                                                if (in_array($key, $tbody_col_require)) {
                                                    ?>
                                                    <td>
                                                        <?php echo $_j_value ?>
                                                        <?php if ($j == 0) { ?>
                                                            <div class="row-actions">
                                    <span class="move">
                                        <a class="js-tp-btn__edit"
                                           href="javacript:void();"><?php echo TPLanguage::_('JACTION_EDIT'); ?></a>
                                    </span> |
                                                                <span class="delete">
                                        <a class="js-tp-btn__remove"
                                           href="javacript:void();"><?php echo TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_REMOVE'); ?></a>
                                    </span>
                                                                <input type="hidden"
                                                                       name="<?php echo $this->getName($this->fieldname); ?>"
                                                                       value="<?php echo htmlspecialchars($value); ?>" <?php echo $class . $disabled . $onchange ?>/>
                                                            </div>
                                                        <?php } ?>
                                                    </td>
                                                    <?php
                                                    $j++;
                                                }
                                            }
                                        }
                                        ?>
                                    </tr>
                                <?php }
                            }
                        } ?>
                        </tbody>
                    </table>
                    <?php
                }

                //        echo implode("\n",$form_control);

                //        $tbody_row_html[]   = '<td style="text-align: center;">'
                //            .'<div class="btn-group">'
                //            .'<button type="button" class="btn btn-secondary btn-small btn-sm js-tp-btn__edit hasTooltip" title="'
                //            .TPLanguage::_('JACTION_EDIT').'"><i class="icon-edit"></i></button>'
                //            .'<button type="button" class="btn btn-danger btn-small btn-sm js-tp-btn__remove hasTooltip" title="'
                //            .TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_REMOVE').'">'
                //            .'<i class="icon-trash"></i></button>'
                //            .'</div>'
                //            .'<input type="hidden" name="' . $this -> getName($this -> fieldname) . '" value="{'.
                //            $this -> id.'}"' . $class . $disabled . $onchange . ' />'
                //            .'</td>';

                //        $config = JFactory::getConfig();

                $tbody_row_html = '<tr class="inactive">' . implode('', $tbody_row_html) . '</tr>';
                ?>
                <script>
                    function htmlspecialchars(str) {
                        if (typeof(str) == "string") {
                            str = str.replace(/&/g, "&amp;");
                            /* must do &amp; first */
                            str = str.replace(/"/g, "&quot;");
                            str = str.replace(/'/g, "&#039;");
                            str = str.replace(/</g, "&lt;");
                            str = str.replace(/>/g, "&gt;");
                        }
                        return str;
                    }


                    (function ($) {
                        $(document).ready(function () {

                            var $tbody_row_html = "<?php echo tp_js_add_slashes('' . trim($tbody_row_html));?>";
                            var $tzpricing_table_id = "<?php echo $this->id;?>";
                            var $tbody_control_id = <?php echo json_encode($tzform_control_id);?>;
                            var $hidden_name = "<?php echo tp_js_add_slashes($this->getName($this->fieldname));?>";
                            var $tzpricing_position = -1;

                            // Add new data row
                            $("#<?php echo $id;?>-content .js-tp-btn__add").bind("click", function (e) {

                                // Create input hidden with data were put
                                var $tbody_row_html_clone = $tbody_row_html;
                                var $tbody_bool = true;
                                var $content = {};

                                $.each($tbody_control_id, function (key, value) {
                                    var input_name = value["name"].replace(/\[/, "\\[")
                                        .replace(/\]/, "\\]");

                                    if (value["field_required"]) {
                                        $tbody_bool = false;
                                        if (!$("#" + value["id"]).val().length) {
                                            alert("<?php echo TPLanguage::sprintf('JLIB_FORM_VALIDATE_FIELD_INVALID', '');?>"
                                                + value["label"]);
                                            $("#" + value["id"]).focus();
                                            return false;
                                        }
                                    }

                                    if (value["value_validate"]) {
                                        if ($("#" + value["id"]).val() == value["value_validate"]) {
                                            alert("<?php echo TPLanguage::sprintf('COM_TZ_PORTFOLIO_PLUS_FAILED_TO_VALUE', '')?>"
                                                + value['value_validate']
                                                + " <?php echo TPLanguage::sprintf('COM_TZ_PORTFOLIO_PLUS_FAILED_OF_FIELD', '')?>"
                                                + value["label"]);
                                            return false;
                                        }
                                    }

                                    // Check required and create row for table
                                    if (value["table_required"]) {
                                        var pattern = "\\{" + value["id"] + "\\}";
                                        var regex = new RegExp(pattern, 'gi');
                                        $tbody_row_html_clone = $tbody_row_html_clone.replace(regex, $("#" + value["id"]).val());
                                    }

                                    $tbody_bool = true;

                                    if (value["type"].toLowerCase() == 'editor') {
                                        // tinyMCE.activeEditor.getContent();
                                        //WFEditor.getContent(id)
                                        <!--                                    --><?php //if($config -> get('editor') == 'jce'){?>
//                                        $content[value["fieldname"]]    =  WFEditor.getContent(value["id"]);
//                                    <?php //}elseif($config -> get('editor') == 'tinymce'){?>
//                                        $content[value["fieldname"]]    =  tinyMCE.activeEditor.getContent();
//                                    <?php //}elseif($config -> get('editor') == 'codemirror'){?>
//                                        $content[value["fieldname"]]    =  Joomla.editors.instances[value["id"]].getValue();
//                                    <?php //}?>
                                        $content[value["fieldname"]] = $("#" + value["id"]).val();
                                    } else {
                                        if ($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'input'
                                            && $("[name=" + input_name + "]").prop('type') == 'radio') {
                                            $content[value["fieldname"]] = $("[name=" + value["name"].replace(/\[/, "\\[")
                                                .replace(/\]/, "\\]") + "]:checked").val();
                                        } else {
                                            $content[value["fieldname"]] = $("#" + value["id"]).val();
                                        }
                                    }
                                });

                                if ($tbody_bool && Object.keys($content).length) {
                                    var pattern2 = "\\{" + $tzpricing_table_id + "\\}";
                                    var regex2 = new RegExp(pattern2, 'gi');
                                    $tbody_row_html_clone = $tbody_row_html_clone.replace(regex2, htmlspecialchars(JSON.stringify($content)));
                                    if ($tzpricing_position > -1) {
                                        $("#" + $tzpricing_table_id + "-content .js-tp-table__field tbody tr")
                                            .eq($tzpricing_position).after($tbody_row_html_clone).remove();
                                        $tzpricing_position = -1;
                                    } else {
                                        $("#" + $tzpricing_table_id + "-content .js-tp-table__field tbody").prepend($tbody_row_html_clone);
                                    }

                                    // Call trigger reset form
                                    $("#<?php echo $id;?>-content .js-tp-btn__reset").trigger("click");

                                    tzPricingTableAction();
                                }

                            });
                            // Reset form
                            $("#<?php echo $id;?>-content .js-tp-btn__reset").bind("click", function () {
                                if ($tbody_control_id.length) {
                                    $.each($tbody_control_id, function (key, value) {
                                        var input_name = value["name"].replace(/\[/, "\\[")
                                            .replace(/\]/, "\\]");
                                        if (value["type"].toLowerCase() == 'editor') {
                                            // tinyMCE.activeEditor.getContent();
                                            //WFEditor.getContent(id)
                                            <!--                                        --><?php //if($config -> get('editor') == 'jce'){?>
//                                        WFEditor.setContent(value["id"], value["default"]);
//                                        <?php //}elseif($config -> get('editor') == 'tinymce'){?>
//                                        tinyMCE.activeEditor.setContent(value["default"]);
//                                        <?php //}elseif($config -> get('editor') == 'codemirror'){?>
//                                        Joomla.editors.instances[value["id"]].setValue(value["default"]);
//                                        <?php //}?>
                                            $("#" + value["id"]).val('');
                                        } else {
                                            if ($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'select') {
                                                $("#" + value["id"]).val(value["default"])
                                                    .trigger("liszt:updated");
                                            } else {
                                                if ($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'input'
                                                    && $("[name=" + input_name + "]").prop('type') == 'radio') {
                                                    $("[name=" + input_name + "]").removeAttr("checked");
                                                    $("#" + value["id"] + " label[for=" + $("[name=" + input_name + "][value="
                                                        + value["default"] + "]").attr("id")
                                                        + "]").trigger("click");
                                                } else {
                                                    $("#" + value["id"]).val(value["default"]);
                                                }
                                            }
                                        }
                                    });
                                    $tzpricing_position = -1;
                                }
                            });

                            function tzPricingTableAction() {
                                // Edit data
                                $("#<?php echo $id;?>-content .js-tp-btn__edit").unbind("click").bind("click", function () {
                                    var $hidden_value = $(this).parents("td").first()
                                        .find("input[name=\"" + $hidden_name + "\"]").val();
                                    if ($hidden_value.length) {
                                        var $hidden_obj_value = $.parseJSON($hidden_value);
                                        if ($tbody_control_id.length) {
                                            $.each($tbody_control_id, function (key, value) {
                                                var input_name = value["name"].replace(/\[/, "\\[")
                                                    .replace(/\]/, "\\]");
                                                if (value["type"].toLowerCase() == 'editor') {
                                                    <!--                                                --><?php //if($config -> get('editor') == 'jce'){?>
//                                                WFEditor.setContent(value["id"], $hidden_obj_value[value["fieldname"]]);
//                                                <?php //}elseif($config -> get('editor') == 'tinymce'){?>
//                                                tinyMCE.activeEditor.setContent($hidden_obj_value[value["fieldname"]]);
//                                                <?php //}elseif($config -> get('editor') == 'codemirror'){?>
//                                                Joomla.editors.instances[value["id"]].setValue($hidden_obj_value[value["fieldname"]]);
//                                                <?php //}?>
                                                    $("#" + value["id"]).val($hidden_obj_value[value["fieldname"]]);
                                                } else {
                                                    if ($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'select') {
                                                        $("#" + value["id"]).val($hidden_obj_value[value["fieldname"]])
                                                            .trigger("liszt:updated");
                                                    } else {
                                                        if ($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'input'
                                                            && $("[name=" + input_name + "]").prop('type') == 'radio') {
                                                            $("[name=" + input_name + "]").removeAttr("checked");
                                                            $("#" + value["id"] + " label[for=" + $("[name=" + input_name + "][value="
                                                                + $hidden_obj_value[value["fieldname"]] + "]").attr("id")
                                                                + "]").trigger("click");
                                                        } else {
                                                            $("#" + value["id"]).val($hidden_obj_value[value["fieldname"]]);
                                                        }
                                                    }
                                                }
                                            });
                                            $tzpricing_position = $("#<?php echo $id;?>-content .js-tp-table__field tbody tr")
                                                .index($(this).parents("tr").first());
                                        }
                                    }
                                });

                                // Remove data row
                                $("#<?php echo $id;?>-content .js-tp-btn__remove").unbind("click").bind("click", function () {
                                    var message = confirm('<?php echo TPLanguage::_('COM_TZ_PORTFOLIO_PLUS_REMOVE_THIS_ITEM');?>');
                                    if (message) {
                                        $(this).parents('tr').first().remove();
                                    }
                                });
                            }

                            tzPricingTableAction();

                            // Sortable row
                            $("#" + $tzpricing_table_id + "-content .js-tp-table__field tbody").sortable({
                                cursor: "move",
                                items: "> tr",
                                revert: true,
                                handle: ".icon-move",
                                forceHelperSize: true,
                                placeholder: "ui-state-highlight",
                                start: function (event, ui) {
                                    $(ui.item).parents("table").find("thead > tr > th").each(function (index) {
                                        $(ui.item).find("> td").eq(index).width($(this).width());
                                    });
                                }
                            });
                        });
                    })(jQuery);

                </script>
            </div>
            <?php
            $html[] = ob_get_contents();
            ob_end_clean();

            return implode("\n", $html);
        }
    }
}