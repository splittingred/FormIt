<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

$_lang['formit']                                                = 'FormIt';
$_lang['formit.desc']                                           = 'View all submitted forms.';

$_lang['area_formit']                                           = 'FormIt';
$_lang['area_formit_recaptcha']                                 = 'FormIt reCaptcha';

$_lang['formit.form']                                           = 'Form';
$_lang['formit.forms']                                          = 'Forms';
$_lang['formit.forms_desc']                                     = 'View all submitted forms.';
$_lang['formit.form_view']                                      = 'View form';
$_lang['formit.form_remove']                                    = 'Remove form';
$_lang['formit.form_remove_confirm']                            = 'Are you sure you want to remove this form?';
$_lang['formit.forms_remove']                                   = 'Remove forms';
$_lang['formit.forms_remove_confirm']                           = 'Are you sure you want to remove all forms?';
$_lang['formit.forms_clean']                                    = 'Clean forms';
$_lang['formit.forms_clean_confirm']                            = 'Are you sure you want to clean all old forms?';
$_lang['formit.forms_export']                                   = 'Export forms';
$_lang['formit.form_encrypt']                                   = 'Encrypt form(s)';
$_lang['formit.form_encrypt_confirm']                           = 'Are you sure you want to encrypt the form(s)?';
$_lang['formit.form_decrypt']                                   = 'Undo form encryption(s)';
$_lang['formit.form_decrypt_confirm']                           = 'Are you sure you want to undo the form encryption(s)?';
$_lang['formit.view_ip']                                        = 'View all forms from this IP';

$_lang['formit.encryption']                                     = 'Encrypted form';
$_lang['formit.encryptions']                                    = 'Encrypted forms';
$_lang['formit.encryptions_desc']                               = 'View all encrypted and non encrypted forms.';

$_lang['formit.label_form_name']                                = 'Name';
$_lang['formit.label_form_name_desc']                           = 'The name of the form.';
$_lang['formit.label_form_values']                              = 'Form values';
$_lang['formit.label_form_values_desc']                         = 'The values of the form';
$_lang['formit.label_form_ip']                                  = 'IP number';
$_lang['formit.label_form_ip_desc']                             = 'The IP number of the visitor that has submitted the form.';
$_lang['formit.label_form_date']                                = 'Date';
$_lang['formit.label_form_date_desc']                           = 'The date when the form is submitted.';
$_lang['formit.label_form_encrypted']                           = 'Encrypted';
$_lang['formit.label_form_encrypted_desc']                      = '';
$_lang['formit.label_form_decrypted']                           = 'Not encrypted';
$_lang['formit.label_form_decrypted_desc']                      = '';
$_lang['formit.label_form_total']                               = 'Total';
$_lang['formit.label_form_total_desc']                          = '';

$_lang['formit.label_clean_label']                              = 'Remove forms older than';
$_lang['formit.label_clean_desc']                               = 'days';

$_lang['formit.label_export_form']                              = 'Form';
$_lang['formit.label_export_form_desc']                         = 'Select a form to export.';
$_lang['formit.label_export_start_date']                        = 'Date from';
$_lang['formit.label_export_start_date_desc']                   = 'Select a date to export forms from that date.';
$_lang['formit.label_export_end_date']                          = 'Date till';
$_lang['formit.label_export_end_date_desc']                     = 'Select a date to export forms till that date.';
$_lang['formit.label_export_delimiter']                         = 'CSV delimiter';
$_lang['formit.label_export_delimiter_desc']                    = 'The Het CSV delimiter to separate the columns. Default is ";".';

$_lang['formit.filter_form']                                    = 'Filter on form';
$_lang['formit.filter_start_date']                              = 'Filter from';
$_lang['formit.filter_end_date']                                = 'Filter till';
$_lang['formit.encryption_unavailable']                         = 'PHP OpenSSL functions openssl_encrypt and openssl_decrypt are not available. Please install PHP OpenSSL on your server. See http://www.php.net/manual/en/openssl.requirements.php for more information.';
$_lang['formit.encryption_unavailable_warning']                 = '<strong>Warning</strong>: PHP OpenSSL functions openssl_encrypt and openssl_decrypt are not available. This means that you cannot use encryption on your forms. Please install PHP OpenSSL on your server. Visit <a href="http://www.php.net/manual/en/openssl.requirements.php" target="_blank">this page</a> for more information.';
$_lang['formit.forms_clean_desc']                               = 'The European <a href="https://ec.europa.eu/commission/priorities/justice-and-fundamental-rights/data-protection/2018-reform-eu-data-protection-rules_en" target="_blank">General Data Protection Regulation (GDPR)</a> requires that personal data, which is no longer necessary to possess, is removed. This tool makes it possible to remove saved forms with an age older than the given days. This action can not be undone!';
$_lang['formit.forms_clean_executing']                          = 'Cleaning up forms';
$_lang['formit.forms_clean_success']                            = '[[+amount]] form(s) removed.';
$_lang['formit.export_failed']                                  = 'The export of the forms failed, please try again.';
$_lang['formit.export_dir_failed']                              = 'An error occurred while exporting the form, the export folder could not be created.';

