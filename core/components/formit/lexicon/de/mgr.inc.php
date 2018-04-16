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
 * German Translation by Sebastian G. Marinescu
 *
 * @package formit
 * @subpackage lexicon
 */
$_lang['formit'] = 'FormIt';
//$_lang['formit.menu_desc'] = 'Alle bisher in der Datenbank gespeicherten Formulare.';
$_lang['formit.intro_msg'] = 'Alle bisher in der Datenbank gespeicherten Formulare.';

$_lang['formit.form'] = 'Formular';
$_lang['formit.forms'] = 'Formulare';
$_lang['formit.values'] = 'Werte';
$_lang['formit.date'] = 'Datum';
$_lang['formit.hash'] = 'Hash-Schlüssel';
$_lang['formit.ip'] = 'IP Adresse';
$_lang['formit.form_view'] = 'Formular betrachten';
$_lang['formit.form_remove'] = 'Formular löschen';
$_lang['formit.form_remove_confirm'] = 'Sind Sie sicher, dass Sie dieses versendete Formular löschen möchten?';
$_lang['formit.select_context'] = 'Auswahl Kontext';
$_lang['formit.select_form'] = 'Auswahl Formular';
$_lang['formit.select_start_date'] = 'Auswahl Startdatum';
$_lang['formit.select_end_date'] = 'Auswahl Enddatum';
$_lang['formit.clear'] = 'Filter zurücksetzen';
$_lang['formit.export'] = 'Exportieren';
$_lang['formit.encryption'] = 'Verschlüsselung';
$_lang['formit.encryption_msg'] = 'Verwaltung der Verschlüsselung aller versendeten Formulare.';
$_lang['formit.encrypted'] = 'Verschlüsselt';
$_lang['formit.total'] = 'Insgesamt';
$_lang['formit.form_encryptall'] = 'Verschlüsselung aller versendeten Formulare';
$_lang['formit.form_decryptall'] = 'Entschlüsselung aller versendeten Formulare';
$_lang['formit.form_encrypt'] = 'Verschlüsseln';
$_lang['formit.form_encrypt_confirm'] = 'Sind Sie sicher, dass Sie alle versendeten Formulare verschlüsseln möchten?';
$_lang['formit.form_decrypt'] = 'Entschlüsseln';
$_lang['formit.form_decrypt_confirm'] = 'Sind Sie sicher, dass Sie alle versendeten Formulare entschlüsseln möchten?';

/* Encryption migration */
$_lang['formit.migrate'] = 'Verschlüsselte Daten aus versendeten Formularen migrieren';
$_lang['formit.migrate_desc'] = 'Beim Upgrade auf FormIt 3.0 wird auch die Verschlüsselungsmethode geändert, die für die Verschlüsselung der Daten aus versendeten Formularen verwendet wird.
FormIt 2.x verwendete mcrypt für die Ver- und Entschlüsselung, Version 3.0 dagegen verwendet die OpenSSL-Methoden. Damit dies korrekt funktioniert, müssen die bereits verschlüsselten Formulardaten von mcrypt zu OpenSSL migriert werden.';
$_lang['formit.migrate_alert'] = 'FormIt wurde upgedatet, aber Ihre verschlüsselten Formulardaten müssen migriert werden. Klicken Sie hier, um die Migration zu starten.';
$_lang['formit.migrate_status'] = 'Status';
$_lang['formit.migrate_running'] = 'Der Migrations-Prozess läuft gerade im Hintergrund. Bitte lassen Sie diese Seite in Ihrem Browser geöffnet. SCHLIESSEN SIE AUF KEINEN FALL DIESE SEITE!';
$_lang['formit.migrate_success'] = 'Migration abgeschlossen';
$_lang['formit.migrate_success_msg'] = 'Alle Ihre verschlüsselten Formulardaten wurden erfolgreich migriert.';

$_lang['formit.encryption_unavailable'] = 'Die OpenSSL-Funktionen openssl_encrypt und openssl_decrypt sind nicht verfügbar.
Bitte installieren Sie OpenSSL auf Ihrem Server. Weitere Informationen erhalten Sie unter http://www.php.net/manual/en/openssl.requirements.php.';
$_lang['formit.encryption_unavailable_warning'] = 'Warnung: Die OpenSSL-Funktionen openssl_encrypt und openssl_decrypt sind nicht verfügbar. Dies bedeutet, dass Sie Formulardaten nicht verschlüsseln können. Bitte installieren Sie OpenSSL auf Ihrem Server.
Besuchen Sie <a href="http://www.php.net/manual/en/openssl.requirements.php" target="_blank">this page</a>, um weitere Informationen zu erhalten.';
