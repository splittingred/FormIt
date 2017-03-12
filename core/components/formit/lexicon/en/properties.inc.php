<?php
/**
 * FormIt
 *
 * Copyright 2009-2012 by Shaun McCormick <shaun@modx.com>
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
 * Properties Lexicon Topic
 *
 * @package formit
 * @subpackage lexicon
 * @language en
 */
/* FormIt properties */
$_lang['prop_formit.hooks_desc'] = 'What scripts to fire, if any, after the form passes validation. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_formit.prehooks_desc'] = 'What scripts to fire, if any, once the form loads. You can pre-set form fields via $scriptProperties[`hook`]->fields[`fieldname`]. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_formit.submitvar_desc'] = 'If set, will not begin form processing if this POST variable is not passed.';
$_lang['prop_formit.validate_desc'] = 'A comma-separated list of fields to validate, with each field name as name:validator (eg: username:required,email:required). Validators can also be chained, like email:email:required. This property can be specified on multiple lines.';
$_lang['prop_formit.errtpl_desc'] = 'The wrapper template for error messages.';
$_lang['prop_formit.validationerrormessage_desc'] = 'A general error message to set to a placeholder if validation fails. Can contain [[+errors]] if you want to display a list of all errors at the top.';
$_lang['prop_formit.validationerrorbulktpl_desc'] = 'HTML tpl that is used for each individual error in the generic validation error message value.';
$_lang['prop_formit.customvalidators_desc'] = 'A comma-separated list of custom validator names (snippets) you plan to use in this form. They must be explicitly stated here, or they will not be run.';
$_lang['prop_formit.trimvaluesdeforevalidation_desc'] = 'Whether or not to trim spaces from the beginning and end of values before attempting validation. Defaults to true.';
$_lang['prop_formit.clearfieldsonsuccess_desc'] = 'If true, will clear the fields on a successful form submission that does not redirect.';
$_lang['prop_formit.successmessage_desc'] = 'If set, will set this a placeholder with the name of the value of the property &successMessagePlaceholder, which defaults to `fi.successMessage`.';
$_lang['prop_formit.successmessageplaceholder_desc'] = 'The placeholder to set the success message to.';
$_lang['prop_formit.store_desc'] = 'If true, will store the data in the cache for retrieval using the FormItRetriever snippet.';
$_lang['prop_formit.storetime_desc'] = 'If `store` is set to true, this specifies the number of seconds to store the data from the form submission. Defaults to five minutes.';
$_lang['prop_formit.storelocation_desc'] = 'If `store` is set to true, this specifies the cache location of the data from the form submission. Defaults to MODX cache.';
$_lang['prop_formit.allowfiles_desc'] = 'If set to 0, will prevent files from being submitted on the form.';
$_lang['prop_formit.placeholderprefix_desc'] = 'The prefix to use for all placeholders set by FormIt for fields. Defaults to `fi.`';
$_lang['prop_formit.redirectto_desc'] = 'If `redirect` is set as a hook, this must specify the Resource ID to redirect to.';
$_lang['prop_formit.redirectparams_desc'] = 'A JSON array of parameters to pass to the redirect hook that will be passed when redirecting.';
$_lang['prop_formit.recaptchajs_desc'] = 'If `recaptcha` is set as a hook, this can be a JSON object that will be set to the JS RecaptchaOptions variable, which configures options for reCaptcha.';
$_lang['prop_formit.recaptchaheight_desc'] = 'If `recaptcha` is set as a hook, this will select the height for the reCaptcha widget.';
$_lang['prop_formit.recaptchatheme_desc'] = 'If `recaptcha` is set as a hook, this will select a theme for the reCaptcha widget.';
$_lang['prop_formit.recaptchawidth_desc'] = 'If `recaptcha` is set as a hook, this will set the width for the reCaptcha widget.';
$_lang['prop_formit.spamemailfields_desc'] = 'If `spam` is set as a hook, a comma-separated list of fields containing emails to check spam against.';
$_lang['prop_formit.spamcheckip_desc'] = 'If `spam` is set as a hook, and this is true, will check the IP as well.';
$_lang['prop_formit.emailbcc_desc'] = 'If `email` is set as a hook, then this specifies the email(s) to send the email to as a BCC. Can be a comma-separated list of email addresses.';
$_lang['prop_formit.emailbccname_desc'] = 'Optional. If `email` is set as a hook, then this must be a parallel list of comma-separated names for the email addresses specified in the `emailBCC` property.';
$_lang['prop_formit.emailcc_desc'] = 'If `email` is set as a hook, then this specifies the email(s) to send the email to as a CC. Can be a comma-separated list of email addresses.';
$_lang['prop_formit.emailccname_desc'] = 'Optional. If `email` is set as a hook, then this must be a parallel list of comma-separated names for the email addresses specified in the `emailCC` property.';
$_lang['prop_formit.emailto_desc'] = 'If `email` is set as a hook, then this specifies the email(s) to send the email to. Can be a comma-separated list of email addresses.';
$_lang['prop_formit.emailtoname_desc'] = 'Optional. If `email` is set as a hook, then this must be a parallel list of comma-separated names for the email addresses specified in the `emailTo` property.';
$_lang['prop_formit.emailfrom_desc'] = 'Optional. If `email` is set as a hook, and this is set, will specify the From: address for the email. If not set, will first look for an `email` form field. If none is found, will default to the `emailsender` system setting.';
$_lang['prop_formit.emailfromname_desc'] = 'Optional. If `email` is set as a hook, and this is set, will specify the From: name for the email.';
$_lang['prop_formit.emailreplyto_desc'] = 'Optional. If `email` is set as a hook, and this is set, will specify the Reply-To: address for the email.';
$_lang['prop_formit.emailreplytoname_desc'] = 'Optional. If `email` is set as a hook, and this is set, will specify the Reply-To: name for the email.';
$_lang['prop_formit.emailreturnpath_desc'] = 'Optional. If `email` is set as a hook, and this is set, will specify the Return-path: address for the email. If not set, will take the value of `emailFrom` property.';
$_lang['prop_formit.emailsubject_desc'] = 'If `email` is set as a hook, this is required as a subject line for the email.';
$_lang['prop_formit.emailusefieldforsubject_desc'] = 'If the field `subject` is passed into the form, if this is true, it will use the field content for the subject line of the email.';
$_lang['prop_formit.emailhtml_desc'] = 'Optional. If `email` is set as a hook, this toggles HTML emails or not. Defaults to true.';
$_lang['prop_formit.emailconvertnewlines_desc'] = 'If true and emailHtml is set to 1, will convert newlines to BR tags in the email.';
$_lang['prop_formit.emailmultiseparator_desc'] = 'The default separator for collections of items sent through checkboxes/multi-selects. Defaults to a newline.';
$_lang['prop_formit.emailmultiwrapper_desc'] = 'Will wrap each item in a collection of fields sent via checkboxes/multi-selects. Defaults to just the value.';

