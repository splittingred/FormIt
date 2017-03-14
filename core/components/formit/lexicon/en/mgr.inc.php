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