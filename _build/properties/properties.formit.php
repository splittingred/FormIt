<?php
/**
 * FormIt
 *
 * Copyright 2009-2010 by Shaun McCormick <shaun@modx.com>
 *
 * FormIt is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * FormIt is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * FormIt; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package formit
 */
/**
 * Default properties for FormIt snippet
 *
 * @package formit
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'hooks',
        'desc' => 'prop_formit.hooks_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'submitVar',
        'desc' => 'prop_formit.submitvar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'errTpl',
        'desc' => 'prop_formit.errtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '<span class="error">[[+error]]</span>',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'customValidators',
        'desc' => 'prop_formit.customvalidators_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'clearFieldsOnSuccess',
        'desc' => 'prop_formit.clearfieldsonsuccess_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'formit:properties',
    ),
    /* redirect hook */
    array(
        'name' => 'redirectTo',
        'desc' => 'prop_formit.redirectto_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    /* email hook */
    array(
        'name' => 'emailTo',
        'desc' => 'prop_formit.emailto_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailToName',
        'desc' => 'prop_formit.emailtoname_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailFrom',
        'desc' => 'prop_formit.emailfrom_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailFromName',
        'desc' => 'prop_formit.emailfromname_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailReplyTo',
        'desc' => 'prop_formit.emailreplyto_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailReplyToName',
        'desc' => 'prop_formit.emailreplytoname_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailCC',
        'desc' => 'prop_formit.emailcc_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailCCName',
        'desc' => 'prop_formit.emailccname_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailBCC',
        'desc' => 'prop_formit.emailbcc_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailBCCName',
        'desc' => 'prop_formit.emailbccname_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailSubject',
        'desc' => 'prop_formit.emailsubject_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailUseFieldForSubject',
        'desc' => 'prop_formit.emailusefieldforsubject_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'formit:properties',
    ),
    array(
        'name' => 'emailHtml',
        'desc' => 'prop_formit.emailhtml_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'formit:properties',
    ),
);

return $properties;