<?php
/**
 * Get list Items
 *
 * @package formit
 * @subpackage processors
 */
class FormItFormExportProcessor extends modObjectGetListProcessor
{
    public $classKey = 'FormItForm';
    public $languageTopics = array('formit:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $form = $this->getProperty('form');
        if (!empty($form)) {
            $c->andCondition(array('form' => $form));
        }
        
        $context_key = $this->getProperty('context_key');
        if (!empty($context_key)) {
            $c->andCondition(array('context_key' => $context_key));
        }

        $startDate = $this->getProperty('startDate');
        if ($startDate != '') {
            $c->andCondition(array('date:>' => strtotime($startDate.' 00:00:00')));
        }

        $endDate = $this->getProperty('endDate');
        if ($endDate != '') {
            $c->andCondition(array('date:<' => strtotime($endDate.' 23:59:59')));
        }
        $c->prepare();
        return $c;
    }

    public function process()
    {
        $beforeQuery = $this->beforeQuery();
        if ($beforeQuery !== true) {
            return $this->failure($beforeQuery);
        }
        $data = $this->getData();

        $exportPath = $this->modx->getOption('core_path', null, MODX_CORE_PATH).'export/'.$this->classKey.'/';

        $fileName = 'formit-export-'.time().'.csv';
        if (!is_dir($exportPath)) {
            mkdir($exportPath);
        }
        //$fileName = $exportPath.$f;

        $list = $this->createCsv($exportPath, $fileName, $data);
        return $this->outputArray($list, $data['total']);
    }

    public function createCsv($exportPath, $file, array $data)
    {

        $keys = array('IP', 'Date');

        $handle = $exportPath.$file;
        if ($this->getProperty('download')) {
            ob_start();
            $handle = 'php://output';
        }
        $fp = fopen($handle, 'w');

        foreach ($data['results'] as $object) {
            if ($this->checkListPermission && $object instanceof modAccessibleObject && !$object->checkPolicy('list')) {
                continue;
            }
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $keys = array_unique(array_merge($keys, array_keys($objectArray['values'])));
                //fputcsv($fp, $objectArray['data']);
            }
        }

        $defaultArr = array_flip($keys);
        $defaultArr = array_map(
            function () {
            },
            $defaultArr
        );

        fputcsv($fp, $keys, ';');
        foreach ($data['results'] as $object) {
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $objectArray['values']['IP'] = $object->get('ip');
                $objectArray['values']['Date'] = date($this->modx->getOption('manager_date_format').' '.$this->modx->getOption('manager_time_format'), $object->get('date'));
                foreach ($objectArray['values'] as $vk => $vv) {
                    $objectArray['values'][$vk] = (is_array($vv)) ? implode(',', $vv) : $vv;
                }
                fputcsv($fp, array_merge($defaultArr, $objectArray['values']), ';');
            }
        }
        fclose($fp);

        if ($this->getProperty('download')) {
            $str = ob_get_clean();
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="'.$file.'"');
            header("Pragma: no-cache");
            header("Expires: 0");
            echo "\xEF\xBB\xBF";
            echo $str;
            exit;
        }
 
        return array('file' =>$handle, 'filename' => $file, 'content' => ob_get_clean());
    }
 
    public function prepareRow(xPDOObject $object)
    {
        $ff = $object->toArray();
        
        if ($ff['encrypted']) {
            $ff['values'] = $object->decrypt($ff['values']);
        }
        $ff['values'] = json_decode($ff['values'], true);
        return $ff;
    }
}
return 'FormItFormExportProcessor';
