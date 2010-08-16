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
 * Properties Lexicon Topic
 *
 * @package formit
 * @subpackge lexicon
 * @language en
 */
$_lang['prop_formit.hooks_desc'] = 'What scripts to fire, if any, after the form passes validation. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_formit.submitvar_desc'] = 'If set, will not begin form processing if this POST variable is not passed.';
$_lang['prop_formit.errtpl_desc'] = 'The wrapper template for error messages.';
$_lang['prop_formit.redirectto_desc'] = 'If `redirect` is set as a hook, this must specify the Resource ID to redirect to.';
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
$_lang['prop_formit.emailsubject_desc'] = 'If `email` is set as a hook, this is required as a subject line for the email.';
$_lang['prop_formit.emailusefieldforsubject_desc'] = 'If the field `subject` is passed into the form, if this is true, it will use the field content for the subject line of the email.';
$_lang['prop_formit.emailhtml_desc'] = 'Optional. If `email` is set as a hook, this toggles HTML emails or not. Defaults to true.';