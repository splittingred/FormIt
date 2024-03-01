<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

$_lang['formit']                                                = 'FormIt';
$_lang['formit.desc']                                           = 'Bekijk hier alle ingezonden formulieren.';

$_lang['area_formit']                                           = 'FormIt';
$_lang['area_formit_recaptcha']                                 = 'FormIt reCAPTCHA';

$_lang['formit.form']                                           = 'Formulier';
$_lang['formit.forms']                                          = 'Formulieren';
$_lang['formit.forms_desc']                                     = 'Bekijk hier alle ingezonden formulieren.';
$_lang['formit.form_view']                                      = 'Formulier bekijken';
$_lang['formit.form_remove']                                    = 'Formulier verwijderen';
$_lang['formit.form_remove_confirm']                            = 'Weet je zeker dat je dit formulier wilt verwijderen?';
$_lang['formit.forms_remove']                                   = 'Formulieren verwijderen';
$_lang['formit.forms_remove_confirm']                           = 'Weet je zeker dat je de geselecteerde formulier(en) wilt verwijderen?';
$_lang['formit.forms_clean']                                    = 'Formulieren opruimen';
$_lang['formit.forms_clean_confirm']                            = 'Weet je zeker dat je alle oude formulieren wilt opruimen?';
$_lang['formit.forms_export']                                   = 'Formulieren exporteren';
$_lang['formit.form_encrypt']                                   = 'Formulier(en) versleutelen';
$_lang['formit.form_encrypt_confirm']                           = 'Weet je zeker dat je de formulier(en) wilt versleutelen?';
$_lang['formit.form_decrypt']                                   = 'Formulier versleuteling(en) ongedaan maken';
$_lang['formit.form_decrypt_confirm']                           = 'Weet je zeker dat je de formulier versleuteling(en) ongedaan wilt maken?';
$_lang['formit.view_ip']                                        = 'Alle formulieren van dit IP bekijken';

$_lang['formit.encryption']                                     = 'Versleuteld formulier';
$_lang['formit.encryptions']                                    = 'Versleutelde formulieren';
$_lang['formit.encryptions_desc']                               = 'Bekijk hier alle versleutelde en niet versleutelde formulieren.';

$_lang['formit.label_form_name']                                = 'Naam';
$_lang['formit.label_form_name_desc']                           = 'De naam van het formulier.';
$_lang['formit.label_form_values']                              = 'Formulier waardes';
$_lang['formit.label_form_values_desc']                         = 'De waardes van het formulier';
$_lang['formit.label_form_ip']                                  = 'IP nummer';
$_lang['formit.label_form_ip_desc']                             = 'Het IP nummer van de bezoeker die het formulier ingezonden heeft.';
$_lang['formit.label_form_date']                                = 'Datum';
$_lang['formit.label_form_date_desc']                           = 'De datum van wanneer het formulier ingezonden is.';
$_lang['formit.label_form_encrypted']                           = 'Versleuteld';
$_lang['formit.label_form_encrypted_desc']                      = '';
$_lang['formit.label_form_decrypted']                           = 'Niet versleuteld';
$_lang['formit.label_form_decrypted_desc']                      = '';
$_lang['formit.label_form_total']                               = 'Totaal';
$_lang['formit.label_form_total_desc']                          = '';

$_lang['formit.label_clean_label']                              = 'Verwijder formulieren ouder dan';
$_lang['formit.label_clean_desc']                               = 'dagen';

$_lang['formit.label_export_form']                              = 'Formulier';
$_lang['formit.label_export_form_desc']                         = 'Selecteer een formulier om te exporteren.';
$_lang['formit.label_export_start_date']                        = 'Datum vanaf';
$_lang['formit.label_export_start_date_desc']                   = 'Selecteer een datum om vanaf die datum de formulieren te exporteren.';
$_lang['formit.label_export_end_date']                          = 'Datum tot';
$_lang['formit.label_export_end_date_desc']                     = 'Selecteer een datum om tot die datum de formulieren te exporteren.';
$_lang['formit.label_export_delimiter']                         = 'CSV scheidingsteken';
$_lang['formit.label_export_delimiter_desc']                    = 'Het CSV scheidingsteken waarmee kolommen gescheiden worden. Standaard is ";".';

