<?php

if (!isset($this->sanitizedGets['path'])) {
?>
<div id="e2g_bottommenu">
    <ul class="actionButtons e2g_wrapper">
        <li style="float:left;">
            <b><?php echo $this->lng['withselected']; ?>: </b>
            <select id="fileActions" name="fileActions">
                <option value="">&nbsp;</option>
                <option value="show"><?php echo $this->lng['show']; ?></option>
                <option value="hide"><?php echo $this->lng['hide']; ?></option>
                <option value="delete"><?php echo $this->lng['delete']; ?></option>
                <?php if(class_exists('ZipArchive')) { ?>
                <option value="download"><?php echo $this->lng['download']; ?></option>
                    <?php } ?>
                <option value="tag"><?php echo $this->lng['tag']; ?></option>
                <option value="move"><?php echo $this->lng['move']; ?></option>
            </select>
            <!--a href="javascript:void(0)" onclick="submitfileform(1)">
                <i class="icon-no-border icon-color-green fa fa-play-circle fa-2x icon-vs"></i>
                <span><?php echo $this->lng['go']; ?></span>
            </a-->
        </li>
        <li id="showActions" style="display: none;">
            <a name="show" href="javascript:;" onclick="submitform(3)">
                <i class="fa fa-eye fa-2x icon-vs"></i> <?php echo $this->lng['show']; ?>
            </a>
        </li>
        <li id="hideActions" style="display: none;">
            <a name="hide" href="javascript:;" onclick="submitform(4)">
                <i class="fa fa-eye-slash fa-2x icon-vs"></i> <?php echo $this->lng['hide']; ?>
            </a>
        </li>
        <li id="deleteActions" style="display: none;">
            <a name="delete" href="javascript:;" onclick="submitform(5)" style="font-weight:bold;color:red">
                <i class="fa fa-minus-circle fa-2x icon-vs"></i> <?php echo $this->lng['delete']; ?>
            </a>
        </li>
        <?php if(class_exists('ZipArchive')) { ?>
        <li id="downloadActions" style="display: none;">
            <a name="download" href="javascript:;" onclick="submitform(6)">
                <i class="fa fa-download fa-2x icon-vs"></i> <?php echo $this->lng['download']; ?>
            </a>
        </li><?php } ?>
        <li id="tagActions" style="display: none;">
            <?php echo $this->lng['tag'];?>:
            <input name="tag_input" type="text" size="20" />&nbsp;
            <a name="tag_add" href="javascript:;" onclick="submitform(8)" title="Add Tag">
                <i class="fa fa-plus-circle fa-2x icon-vs"></i> <?php echo $this->lng['add']; ?>
            </a>&nbsp;
            <a name="tag_del" href="javascript:;" onclick="submitform(9)" title="Remove Tag">
                <i class="fa fa-minus-circle fa-2x icon-vs"></i> <?php echo $this->lng['remove']; ?>
            </a>
        </li>
        <li id="moveActions" style="display: none;">
            <span><?php echo $this->lng['movetofolder']; ?> :</span>
            <select name="newparent">
                <option value="">&nbsp;</option>
                <?php echo $this->_getDirDropDownOptions(); ?>
            </select>
            <span><?php echo $this->lng['and']; ?></span>
            <input type="radio" name="gotofolder" value="gothere" checked="checked"/><?php echo $this->lng['go_there']; ?>
            <input type="radio" name="gotofolder" value="stayhere" /><?php echo $this->lng['stay_here']; ?>
            <a name="move" href="javascript:;" onclick="submitform(7)">
                <span><i class="fa fa-random fa-2x icon-vs"></i> <?php echo $this->lng['go']; ?></span>
            </a>
        </li>
    </ul>
</div>
<?php
}