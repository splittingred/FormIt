<?php
/**
 * FormIt
 *
 * Copyright 2015 by Wieger Sloot <wieger@sterc.nl>
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
 * Default Lexicon Topic
 *
 * @package formit
 * @subpackage lexicon
 */
$_lang['formit'] = 'FormIt';
$_lang['formit.intro_msg'] = 'View all your submitted forms.';

$_lang['formit.form'] = 'Form';
$_lang['formit.forms'] = 'Forms';
$_lang['formit.values'] = 'Values';
$_lang['formit.date'] = 'Date';
$_lang['formit.hash'] = 'Hash Key';
$_lang['formit.ip'] = 'IP Address';
$_lang['formit.form_view'] = 'View form';
$_lang['formit.form_remove'] = 'Remove form';
$_lang['formit.form_remove_confirm'] = 'Are you sure you want to remove this submitted form?';
$_lang['formit.select_context'] = 'Select context';
$_lang['formit.select_form'] = 'Select form';
$_lang['formit.select_start_date'] = 'Select start date';
$_lang['formit.select_end_date'] = 'Select end date';
$_lang['formit.clear'] = 'Clear filter';
$_lang['formit.export'] = 'Export';
$_lang['formit.encryption'] = 'Encryption';
$_lang['formit.encryption_msg'] = 'Manage encryption for all the submitted forms.';
$_lang['formit.encrypted'] = 'Encrypted';
$_lang['formit.total'] = 'Total';
$_lang['formit.form_encryptall'] = 'Encrypt all submitted forms';
$_lang['formit.form_decryptall'] = 'Decrypt all submitted forms';
$_lang['formit.form_encrypt'] = 'Encrypt';
$_lang['formit.form_encrypt_confirm'] = 'Are you sure you want to encrypt all the submitted forms?';
$_lang['formit.form_decrypt'] = 'Decrypt';
$_lang['formit.form_decrypt_confirm'] = 'Are you sure you want to decrypt all the submitted forms?';

/* Encryption migration */
$_lang['formit.migrate'] = 'Migrate encrypted form submissions';
$_lang['formit.migrate_desc'] = 'Upgrading to FormIt 3.0 will also update the encryption method used for encrypting submitted form data. 
FormIt 2.x used mcrypt for encrypting and decrypting, but 3.0 uses the openssl methods. For this to work correctly the currently encrypted forms need to be migrated from mcrypt to openssl.';
$_lang['formit.migrate_alert'] = 'FormIt was updated, but your encrypted form submissions need to be migrated. Click here to start the migration.';
$_lang['formit.migrate_status'] = 'Status';
$_lang['formit.migrate_running'] = 'Currently running migration process in the background. Please keep this page open in your browser. DO NOT CLOSE THIS PAGE!';
$_lang['formit.migrate_success'] = 'Migration completed';
$_lang['formit.migrate_success_msg'] = 'All your encrypted forms have been successfully migrated.';

$_lang['formit.encryption_unavailable'] = 'OpenSSL functions openssl_encrypt and openssl_decrypt are not available. 
Please install OpenSSL on your server. See http://www.php.net/manual/en/openssl.requirements.php for more information.';
$_lang['formit.encryption_unavailable_warning'] = 'Warning: OpenSSL functions openssl_encrypt and openssl_decrypt are not available. This means that you cannot use encryption on your forms. Please install OpenSSL on your server. 
Visit <a href="http://www.php.net/manual/en/openssl.requirements.php" target="_blank">this page</a> for more information.';

/* Clean up forms */
$_lang['formit.clean_forms'] = 'Clean up forms';
$_lang['formit.window.cleanforms.days_to_delete'] = 'Delete forms older than:';
$_lang['formit.window.cleanforms.days'] = 'days.';
$_lang['formit.window.cleanforms.execute'] = 'Clean up forms';
$_lang['formit.window.cleanforms.executing'] = 'Cleaning up forms';
$_lang['formit.window.cleanforms.intro_msg'] = 'The European <a href="https://ec.europa.eu/commission/priorities/justice-and-fundamental-rights/data-protection/2018-reform-eu-data-protection-rules_en" target="_BLANK">General Data Protection Regulation (GDPR)</a> 
requires that personal data, which is no longer necessary to possess, is removed. This tool makes it possible to remove saved forms with an age older than the given days. This action can not be undone!';
$_lang['formit.window.cleanforms.success_description'] = 'Removed [[+amount]] form(s).';