$_lang['formit.filter_form']                                    = 'Filter op formulier';
$_lang['formit.filter_start_date']                              = 'Filter vanaf';
$_lang['formit.filter_end_date']                                = 'Filter tot';
$_lang['formit.encryption_unavailable']                         = 'PHP OpenSSL functies openssl_encrypt en openssl_decrypt zijn niet beschikbaar. Om deze functies beschikbaar te maken installeer PHP OpenSSL op je server. Bekijk http://www.php.net/manual/en/openssl.requirements.php voor meer informatie.';
$_lang['formit.encryption_unavailable_warning']                 = '<strong>Waarschuwing</strong>: PHP OpenSSL functies openssl_encrypt en openssl_decrypt zijn niet beschikbaar. Dit betekend dat je formulieren niet kunt versleutelen. Om deze functies beschikbaar te maken installeer PHP OpenSSL op je server. Bekijk <a href="http://www.php.net/manual/en/openssl.requirements.php" target="_blank">deze pagina</a> voor meer informatie.';
$_lang['formit.forms_clean_desc']                               = 'De <a href="https://autoriteitpersoonsgegevens.nl/nl/onderwerpen/avg-europese-privacywetgeving/algemene-informatie-avg" target="_blank">Algemene verordening gegevensbescherming (AVG)</a> stelt verplicht dat persoonlijke data, dat niet langer noodzakelijk is om te bewaren, wordt verwijderd. Deze functie maakt het mogelijk om formulieren, ouder dan het opgegeven aantal dagen, te verwijderen. Deze actie kan niet worden teruggedraaid!';
$_lang['formit.forms_clean_executing']                          = 'Bezig met opruimen van formulieren';
$_lang['formit.forms_clean_success']                            = '[[+amount]] formulier(en) verwijderd.';
$_lang['formit.export_failed']                                  = 'Het exporteren van de formulieren is mislukt, probeer het nog eens.';
$_lang['formit.export_dir_failed']                              = 'Er is een fout opgetreden tijdens het exporteren van de formulier, de export map kon niet aangemaakt worden.';

$_lang['formit.contains']                                       = 'Het veld moet de volgende waarde bevatten: "[[+value]]".';
$_lang['formit.email_invalid']                                  = 'Vul een geldig e-mailadres in.';
$_lang['formit.email_invalid_domain']                           = 'Het e-mailadres heeft geen geldige domeinnaam.';
$_lang['formit.email_no_recipient']                             = 'Voer de ontvanger of ontvangers voor deze e-mail in.';
$_lang['formit.email_not_sent']                                 = 'Er is iets fout gegaan tijdens het verzenden van de e-mail.';
$_lang['formit.email_tpl_nf']                                   = 'Er is geen e-mail template opgegeven.';
$_lang['formit.field_not_empty']                                = 'Het veld moet leeg zijn.';
$_lang['formit.field_required']                                 = 'Het veld is verplicht.';
$_lang['formit.math_incorrect']                                 = 'Onjuist antwoord!';
$_lang['formit.math_field_nf']                                  = '[[+field]] is niet gespecificeerd in het formulier.';
$_lang['formit.max_length']                                     = 'De invoer mag niet langer zijn dan [[+length]] tekens.';
$_lang['formit.max_value']                                      = 'De invoer mag niet groter zijn dan [[+value]].';
$_lang['formit.min_length']                                     = 'De invoer moet minstens [[+length]] tekens lang zijn.';
$_lang['formit.min_value']                                      = 'De invoer mag niet kleiner zijn dan [[+value]].';
$_lang['formit.not_date']                                       = 'De waarde moet een datum zijn.';
$_lang['formit.not_lowercase']                                  = 'De waarde mag geen hoofdletters bevatten.';
$_lang['formit.not_number']                                     = 'De invoer moet een geldig nummer zijn.';
$_lang['formit.not_uppercase']                                  = 'De waarde mag geen kleine letters bevatten.';
$_lang['formit.password_dont_match']                            = 'De wachtwoorden zijn niet hetzelfde.';
$_lang['formit.password_not_confirmed']                         = 'Bevestig het wachtwoord.';
$_lang['formit.prioritized_group_text']                         = 'Frequente bezoekers';
$_lang['formit.range_invalid']                                  = 'Ongeldig bereik ingevoerd.';
$_lang['formit.range']                                          = 'De invoer moet tussen de [[+min]] en [[+max]] zijn.';
$_lang['formit.recaptcha_err_load']                             = 'Could not load FormItReCaptcha service class.';
$_lang['formit.spam_blocked']                                   = 'Het formulier is geweigerd door een spamfilter.';
$_lang['formit.spam_marked']                                    = ' - gemarkeerd als spam.';
$_lang['formit.username_taken']                                 = 'Gebruikersnaam al in gebruik. Kies een andere';
$_lang['formit.not_regexp']                                     = 'De waarde voldoet niet aan het verwachte formaat.';
$_lang['formit.all_group_text']                                 = 'Alle landen';
$_lang['formit.storeAttachment_mediasource_error']              = 'Kan Mediabron niet vinden! Mediabron ID is: ';
$_lang['formit.storeAttachment_access_error']                   = 'Directory is niet schrijfbaar! Controleer de machtigingen voor: ';