$_lang['formit.contains']                                       = 'Hodnota musí obsahovat frázi "[[+value]]".';
$_lang['formit.email_invalid']                                  = 'Zadejte platnou e-mailovou adresu.';
$_lang['formit.email_invalid_domain']                           = 'Doménové jméno zadané e-mailové adresy není platné.';
$_lang['formit.email_no_recipient']                             = 'Zadejte příjemce e-mailu.';
$_lang['formit.email_not_sent']                                 = 'Nastala chyba při odesílání e-mailu.';
$_lang['formit.email_tpl_nf']                                   = 'Zadejte šablonu e-mailu.';
$_lang['formit.field_not_empty']                                = 'Toto políčko musí zůstat prázdné.';
$_lang['formit.field_required']                                 = 'Toto políčko je vyžadováno.';
$_lang['formit.math_incorrect']                                 = 'Nesprávná odpověď!';
$_lang['formit.math_field_nf']                                  = 'Políčko [[+field]] nebylo ve formuláři nalezeno.';
$_lang['formit.max_length']                                     = 'Obsah tohoto políčka může být dlouhý maximálně [[+length]] znaků.';
$_lang['formit.max_value']                                      = 'Hodnota může být maximálně [[+value]].';
$_lang['formit.min_length']                                     = 'Obsah tohoto políčka musí být dlouhý alespoň [[+length]] znaků.';
$_lang['formit.min_value']                                      = 'Hodnota musí být minimálně [[+value]].';
$_lang['formit.not_date']                                       = 'Toto políčko musí obsahovat platné datum.';
$_lang['formit.not_lowercase']                                  = 'Obsah tohoto políčka musí být malými písmeny.';
$_lang['formit.not_number']                                     = 'Obsah tohoto políčka musí být číslo.';
$_lang['formit.not_uppercase']                                  = 'Obsah tohoto políčka musí být velkými písmeny.';
$_lang['formit.password_dont_match']                            = 'Vaše hesla se neshodují.';
$_lang['formit.password_not_confirmed']                         = 'Prosím, potvrďte heslo.';
$_lang['formit.prioritized_group_text']                         = 'Častí návštěvníci';
$_lang['formit.range_invalid']                                  = 'Zadán neplatný rozsah.';
$_lang['formit.range']                                          = 'Hodnota musí být mezi [[+min]] a [[+max]].';
$_lang['formit.recaptcha_err_load']                             = 'Nelze načíst třídu služby FormItReCaptcha.';
$_lang['formit.spam_blocked']                                   = 'Odeslání bylo zablokováno spamovým filtrem: ';
$_lang['formit.spam_marked']                                    = ' - označen jako spam.';
$_lang['formit.username_taken']                                 = 'Uživatelské jméno je již použito. Zadejte jiné.';
$_lang['formit.not_regexp']                                     = 'Your value did not match the expected format.';
$_lang['formit.all_group_text']                                 = 'Všechny země';
$_lang['formit.storeAttachment_mediasource_error']              = 'Cant find MediaSource! Mediasource id is: ';
$_lang['formit.storeAttachment_access_error']                   = 'Directory is not writable! Check the permissions for: ';

$_lang['formit.migrate']                                        = 'Migrate encrypted form submissions';
$_lang['formit.migrate_desc']                                   = 'Upgrading to FormIt 3.0 will also update the encryption method used for encrypting submitted form data. FormIt 2.x used mcrypt for encrypting and decrypting, but 3.0 uses the openssl methods. For this to work correctly the currently encrypted forms need to be migrated from mcrypt to openssl.';
$_lang['formit.migrate_alert']                                  = 'FormIt was updated, but your encrypted form submissions need to be migrated. Click here to start the migration.';
$_lang['formit.migrate_status']                                 = 'Status';
$_lang['formit.migrate_running']                                = 'Currently running migration process in the background. Please keep this page open in your browser. DO NOT CLOSE THIS PAGE!';
$_lang['formit.migrate_success']                                = 'Migration completed';
$_lang['formit.migrate_success_msg']                            = 'All your encrypted forms have been successfully migrated.';
