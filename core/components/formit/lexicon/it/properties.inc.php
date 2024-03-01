<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

$_lang['prop_formit.hooks_desc']                                = 'Quale programma da inquadrare, se nessuno, in seguito alla validazione del modulo passata correttamente. Può essere una lista di Hooks separata da virgola, se il primo fallisce, i seguenti non saranno inquadrati. Un hook può essere anche uno Snippet che eseguirà tale Snippet.';
$_lang['prop_formit.prehooks_desc']                             = 'Quale programma da inquadrare, se nessuno, una volta che il modulo è caricato. Potete pre-impostare i campi del modulo attraverso $scriptProperties[`hook`]->fields[`fieldname`]. Può essere una lista di hooks separati con una virgola, e se il primo fallisce, i seguenti non saranno inquadrati. Un hook può anche essere un nome di Snippet che eseguirà tale Snippet.';
$_lang['prop_formit.submitvar_desc']                            = 'Se impostato, non comincerà il procedimento del modulo se questa variabile POST non è passata.';
$_lang['prop_formit.validate_desc']                             = 'Una lista di campi da validare separati con una virgola, con ogni nome del campo come nome:validatore (es: nomeutente:obbligatorio,email:obbligatoria). I validatori possono anche essere concatenati, come email:email:obbligatoria. Questa proprietà può essere specificata su linee multiple.';
$_lang['prop_formit.errtpl_desc']                               = 'Il tema utilizzato per i messaggi di errore';
$_lang['prop_formit.validationerrormessage_desc']               = 'Un messaggio di errore generale da impostare come segnaposto se la validazione fallisce. Può contenere [[+errors]] se volete mostrare una lista di tutti gli errori all\'inizio.';
$_lang['prop_formit.validationerrorbulktpl_desc']               = 'Il tpl HTML che è utilizzato per ogni errore individuale nel valore del messaggio di errore generico.';
$_lang['prop_formit.customvalidators_desc']                     = 'Una lista di nomi per una validazione personalizzata (snippets) separati da una virgola che avete intenzione di utilizzare in questo modulo. Loro devono essere esplicitamente dichiarati qui, oppure non saranno lanciati.';
$_lang['prop_formit.trimvaluesdeforevalidation_desc']           = 'Whether or not to trim spaces from the beginning and end of values before attempting validation. Defaults to true.';
$_lang['prop_formit.clearfieldsonsuccess_desc']                 = 'Se vero, pulirà i campi nella prossima corretta sottoscrizione al modulo che non reinderizza.';
$_lang['prop_formit.successmessage_desc']                       = 'Se impostato, imposterà questo come segnaposto con il nome del valore della proprietà &successMessagePlaceholder, che di predefinito è impostato a `fi.successMessage`.';
$_lang['prop_formit.successmessageplaceholder_desc']            = 'Il segnaposto a cui impostare il messaggio di successo.';
$_lang['prop_formit.store_desc']                                = 'Se VERO, immagazzinerà i dati nella cache per il recupero utilizzando lo snippet FormItRetriever.';
$_lang['prop_formit.storetime_desc']                            = 'Se `store` è impostato a VERO, questo specifica il numero di secondi da immagazzinare i dati dall\'invio del modulo. I valori predefiniti sono cinque minuti.';
$_lang['prop_formit.storelocation_desc']                        = 'If `store` is set to true, this specifies the cache location of the data from the form submission. Defaults to MODX cache.';
$_lang['prop_formit.allowfiles_desc']                           = 'If set to 0, will prevent files from being submitted on the form.';
$_lang['prop_formit.placeholderprefix_desc']                    = 'Il prefisso da utilizzare per tutti i segnaposto impostati da FormIt per i campi. Il valore predefinito è impostato a `fi.`';
$_lang['prop_formit.redirectto_desc']                           = 'Se `redirect` è impostato come hook, questo deve specificare l\'ID della Risorsa a cui reindirizzare.';
$_lang['prop_formit.redirectparams_desc']                       = 'Una lista di parametri JSON da passare all\'hook di reindirizzamento che sarà passato durante il reinderizzamento.';
$_lang['prop_formit.recaptchajs_desc']                          = 'Se `recaptcha` è impostato come hook, questo può essere un oggetto JSON che sarà impostato alla variabile JS RecaptchaOptions, che configura le opzioni per il reCaptcha.';
$_lang['prop_formit.recaptchaheight_desc']                      = 'Se `recaptcha` è impostato come hook, questo selezionerà l\'altezza del widget reCaptcha.';
$_lang['prop_formit.recaptchatheme_desc']                       = 'Se `recaptcha` è impostato come hook, questo selezionerà un tema per il widget reCaptcha.';
$_lang['prop_formit.recaptchawidth_desc']                       = 'Se `recaptcha` è impostato come hook, questo imposterà la larghezza per il widget reCaptcha.';
$_lang['prop_formit.spamemailfields_desc']                      = 'Se `spam` è impostato come hook, una lista di campi separati da una virgola contenenti gli indirizzi e-mail da controllare contro lo spam.';
$_lang['prop_formit.spamcheckip_desc']                          = 'Se `spam` è impostato come hook, e questo è VERO, controllerà anche l\'IP.';
$_lang['prop_formit.emailbcc_desc']                             = 'Se `email` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail come un BCC.Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_formit.emailbccname_desc']                         = 'Opzionale. Se `email` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailBCC`.';
$_lang['prop_formit.emailcc_desc']                              = 'Se `email` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail come un CC.Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_formit.emailccname_desc']                          = 'Opzionale. Se `email` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailCC`.';
$_lang['prop_formit.emailto_desc']                              = 'Se `email` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail. Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_formit.emailtoname_desc']                          = 'Opzionale. Se `email` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailTo`.';
$_lang['prop_formit.emailfrom_desc']                            = 'Opzionale. Se `email` è impostato come hook, e questo è impostato, specificherà l\'indirizzo Da: per le e-mail. Se non è impostato, ricercherà per primo un campo `email` nel modulo. Se nessuno viene trovato, il valore predefinito sarà impostato ad `emailsender` delle impostazioni di sistema.';
$_lang['prop_formit.emailfromname_desc']                        = 'Opzionale. Se `email` è impostato come hook, e questo è impostato, specificherà il nome del campo Da: per le e-mail.';
$_lang['prop_formit.emailreplyto_desc']                         = 'Opzionale. Se `email` è impostato come hook, e questo è impostato, specificherà l\'indirizzo del campo Reply-To: per le e-mail.';
$_lang['prop_formit.emailreplytoname_desc']                     = 'Opzionale. Se `email` è impostato come hook, e questo è impostato, specificherà il nome del campo Reply-To: per le e-mail.';
$_lang['prop_formit.emailreturnpath_desc']                      = 'Optional. If `email` is set as a hook, and this is set, will specify the Return-path: address for the email. If not set, will take the value of `emailFrom` property.';
$_lang['prop_formit.emailsubject_desc']                         = 'Se `email` è impostato come hook, questo è obbligatorio come una linea di oggetto per le e-mail.';
$_lang['prop_formit.emailusefieldforsubject_desc']              = 'Se il campo `oggetto` è passato nel modulo, se questo è VERO, sarà utilizzato il campo contenuto come linea dell\'oggetto e-mail.';
$_lang['prop_formit.emailhtml_desc']                            = 'Opzionale. Se `email` è impostato come hook, questo alterna le e-mail in HTML oppure no. Il valore predefinito è settato a VERO.';
$_lang['prop_formit.emailconvertnewlines_desc']                 = 'Se VERO ed emailHtml è impostato a 1, convertirà le Nuove linee al marcatore BR nelle e-mail.';
$_lang['prop_formit.emailmultiseparator_desc']                  = 'Il separatore predefinito per le collezioni di voci inviate attraverso campi di controllo/multi-selezioni.Valore predefinito settato a Nuova linea.';
$_lang['prop_formit.emailmultiwrapper_desc']                    = 'Includerà ogni voce in una collezione di campi inviati attraverso Controlli di campo/selezioni-multiple. I valori predefiniti sono impostati giusto al valore.';

