<?php

namespace Sterc\FormIt\Hook;

class Math
{
    /**
     * A reference to the hook instance.
     * @var \Sterc\FormIt\Hook\ $hook
     */
    public $hook;

    /**
     * A reference to the modX instance.
     * @var \modx $modx
     */
    public $modx;

    /**
     * An array of configuration properties
     * @var array $config
     */
    public $config = [];

    /**
     * A reference to the FormIt instance.
     * @var \Sterc\FormIt $formit
     */
    public $formit;

    /**
     * @param \Sterc\FormIt\Hook $hook
     * @param array $config
     */
    public function __construct($hook, array $config = array())
    {
        $this->hook =& $hook;
        $this->formit =& $hook->formit;
        $this->modx = $hook->formit->modx;
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Math field hook for anti-spam math input field.
     *
     * @param array $fields An array of cleaned POST fields
     *
     * @return bool True if email was successfully sent.
     */
    public function process($fields = [])
    {
        $mathField = $this->modx->getOption('mathField', $this->config, 'math');
        if (!isset($fields[$mathField])) {
            $this->hook->errors[$mathField] = $this->modx->lexicon(
                'formit.math_field_nf',
                array('field' => $mathField)
            );
            return false;
        }

        if (empty($fields[$mathField])) {
            $this->hook->errors[$mathField] = $this->modx->lexicon(
                'formit.field_required',
                array('field' => $mathField)
            );
            return false;
        }

        $passed = false;
        if (isset(
            $_SESSION['formitMath']['op1'],
            $_SESSION['formitMath']['op2'],
            $_SESSION['formitMath']['operator']
        )) {
            $answer = false;
            $op1 = $_SESSION['formitMath']['op1'];
            $op2 = $_SESSION['formitMath']['op2'];

            switch ($_SESSION['formitMath']['operator']) {
                case '+':
                    $answer = $op1 + $op2;
                    break;
                case '-':
                    $answer = $op1 - $op2;
                    break;
                case '*':
                    $answer = $op1 * $op2;
                    break;
            }
            $guess = (int) $fields[$mathField];
            $passed = ($guess === $answer);
        }

        if (!$passed) {
            $this->hook->addError($mathField, $this->modx->lexicon('formit.math_incorrect'));
        }

        return $passed;
    }
}
