<?php

class ModuleSynchroProcessor extends E2GProcessor {

    public function process() {
        $userId = $this->config['uid'];

        $synchro = $this->modx->e2gMod->synchro('../../../../../' . $this->config['path'], $this->config['pid'], $userId);
        if ($synchro !== TRUE) {
            $output = $this->modx->e2gMod->getError();
        } else {
            $output = '<div class="alert alert-success" style="margin: 30px; padding: 10px;"><i class="fa fa-info-circle fa-2x"></i> ' . $this->modx->e2gMod->lng['synchro_suc'] . '</div>';
        }

        return $output;
    }

}

return 'ModuleSynchroProcessor';