$_lang['prop_fiar.fiartpl_desc']                                = 'Se `FormItAutoResponder` è impostato come hook, allora questo specifica il tema delle e-mail auto-risposte a cui inviare come e-mail.';
$_lang['prop_fiar.fiartofield_desc']                            = 'Se `FormItAutoResponder` è impostato come hook, allora questo specifica quale campo del modulo verrà utilizzato come indirizzo per il campo A: nella e-mail di auto-risposta.';
$_lang['prop_fiar.fiarbcc_desc']                                = 'Se `FormItAutoResponder` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail come un BCC. Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_fiar.fiarbccname_desc']                            = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailBCC`.';
$_lang['prop_fiar.fiarcc_desc']                                 = 'Se `FormItAutoResponder` è impostato come hook, allora questo specifica gli indirizzi e-mail a cui inviare le e-mail come un CC. Può essere una lista di indirizzi e-mail separati da una virgola.';
$_lang['prop_fiar.fiarccname_desc']                             = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, allora questo deve essere una lista parallela di nomi separati da una virgola per gli indirizzi e-mail specificati nella proprietà `emailCC`.';
$_lang['prop_fiar.fiarfrom_desc']                               = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, è questo è impostato, specificherà l\'indirizzo del campo Da: per le e-mail. Se non impostato, sarà ricercato per primo un campo `email` nel modulo. Se nessuno sarà stato trovato, sarà impostato come predefinito il campo `emailsender` nelle impostazioni di sistema.';
$_lang['prop_fiar.fiarfromname_desc']                           = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, è questo è impostato, specificherà il nome Da: per le e-mail.';
$_lang['prop_fiar.fiarreplyto_desc']                            = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, e questo è impostato, specificherà l\'indirizzo Reply-To: per le e-mail.';
$_lang['prop_fiar.fiarreplytoname_desc']                        = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, e questo è impostato, specificherà il nome Reply-To: per le e-mail.';
$_lang['prop_fiar.fiarsubject_desc']                            = 'Se `FormItAutoResponder` è impostato come hook, questo è obbligatorio come una linea di oggetto per l\'e-mail.';
$_lang['prop_fiar.fiarhtml_desc']                               = 'Opzionale. Se `FormItAutoResponder` è impostato come hook, questo alterna e-mail in HTML oppure no. Valore predefinito settato a VERO.';

$_lang['prop_fir.placeholderprefix_desc']                       = 'Il prefisso da utilizzare con i segnaposto dai dati del modulo.';
$_lang['prop_fir.redirecttoonnotfound_desc']                    = 'Se i dati non vengono trovati, e questo è impostato, reindirizza alla Risorsa con questo ID.';
$_lang['prop_fir.eraseonload_desc']                             = 'Se VERO, questo eliminerà i dati del modulo immagazzinati al caricamento. Si raccomanda fortemente di lasciarlo impostato a FALSE a meno che non si desideri che i dati siano caricati una sola volta.';
$_lang['prop_fir.storelocation_desc']                           = 'If `store` is set to true, this specifies the cache location of the data from the form submission. Defaults to MODX cache.';

$_lang['prop_math.mathminrange_desc']                           = 'Se `math` è impostato come hook, il minimo raggio per ogni numero nella equazione.';
$_lang['prop_math.mathmaxrange_desc']                           = 'Se `math` è impostato come hook, il raggio massimo per ogni numero nella equazione.';
$_lang['prop_math.mathfield_desc']                              = 'Se `math` è impostato come hook, il nome del campo di inserimento per la risposta.';
$_lang['prop_math.mathop1field_desc']                           = 'Se `math` è impostato come hook, il nome del campo per il primo numero nella equazione.';
$_lang['prop_math.mathop2field_desc']                           = 'Se `math` è impostato come hook, il nome del campo per il secondo numero nella equazione.';
$_lang['prop_math.mathoperatorfield_desc']                      = 'Se `math` è impostato come hook, il nome del campo per l\'operatore nella equazione.';

$_lang['prop_fico.allgrouptext_desc']                           = 'Optional. If set and &prioritized is in use, will be the text label for the all other countries option group.';
$_lang['prop_fico.optgrouptpl_desc']                            = 'Optional. If set and &prioritized is in use, will be the chunk tpl to use for the option group markup.';
$_lang['prop_fico.limited_desc']                                = 'Optional. A comma-separated list of ISO codes for selected countries the full list will be limited to.';
$_lang['prop_fico.prioritized_desc']                            = 'Optional. A comma-separated list of ISO codes for countries that will move them into a prioritized "Frequent Visitors" group at the top of the dropdown. This can be used for your commonly-selected countries.';
$_lang['prop_fico.prioritizedgrouptext_desc']                   = 'Optional. If set and &prioritized is in use, will be the text label for the prioritized option group.';
$_lang['prop_fico.selected_desc']                               = 'The country value to select.';
$_lang['prop_fico.selectedattribute_desc']                      = 'Optional. The HTML attribute to add to a selected country.';
$_lang['prop_fico.toplaceholder_desc']                          = 'Optional. Use this to set the output to a placeholder instead of outputting directly.';
$_lang['prop_fico.tpl_desc']                                    = 'Optional. The chunk to use for each country dropdown option.';
$_lang['prop_fico.useisocode_desc']                             = 'If 1, will use the ISO country code for the value. If 0, will use the country name.';
$_lang['prop_fico.country_desc']                                = 'Optional. Set to use a different countries file when loading a list of countries.';

$_lang['prop_fiso.country_desc']                                = 'Optional. Set to use a different states file when loading a list of states.';
$_lang['prop_fiso.selected_desc']                               = 'The country value to select.';
$_lang['prop_fiso.selectedattribute_desc']                      = 'Optional. The HTML attribute to add to a selected country.';
$_lang['prop_fiso.toplaceholder_desc']                          = 'Optional. Use this to set the output to a placeholder instead of outputting directly.';
$_lang['prop_fiso.tpl_desc']                                    = 'Optional. The chunk to use for each country dropdown option.';
$_lang['prop_fiso.useabbr_desc']                                = 'If 1, will use the state abbreviation for the value. If 0, will use the full state name.';

$_lang['formit.opt_blackglass']                                 = 'Bicchiere nero';
$_lang['formit.opt_clean']                                      = 'Pulito';
$_lang['formit.opt_red']                                        = 'Rosso';
$_lang['formit.opt_white']                                      = 'Bianco';
$_lang['formit.opt_cache']                                      = 'MODX Cache';
$_lang['formit.opt_session']                                    = 'Session';
$_lang['prop_formit.savetmpfiles_desc']                         = 'If set to 1, FormIt will store submitted files in a temporary folder.';
$_lang['prop_formit.attachfiles_desc']                          = 'If true, FormIt will add all file fields as attachments in the email.';
