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
 * Properties Lexicon Topic : translation by Anselm Hannemann
 *
 * @package formit
 * @subpackage lexicon
 * @language de
 */
/* FormIt properties */
$_lang['prop_formit.hooks_desc'] = 'Gibt an, welche Scripts ausgeführt werden sollen, nachdem das Formular validiert wurde. Dies kann eine kommaseparierte Liste von Hooks sein. Schlägt ein Hook fehl, werden nachfolgende nicht ausgeführt. Ein Hook kann ebenfalls ein Name eines Snippets sein, das daraufhin ausgeführt wird.';
$_lang['prop_formit.prehooks_desc'] = 'Gibt an, welche Scripts ausgeführt werden sollen (falls vorhanden), sobald das Formular geladen wird. Sie können Formularfelder vorausfüllen: $scriptProperties[`hook`]->fields[`fieldname`]. Dies kann eine kommaseparierte Liste von Hooks sein. Schlägt ein Hook fehl, werden nachfolgende nicht ausgeführt. Ein Hook kann ebenfalls ein Name eines Snippets sein, das daraufhin ausgeführt wird.';
$_lang['prop_formit.submitvar_desc'] = 'Falls gesetzt, wird das Formular nicht ausgewertet, wenn die POST-Variable nicht übergeben wurde.';
$_lang['prop_formit.validate_desc'] = 'Dies kann eine kommaseparierte Liste von Feldern, die validiert werden sollen, mit jeweils dem Feldnamen als Validator (Bsp.: username:required,email:required) sein. Validatoren können auch verkettet werden, z.B. email:email:required. Diese Eigenschaft kann in mehreren Zeilen angegeben werden.';
$_lang['prop_formit.errtpl_desc'] = 'Das Wrapper-Template für Fehlermeldungen.';
$_lang['prop_formit.validationerrormessage_desc'] = 'Eine generelle Fehlermeldung, die angezeigt wird, wenn eine Validierung nicht bestanden wurde. Kann die Variable [[+errors]] enthalten, wenn die genaue Liste von Fehlern mit angezeigt werden soll.';
$_lang['prop_formit.validationerrorbulktpl_desc'] = 'HTML-Template, das für die individuelle Fehlermeldungsanzeige benutzt wird, wenn eine Validierung nicht bestanden wurde.';
$_lang['prop_formit.customvalidators_desc'] = 'Eine kommaseparierte Liste von eigenen Validatoren (snippets), die auf das Formular angewendet werden sollen. Sie müssen hier explizit angegeben werden, um abzulaufen.';
$_lang['prop_formit.trimvaluesdeforevalidation_desc'] = 'Gibt an, ob Leerzeichen vom Anfang und vom Ende von Werten entfernt werden, bevor versucht wird, sie zu validieren. Standardeinstellung ist "Ja".';
$_lang['prop_formit.clearfieldsonsuccess_desc'] = 'Falls gesetzt, werden die Formularinhalte nach dem Absenden gelöscht, wenn kein Redirect gesetzt wurde.';
$_lang['prop_formit.successmessage_desc'] = 'Falls gesetzt, wird ein Platzhalter mit dem Wert ausgegeben, der von &successMessagePlaceholder mitgegeben wird. Standardwert ist: `fi.successMessage`.';
$_lang['prop_formit.successmessageplaceholder_desc'] = 'Der Platzhalter mit der Erfolgsnachricht.';
$_lang['prop_formit.store_desc'] = 'Falls gesetzt, werden die eingegebenen Daten im Cache gespeichert, um vom FormItRetriever-Snippet weiterverwendet zu werden.';
$_lang['prop_formit.storetime_desc'] = 'Falls `store` auf `Ja` gesetzt wurde, wird hier die Zeit in Sekunden angegeben, innerhalb derer die Formulardaten gespeichert werden müssen. Standardwert ist 5 Minuten.';
$_lang['prop_formit.storelocation_desc'] = 'Falls `store` auf `Ja` gesetzt wurde, wird hier angegeben, wo die vom Formular übermittelten Daten gecacht werden. Standard ist der MODX-Cache.';
$_lang['prop_formit.allowfiles_desc'] = 'Wenn diese Einstellung auf "Nein" gesetzt wird, wird die Übermittlung von Dateien über das Formular verhindert.';
$_lang['prop_formit.placeholderprefix_desc'] = 'Der zu nutzende Prefix für alle Platzhalter, die von FormIt für Felder gesetzt wurden. Standard ist `fi.`';
$_lang['prop_formit.redirectto_desc'] = 'Falls `redirect` als Hook gesetzt wurde, geben Sie hier die Ressourcen-ID an, zu der weitergeleitet werden soll.';
$_lang['prop_formit.redirectparams_desc'] = 'Ein JSON-Array mit Parametern, die an den Redirect-Hook übergeben werden sollen.';
$_lang['prop_formit.recaptchajs_desc'] = 'Falls `recaptcha` als Hook gesetzt wurde, kann dies ein JSON Objekt sein, das als JavaScript-RecaptchaOptions-Variable gesetzt wird, die die Optionen für reCaptcha definiert.';
$_lang['prop_formit.recaptchaheight_desc'] = 'Falls `recaptcha` als Hook gesetzt wurde, wird hier die Höhe des reCaptcha-Widgets festgelegt.';
$_lang['prop_formit.recaptchatheme_desc'] = 'Falls `recaptcha` als Hook gesetzt wurde, wird hier das Theme des reCaptcha-Widgets festgelegt.';
$_lang['prop_formit.recaptchawidth_desc'] = 'Falls `recaptcha` als Hook gesetzt wurde, wird hier die Breite des reCaptcha-Widgets festgelegt.';
$_lang['prop_formit.spamemailfields_desc'] = 'Falls `spam` als Hook gesetzt wurde, geben Sie hier eine kommaseparierte Liste von Feldern an, die E-Mail-Adressen enthalten und auf Spam geprüft werden sollen.';
$_lang['prop_formit.spamcheckip_desc'] = 'Falls `spam` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird die IP ebenfalls geprüft.';
$_lang['prop_formit.emailbcc_desc'] = 'Falls `email` als Hook gesetzt wurde, werden hier E-Mail-Adressen angegeben, an die die E-Mail per BCC gesendet wird. Kann eine kommaseparierte Liste von E-Mail-Adressen sein.';
$_lang['prop_formit.emailbccname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, muss dies eine parallele Liste von kommaseparierten Namen für die entsprechenden E-Mail-Adressen sein, die im `emailBCC` festgelegt wurden.';
$_lang['prop_formit.emailcc_desc'] = 'Falls `email` als Hook gesetzt wurde, werden hier E-Mail-Adressen angegeben, an die die E-Mail per CC gesendet wird. Kann eine kommaseparierte Liste von E-Mail-Adressen sein.';
$_lang['prop_formit.emailccname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, muss dies eine parallele Liste von kommaseparierten Namen für die entsprechenden E-Mail-Adressen sein, die im `emailCC` festgelegt wurden.';
$_lang['prop_formit.emailto_desc'] = 'Falls `email` als Hook gesetzt wurde, werden hier E-Mail-Adressen angegeben, an die die E-Mail gesendet wird. Kann eine kommaseparierte Liste von E-Mail-Adressen sein.';
$_lang['prop_formit.emailtoname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, muss dies eine parallele Liste von kommaseparierten Namen für die entsprechenden E-Mail-Adressen sein, die im `emailTo` festgelegt wurden.';
$_lang['prop_formit.emailfrom_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird hier die Absenderadresse gesetzt. Falls nicht gesetzt, wird nach einem email-Feld gesucht. Wird keines gefunden, wird die E-Mail-Adresse aus den Systemeinstellungen verwendet.';
$_lang['prop_formit.emailfromname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird hier der Name des Absenders zur passenden E-Mail-Adresse angegeben.';
$_lang['prop_formit.emailreplyto_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird hier die Antwort-E-Mail-Adresse angegeben.';
$_lang['prop_formit.emailreplytoname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird hier der Name des Antwortadresskontakts zur passenden E-Mail-Adresse angegeben.';
$_lang['prop_formit.emailreturnpath_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird hier die Return-path-Adresse der E-Mail angegeben. Wird hier nichts angegeben, wird der Wert der Eigenschaft `emailFrom` verwendet.';
$_lang['prop_formit.emailsubject_desc'] = 'Falls `email` als Hook gesetzt wurde, ist dies der Betreff für die E-Mail.';
$_lang['prop_formit.emailusefieldforsubject_desc'] = 'Falls ein Formularfeld `subject` im Formular mit übergeben wird und dieses einen Wert enthält, wird dieser Wert als Betreff verwendet.';
$_lang['prop_formit.emailhtml_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, kann hier zwischen Plaintext und HTML-Mail gewählt werden. Standard ist HTML.';
$_lang['prop_formit.emailconvertnewlines_desc'] = 'Falls sowohl diese Einstellung als auch emailHtml auf "Ja" gesetzt wurden, werden Zeilenumbrüche in BR-Tags konvertiert.';
$_lang['prop_formit.emailmultiseparator_desc'] = 'Der Standardseparator für Sammlungen von Einträgen, die über Checkboxen oder Multi-Auswahlfelder übergeben werden. Standard ist \newline';
$_lang['prop_formit.emailmultiwrapper_desc'] = 'Umfasst jeden Eintrag einer Sammlung von Feldern, die über Checkboxen oder Multi-Auswahlfelder übergeben werden. Standard ist `value`';

/* FormIt Auto-Responder properties */
$_lang['prop_fiar.fiartpl_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, wird hier das Template für die automatische Antwort-E-Mail angebeben.';
$_lang['prop_fiar.fiartofield_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, wird hier angegeben, welches Formularfeld für die Empfänger-Adresse in der automatischen Antwort-E-Mail verwendet werden soll.';
$_lang['prop_fiar.fiarbcc_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, werden hier die E-Mail-Adressen angebeben, an die die E-Mail als BCC gesendet werden soll. Kann eine kommaseparierte Liste sein.';
$_lang['prop_fiar.fiarbccname_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, muss dies eine parallele Liste von kommaseparierten Namen für die entsprechenden E-Mail-Adressen sein, die im `emailBCC` festgelegt wurden.';
$_lang['prop_fiar.fiarcc_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, werden hier E-Mail-Adressen angegeben, an die die E-Mail per CC gesendet wird. Kann eine kommaseparierte Liste von E-Mail-Adressen sein.';
$_lang['prop_fiar.fiarccname_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, muss dies eine parallele Liste von kommaseparierten Namen für die entsprechenden E-Mail-Adressen sein, die in `emailCC` festgelegt wurden.';
$_lang['prop_fiar.fiarfrom_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird hier die Absenderadresse festgelegt. Falls nicht gesetzt, wird nach einem email-Feld gesucht. Wird keines gefunden, wird die E-Mail-Adresse aus den Systemeinstellungen verwendet.';
$_lang['prop_fiar.fiarfromname_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird hier der Name des Absenders zur passenden E-Mail-Adresse angegeben.';
$_lang['prop_fiar.fiarreplyto_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird hier die Antwort-E-Mail-Adresse angegeben.';
$_lang['prop_fiar.fiarreplytoname_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde und diese Einstellung auf "Ja" gestellt wird, wird hier der Name des Antwortadresskontakts zur passenden E-Mail-Adresse angegeben.';
$_lang['prop_fiar.fiarsubject_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, ist dies der Betreff für die E-Mail.';
$_lang['prop_fiar.fiarhtml_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, kann hier zwischen Plaintext und HTML-Mail gewählt werden. Standard ist HTML.';

/* FormItRetriever properties */
$_lang['prop_fir.placeholderprefix_desc'] = 'Das Präfix, das für Platzhalter aus den Formular-Daten genutzt werden soll.';
$_lang['prop_fir.redirecttoonnotfound_desc'] = 'Falls keine Daten gefunden wurden, leite zur angegebenen Ressourcen-ID weiter.';
$_lang['prop_fir.eraseonload_desc'] = 'Falls zutreffend, werden die gespeicherten Formulardaten beim Laden gelöscht. Es wird dringend empfohlen, diese Einstellung auf "Nein" zu lassen, wenn Sie die Daten nicht nur ein einziges Mal wiederverwenden möchten.';
$_lang['prop_fir.storelocation_desc'] = 'Falls `store` auf `Ja` gesetzt wurde, wird hier angegeben, wo die vom Formular übermittelten Daten gecacht werden. Standard ist der MODX-Cache.';

/* FormIt Math hook properties */
$_lang['prop_math.mathminrange_desc'] = 'Falls `math` als Hook gesetzt wurde, der minimale Bereich für jede Zahl in der Gleichung.';
$_lang['prop_math.mathmaxrange_desc'] = 'Falls `math` als Hook gesetzt wurde, der maximale Bereich für jede Zahl in der Gleichung.';
$_lang['prop_math.mathfield_desc'] = 'Falls `math` als Hook gesetzt wurde, der Name des Formularfeldes für die Antwort.';
$_lang['prop_math.mathop1field_desc'] = 'Falls `math` als Hook gesetzt wurde, der Name des Formularfeldes für die 1. Zahl in der Gleichung.';
$_lang['prop_math.mathop2field_desc'] = 'Falls `math` als Hook gesetzt wurde, der Name des Formularfeldes für die 2. Zahl in der Gleichung.';
$_lang['prop_math.mathoperatorfield_desc'] = 'Falls `math` als Hook gesetzt wurde, der Name des Formularfeldes für den Operator in der Gleichung.';

/* FormItCountryOptions properties */
$_lang['prop_fico.allgrouptext_desc'] = 'Optional. Falls hier etwas eingegeben wird und &prioritized genutzt wird, wird hier das Text-Label für die Optionsgruppe für alle anderen Länder festgelegt.';
$_lang['prop_fico.optgrouptpl_desc'] = 'Optional. Falls hier etwas eingegeben wird und &prioritized genutzt wird, ist dies das Chunk-Template, das für das Optionsgruppen-Markup verwendet wird.';
$_lang['prop_fico.limited_desc'] = 'Optional. Eine kommaseparierte Liste von ISO-Codes für ausgewählte Länder auf welche die vollständige Liste beschränkt wird.';
$_lang['prop_fico.prioritized_desc'] = 'Optional. Eine kommaseparierte Liste von ISO-Codes für Länder, die in die priorisierte Listengruppe der "häufigen Besucher" gehören sollen. Dies kann für Ihre üblicherweise ausgewählten Länder verwendet werden.';
$_lang['prop_fico.prioritizedgrouptext_desc'] = 'Optional. Falls hier etwas eingegeben wird und &prioritized genutzt wird, wird hier das Text-Label für die Optionsgruppe der priorisierten Länder festgelegt.';
$_lang['prop_fico.selected_desc'] = 'Der auszuwählende Länderwert.';
$_lang['prop_fico.selectedattribute_desc'] = 'Optional. Das HTML-Attribut, das einem ausgewählten Land hinzugefügt wird.';
$_lang['prop_fico.toplaceholder_desc'] = 'Optional. Verwenden Sie dies, um den Wert in einen Platzhalter auszugeben, statt ihn direkt auszugeben.';
$_lang['prop_fico.tpl_desc'] = 'Optional. Der zu nutzende Chunk für jede Option der Länderauswahl.';
$_lang['prop_fico.useisocode_desc'] = 'Falls diese Einstellung auf "Ja" steht, wird der ISO-Ländercode als Wert genutzt. Falls diese Einstellung auf "Nein" steht, wird der Ländername verwendet.';
$_lang['prop_fico.country_desc'] = 'Optional. Verwenden Sie dieses Feld, um eine andere Länder-Datei zu verwenden, wenn eine Liste von Ländern geladen wird.';

/* FormItStateOptions properties */
$_lang['prop_fiso.country_desc'] = 'Optional. Verwenden Sie dieses Feld, um eine andere Staaten-Datei zu verwenden, wenn eine Liste von Staaten geladen wird.';
$_lang['prop_fiso.selected_desc'] = 'Der auszuwählende Länderwert.';
$_lang['prop_fiso.selectedattribute_desc'] = 'Optional. Das HTML-Attribut, das einem ausgewählten Land hinzugefügt werden soll.';
$_lang['prop_fiso.toplaceholder_desc'] = 'Optional. Verwenden Sie dies, um den Wert in einen Platzhalter auszugeben, statt ihn direkt auszugeben.';
$_lang['prop_fiso.tpl_desc'] = 'Optional. Der zu nutzende Chunk für jede Option der Länderauswahl.';
$_lang['prop_fiso.useabbr_desc'] = 'Falls diese Einstellung auf "Ja" steht, wird die Abkürzung des Bundesstaates verwendet. Falls diese Einstellung auf "Nein" steht, wird der vollständige Name des Bundesstaates verwendet.';

/* FormIt Options */
$_lang['formit.opt_blackglass'] = 'schwarzes Glas';
$_lang['formit.opt_clean'] = 'aufgeräumt';
$_lang['formit.opt_red'] = 'rot';
$_lang['formit.opt_white'] = 'weiß';
$_lang['formit.opt_cache'] = 'MODX-Cache';
$_lang['formit.opt_session'] = 'Session';
$_lang['prop_formit.savetmpfiles_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, speichert FormIt übertragene Dateien in einem temporären Verzeichnis.';
$_lang['prop_formit.attachfiles_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, fügt FormItn der E-Mail alle übertragenen Dateien als Anhänge hinzu.';
