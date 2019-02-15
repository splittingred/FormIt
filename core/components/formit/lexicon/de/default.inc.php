<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

$_lang['formit']                                                = 'FormIt';
$_lang['formit.desc']                                           = 'Per Formular übermittelte Daten anzeigen und Formular-Verschlüsselung verwalten.';

$_lang['area_formit']                                           = 'FormIt';
$_lang['area_formit_recaptcha']                                 = 'FormIt reCaptcha';

$_lang['formit.form']                                           = 'Formular';
$_lang['formit.forms']                                          = 'Formulare';
$_lang['formit.forms_desc']                                     = 'Alle bisher in der Datenbank gespeicherten Formulare.';
$_lang['formit.form_view']                                      = 'Formular betrachten';
$_lang['formit.form_remove']                                    = 'Formular löschen';
$_lang['formit.form_remove_confirm']                            = 'Sind Sie sicher, dass Sie dieses versendete Formular löschen möchten?';
$_lang['formit.forms_remove']                                   = 'Formulare entfernen';
$_lang['formit.forms_remove_confirm']                           = 'Möchten Sie wirklich alle Formulare entfernen?';
$_lang['formit.forms_clean']                                    = 'Formulare reinigen';
$_lang['formit.forms_clean_confirm']                            = 'Möchten Sie wirklich alle alten Formulare bereinigen?';
$_lang['formit.forms_export']                                   = 'Formulare Exportieren';
$_lang['formit.form_encrypt']                                   = 'Formular(e) verschlüsseln';
$_lang['formit.form_encrypt_confirm']                           = 'Möchten Sie die Formular(e) wirklich verschlüsseln?';
$_lang['formit.form_decrypt']                                   = 'Formularverschlüsselung rückgängig machen';
$_lang['formit.form_decrypt_confirm']                           = 'Möchten Sie die Formularverschlüsselung wirklich rückgängig machen?';
$_lang['formit.view_ip']                                        = 'View all forms from this IP';

$_lang['formit.encryption']                                     = 'Verschlüsseltes Formular';
$_lang['formit.encryptions']                                    = 'Verschlüsselte Formulare';
$_lang['formit.encryptions_desc']                               = 'Alle verschlüsselten und nicht verschlüsselten Formulare anzeigen.';

$_lang['formit.label_form_name']                                = 'Name';
$_lang['formit.label_form_name_desc']                           = 'Der Name des Formulars';
$_lang['formit.label_form_values']                              = 'Werte bilden';
$_lang['formit.label_form_values_desc']                         = 'Die Werte des Formulars.';
$_lang['formit.label_form_ip']                                  = 'IP Adresse';
$_lang['formit.label_form_ip_desc']                             = 'Die IP Adresse des Besuchers, der das Formular übermittelt hat.';
$_lang['formit.label_form_date']                                = 'Datum';
$_lang['formit.label_form_date_desc']                           = 'Das Datum, an dem das Formular gesendet wird.';
$_lang['formit.label_form_encrypted']                           = 'Verschlüsselt';
$_lang['formit.label_form_encrypted_desc']                      = '';
$_lang['formit.label_form_decrypted']                           = 'Nicht verschlüsselt';
$_lang['formit.label_form_decrypted_desc']                      = '';
$_lang['formit.label_form_total']                               = 'Insgesamt';
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

$_lang['formit.filter_form']                                    = 'Auswahl Formular';
$_lang['formit.filter_start_date']                              = 'Auswahl Startdatum';
$_lang['formit.filter_end_date']                                = 'Auswahl Enddatum';
$_lang['formit.encryption_unavailable']                         = 'Die PHP OpenSSL-Funktionen openssl_encrypt und openssl_decrypt sind nicht verfügbar. Bitte installieren Sie PHP OpenSSL auf Ihrem Server. Weitere Informationen erhalten Sie unter http://www.php.net/manual/en/openssl.requirements.php.';
$_lang['formit.encryption_unavailable_warning']                 = '<strong>Warnung</strong>: Die PHP OpenSSL-Funktionen openssl_encrypt und openssl_decrypt sind nicht verfügbar. Dies bedeutet, dass Sie Formulardaten nicht verschlüsseln können. Bitte installieren Sie PHP OpenSSL auf Ihrem Server. Besuchen Sie <a href="http://www.php.net/manual/en/openssl.requirements.php" target="_blank">this page</a>, um weitere Informationen zu erhalten.';
$_lang['formit.forms_clean_desc']                               = 'The European <a href="https://ec.europa.eu/commission/priorities/justice-and-fundamental-rights/data-protection/2018-reform-eu-data-protection-rules_en" target="_blank">General Data Protection Regulation (GDPR)</a> requires that personal data, which is no longer necessary to possess, is removed. This tool makes it possible to remove saved forms with an age older than the given days. This action can not be undone!';
$_lang['formit.forms_clean_executing']                          = 'Formulare aufräumen';
$_lang['formit.forms_clean_success']                            = '[[+amount]] Formular(e) wurden entfernt.';
$_lang['formit.export_failed']                                  = 'The export of the forms failed, please try again.';
$_lang['formit.export_dir_failed']                              = 'An error occurred while exporting the form, the export folder could not be created.';

