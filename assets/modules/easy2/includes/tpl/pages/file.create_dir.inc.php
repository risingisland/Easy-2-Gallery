<?php
if (IN_MANAGER_MODE != 'true')
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
?>
<ul class="actionButtons">
    <li>
        <a href="<?php echo $this->e2gModCfg['index']; ?>&amp;pid=<?php echo $this->e2gModCfg['parent_id']; ?>">
            <i class="icon-no-border icon-color-yellow fa fa-arrow-circle-left fa-2x icon-vs"></i> <?php echo $this->lng['back']; ?>
        </a>
    </li>
    <li>
        <span class="h2title"><?php echo $this->lng['dir_create']; ?></span>
    </li>
</ul>
<form name="list" action="<?php echo $this->e2gModCfg['index'] . '&amp;act=create_dir&amp;pid=' . $this->e2gModCfg['parent_id']; ?>" method="post">
    <?php echo $this->plugin('OnE2GFolderCreateFormPrerender'); ?>
    <table id="dir_create" cellspacing="0" cellpadding="2" class="aForm" >
        <tr>
            <td><b><?php echo $this->lng['enter_dirname']; ?> :</b></td>
            <td><input name="name" type="text" size="30" /></td>
        </tr>
    </table>
    <div class="tab-pane" id="tabCreateFolderPane">
        <script type="text/javascript">
            tpCreateFolder = new WebFXTabPane(document.getElementById('tabCreateFolderPane'));
        </script>
        <div class="tab-page" id="tabCreateFolderPage">
            <h2 class="tab"><?php echo $this->lng['general']; ?></h2>
            <script type="text/javascript">
                tpCreateFolder.addTabPage( document.getElementById( 'tabCreateFolderPage') );
            </script>
            <table id="dir_info" cellspacing="0" cellpadding="2" class="aForm" >
                <tr>
                    <td><b><?php echo $this->lng['enter_new_alias']; ?> :</b></td>
                    <td><input name="alias" type="text" size="30" /></td>
                </tr>
                <tr>
                    <td><b><?php echo $this->lng['summary']; ?> :</b></td>
                    <td><input name="summary" type="text" size="95" /></td>
                </tr>
                <tr>
                    <td><b><?php echo $this->lng['tag']; ?> :</b></td>
                    <td><input name="tag" type="text" size="95" /></td>
                </tr>
                <tr>
                    <td valign="top"><b><?php echo $this->lng['description']; ?> :</b></td>
                    <td><textarea name="description" class="e2gRichText" style="width:500px" cols="" rows="5"></textarea></td>
                </tr>
            </table>
        </div>
    </div><?php echo $this->plugin('OnE2GFolderCreateFormRender'); ?>
    <div style="margin: 20px 0 20px 190px;">
        <input type="submit" class="btn btn-success" value="<?php echo $this->lng['save']; ?>" />
        <input type="button" class="btn btn-warning" value="<?php echo $this->lng['cancel']; ?>" onclick="document.location.href='<?php echo $this->e2gModCfg['index']; ?>&amp;pid=<?php echo $this->e2gModCfg['parent_id']; ?>'" />
    </div>
</form>