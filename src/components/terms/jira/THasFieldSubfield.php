<?php
namespace extas\components\terms\jira;

use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\jira\IHasFieldSubfield;

/**
 * Trait THasFieldSubfield
 *
 * @package extas\components\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasFieldSubfield
{
    /**
     * @param ITerm $term
     * @return string
     */
    public function getField(ITerm $term): string
    {
        return $term->getParameterValue(IHasFieldSubfield::TERM_PARAM__FIELD_NAME, '');
    }

    /**
     * @param ITerm $term
     * @return string
     */
    public function getSubfieldMethod(ITerm $term): string
    {
        $subfield = $term->getParameterValue(IHasFieldSubfield::TERM_PARAM__SUBFIELD_NAME, '');
        return $subfield ? 'getField' . ucfirst($subfield) : 'getFieldValue';
    }
}
