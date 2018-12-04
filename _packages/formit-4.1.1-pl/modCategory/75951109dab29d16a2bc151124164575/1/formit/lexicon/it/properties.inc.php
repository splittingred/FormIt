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
 * @subpackge lexicon
 * @language it
 */
/* FormIt properties */
$_lang['prop_formit.hooks_desc'] = 'Quale programma da inquadrare, se nessuno, in seguito alla validazione del modulo passata correttamente. Può essere una lista di Hooks separata da virgola, se il primo fallisce, i seguenti non saranno inquadrati. Un hook può essere anche uno Snippet che eseguirà tale Snippet.';
$_lang['prop_formit.prehooks_desc'] = 'Quale programma da inquadrare, se nessuno, una volta che il modulo è caricato. Potete pre-impostare i campi del modulo attraverso $scriptProperties[`hook`]->fields[`fieldname`]. Può essere una lista di hooks separati con una virgola, e se il primo fallisce, i seguenti non saranno inquadrati. Un hook può anche essere un nome di Snippet che eseguirà tale Snippet.';
$_lang['prop_formit.submitvar_desc'] = 'Se impostato, non comincerà il procedimento del modulo se questa variabile POST non è passata.';
$_lang['prop_formit.validate_desc'] = 'Una lista di campi da validare separati con una virgola, con ogni nome del campo come nome:validatore (es: nomeutente:obbligatorio,email:obbligatoria). I validatori possono anche essere concatenati, come email:email:obbligatoria. Questa proprietà può essere specificata su linee multiple.';
$_lang['prop_formit.errtpl_desc'] = 'Il tema utilizzato per i messaggi di errore';
$_lang['prop_formit.validationerrormessage_desc'] = 'Un messaggio di errore generale da impostare come segnaposto se la validazione fallisce. Può contenere [[+errors]] se volete mostrare una lista di tutti gli errori all\'inizio.';
$_lang['prop_formit.validationerrorbulktpl_desc'] = 'Il tpl HTML che è utilizzato per ogni errore individuale nel valore del messaggio di errore generico.';
$_lang['prop_formit.customvalidators_desc'] = 'Una lista di nomi per una validazione personalizzata (snippets) separati da una virgola che avete intenzione di utilizzare in questo modulo. Loro devono essere esplicitamente dichiarati qui, oppure non saranno lanciati.';
$_lang['prop_formit.clearfieldsonsuccess_desc'] = 'Se vero, pulirà i campi nella prossima corretta sottoscrizione al modulo che non reinderizza.';
$_lang['prop_formit.successmessage_desc'] = 'Se impostato, imposterà questo come segnaposto con il nome del valore della proprietà &successMessagePlaceholder, che di predefinito è impostato a `fi.successMessage`.';
$_lang['prop_formit.successmessageplaceholder_desc'] = 'Il segnaposto a cui impostare il messaggio di successo.';
$_lang['prop_formit.store_desc'] = 'Se VERO, immagazzinerà i dati nella cache per il recupero utilizzando lo snippet FormItRetriever.';
$_lang['prop_formit.storetime_desc'] = 'Se `store` è impostato a VERO, questo specifica il numero di secondi da immagazzinare i dati dall\'invio del modulo. I valori predefiniti sono cinque minuti.';
$_lang['prop_formit.placeholderprefix_desc'] = 'Il prefisso da utilizzare per tutti i segnaposto impostati da FormIt per i campi. Il valore predefinito è impostato a `fi.`';
$_lang['prop_formit.redirectto_desc'] = 'Se `redirect` è impostato come hook, questo deve specificare l\'ID della Risorsa a cui reindirizzare.';
$_lang['prop_formit.redirectparams_desc'] = 'Una lista di parametri JSON da passare all\'hook di reindirizzamento che sarà passato durante il reinderizzamento.';
$_lang['prop_formit.recaptchajs_desc'] = 'Se `recaptcha` è impostato come hook, questo può essere un oggetto JSON che sarà impostato alla variabile JS RecaptchaOptions, che configura le opzioni per il reCaptcha.';
$_lang['prop_formit.recaptchaheight_desc'] = 'Se `recaptcha` è impostato come hook, questo selezionerà l\'altezza del widget reCaptcha.';
$_lang['prop_formit.recaptchatheme_desc'] = 'Se `recaptcha` è impostato come hook, questo selezionerà un tema per il widget reCaptcha.';
$_lang['prop_formit.recaptchawidth_desc'] = 'Se `recaptcha` è impostato come hook, questo imposterà la larghezza per il widget reCaptcha.';
$_lang['prop_formit.spamemailfields_desc'] = 'Se `spam` è impostato come hook, una lista di campi separati da una virgola contenenti gli indirizzi e-mail da controllare contro lo spam.';
$_lang['prop_formit.spamcheckip_desc'] = 'Se `spam` è impostato come hook, e questo è VERO, controllerà anche l\'IP.';
$_lang['prop_formit.emailbcc_desc'] = 'Se `email` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail come un BCC.Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_formit.emailbccname_desc'] = 'Opzionale. Se `email` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailBCC`.';
$_lang['prop_formit.emailcc_desc'] = 'Se `email` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail come un CC.Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_formit.emailccname_desc'] = 'Opzionale. Se `email` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailCC`.';
$_lang['prop_formit.emailto_desc'] = 'Se `email` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail. Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_formit.emailtoname_desc'] = 'Opzionale. Se `email` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailTo`.';
$_lang['prop_formit.emailfrom_desc'] = 'Opzionale. Se `email` è impostato come hook, e questo è impostato, specificherà l\'indirizzo Da: per le e-mail. Se non è impostato, ricercherà per primo un campo `email` nel modulo. Se nessuno viene trovato, il valore predefinito sarà impostato ad `emailsender` delle impostazioni di sistema.';
$_lang['prop_formit.emailfromname_desc'] = 'Opzionale. Se `email` è impostato come hook, e questo è impostato, specificherà il nome del campo Da: per le e-mail.';
$_lang['prop_formit.emailreplyto_desc'] = 'Opzionale. Se `email` è impostato come hook, e questo è impostato, specificherà l\'indirizzo del campo Reply-To: per le e-mail.';
$_lang['prop_formit.emailreplytoname_desc'] = 'Opzionale. Se `email` è impostato come hook, e questo è impostato, specificherà il nome del campo Reply-To: per le e-mail.';
$_lang['prop_formit.emailsubject_desc'] = 'Se `email` è impostato come hook, questo è obbligatorio come una linea di oggetto per le e-mail.';
$_lang['prop_formit.emailusefieldforsubject_desc'] = 'Se il campo `oggetto` è passato nel modulo, se questo è VERO, sarà utilizzato il campo contenuto come linea dell\'oggetto e-mail.';
$_lang['prop_formit.emailhtml_desc'] = 'Opzionale. Se `email` è impostato come hook, questo alterna le e-mail in HTML oppure no. Il valore predefinito è settato a VERO.';
$_lang['prop_formit.emailconvertnewlines_desc'] = 'Se VERO ed emailHtml è impostato a 1, convertirà le Nuove linee al marcatore BR nelle e-mail.';
$_lang['prop_formit.emailmultiseparator_desc'] = 'Il separatore predefinito per le collezioni di voci inviate attraverso campi di controllo/multi-selezioni.Valore predefinito settato a Nuova linea.';
$_lang['prop_formit.emailmultiwrapper_desc'] = 'Includerà ogni voce in una collezione di campi inviati attraverso Controlli di campo/selezioni-multiple. I valori predefiniti sono impostati giusto al valore.';

