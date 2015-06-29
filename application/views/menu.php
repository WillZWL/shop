<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - <?= SITE_NAME; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
    <link rel="stylesheet" type="text/css" href="/css/style.css"/>
    <script type="text/javascript" src="<?= base_url('js/common.js') ?>"></script>
</head>
<body>
<div align="center" style="font-size:20px; font-weight:bold; color:#FFA500; line-height:60px">WELCOME TO ADMINCENTRE
</div>
<table width="100%" align="center">
    <tr>
        <td width="40">&nbsp;</td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($master_cfg_menu)): ?>
                    <tr>
                        <th class="admin_menu">Master Control Management</th>
                    </tr>
                    <?= $master_cfg_menu ?>
                <?php endif ?>
            </table>
        </td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($marketing_menu)): ?>
                    <tr>
                        <th class="admin_menu">Marketing Tool & Content Management</th>
                    </tr>
                    <?= $marketing_menu ?>
                <?php endif ?>
            </table>
        </td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($supply_menu)): ?>
                    <tr>
                        <th class="admin_menu">Supply Chain Management</th>
                    </tr>
                    <?= $supply_menu ?>
                <?php endif ?>
                <?php if ($this->authorization_service->user_app_rights('ORD0001')): ?>
                    <tr>
                        <td class="admin_menu"><a href="<?= base_url() ?>order/supplier_order/confirm_shipment"
                                                  onClick="Pop('<?= base_url() ?>order/supplier_order/confirm_shipment','confirm_shipment');"
                                                  target="confirm_shipment" class="admin_menu">Confirm Shipment</a></td>
                    </tr>
                <?php endif ?>
            </table>
        </td>
        <td width="40" class="admin_menu">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="5" height="30">&nbsp;</td>
    </tr>
    <tr>
        <td width="40">&nbsp;</td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($order_menu)): ?>
                    <tr>
                        <th class="admin_menu">Order Management</th>
                    </tr>
                    <?= $order_menu ?>
                <?php endif ?>
            </table>
        </td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($integration_menu)): ?>
                    <tr>
                        <th class="admin_menu">Integration</th>
                    </tr>
                    <?= $integration_menu ?>
                <?php endif ?>
            </table>
        </td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($customer_service_menu)): ?>
                    <tr>
                        <th class="admin_menu">Customer Service</th>
                    </tr>
                    <?= $customer_service_menu ?>
                <?php endif ?>
                <?php if ($this->authorization_service->user_app_rights('ORD0007')): ?>
                    <tr>
                        <td class="admin_menu"><a href="<?= base_url() ?>order/on_hold_admin/"
                                                  onClick="Pop('<?= base_url() ?>order/on_hold_admin/','on_hold_admin');"
                                                  target="on_hold_admin" class="admin_menu">On Hold Admin</a></td>
                    </tr>
                    <tr>
                        <td class="admin_menu"><a href="<?= base_url() ?>order/on_hold_admin/oc_index/"
                                                  onClick="Pop('<?= base_url() ?>order/on_hold_admin/oc_index/','on_hold_admin_oc');"
                                                  target="on_hold_admin_oc" class="admin_menu">On Hold Admin - OC
                                Page</a></td>
                    </tr>
                    <tr>
                        <td class="admin_menu"><a href="<?= base_url() ?>order/on_hold_admin/oc_index/cc"
                                                  onClick="Pop('<?= base_url() ?>order/on_hold_admin/oc_index/cc','on_hold_admin_cc');"
                                                  target="on_hold_admin_cc" class="admin_menu">On Hold Admin - CC
                                Page</a></td>
                    </tr>
                    <tr>
                        <td class="admin_menu"><a href="<?= base_url() ?>order/on_hold_admin/oc_index/vv"
                                                  onClick="Pop('<?= base_url() ?>order/on_hold_admin/oc_index/vv','on_hold_admin_vv');"
                                                  target="on_hold_admin_vv" class="admin_menu">On Hold Admin - VV
                                Page</a></td>
                    </tr>
                <?php endif ?>
                <?php if ($this->authorization_service->user_app_rights('CS000405')): ?>
                    <tr>
                        <td class="admin_menu"><a href="<?= base_url() ?>cs/compensation/manager_approval"
                                                  onClick="Pop('<?= base_url() ?>cs/compensation/manager_approval','compensation_admin');"
                                                  target="compensation_admin" class="admin_menu">Manager Approval Page
                                for Compensation</a></td>
                    </tr>
                <?php endif ?>
            </table>
        </td>
        <td width="40" class="admin_menu">&nbsp;</td>
    </tr>
    <tr>
        <td width="40">&nbsp;</td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($compliance_menu)): ?>
                    <tr>
                        <th class="admin_menu">Compliance</th>
                    </tr>
                    <?= $compliance_menu ?>
                <?php endif ?>
            </table>
        </td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($report_menu)): ?>
                    <tr>
                        <th class="admin_menu">Report</th>
                    </tr>
                    <?= $report_menu ?>
                <?php endif ?>
            </table>
        </td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($finance_menu)): ?>
                    <tr>
                        <th class="admin_menu">Finance</th>
                    </tr>
                    <?= $finance_menu ?>
                <?php endif ?>
            </table>
        </td>
        <td width="40" class="admin_menu">&nbsp;</td>
    </tr>
    <tr>
        <td width="40">&nbsp;</td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($competitor_analysis)): ?>
                    <tr>
                        <th class="admin_menu">Competitor Analysis Tool</th>
                    </tr>
                    <?= $competitor_analysis ?>
                <?php endif ?>
            </table>
        </td>
        <td width="40">&nbsp;</td>
        <td width="400" valign="top">
            <table class="admin_menu" width="100%">
                <?php if (!empty($marketplace)): ?>
                    <tr>
                        <th class="admin_menu">Marketplaces</th>
                    </tr>
                    <?= $marketplace ?>
                <?php endif ?>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="5" class="admin_menu"><br/><br/><br/></td>
    </tr>
    <tr>
        <td colspan="5" class="admin_button">
            <form name="logout" method="get" action="/auth/auth/deauth"><input class="admin_button" type="submit"
                                                                               value="logout"/></form>
        </td>
    </tr>
</table>
</body>
</html>
