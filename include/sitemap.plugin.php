<?php

/**
 * sitemap-plugin
 * version 1.5
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * @return array
 */
function b_sitemap_lexikon()
{
    $db   = \XoopsDatabaseFactory::getDatabaseConnection();
    $myts = \MyTextSanitizer::getInstance();

    // Permission
    global $xoopsUser;
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    $groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname('lexikon');
    $module_id     = $module->getVar('mid');
    $allowed_cats  = $grouppermHandler->getItemIds('lexikon_view', $groups, $module_id);
    $catids        = implode(',', $allowed_cats);
    $catperms      = " WHERE categoryID IN ($catids) ";
    $result        = $db->query('SELECT categoryID, name FROM ' . $db->prefix('lxcategories') . ' ' . $catperms . ' ORDER BY weight');

    $ret = [];
    while (list($id, $name) = $db->fetchRow($result)) {
        $ret['parent'][] = [
            'id'    => $id,
            'title' => htmlspecialchars($name, ENT_QUOTES | ENT_HTML5),
            'url'   => "category.php?categoryID=$id",
        ];
    }

    return $ret;
}