/* FormIt Auto-Responder properties */
$_lang['prop_fiar.fiartpl_desc'] = 'Se `FormItAutoResponder` è impostato come hook, allora questo specifica il tema delle e-mail auto-risposte a cui inviare come e-mail.';
$_lang['prop_fiar.fiartofield_desc'] = 'Se `FormItAutoResponder` è impostato come hook, allora questo specifica quale campo del modulo verrà utilizzato come indirizzo per il campo A: nella e-mail di auto-risposta.';
$_lang['prop_fiar.fiarbcc_desc'] = 'Se `FormItAutoResponder` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail come un BCC. Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_fiar.fiarbccname_desc'] = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailBCC`.';
$_lang['prop_fiar.fiarcc_desc'] = 'Se `FormItAutoResponder` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail come un CC. Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_fiar.fiarccname_desc'] = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailCC`.';
$_lang['prop_fiar.fiarfrom_desc'] = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, è questo è impostato, specificherà l\'indirizzo del campo Da: per le e-mail. Se non impostato, sarà ricercato per primo un campo `email` nel modulo. Se nessuno sarà stato trovato, sarà impostato come predefinito il campo `emailsender` nelle impostazioni di sistema.';
$_lang['prop_fiar.fiarfromname_desc'] = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, è questo è impostato, specificherà il nome Da: per le e-mail.';
$_lang['prop_fiar.fiarreplyto_desc'] = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, e questo è impostato, specificherà l\'indirizzo Reply-To: per le e-mail.';
$_lang['prop_fiar.fiarreplytoname_desc'] = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, e questo è impostato, specificherà il nome Reply-To: per le e-mail.';
$_lang['prop_fiar.fiarsubject_desc'] = 'Se `FormItAutoResponder` è impostato come hook, questo è obbligatorio come una linea di oggetto per l\'e-mail.';
$_lang['prop_fiar.fiarhtml_desc'] = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, questo alterna e-mail in HTML oppure no. Valore predefinito settato a VERO.';

/* FormItRetriever properties */
$_lang['prop_fir.placeholderprefix_desc'] = 'Il prefisso da utilizzare con i segnaposto dai dati del modulo.';
$_lang['prop_fir.redirecttoonnotfound_desc'] = 'Se i dati non vengono trovati, e questo è impostato, reindirizza alla Risorsa con questo ID.';
$_lang['prop_fir.eraseonload_desc'] = 'Se VERO, questo eliminerà i dati del modulo immagazzinati al caricamento. Si raccomanda fortemente di lasciarlo impostato a FALSE a meno che non si desideri che i dati siano caricati una sola volta.';

/* FormIt Math hook properties */
$_lang['prop_math.mathminrange_desc'] = 'Se `math` è impostato come hook, il minimo raggio per ogni numero nella equazione.';
$_lang['prop_math.mathmaxrange_desc'] = 'Se `math` è impostato come hook, il raggio massimo per ogni numero nella equazione.';
$_lang['prop_math.mathfield_desc'] = 'Se `math` è impostato come hook, il nome del campo di inserimento per la risposta.';
$_lang['prop_math.mathop1field_desc'] = 'Se `math` è impostato come hook, il nome del campo per il primo numero nella equazione.';
$_lang['prop_math.mathop2field_desc'] = 'Se `math` è impostato come hook, il nome del campo per il secondo numero nella equazione.';
$_lang['prop_math.mathoperatorfield_desc'] = 'Se `math` è impostato come hook, il nome del campo per l\'operatore nella equazione.';

/* FormIt Options */
$_lang['formit.opt_blackglass'] = 'Bicchiere nero';
$_lang['formit.opt_clean'] = 'Pulito';
$_lang['formit.opt_red'] = 'Rosso';
$_lang['formit.opt_white'] = 'Bianco';