$_lang['formit.contains']                                       = 'Ihre Eingabe muss die "[[+value]]" enthalten.';
$_lang['formit.email_invalid']                                  = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
$_lang['formit.email_invalid_domain']                           = 'Ihre E-Mail-Adresse enthält keinen gültigen Domainnamen.';
$_lang['formit.email_no_recipient']                             = 'Bitte geben Sie einen oder mehrere Empfänger für die E-Mail an.';
$_lang['formit.email_not_sent']                                 = 'Beim Versuch, die E-Mail zu versenden, ist ein Fehler aufgetreten.';
$_lang['formit.email_tpl_nf']                                   = 'Bitte geben Sie eine E-Mail-Vorlage an.';
$_lang['formit.field_not_empty']                                = 'Dieses Feld muss leer bleiben.';
$_lang['formit.field_required']                                 = 'Dieses Feld muss ausgefüllt werden.';
$_lang['formit.math_incorrect']                                 = 'Falsche Antwort!';
$_lang['formit.math_field_nf']                                  = '[[+field]] Eingabefeld nicht im Formular definiert.';
$_lang['formit.max_length']                                     = 'Dieses Feld darf nicht mehr als [[+length]] Zeichen enthalten.';
$_lang['formit.max_value']                                      = 'Dieser Wert darf nicht größer als [[+value]] sein.';
$_lang['formit.min_length']                                     = 'Dieses Feld muss mindestens [[+length]] Zeichen lang sein.';
$_lang['formit.min_value']                                      = 'Dieser Wert darf nicht kleiner als [[+value]] sein.';
$_lang['formit.not_date']                                       = 'Hier bitte ein gültiges Datum eingeben.';
$_lang['formit.not_lowercase']                                  = 'Dieses Feld bitte nur in Kleinbuchstaben ausfüllen.';
$_lang['formit.not_number']                                     = 'Dieses Feld darf nur eine Zahl enthalten.';
$_lang['formit.not_uppercase']                                  = 'Dieses Feld bitte nur in Großbuchstaben ausfüllen.';
$_lang['formit.password_dont_match']                            = 'Passworte stimmen nicht überein.';
$_lang['formit.password_not_confirmed']                         = 'Bitte bestätigen Sie das Passwort.';
$_lang['formit.prioritized_group_text']                         = 'Häufige Besucher';
$_lang['formit.range_invalid']                                  = 'Unzulässiger Wertebereich.';
$_lang['formit.range']                                          = 'Dieser Wert muss zwischen [[+min]] und [[+max]] liegen.';
$_lang['formit.recaptcha_err_load']                             = 'Konnte reCaptcha-Service-Klasse nicht laden.';
$_lang['formit.spam_blocked']                                   = 'Ihre Eingabe wurde durch einen Spamfilter blockiert: ';
$_lang['formit.spam_marked']                                    = ' - als Spam markiert.';
$_lang['formit.username_taken']                                 = 'Benutzername schon vergeben, bitte wählen Sie einen anderen.';
$_lang['formit.not_regexp']                                     = 'Der von Ihnen eingegebene Wert hat nicht das erwartete Format.';
$_lang['formit.all_group_text']                                 = 'Alle Länder';
$_lang['formit.storeAttachment_mediasource_error']              = 'Cant find MediaSource! Mediasource id is: ';
$_lang['formit.storeAttachment_access_error']                   = 'Directory is not writable! Check the permissions for: ';

$_lang['formit.migrate']                                        = 'Verschlüsselte Daten aus versendeten Formularen migrieren';
$_lang['formit.migrate_desc']                                   = 'Beim Upgrade auf FormIt 3.0 wird auch die Verschlüsselungsmethode geändert, die für die Verschlüsselung der Daten aus versendeten Formularen verwendet wird. FormIt 2.x verwendete mcrypt für die Ver- und Entschlüsselung, Version 3.0 dagegen verwendet die OpenSSL-Methoden. Damit dies korrekt funktioniert, müssen die bereits verschlüsselten Formulardaten von mcrypt zu OpenSSL migriert werden.';
$_lang['formit.migrate_alert']                                  = 'FormIt wurde upgedatet, aber Ihre verschlüsselten Formulardaten müssen migriert werden. Klicken Sie hier, um die Migration zu starten.';
$_lang['formit.migrate_status']                                 = 'Status';
$_lang['formit.migrate_running']                                = 'Der Migrations-Prozess läuft gerade im Hintergrund. Bitte lassen Sie diese Seite in Ihrem Browser geöffnet. SCHLIESSEN SIE AUF KEINEN FALL DIESE SEITE!';
$_lang['formit.migrate_success']                                = 'Migration abgeschlossen';
$_lang['formit.migrate_success_msg']                            = 'Alle Ihre verschlüsselten Formulardaten wurden erfolgreich migriert.';
