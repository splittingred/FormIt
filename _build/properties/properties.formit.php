<?php
/**
 * FormIt
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
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
        'desc' => 'What scripts to fire, if any, after the form passes validation. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'submitVar',
        'desc' => 'If set, will not begin form processing if this POST variable is not passed.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'errTpl',
        'desc' => 'The wrapper template for error messages.',
        'type' => 'textfield',
        'options' => '',
        'value' => '<span class="error">[[+error]]</span>',
    ),
    /* redirect hook */
    array(
        'name' => 'redirectTo',
        'desc' => 'If `redirect` is set as a hook, this must specify the Resource ID to redirect to.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    /* email hook */
    array(
        'name' => 'emailTo',
        'desc' => 'If `email` is set as a hook, then this specifies the email(s) to send the email to. Can be a comma-separated list of email addresses.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'emailToName',
        'desc' => 'Optional. If `email` is set as a hook, then this must be a parallel list of comma-separated names for the email addresses specified in the `emailTo` property.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'emailFrom',
        'desc' => 'Optional. If `email` is set as a hook, and this is set, will specify the From: address for the email. If not set, will first look for an `email` form field. If none is found, will default to the `emailsender` system setting.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'emailFromName',
        'desc' => 'Optional. If `email` is set as a hook, and this is set, will specify the From: name for the email.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'emailSubject',
        'desc' => 'If `email` is set as a hook, this is required as a subject line for the email.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'emailUseFieldForSubject',
        'desc' => 'If the field `subject` is passed into the form, if this is true, it will use the field content for the subject line of the email.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'emailHtml',
        'desc' => 'Optional. If `email` is set as a hook, this toggles HTML emails or not. Defaults to true.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    /* smtp */
    array(
        'name' => 'smtpEnabled',
        'desc' => 'If true, will use SMTP instead of the default mail protocol.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'smtpAuth',
        'desc' => 'If true, will attempt to auth to smtp.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'smtpHost',
        'desc' => 'The host for SMTP.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'localhost',
    ),
    array(
        'name' => 'smtpPassword',
        'desc' => 'The password for auth to SMTP.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'password',
    ),
    array(
        'name' => 'smtpPort',
        'desc' => 'The port to connect to SMTP.',
        'type' => 'textfield',
        'options' => '',
        'value' => '587',
    ),
    array(
        'name' => 'smtpUsername',
        'desc' => 'The username for auth to SMTP.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'username',
    ),
    array(
        'name' => 'smtpPrefix',
        'desc' => 'The SMTP prefix.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
);

return $properties;