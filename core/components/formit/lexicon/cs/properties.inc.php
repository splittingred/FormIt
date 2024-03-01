<?php

/**
 * FormIt
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

$_lang['prop_formit.hooks_desc']                                = 'Jaké skripty se mají spustit, poté co je pozitivně dokončena validace dat. Jeden nebo čárkou oddělený seznam hooků. Jakmile jeden z hooků vrátí negativní odpověď ("false") je vykonání dalších hooků v pořadí ukončeno. Hook může být také název snippetu, který bude tímto v danou chvíli spuštěn.';
$_lang['prop_formit.prehooks_desc']                             = 'Jaké skripty se mají spustit, při inicializaci formuláře. Tímto je např. možno předvyplnit políčka pomocí konstrukce $scriptProperties[`hook`]->fields[`nazevpolicka`]. Jeden nebo čárkou oddělený seznam hooků. Jakmile jeden vrátí negativní odpověď ("false") je vykonání dalších hooků v pořadí ukončeno. Hook může být také název snippetu, který bude tímto v danou chvíli spuštěn.';
$_lang['prop_formit.submitvar_desc']                            = 'Je-li tento parametr nastaven, formulář nebude zpracován dokud daná proměnná nebude definována v $_POST.';
$_lang['prop_formit.validate_desc']                             = 'Čárkou oddělený seznam názvů políček, které se mají validovat. Pro každé pole musí být zadáno pravidlo ve tvaru nazev:validator (např: username:required, email:required). Validátory mohou být také řetězeny, jako např. email:email:required. Takové pravidlo může být definováno na více řádcích.';
$_lang['prop_formit.errtpl_desc']                               = 'Šablona oblasti pro chybové zprávy.';
$_lang['prop_formit.validationerrormessage_desc']               = 'Obecná chybová zpráva, která je nastavena do placeholderu pokud validace neproběhla v pořádku. Může obsahovat [[+errors]] v případě, že chcete zobrazit seznam všech chyb nad formulářem.';
$_lang['prop_formit.validationerrorbulktpl_desc']               = 'HTML šablona, která je použita pro každou chybu v obecné zprávě při zobrazení chyb.';
$_lang['prop_formit.customvalidators_desc']                     = 'Čárkou oddělený seznam uživatelských validátorů (názvů snippetů), které budete využívat v tomto formuláři. Pokud nejsou dané uživatelské validátory uvedeny v tomto parametru nelze je následně využít.';
$_lang['prop_formit.trimvaluesdeforevalidation_desc']           = 'Whether or not to trim spaces from the beginning and end of values before attempting validation. Defaults to true.';
$_lang['prop_formit.clearfieldsonsuccess_desc']                 = 'Je-li nastaven na "true", budou po úspěšném odeslání formuláře (který se nepřesměrovává) vymazány hodnoty všech políček.';
$_lang['prop_formit.successmessage_desc']                       = 'Je-li tento parametr nastaven, bude nastaven placeholder s názvem z parametru "&successMessagePlaceholder" na hodnotu zapsanou v tomto parametru, výchozí hodnota je `fi.successMessage`. Tato hodnota bude zobrazena po úspěšném odeslání formuláře pokud není nastaveno přesměrování.';
$_lang['prop_formit.successmessageplaceholder_desc']            = 'Název placeholderu, kam bude uložen text zprávy o úspěšném odeslání formuláře.';
$_lang['prop_formit.store_desc']                                = 'Je-li parametr nastaven na "true", FormIt uloží data z formuláře do cache a umožní tak pozdější zpracování pomocí snippetu FormItRetriever.';
$_lang['prop_formit.storetime_desc']                            = 'Je-li parametr `store` nastaven na "true", pak toto číslo určuje počet sekund, po kterou mají být data uložena od odeslání formuláře. Výchozí hodnotou je 300 sekund = 5 minut.';
$_lang['prop_formit.storelocation_desc']                        = 'If `store` is set to true, this specifies the cache location of the data from the form submission. Defaults to MODX cache.';
$_lang['prop_formit.allowfiles_desc']                           = 'Je-li nastaven na 0, bude zamezeno odeslání souborů skrze formulář.';
$_lang['prop_formit.placeholderprefix_desc']                    = 'Prefix, který bude aplikován na všechny placeholdery pro políčka vygenerovaná snippetem FormIt. Výchozí hodnota je `fi.`';
$_lang['prop_formit.redirectto_desc']                           = 'Je-li nastaven hook `redirect`, musí tento parametr obsahovat ID dokumentu, na který má být přesměrováno.';
$_lang['prop_formit.redirectparams_desc']                       = 'JSON pole parametrů, které se připojí jako $_GET proměnné pro cílovou stránku přesměrování.';
$_lang['prop_formit.recaptchajs_desc']                          = 'Je-li nastaven hook `recaptcha` mohou zde být definovány parametry proměnné RecaptchaOptions jako JSON pole, která dává možnost změnit nastavení reCaptcha widgetu.';
$_lang['prop_formit.recaptchaheight_desc']                      = 'Je-li nastaven hook `recaptcha` lze pomocí tohoto parametru nastavit výšku reCaptcha widgetu.';
$_lang['prop_formit.recaptchatheme_desc']                       = 'Je-li nastaven hook `recaptcha` máte pomocí tohoto parametru možnost nastavit téma pro reCaptcha widget.';
$_lang['prop_formit.recaptchawidth_desc']                       = 'Je-li nastaven hook `recaptcha` můžete tímto parametrem určit šířku reCaptcha widgetu.';
$_lang['prop_formit.spamemailfields_desc']                      = 'Je-li nastaven hook `spam` můžete zde určit čárkou oddělený seznam políček obsahujících e-mailové adresy vůči, kterým se má kontrola provést.';
$_lang['prop_formit.spamcheckip_desc']                          = 'Je-li nastaven hook `spam` a je-li tento parametr nastaven na "true", bude kontrolována také IP adresa.';
$_lang['prop_formit.emailbcc_desc']                             = 'Je-li nastaven hook `email` pak tento parametr obsahuje e-mailové adresy, na které je zpráva odeslána jako příjemce BCC. Může být buď jedna nebo čárkou oddělený seznam e-mailových adres.';
$_lang['prop_formit.emailbccname_desc']                         = 'Volitelné. Je-li nastaven hook `email` pak lze pomocí tohoto parametr nastavit paralelně jména pro e-mailové adresy nastavené pomocí parametru `emailBCC`. Může zde být buď jedno jméno nebo čárkou oddělený seznam jmen. Pozor na stejný počet záznámů v tomto parametru a v parametru `emailBCC`!';
$_lang['prop_formit.emailcc_desc']                              = 'Je-li nastaven hook `email` pak tento parametr obsahuje e-mailové adresy, na které je zpráva odeslána jako příjemce CC. Může být buď jedna nebo čárkou oddělený seznam e-mailových adres.';
$_lang['prop_formit.emailccname_desc']                          = 'Volitelné. Je-li nastaven hook `email` pak lze pomocí tohoto parametru nastavit paralelně jména pro e-mailové adresy nastavené pomocí parametru `emailCC`. Může zde být buď jedno jméno nebo čárkou oddělený seznam jmen. Pozor na stejný počet záznámů v tomto parametru a v parametru `emailCC`!';
$_lang['prop_formit.emailto_desc']                              = 'Je-li nastaven hook `email` pak tento parametr obsahuje e-mailové adresy, na které je zpráva odeslána. Může být buď jedna nebo čárkou oddělený seznam e-mailových adres.';
$_lang['prop_formit.emailtoname_desc']                          = 'Volitelné. Je-li nastaven hook `email` pak lze pomocí tohoto parametr nastavit paralelně jména pro e-mailové adresy nastavené pomocí parametru `emailTo`. Může zde být buď jedno jméno nebo čárkou oddělený seznam jmen. Pozor na stejný počet záznámů v tomto parametru a v parametru `emailTo`!';
$_lang['prop_formit.emailfrom_desc']                            = 'Volitelné. Je-li nastave hook `email` pak tímto parametrem můžete nastavit e-mailovou adresu, která bude zobrazena jako odesílatel zprávy. Není-li parametr nastaven, zjistí se zda ve formuláři existuje políčko `email`, pak je e-mail z tohoto pole použit. Pokud toto políčko ve formuláři neexistuje, použije se jako výchozí hodnota e-mail nastavený v konfiguraci systému klíčem `emailsender`.';
$_lang['prop_formit.emailfromname_desc']                        = 'Volitelné. Je-li nastaven hook `email` pak tímto parametrem můžete nastavit jméno zobrazené jako odesílatel zprávy.';
$_lang['prop_formit.emailreplyto_desc']                         = 'Volitelné. Je-li nastaven hook `email` pak tímto parametrem můžete určit e-mailovou adresu, na kterou budou směřovat případné odpovědi od uživatelů, kteří obdrží zprávu z MODx. (Reply-To).';
$_lang['prop_formit.emailreplytoname_desc']                     = 'Volitelné. Je-li nastaven hook `email` pam můžete tímto parametrem určit jméno, které se bude zobrazovat při odpovědi na danou zprávu uživateli (Reply-To name).';
$_lang['prop_formit.emailreturnpath_desc']                      = 'Optional. If `email` is set as a hook, and this is set, will specify the Return-path: address for the email. If not set, will take the value of `emailFrom` property.';
$_lang['prop_formit.emailsubject_desc']                         = 'Je-li nastaven hook `email` pak je tento parametr vyžadován a definuje předmět zprávy.';
$_lang['prop_formit.emailusefieldforsubject_desc']              = 'Je-li ve formuláři definováno políčko `subject` a tento parametr je nastaven na "true" bude hodnota formulářového políčka `subject` použita jako předmět zprávy.';
$_lang['prop_formit.emailhtml_desc']                            = 'Volitelné. Je-li nastaven hook `email` pak tímto parametrem můžete aktivovat odeslání zprávy ve formátu HTML, jinak bude zpráva odeslána v textovém formátu. Ve výchozím nastavení se použije HTML.';
$_lang['prop_formit.emailconvertnewlines_desc']                 = 'Je-li nataveno na Ano a emailHtml je nastaveno na 1, budou v e-mailu překonvertovány nové řádky na HTML značku BR.';
$_lang['prop_formit.emailmultiseparator_desc']                  = 'Výchozí oddělovač pro sady hodnot odesálané skrze checkboxy/multi-selecty. Výchozí hodnotou je nový řádek.';
$_lang['prop_formit.emailmultiwrapper_desc']                    = 'Obalovač každé hodnoty sady odesílané skrze checkboxy/multi-selecty. Výchozí hodnotou je pouze hodnota.';

$_lang['prop_fiar.fiartpl_desc']                                = 'Je-li nastaven hook `FormItAutoResponder` pak tímto parametrem nastavíte šablonu e-mailu, která bude automaticky odeslána.';
$_lang['prop_fiar.fiartofield_desc']                            = 'Je-li nastaven hook `FormItAutoResponder` pak tento parametr určí, které formulářové políčko bude použito jako příjemce automaticky odesílané zprávy.';
$_lang['prop_fiar.fiarbcc_desc']                                = 'Je-li nastaven hook `FormItAutoResponder` pak tento parametr obsahuje e-mailové adresy, na které je zpráva odeslána jako příjemce BCC. Může být buď jedna nebo čárkou oddělený seznam e-mailových adres.';
$_lang['prop_fiar.fiarbccname_desc']                            = 'Volitelné. Je-li nastaven hook `FormItAutoResponder`  pak lze pomocí tohoto parametru nastavit paralelně jména pro e-mailové adresy nastavené pomocí parametru `emailBCC`. Může zde být buď jedno jméno nebo čárkou oddělený seznam jmen. Pozor na stejný počet záznámů v tomto parametru a v parametru `emailBCC`!';
$_lang['prop_fiar.fiarcc_desc']                                 = 'Je-li nastaven hook `FormItAutoResponder`  pak tento parametr obsahuje e-mailové adresy, na které je zpráva odeslána jako příjemce CC. Může být buď jedna nebo čárkou oddělený seznam e-mailových adres.';
$_lang['prop_fiar.fiarccname_desc']                             = 'Volitelné. Je-li nastaven hook `FormItAutoResponder`  pak lze pomocí tohoto parametru nastavit paralelně jména pro e-mailové adresy nastavené pomocí parametru `emailCC`. Může zde být buď jedno jméno nebo čárkou oddělený seznam jmen.';
$_lang['prop_fiar.fiarfrom_desc']                               = 'Volitelné. Je-li nastaven hook `FormItAutoResponder` pak tímto parametrem můžete nastavit e-mailovou adresu, která bude zobrazena jako odesílatel zprávy. Není-li parametr nastaven, zjistí se zda ve formuláři existuje políčko `email`, pak je e-mail z tohoto pole použit. Pokud toto políčko ve formuláři neexistuje, použije se jako výchozí hodnota e-mail nastavený v konfiguraci systému klíčem `emailsender`.';
$_lang['prop_fiar.fiarfromname_desc']                           = 'Volitelné. Je-li nastaven hook `FormItAutoResponder` pak tímto parametrem můžete nastavit jméno zobrazené jako odesílatel zprávy.';
$_lang['prop_fiar.fiarreplyto_desc']                            = 'Volitelné. Je-li nastaven hook `FormItAutoResponder` pak tímto parametrem můžete určit e-mailovou adresu, na kterou budou směřovat případné odpovědi od uživatelů, kteří obdrží zprávu z MODx. (Reply-To).';
$_lang['prop_fiar.fiarreplytoname_desc']                        = 'Volitelné. Je-li nastaven hook `FormItAutoResponder` pam můžete tímto parametrem určit jméno, které se bude zobrazovat při odpovědi na danou zprávu uživateli (Reply-To name).';
$_lang['prop_fiar.fiarsubject_desc']                            = 'Je-li nastaven hook `FormItAutoResponder` pak je tento parametr vyžadován a definuje předmět zprávy.';
$_lang['prop_fiar.fiarhtml_desc']                               = 'Volitelné. Je-li nastaven hook `FormItAutoResponder` pak tímto parametrem můžete aktivovat odeslání zprávy ve formátu HTML, jinak se použije textová forma zprávy. Ve výchozím nastavení se použije HTML.';

$_lang['prop_fir.placeholderprefix_desc']                       = 'Prefix, který bude aplikován na všechny placeholdery pro políčka vygenerované snippetem FormItRetriever.';
$_lang['prop_fir.redirecttoonnotfound_desc']                    = 'Neobdržíli snippet žádná příchozí data, můžete tímto parametrem nastavit ID dokumentu, na který bude provedeno přesměrování.';
$_lang['prop_fir.eraseonload_desc']                             = 'Je-li tento parametr nastaven na "true" budou všechna uložená data vymazána při načtení formuláře. Doporučujeme ponechat "false", data přeci chcete nahrávat jen jednou.';
$_lang['prop_fir.storelocation_desc']                           = 'If `store` is set to true, this specifies the cache location of the data from the form submission. Defaults to MODX cache.';

$_lang['prop_math.mathminrange_desc']                           = 'Je-li nastaven hook `math` pak tímto parametrem můžete nastavit minimální hodnotu pro každé číslo v rovnici.';
$_lang['prop_math.mathmaxrange_desc']                           = 'Je-li nastaven hook `math` pak tímto parametrem můžete nastavit maximální hodnotu pro každé číslo v rovnici.';
$_lang['prop_math.mathfield_desc']                              = 'Je-li nastaven hook `math` pak tímto parametrem určíte název formulářového políčka pro výsledek rovnice.';
$_lang['prop_math.mathop1field_desc']                           = 'Je-li nastaven hook `math` pak tímto parametrem určíte název formulářového políčka pro první číslo rovnice.';
$_lang['prop_math.mathop2field_desc']                           = 'Je-li nastaven hook `math` pak tímto parametrem určíte název formulářového políčka pro druhé číslo rovnice.';
$_lang['prop_math.mathoperatorfield_desc']                      = 'Je-li nastaven hook `math` pak tímto parametrem nastavíte název políčka pro operátor rovnice.';

$_lang['prop_fico.allgrouptext_desc']                           = 'Volitelné. Je-li nastaven a používá se parametr &prioritized, bude zobrazen tento textový popis pro všechny ostatní země při výpisu.';
$_lang['prop_fico.optgrouptpl_desc']                            = 'Volitelné. Je-li nastaven a používá se parametr &prioritized, bude použit chunk tpl pro označení skupin.';
$_lang['prop_fico.limited_desc']                                = 'Optional. A comma-separated list of ISO codes for selected countries the full list will be limited to.';
$_lang['prop_fico.prioritized_desc']                            = 'Volitelné. Čárkou oddělený seznam ISO kódů zemí, které budou zobrazeny jako prioriováné pod jmenovkou "Častí návštěvníci" na začátku rozbalovacího seznamu. Tuto volbu lze použít pro nastavení často vybíraných zemí.';
$_lang['prop_fico.prioritizedgrouptext_desc']                   = 'Volitelné. Je-li nastaven a používá se parametr &prioritized, bude tento text použit jako jmenovka pro sadu položek rozbalovacího menu.';
$_lang['prop_fico.selected_desc']                               = 'Hodnota země při volbě.';
$_lang['prop_fico.selectedattribute_desc']                      = 'Volitelné. HTML atribut, který se přidá zvolené zemi.';
$_lang['prop_fico.toplaceholder_desc']                          = 'Volitelné. Toto použijte pokud chcete výstup uložit do placeholderu namísto přímého výpisu na obrazovku.';
$_lang['prop_fico.tpl_desc']                                    = 'Volitelné. Chunk, který se má použít jako šablona pro výpis jednotlivých položek rozbalovacího seznamu zemí.';
$_lang['prop_fico.useisocode_desc']                             = 'Je-li nastaveno na 1, budou jako hodnoty použity ISO kódy zemí. Pokud je 0 použijí se názvy zemí.';
$_lang['prop_fico.country_desc']                                = 'Optional. Set to use a different countries file when loading a list of countries.';

$_lang['prop_fiso.country_desc']                                = 'Optional. Set to use a different states file when loading a list of states.';
$_lang['prop_fiso.selected_desc']                               = 'Výběr země.';
$_lang['prop_fiso.selectedattribute_desc']                      = 'Volitelné. HTML atribut přidávaný ke vzvolené zemi.';
$_lang['prop_fiso.toplaceholder_desc']                          = 'Volitelné. Toto použijte pokud chcete uložit výstup do placeholderu namísto jeho přímoho vypsání na obrazovku.';
$_lang['prop_fiso.tpl_desc']                                    = 'Volitelné. Chunk, který se použije pro každou položku rozbalovacího menu zemí.';
$_lang['prop_fiso.useabbr_desc']                                = 'Je-li nastaveno na hodnotu 1, budou jako odesílané hodnoty použity zkratky států. Je-li 0, budou se odesílat celé názvy států.';

$_lang['formit.opt_blackglass']                                 = 'Black Glass';
$_lang['formit.opt_clean']                                      = 'Clean';
$_lang['formit.opt_red']                                        = 'Red';
$_lang['formit.opt_white']                                      = 'White';
$_lang['formit.opt_cache']                                      = 'MODX Cache';
$_lang['formit.opt_session']                                    = 'Session';
$_lang['prop_formit.savetmpfiles_desc']                         = 'If set to 1, FormIt will store submitted files in a temporary folder.';
$_lang['prop_formit.attachfiles_desc']                          = 'If true, FormIt will add all file fields as attachments in the email.';
