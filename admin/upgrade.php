<?php
/**
 * Module: lexikon
 * Version: v 1.00
 * Release Date: 18 Dec 2011
 * Author: Yerres
 * Licence: GNU
 */

use Xmf\Request;
use XoopsModules\Lexikon\{
    Helper,
    Utility
};
/** @var Helper $helper */

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();


$helper = Helper::getInstance();

global $xoopsUser, $xoopsModule, $xoopsDB;
$go = Request::getInt('go', 0, 'POST');

/**
 * @param $msg
 */
function showerror($msg)
{
    global $xoopsDB;
    if ('' != $xoopsDB->error()) {
        echo '<br>' . $msg . '  -  ERROR: ' . $xoopsDB->error();
    } else {
        echo '<br>' . $msg . ' O.K.!';
    }
}

if ($go) {
    if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
        // 0) update the categories table
        if (!Utility::fieldExists('logourl', $xoopsDB->prefix('lxcategories'))) {
            $sql = $xoopsDB->queryF('ALTER TABLE ' . $xoopsDB->prefix('lxcategories') . " ADD logourl VARCHAR ( 150 ) NOT NULL DEFAULT '' AFTER weight");
            showerror('Update table "lxcategories" ...');
        }
        // 1) if downgrade
        if (Utility::fieldExists('parent', $xoopsDB->prefix('lxcategories'))) {
            $sql = $xoopsDB->queryF('ALTER TABLE ' . $xoopsDB->prefix('lxcategories') . ' DROP `parent`');
            showerror('Update table "lxcategories" ...');
        }

        // 2) if multicats OFF set categoryID to '1' (prior '0')
        if (0 === $helper->getConfig('multicats')) {
            $result = $xoopsDB->query(
                'SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE categoryID = 0  '
            );
            [$totals] = $xoopsDB->fetchRow($result);
            if ($totals > 0) {
                $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('lxentries') . ' SET categoryID = 1 WHERE categoryID = 0 ');
                showerror('Update table "lxentries" ...');
            }
        }
        // 3) tag module
        if (!Utility::fieldExists('item_tag', $xoopsDB->prefix('lxentries'))) {
            $sql = $xoopsDB->queryF('ALTER TABLE ' . $xoopsDB->prefix('lxentries') . ' ADD item_tag TEXT NULL AFTER comments');
            showerror('Update table "lxentries" ...');
        }
        //-------------
        echo "<br><br><H3>Update finished!</H3><br><a href='index.php'>Back to Admin</a>";
    } else {
        printf("<div class='center;'><H2>%s</H2></div>\n", _AM_LEXIKON_UPGR_ACCESS_ERROR);
    }
    xoops_cp_footer();
} else {
    echo "<table class='outer' style='width: 60%' cellspacing='1' cellpadding='0' align='center'>
    <tr class='odd'><td align='left'><h2>Upgradescript Lexikon</h2>
    <span style='color: #0066CC'>This script updates from any previous version </span><br><br>
    The script will adapt the database-structure to the new module-functions.<br>
    Excute only once. Dont forget to update the Module-templates. <br><br>
    <form method='post' action='upgrade.php' name='frmAct'>
    <input type='hidden' name='go' value='1' >
    <input type='submit' name='sbt' value='Start' class='formButton' >
    </form></td></tr></table>";
    xoops_cp_footer();
}
