<?php
/**
 * @category   ZExt
 * @package    ZExt_Validate
 * @author     Sean P. O. MacCath-Moran
 * @email      zendcode@emanaton.com
 * @website    http://www.emanaton.com
 * @copyright  This work is licenced under a Attribution Non-commercial Share Alike Creative Commons licence
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/us/
 */

/**
 * @uses       ZExt_Validate_IdenticalField
 * @package    ZExt_Validate
 * @author     Sean P. O. MacCath-Moran
 * @email      zendcode@emanaton.com
 * @website    http://www.emanaton.com
 * @copyright  This work is licenced under a Attribution Non-commercial Share Alike Creative Commons licence
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/us/
 */
class Oxy_Validate_IdenticalField extends Zend_Validate_Abstract
{
    const NOT_MATCH = 'notMatch';
    const MISSING_FIELD_NAME = 'missingFieldName';
    const INVALID_FIELD_NAME = 'invalidFieldName';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::MISSING_FIELD_NAME => 'DEVELOPMENT ERROR: Field name to match against was not provided.',
        self::INVALID_FIELD_NAME => 'DEVELOPMENT ERROR: The field "%fieldName%" was not provided to match against.',
        self::NOT_MATCH => 'Does not match %fieldTitle%.'
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'fieldName' => '_fieldName',
        'fieldTitle' => '_fieldTitle'
    );

    /**
     * Internal options array
     */
    protected $_options = array(
        'fieldName'  => '',
        'fieldTitle' => ''
    );

    /**
     * Name of the field as it appear in the $context array.
     *
     * @var string
     */
    protected $_fieldName;

    /**
     * Title of the field to display in an error message.
     *
     * If evaluates to false then will be set to $this->_fieldName.
     *
     * @var string
     */
    protected $_fieldTitle;

    /**
     *
     * The following option keys are supported:
     * 'fieldName'  => Field name to validate identity against
     * 'fieldTitle' => Field title to use when reporting failure
     *
     * @param array|Zend_Config $options OPTIONAL
     * @return void
     */
    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else if (!is_array($options)) {
            $options = func_get_args();
            $temp['fieldName'] = array_shift($options);
            if (!empty($options)) {
                $temp['fieldTitle'] = array_shift($options);
            }

            $options = $temp;
        }

        $options += $this->_options;
        $this->setOptions($options);
    }

    /**
     * Returns all set Options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Set options for the email validator
     *
     * @param array $options
     * @return Oxy_Validate_IdenticalField fluid interface
     */
    public function setOptions(array $options = array())
    {
        if (array_key_exists('messages', $options)) {
            $this->setMessages($options['messages']);
        }

        if (array_key_exists('fieldName', $options)) {
            $this->setFieldName($options['fieldName']);
        }

        if (array_key_exists('fieldTitle', $options)) {
            $this->setFieldTitle($options['fieldTitle']);
        }

        return $this;
    }

    /**
     * Returns the field name.
     *
     * @return string
     */
    public function getFieldName ()
    {
        return $this->_fieldName;
    }
    /**
     * Sets the field name.
     *
     * @param  string $fieldName
     * @return Zend_Validate_IdenticalField Provides a fluent interface
     */
    public function setFieldName ($fieldName)
    {
        $this->_fieldName = $fieldName;
        return $this;
    }
    /**
     * Returns the field title.
     *
     * @return integer
     */
    public function getFieldTitle ()
    {
        return $this->_fieldTitle;
    }
    /**
     * Sets the field title.
     *
     * @param  string:null $fieldTitle
     * @return Zend_Validate_IdenticalField Provides a fluent interface
     */
    public function setFieldTitle ($fieldTitle = null)
    {
        $this->_fieldTitle = $fieldTitle ? $fieldTitle : $this->_fieldName;
        return $this;
    }
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if a field name has been set, the field name is available in the
     * context, and the value of that field name matches the provided value.
     *
     * @param  string $value
     *
     * @return boolean
     */
    public function isValid ($value, $context = null)
    {
        $this->_setValue($value);
        $field = $this->getFieldName();
        if (empty($field)) {
            $this->_error(
            self::MISSING_FIELD_NAME);
            return false;
        } elseif (! isset($context[$field])) {
            $this->_error(
            self::INVALID_FIELD_NAME);
            return false;
        } elseif (is_array($context)) {
            if ($value == $context[$field]) {
                return true;
            }
        } elseif (is_string($context) && ($value ==
         $context)) {
            return true;
        }
        $this->_error(self::NOT_MATCH);
        return false;
    }
}
