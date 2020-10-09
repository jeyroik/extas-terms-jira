<?php
namespace extas\interfaces\terms\jira;

use extas\interfaces\terms\ITerm;

interface IHasFieldSubfield
{
    public const TERM_PARAM__FIELD_NAME = 'field_name';
    public const TERM_PARAM__SUBFIELD_NAME = 'subfield_name';

    /**
     * @param ITerm $term
     * @return string
     */
    public function getField(ITerm $term): string;

    /**
     * @param ITerm $term
     * @return string
     */
    public function getSubfieldMethod(ITerm $term): string;
}