<?php

$meican_descr = isset($argsToElement->meican_descr) ? $argsToElement->meican_descr : NULL;
$meican_ip = isset($argsToElement->meican_ip) ? $argsToElement->meican_ip : NULL;
$meican_dir_name = isset($argsToElement->meican_dir_name) ? $argsToElement->meican_dir_name : NULL;
$is_local_domain = isset($argsToElement->local_domain) ? $argsToElement->local_domain : FALSE;

?>

<table>
    <tr>
        <th>
            <?php echo _("Name"); ?>:
        </th>
        <td>
            <input type="text" name="meican_descr" size="50" value="<?php echo $meican_descr; ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo _("MEICAN IP"); ?>:
        </th>
        <td>
            <input type="text" name="meican_ip" size="50" value="<?php echo $meican_ip; ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo _("Directory Name"); ?>:
        </th>
        <td>
            <input type="text" name="meican_dir_name" size="50" value="<?php echo $meican_dir_name; ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo _("Is Local Domain?"); ?>
        </th>
        <td>
            <input type="checkbox" name="local_domain" <?php if ($is_local_domain) echo 'checked="true"'; ?>/>
        </td>
    </tr>
</table>