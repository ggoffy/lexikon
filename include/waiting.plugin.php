<?php
// waiting Plugin for Lexikon glossary module

/**
 * @return array
 */
function b_waiting_lexikon()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = [];

    $moduleDirName = \basename(\dirname(__DIR__));
    xoops_loadLanguage('plugin', $moduleDirName);

    // Waiting
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE submit=1 AND request=0 ');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/lexikon/admin/main.php?statussel=1';
        [$block['pendingnum']] = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_SUBMITTED;
    }
    $ret[] = $block;

    // Request
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE submit=1 AND request=1 ');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/lexikon/admin/main.php?statussel=4';
        [$block['pendingnum']] = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_REQUESTS;
    }
    $ret[] = $block;

    return $ret;
}
