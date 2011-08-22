<?php
/**
 * FormIt
 *
 * Copyright 2009-2011 by Shaun McCormick <shaun@modx.com>
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
 * FormIt; Falls not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package formit
 */
/**
 * Properties Lexicon Topic : translation from Anselm Hannemann
 *
 * @package formit
 * @subpackage lexicon
 * @language en
 */
/* FormIt properties */
$_lang['prop_formit.hooks_desc'] = 'Welche Scripts ausgeführt werden sollen nachdem das Formular validiert wurde. Dies kann eine Kommaseparierte Liste von Hooks sein. Schlägt ein Hook fehl, werden folgende nicht ausgeführt. Ein Hook kann ebenfalls ein Name eines Snippets sein, das daraufhin ausgeführt wird.';
$_lang['prop_formit.prehooks_desc'] = 'Welche Scripts ausgeführt werden sollen (falls vorhanden) sobald das Formular geladen wird. Sie können Formularfelder vorausfüllen: $scriptProperties[`hook`]->fields[`fieldname`]. Dies kann eine Kommaseparierte Liste von Hooks sein. Schlägt ein Hook fehl, werden folgende nicht ausgeführt. Ein Hook kann ebenfalls ein Name eines Snippets sein, das daraufhin ausgeführt wird.';
$_lang['prop_formit.submitvar_desc'] = 'Falls gesetzt, wird das Formular nicht ausgewertet, wenn die POST Variable nicht übergeben wurde.';
$_lang['prop_formit.validate_desc'] = 'Dies kann eine Kommaseparierte Liste von Feldern, die validiert werden sollen mit jeweils dem Feldnamen als Validator (Bsp.: username:required,email:required). Validatoren können auch verkettet werden, z.B. email:email:required. Diese Eigenschaft kann in mehreren Zeilen angegeben werden.';
$_lang['prop_formit.errtpl_desc'] = 'Das Wrapper-Template für Fehlermeldungen.';
$_lang['prop_formit.validationerrormessage_desc'] = 'Eine generelle Fehlermeldung, die angezeigt wird, wenn eine Validierung nicht bestanden wurde. Kann die Variable [[+errors]] enthalten, wenn die genaue Liste an Fehlern mit angezeigt werden soll.';
$_lang['prop_formit.validationerrorbulktpl_desc'] = 'HTML Template, das für die individuelle Fehlermeldungsanzeige benutzt wird, wenn eine Validierung nicht bestanden wurde.';
$_lang['prop_formit.customvalidators_desc'] = 'Eine Kommaseparierte Liste an eigenen Validatoren (snippets), die auf das Formular angewandt werden sollen. Sie müssen hier explizit angegeben werden, um abzulaufen.';
$_lang['prop_formit.clearfieldsonsuccess_desc'] = 'Falls gesetzt, werden die Formularinhalte nach dem Absenden gelöscht, wenn kein Redirect gesetzt wurde.';
$_lang['prop_formit.successmessage_desc'] = 'Falls gesetz, wird ein Platzhalter mit dem Wert ausgegeben, der von &successMessagePlaceholder mitgegeben wird. Standardwert ist: `fi.successMessage`.';
$_lang['prop_formit.successmessageplaceholder_desc'] = 'Der Platzhalter mit der Erfolgsnachricht.';
$_lang['prop_formit.store_desc'] = 'Falls gesetzt, werden die eingegebenen Daten im Cache gespeichert, um vom FormItRetriever Snippet weiterverwendet zu werden.';
$_lang['prop_formit.storetime_desc'] = 'Falls `store` auf `zutreffende` gesetzt wurde, wird hier die zeit in Sekunden angegeben, um die Daten zu speichern. Standardwert sit 5 Minuten.';
$_lang['prop_formit.placeholderprefix_desc'] = 'Der zu nutzende Prefix für alle Platzhalter, die von FormIt für Felder gesetzt wurden. Standard ist `fi.`';
$_lang['prop_formit.redirectto_desc'] = 'Falls `redirect` als Hook gesetzt wurde, geben Sie hier die Ressourcen ID an, zu der weitergeleitet werden soll.';
$_lang['prop_formit.redirectparams_desc'] = 'Ein JSON Array mit Parametern, die an den redirect-hoook mitgegeben werden sollen.';
$_lang['prop_formit.recaptchajs_desc'] = 'Falls `recaptcha` als Hook gesetzt wurde, ist dies ein JSON Objektc, das als JS RecaptchaOPtions Variable gesetzt wird und die Optionen für ReCaptcha definiert.';
$_lang['prop_formit.recaptchaheight_desc'] = 'Falls `recaptcha` als Hook gesetzt wurde, wird hier die Höhe des reCaptcha Widgets festgelegt.';
$_lang['prop_formit.recaptchatheme_desc'] = 'Falls `recaptcha` als Hook gesetzt wurde, wird hier das Theme des reCaptcha Widgets festgelegt.';
$_lang['prop_formit.recaptchawidth_desc'] = 'Falls `recaptcha` als Hook gesetzt wurde, wird hier die Breite des reCaptcha Widgets festgelegt.';
$_lang['prop_formit.spamemailfields_desc'] = 'Falls `spam` als Hook gesetzt wurde, geben Sie hier eine kommaseparierte Liste an Feldern an, die E-mail Adressen enthalten und gegen Spam geprüft werden sollen.';
$_lang['prop_formit.spamcheckip_desc'] = 'Falls `spam` als Hook gesetzt wurde, und dies zutrifft, wird die IP ebenfalls geprüft.';
$_lang['prop_formit.emailbcc_desc'] = 'Falls `email` als Hook gesetzt wurde, werden hier E-Mails angegeben, an die die E-mail per BCC gesendet wird. Kann eine kommaseparierte Liste an E-Mail Adressen sein.';
$_lang['prop_formit.emailbccname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, muss dies eine parallele Liste an kommaseparierten Namen für die entsprechenden E-Mail Adressen sein, die im `emailBCC` festgelegt wurden.';
$_lang['prop_formit.emailcc_desc'] = 'Falls `email` als Hook gesetzt wurde, werden hier E-Mails angegeben, an die die E-mail per CC gesendet wird. Kann eine kommaseparierte Liste an E-Mail Adressen sein.';
$_lang['prop_formit.emailccname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, muss dies eine parallele Liste an kommaseparierten Namen für die entsprechenden E-Mail Adressen sein, die im `emailCC` festgelegt wurden.';
$_lang['prop_formit.emailto_desc'] = 'Falls `email` als Hook gesetzt wurde, werden hier E-Mails angegeben, an die die E-mail gesendet wird. Kann eine kommaseparierte Liste an E-Mail Adressen sein.';
$_lang['prop_formit.emailtoname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, muss dies eine parallele Liste an kommaseparierten Namen für die entsprechenden E-Mail Adressen sein, die im `emailTo` festgelegt wurden.';
$_lang['prop_formit.emailfrom_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, und dies zutrifft, wird hier die Absenderadresse gesetzt. Falls nicht gesetzt, wird nach einem email-Feld gesucht. Wird keins gefunden, wird die E-mail aus den Systemeinstellungen verwendet.';
$_lang['prop_formit.emailfromname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, und dies zutrifft, wird hier der Name des Absenders zur passenden E-Mail angegeben.';
$_lang['prop_formit.emailreplyto_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, und dies zutrifft, wird hier die Antwort-E-Mail-Adresse angegeben.';
$_lang['prop_formit.emailreplytoname_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, und dies zutrifft, wird hier der Name des Antwortadresskontakts zur passenden E-Mail angegeben.';
$_lang['prop_formit.emailsubject_desc'] = 'Falls `email` als Hook gesetzt wurde, ist dies der Betreff für die E-Mail.';
$_lang['prop_formit.emailusefieldforsubject_desc'] = 'Falls ein Formularfeld `subject` im Formular mit übergeben wird und dies einen Wert enthält, wird dieser Wert als Betreff verwendet.';
$_lang['prop_formit.emailhtml_desc'] = 'Optional. Falls `email` als Hook gesetzt wurde, kann hier zwischen Plaintext und HTML-Mail gewählt werden. Standard ist HTML.';
$_lang['prop_formit.emailconvertnewlines_desc'] = 'Falls true und emailHtml ist auf 1 gesetzt, werden Umbrüche in br-tags konvertiert.';
$_lang['prop_formit.emailmulitseparator_desc'] = 'Der Standardseparator für Sammlungen von Einträgen, die mit Checkboxen oder Multi-Auswahlfeldern übergeben werden. Standard ist \newline';
$_lang['prop_formit.emailmultiwrapper_desc'] = 'Umfasst jeden Eintrag einer Sammlung von Feldern, die aus Checkboxen oder Multi-Auswahlfeldern übergeben werden. Standard ist `value`';

/* FormIt Auto-Responder properties */
$_lang['prop_fiar.fiartpl_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, wird hier das Template für die Auto-Antwort angebeben.';
$_lang['prop_fiar.fiartofield_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, wird hier angegeben, welches Formularfeld für To: address in der Auto-Antwort-Mail verwendet werden soll.';
$_lang['prop_fiar.fiarbcc_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, wird hier die E-Mail Adresse/n angebeben, an die die E-Mail als BCC gesendet wird. Kann eine kommaseparierte Liste sein.';
$_lang['prop_fiar.fiarbccname_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, muss dies eine parallele Liste an kommaseparierten Namen für die entsprechenden E-Mail Adressen sein, die im `emailBCC` festgelegt wurden.';
$_lang['prop_fiar.fiarcc_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, werden hier E-Mails angegeben, an die die E-mail per CC gesendet wird. Kann eine kommaseparierte Liste an E-Mail Adressen sein.';
$_lang['prop_fiar.fiarccname_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, muss dies eine parallele Liste an kommaseparierten Namen für die entsprechenden E-Mail Adressen sein, die im `emailCC` festgelegt wurden.';
$_lang['prop_fiar.fiarfrom_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, und dies zutrifft, wird hier die Absenderadresse gesetzt. Falls nicht gesetzt, wird nach einem email-Feld gesucht. Wird keins gefunden, wird die E-mail aus den Systemeinstellungen verwendet.';
$_lang['prop_fiar.fiarfromname_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, und dies zutrifft, wird hier der Name des Absenders zur passenden E-Mail angegeben.';
$_lang['prop_fiar.fiarreplyto_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, und dies zutrifft, wird hier die Antwort-E-Mail-Adresse angegeben.';
$_lang['prop_fiar.fiarreplytoname_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, und dies zutrifft, wird hier der Name des Antwortadresskontakts zur passenden E-Mail angegeben.';
$_lang['prop_fiar.fiarsubject_desc'] = 'Falls `FormItAutoResponder` als Hook gesetzt wurde, ist dies der Betreff für die E-Mail.';
$_lang['prop_fiar.fiarhtml_desc'] = 'Optional. Falls `FormItAutoResponder` als Hook gesetzt wurde, kann hier zwischen Plaintext und HTML-Mail gewählt werden. Standard ist HTML.';

/* FormItRetriever properties */
$_lang['prop_fir.placeholderprefix_desc'] = 'Der Prefix, der für Daten aus Platzhaltern der Formularfelder genutzt werden soll.';
$_lang['prop_fir.redirecttoonnotfound_desc'] = 'Falls kein redirect gefunden wurde, leite zur angegebenen Ressourcen ID weiter.';
$_lang['prop_fir.eraseonload_desc'] = 'Falls zutreffend, werden die gespeicherten Daten beim Laden gelöscht. Wird nicht empfohlen auf true zu setzen, wenn Sie nicht die Daten nur ein einziges mal wiederverwenden wollen.';

/* FormIt Math hook properties */
$_lang['prop_math.mathminrange_desc'] = 'Falls `math` als Hook gesetzt wurde, die minimale Zahlenfolge für jede Zahl in der Gleichung.';
$_lang['prop_math.mathmaxrange_desc'] = 'Falls `math` als Hook gesetzt wurde, die maximale Zahlenfolge für jede Zahl in der Gleichung.';
$_lang['prop_math.mathfield_desc'] = 'Falls `math` als Hook gesetzt wurde, der Name des Formularfeldes für die Antwort.';
$_lang['prop_math.mathop1field_desc'] = 'Falls `math` als Hook gesetzt wurde, der Name des Formularfeldes für die 1. Zahl in der Gleichung.';
$_lang['prop_math.mathop2field_desc'] = 'Falls `math` als Hook gesetzt wurde, der Name des Formularfeldes für die 2. Zahl in der Gleichung.';
$_lang['prop_math.mathoperatorfield_desc'] = 'Falls `math` als Hook gesetzt wurde, der Name des Formularfeldes für den Operator in der Gleichung.';

/* FormItCountryOptions properties */
$_lang['prop_fico.allgrouptext_desc'] = 'Optional. Falls gesetzt und &prioritized wird genutzt, wird hier ein Text-Label für alle anderen Länder gesetzt.';
$_lang['prop_fico.optgroup_desc'] = 'Optional. Falls gesetzt und &prioritized wird genutzt, ist dies das chunk Template, das für das option group markup genutzt wird.';
$_lang['prop_fico.prioritized_desc'] = 'Optional. Eine kommaspearierte Liste an ISO Codes für Länder, die in die priorisierte / oft besuchte Listengruppe gehören sollen.';
$_lang['prop_fico.prioritizedgrouptext_desc'] = 'Optional. Falls gesetzt und &prioritized wird genutzt, wird hier ein Text-Label für die priorisierten Länder gesetzt.';
$_lang['prop_fico.selected_desc'] = 'Die zu setzende Länderwert.';
$_lang['prop_fico.selectedattribute_desc'] = 'Optional. Die zu setzenden HTML Attribute für ein ausgewähltes Land.';
$_lang['prop_fico.toplaceholder_desc'] = 'Optional. Verwenden Sie dies, um den Wert in einen Platzhalter auszugeben statt direkt auszugeben.';
$_lang['prop_fico.tpl_desc'] = 'Optional. Der zu nutzende Chunk für jede Länderauswahl.';
$_lang['prop_fico.useisocode_desc'] = 'Falls 1, wird der ISO Ländercode als Wert gesetzt. Falls 0, wird der Ländername ausgegeben.';

/* FormItStateOptions properties */
$_lang['prop_fiso.selected_desc'] = 'Der auszuwählende Länderwert.';
$_lang['prop_fiso.selectedattribute_desc'] = 'Optional. Das HTML Attribut, das einem ausgewählten Land hinzugefügt werden soll.';
$_lang['prop_fiso.toplaceholder_desc'] = 'Optional. Verwenden Sie dies, um den Wert in einen Platzhalter auszugeben statt direkt auszugeben.';
$_lang['prop_fiso.tpl_desc'] = 'Optional. Der zu nutzende Chunk für jede Länderauswahl.';
$_lang['prop_fiso.useabbr_desc'] = 'Falls 1, wird die Abkürzung des Staates verwendet. Falls 0, wird der komplette Name des Staates verwendet.';

/* FormIt Options */
$_lang['formit.opt_blackglass'] = 'schwarzes Glas';
$_lang['formit.opt_clean'] = 'aufgeräumt';
$_lang['formit.opt_red'] = 'rot';
$_lang['formit.opt_white'] = 'weiß';