/* FormIt Auto-Responder properties */
$_lang['prop_fiar.fiartpl_desc'] = 'If `FormItAutoResponder` is set as a hook, then this specifies auto-response template to send as the email.';
$_lang['prop_fiar.fiartofield_desc'] = 'If `FormItAutoResponder` is set as a hook, then this specifies which form field shall be used for the To: address in the auto-response email.';
$_lang['prop_fiar.fiarbcc_desc'] = 'If `FormItAutoResponder` is set as a hook, then this specifies the email(s) to send the email to as a BCC. Can be a comma-separated list of email addresses.';
$_lang['prop_fiar.fiarbccname_desc'] = 'Optional. If `FormItAutoResponder` is set as a hook, then this must be a parallel list of comma-separated names for the email addresses specified in the `emailBCC` property.';
$_lang['prop_fiar.fiarcc_desc'] = 'If `FormItAutoResponder` is set as a hook, then this specifies the email(s) to send the email to as a CC. Can be a comma-separated list of email addresses.';
$_lang['prop_fiar.fiarccname_desc'] = 'Optional. If `FormItAutoResponder` is set as a hook, then this must be a parallel list of comma-separated names for the email addresses specified in the `emailCC` property.';
$_lang['prop_fiar.fiarfrom_desc'] = 'Optional. If `FormItAutoResponder` is set as a hook, and this is set, will specify the From: address for the email. If not set, will first look for an `email` form field. If none is found, will default to the `emailsender` system setting.';
$_lang['prop_fiar.fiarfromname_desc'] = 'Optional. If `FormItAutoResponder` is set as a hook, and this is set, will specify the From: name for the email.';
$_lang['prop_fiar.fiarreplyto_desc'] = 'Optional. If `FormItAutoResponder` is set as a hook, and this is set, will specify the Reply-To: address for the email.';
$_lang['prop_fiar.fiarreplytoname_desc'] = 'Optional. If `FormItAutoResponder` is set as a hook, and this is set, will specify the Reply-To: name for the email.';
$_lang['prop_fiar.fiarsubject_desc'] = 'If `FormItAutoResponder` is set as a hook, this is required as a subject line for the email.';
$_lang['prop_fiar.fiarhtml_desc'] = 'Optional. If `FormItAutoResponder` is set as a hook, this toggles HTML emails or not. Defaults to true.';

