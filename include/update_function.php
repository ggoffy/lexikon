<?php
// $Id$
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://xoops.org>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//

//error_reporting(E_ALL);

use XoopsModules\Lexikon\{
    Helper,
    Common\Configurator,
    Utility
};


/**
 * @param      $module
 * @param null $prev_version
 * @return bool|null
 */
function xoops_module_update_lexikon($module, $prev_version = null)
{
    $moduleDirName      = \basename(\dirname(__DIR__));
    $moduleDirNameUpper = mb_strtoupper($moduleDirName);

    $helper       = Helper::getInstance();
    $utility      = new Utility();
    $configurator = new Configurator();

    $ret = null;
    if ($prev_version < 152) {
        $ret = xoops_module_update_lexikon_v152($module);
    }
    $errors = $module->getErrors();
    if (!empty($errors)) {
        print_r($errors);
    }

    return $ret;
}

/**
 * @param $xoopsModule
 * @return bool
 */
function xoops_module_update_lexikon_v152($xoopsModule)
{
    /**
     * Create default upload directories
     */
    // Copy base file
    $indexFile = XOOPS_UPLOAD_PATH . '/index.html';
    $blankFile = XOOPS_UPLOAD_PATH . '/blank.gif';
    // Making of uploads/lexikon folder
    $p_lexikon = XOOPS_UPLOAD_PATH . '/lexikon';
    if (!is_dir($p_lexikon)) {
        if (!mkdir($p_lexikon, 0777) && !is_dir($p_lexikon)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $p_lexikon));
        }
        chmod($p_lexikon, 0777);
    }
    copy($indexFile, $p_lexikon . '/index.html');
    // Making of categories folder
    $pl_categories = $p_lexikon . '/categories';
    if (!is_dir($pl_categories)) {
        if (!mkdir($pl_categories, 0777) && !is_dir($pl_categories)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $pl_categories));
        }
        chmod($pl_categories, 0777);
    }
    copy($indexFile, $pl_categories . '/index.html');

    $plc_images = $pl_categories . '/images';
    if (!is_dir($plc_images)) {
        if (!mkdir($plc_images, 0777) && !is_dir($plc_images)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $plc_images));
        }
        chmod($plc_images, 0777);
    }
    copy($indexFile, $plc_images . '/index.html');
    copy($blankFile, $plc_images . '/blank.gif');
    // Making of entries folder
    $pl_entries = $p_lexikon . '/entries';
    if (!is_dir($pl_entries)) {
        if (!mkdir($pl_entries, 0777) && !is_dir($pl_entries)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $pl_entries));
        }
        chmod($pl_entries, 0777);
    }
    copy($indexFile, $pl_entries . '/index.html');

    $ple_images = $pl_entries . '/images';
    if (!is_dir($ple_images)) {
        if (!mkdir($ple_images, 0777) && !is_dir($ple_images)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $ple_images));
        }
        chmod($ple_images, 0777);
    }
    copy($indexFile, $ple_images . '/index.html');
    copy($blankFile, $ple_images . '/blank.gif');

    return true;
}
