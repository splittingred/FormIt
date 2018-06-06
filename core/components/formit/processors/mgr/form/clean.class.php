<?php
/**
 * Clean up Items
 *
 * @package formit
 * @subpackage processors
 */
class FormItFormCleanProcessor extends modProcessor
{
    public $classKey = 'FormItForm';
    public $languageTopics = array('formit:default');

    protected $days;

    public function initialize() {
        $days = $this->getProperty('days');
        $this->days = (is_numeric($days) && $days >= 0) ? $days : $this->modx->getOption('formit.cleanform.days');

        return parent::initialize();
    }


    public function process()
    {
        $date =  (new \DateTime())->modify('-' . $this->days .' days');

        $amount = $this->modx->removeCollection($this->classKey, ['date:<' => $date->getTimestamp()]);
        if($amount > 0) {
            $this->modx->log(modX::LOG_LEVEL_INFO, '[FormIt] Cleaned up ' . $amount . ' forms.');
        }

        return $this->success('success', [
            'amount' => $amount
        ]);
    }

}

return 'FormItFormCleanProcessor';
