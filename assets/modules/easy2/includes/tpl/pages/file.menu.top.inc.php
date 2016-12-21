<?php
if (IN_MANAGER_MODE != 'true')
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<div id="e2g_topmenu">
    <form name="topmenu" action="" method="post">
        <ul class="modButtons">
            <li class="system-buttons"><span>System</span>
                <ul>
                    <?php
                    if (!isset($this->sanitizedGets['tag']) && !isset($this->sanitizedGets['path'])) {
                        switch ($_SESSION['mod_view']) {
                            case 'list':
                                ?>
                                <li>
                                    <a href="javascript:;" onclick="viewDefaultGrid('<?php echo $this->galleryPath['string'] ?>','<?php echo $this->e2gModCfg['parent_id'] ?>');" title="<?php echo $this->lng['files_db']; ?>">
                                        <i class="icon-custom rounded-x icon-bg-grey fa fa-database fa-2x fa-fw"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" onclick="viewDefaultGrid('<?php echo $this->galleryPath['string'] ?>','<?php echo $this->e2gModCfg['parent_id'] ?>','rescanhd');" title="<?php echo $this->lng['files_hd']; ?>">
                                        <i  class="icon-custom rounded-x icon-bg-light-grey fa fa-hdd-o fa-2x fa-fw"></i>
                                    </a></li>
                                <?php
                                break;
                            case 'thumbnails':
                                ?>
                                <li>
                                    <a href="javascript:;" onclick="viewDefaultThumbnails('<?php echo $this->galleryPath['string'] ?>','<?php echo $this->e2gModCfg['parent_id'] ?>');" title="<?php echo $this->lng['files_db']; ?>">
                                        <i class="icon-custom rounded-x icon-bg-grey fa fa-database fa-2x fa-fw"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" onclick="viewDefaultThumbnails('<?php echo $this->galleryPath['string'] ?>','<?php echo $this->e2gModCfg['parent_id'] ?>','rescanhd');" title="<?php echo $this->lng['files_hd']; ?>">
                                        <i class="icon-custom rounded-x icon-bg-light-grey fa fa-hdd-o fa-2x fa-fw"></i>
                                    </a></li>
                                <?php
                                break;
                            default:
                                break;
                        }
                        ?>

                        <li>
                            <a href="javascript:;" onclick="synchro('<?php echo $this->e2gModCfg['gdir']; ?>', '<?php echo $this->e2gModCfg['parent_id']; ?>','<?php echo $this->modx->getLoginUserID(); ?>');" title="<?php echo $this->lng['synchro']; ?>">
                                <i class="icon-custom rounded-x icon-bg-orange fa fa-refresh fa-2x fa-fw"></i>
                            </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="<?php echo $this->e2gModCfg['index']; ?>&amp;act=clean_cache" title="<?php echo $this->lng['clean_cache']; ?>">
                            <i class="icon-custom rounded-x icon-bg-red fa fa-trash fa-2x fa-fw"></i>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="system-buttons"><span>Edit</span>
                <ul>
                    <?php if (!isset($this->sanitizedGets['tag']) && !isset($this->sanitizedGets['path'])) { ?>
                        <li>
                            <a href="<?php echo $this->e2gModCfg['index']; ?>&amp;page=create_dir&amp;pid=<?php echo $this->e2gModCfg['parent_id']; ?>" title="<?php echo $this->lng['dir_create']; ?>">
                                <i class="icon-custom rounded-2x icon-bg-yellow fa fa-folder fa-2x fa-fw"></i>
                            </a>
                        </li>
                        <?php if ($userRole == '1' || in_array($this->e2gModCfg['e2gPages']['upload']['access'], $userPermissionsArray)) { ?>
                            <li>
                                <a href="<?php echo $this->e2gModCfg['blank_index']; ?>&amp;e2gpg=<?php echo $this->e2gModCfg['e2gPages']['upload']['e2gpg']; ?>&amp;pid=<?php echo $this->e2gModCfg['parent_id']; ?>" title="<?php echo $this->lng['upload']; ?>">
                                    <i class="icon-custom rounded-2x icon-bg-u fa fa-upload fa-2x fa-fw"></i>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                    <li>
                        <a href="<?php echo $this->e2gModCfg['index']; ?>&amp;view=list<?php
                    echo!empty($this->sanitizedGets['pid']) ? '&amp;pid=' . $this->e2gModCfg['parent_id'] : '';
                    echo!empty($this->sanitizedGets['path']) ? '&amp;path=' . $this->sanitizedGets['path'] : '';
                    ?>" title="<?php echo $this->lng['list']; ?>">
                            <i class="icon-custom rounded-2x icon-bg-brown fa fa-th-list fa-2x fa-fw"></i>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->e2gModCfg['index']; ?>&amp;view=thumbnails<?php
                           echo!empty($this->sanitizedGets['pid']) ? '&amp;pid=' . $this->e2gModCfg['parent_id'] : '';
                           echo!empty($this->sanitizedGets['path']) ? '&amp;path=' . $this->sanitizedGets['path'] : '';
                    ?>" title="<?php echo $this->lng['thumbnails']; ?>">
                            <i class="icon-custom rounded-2x icon-bg-brown fa fa-th-large fa-2x fa-fw"></i>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <ul>
            <li style="float:right; list-style: none;"><?php echo $this->lng['gotofolder']; ?>:
                <select name="newparent" onchange="submitform(1)">
                    <option value="">&nbsp;</option>
                    <?php echo $this->_getDirDropDownOptions(0, 1); ?>
                </select>
            </li>
            <li style="float:right; list-style: none;">
                <?php echo $this->lng['tag']; ?>:
                <select name="opentag" onchange="submitform(2)">
                    <option value="">&nbsp;</option>
                    <?php echo $this->_tagOptions($tag); ?>
                </select>
            </li>
        </ul>
    </form>
</div>
<div style="clear:both;"></div>