/* FormItRetriever properties */
$_lang['prop_fir.placeholderprefix_desc'] = 'The prefix to use with placeholders from the form data.';
$_lang['prop_fir.redirecttoonnotfound_desc'] = 'If the data is not found, if this is set, redirect to the Resource with this ID.';
$_lang['prop_fir.eraseonload_desc'] = 'If true, will erase the stored form data on load. Strongly recommended to leave to false unless you only want the data to load once.';
$_lang['prop_fir.storelocation_desc'] = 'If `store` is set to true, this specifies the cache location of the data from the form submission. Defaults to MODX cache.';

/* FormIt Math hook properties */
$_lang['prop_math.mathminrange_desc'] = 'If `math` is set as a hook, the minimum range for each number in the equation.';
$_lang['prop_math.mathmaxrange_desc'] = 'If `math` is set as a hook, the maximum range for each number in the equation.';
$_lang['prop_math.mathfield_desc'] = 'If `math` is set as a hook, the name of the input field for the answer.';
$_lang['prop_math.mathop1field_desc'] = 'If `math` is set as a hook, the name of the field for the 1st number in the equation.';
$_lang['prop_math.mathop2field_desc'] = 'If `math` is set as a hook, the name of the field for the 2nd number in the equation.';
$_lang['prop_math.mathoperatorfield_desc'] = 'If `math` is set as a hook, the name of the field for the operator in the equation.';

/* FormItCountryOptions properties */
$_lang['prop_fico.allgrouptext_desc'] = 'Optional. If set and &prioritized is in use, will be the text label for the all other countries option group.';
$_lang['prop_fico.optgrouptpl_desc'] = 'Optional. If set and &prioritized is in use, will be the chunk tpl to use for the option group markup.';
$_lang['prop_fico.limited_desc'] = 'Optional. A comma-separated list of ISO codes for selected countries the full list will be limited to.';
$_lang['prop_fico.prioritized_desc'] = 'Optional. A comma-separated list of ISO codes for countries that will move them into a prioritized "Frequent Visitors" group at the top of the dropdown. This can be used for your commonly-selected countries.';
$_lang['prop_fico.prioritizedgrouptext_desc'] = 'Optional. If set and &prioritized is in use, will be the text label for the prioritized option group.';
$_lang['prop_fico.selected_desc'] = 'The country value to select.';
$_lang['prop_fico.selectedattribute_desc'] = 'Optional. The HTML attribute to add to a selected country.';
$_lang['prop_fico.toplaceholder_desc'] = 'Optional. Use this to set the output to a placeholder instead of outputting directly.';
$_lang['prop_fico.tpl_desc'] = 'Optional. The chunk to use for each country dropdown option.';
$_lang['prop_fico.useisocode_desc'] = 'If 1, will use the ISO country code for the value. If 0, will use the country name.';
$_lang['prop_fico.country_desc'] = 'Optional. Set to use a different countries file when loading a list of countries.';

/* FormItStateOptions properties */
$_lang['prop_fiso.country_desc'] = 'Optional. Set to use a different states file when loading a list of states.';
$_lang['prop_fiso.selected_desc'] = 'The country value to select.';
$_lang['prop_fiso.selectedattribute_desc'] = 'Optional. The HTML attribute to add to a selected country.';
$_lang['prop_fiso.toplaceholder_desc'] = 'Optional. Use this to set the output to a placeholder instead of outputting directly.';
$_lang['prop_fiso.tpl_desc'] = 'Optional. The chunk to use for each country dropdown option.';
$_lang['prop_fiso.useabbr_desc'] = 'If 1, will use the state abbreviation for the value. If 0, will use the full state name.';

/* FormIt Options */
$_lang['formit.opt_blackglass'] = 'Black Glass';
$_lang['formit.opt_clean'] = 'Clean';
$_lang['formit.opt_red'] = 'Red';
$_lang['formit.opt_white'] = 'White';
$_lang['formit.opt_cache'] = 'MODX Cache';
$_lang['formit.opt_session'] = 'Session';
