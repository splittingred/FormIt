<?php
/**
 * Migrate encrypted forms from mcrypt to openssl
 *
 * @package formit
 * @subpackage processors
 */

class FormItMigrateProcessor extends modProcessor
{
    public function process()
    {
        /* First check if openssl is available */
        if (!function_exists('openssl_encrypt')) {
            $this->log($this->modx->lexicon('formit.encryption_unavailable'));
            return $this->outputArray(array(), 0);
        }
        $count = 0;
        $limit = 500;

        /* Search for all encrypted forms which are encrypted with old mcrypt method (encryption_type = 1) */
        $c = $this->modx->newQuery('FormItForm');
        $c->where(array(
            'encrypted' => 1,
            'encryption_type' => 1
        ));
        $collection = $this->modx->getIterator('FormItForm', $c);

        foreach ($collection as $form) {
            if ($count > $limit) {
                break;
            }
            $oldValues = $form->get('values');
            $oldValues = $form->decrypt($oldValues, 1);
            /* Fix for when forms are encrypted with openssl, but encryption_type field is not set to 2 */
            if (!is_array(json_decode($oldValues, true))) {
                $newValues = $form->get('values');
            } else {
                $newValues = $form->encrypt($oldValues);
            }
            if ($newValues) {
                $this->modx->exec("UPDATE {$this->modx->getTableName('FormItForm')}
                SET {$this->modx->escape('encryption_type')} = {$this->modx->quote(2)},
                    {$this->modx->escape('values')} = {$this->modx->quote($newValues)} 
                WHERE {$this->modx->escape('id')} = {$this->modx->quote($form->get('id'))}");
                $count++;
            }
        }

        if ($count === 0) {
            $this->log('No mcrypt encrypted forms found.');
        } else {
            $this->log('-------------------------------------------------------------');
            $this->log('Successfully completed migration.');
            $this->log('A total of '.$count.' encrypted forms are migrated.');
        }

        return $this->outputArray(array(), $count);
    }

    private function log($message)
    {
        // Decrease log level to enable INFO level logging
        // First get the current log level
        $logLevel = $this->modx->getOption('log_level');
        $this->modx->setLogLevel(MODx::LOG_LEVEL_INFO);
        $logTarget = array(
            'target' => 'FILE',
            'options' => array(
                'filepath' => $this->modx->formit->config['assetsPath'],
                'filename' => 'migration.log'
            )
        );
        $this->modx->log(MODx::LOG_LEVEL_INFO, $message, $logTarget);
        // Set log level back to original
        $this->modx->setLogLevel($logLevel);
    }
}
return 'FormItMigrateProcessor';
