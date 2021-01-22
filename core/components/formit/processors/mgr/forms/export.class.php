<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

class FormItFormExportProcessor extends modProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'FormItForm';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['formit:default'];

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortField = 'date';

    /**
     * @access public.
     * @var String.
     */
    public $defaultSortDirection = 'DESC';

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'formit.form';

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('formit', 'FormIt', $this->modx->getOption('formit.core_path', null, $this->modx->getOption('core_path') . 'components/formit/') . 'model/formit/');

        $this->setDefaultProperties([
            'columns'       => 'Form,Date,IP',
            'filename'      => $this->objectType . '.csv',
            'directory'     => $this->modx->getOption('core_path') . 'cache/export/formit/',
            'dateFormat'    => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format')
        ]);

        if ($this->getProperty('download') === null) {
            $this->setProperty('download', 0);
        }

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getData()
    {
        $criteria = $this->modx->newQuery($this->classKey);

        $criteria->where([
            'context_key:IN' => $this->getAvailableContexts()
        ]);

        $form = $this->getProperty('form');

        if (!empty($form)) {
            $criteria->where([
                'form' => $form
            ]);
        }

        $context = $this->getProperty('context');

        if (!empty($context)) {
            $criteria->where([
                'context_key' => $context
            ]);
        }

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'form:LIKE' => '%' . $query . '%'
            ]);
        }

        $startDate = $this->getProperty('start_date');

        if (!empty($startDate)) {
            $criteria->where([
                'date:>=' => strtotime(date('Y-m-d', strtotime($startDate)) . ' 00:00:00')
            ]);
        }

        $endDate = $this->getProperty('end_date');

        if (!empty($endDate)) {
            $criteria->where([
                'date:<=' => strtotime(date('Y-m-d', strtotime($endDate)) . ' 23:59:59')
            ]);
        }

        $data = [];

        foreach ($this->modx->getCollection($this->classKey, $criteria) as $object) {
            $array = array_merge($object->toArray(), [
                'date' => date($this->getProperty('dateFormat'), $object->get('date'))
            ]);

            $values = $object->get('values');

            if ((int) $object->get('encrypted') === 1) {
                $values = $object->decrypt($object->get('values'), $object->get('type'));
            }

            $values = json_decode($values, true);

            if ($values) {
                $array['values'] = $values;
            } else {
                $array['values'] = [];
            }

            $data[] = $array;
        }

        return $data;
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getAvailableContexts()
    {
        $contexts = ['-'];

        foreach ($this->modx->getCollection('modContext') as $context) {
            $contexts[] = $context->get('key');
        }

        return $contexts;
    }

    /**
     * @access public.
     * @param Array $data.
     * @param Array $columns.
     * @return Array.
     */
    public function getColumns(array $data = [], array $columns = [])
    {
        foreach ($data as $value) {
            if (isset($value['values'])) {
                foreach ((array) array_keys($value['values']) as $key) {
                    if (!in_array($key, $columns, true)) {
                        $columns[] = $key;
                    }
                }
            }

        }

        return $columns;
    }

    /**
     * @access public.
     * @return mixed.
     */
    public function process()
    {
        if (!is_dir($this->getProperty('directory'))) {
            if (!mkdir($this->getProperty('directory'), 0777, true)) {
                return $this->failure($this->modx->lexicon('formit.export_dir_failed'));
            }
        }

        $file = $this->getProperty('download');

        if (empty($file)) {
            return $this->setFile();
        }

        return $this->getFile();
    }

    /**
     * @access public.
     * @return mixed.
     */
    public function setFile()
    {
        $fopen = fopen($this->getProperty('directory') . $this->getProperty('filename'), 'wb');

        if ($fopen) {
            $data           = $this->getData();
            $columns        = $this->getColumns($data, explode(',', $this->getProperty('columns')));
            $defaultColumns = array_map('strtolower', explode(',', $this->getProperty('columns')));

            if ($columns) {
                fputcsv($fopen, $columns, $this->getProperty('delimiter'));

                foreach ($data as $row) {
                    $value  = [];
                    $row    = array_change_key_case($row, CASE_LOWER);

                    foreach ($columns as $column) {
                        if (in_array(strtolower($column), $defaultColumns, true)) {
                            if (isset($row[strtolower($column)])) {
                                $value[] = is_array($row[strtolower($column)]) ? implode(',', $row[strtolower($column)]) : $row[strtolower($column)];
                            } else {
                                $value[] = '';
                            }
                        } else {
                            if (isset($row['values'][$column])) {
                                $value[] = is_array($row['values'][$column]) ? implode(',', $row['values'][$column]) : $row['values'][$column];
                            } else {
                                $value[] = '';
                            }
                        }
                    }

                    fputcsv($fopen, $value, $this->getProperty('delimiter'));
                }
            }

            fclose($fopen);

            return $this->success($this->modx->lexicon('success'));
        }

        return $this->failure($this->modx->lexicon('formit.export_failed'));
    }

    /**
     * @access public.
     * @return mixed.
     */
    public function getFile()
    {
        $file = $this->getProperty('directory') . $this->getProperty('filename');

        if (is_file($file)) {
            $content = file_get_contents($file);

            if ($content) {
                header('Content-Encoding: UTF-8');
                header('Content-Type: application/force-download');
                header('Content-Disposition: attachment; filename="' . $this->getProperty('filename') . '"');
                header("Pragma: no-cache");
                header("Expires: 0");
                echo "\xEF\xBB\xBF"; // UTF-8 BOM

                return $content;
            }
        }

        return $this->failure($this->modx->lexicon('formit.export_failed'));
    }
}

return 'FormItFormExportProcessor';
