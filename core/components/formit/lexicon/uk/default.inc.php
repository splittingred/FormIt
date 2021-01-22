<?php

/**
 * FormIt
 *
 * Copyright 2020 by Sterc <modx@sterc.nl>
 */

$_lang['formit']                                                = 'FormIt';
$_lang['formit.desc']                                           = 'Подивитися всі відправлені форми';

$_lang['area_formit']                                           = 'FormIt';
$_lang['area_formit_recaptcha']                                 = 'FormIt reCAPTCHA';

$_lang['formit.form']                                           = 'Форма';
$_lang['formit.forms']                                          = 'Форми';
$_lang['formit.forms_desc']                                     = 'Подивитися всі відправлені форми';
$_lang['formit.form_view']                                      = 'Подивитися форму';
$_lang['formit.form_remove']                                    = 'Видалити форму';
$_lang['formit.form_remove_confirm']                            = 'Ви впевнені, що хочете видалити цю форму?';
$_lang['formit.forms_remove']                                   = 'Видалити форми';
$_lang['formit.forms_remove_confirm']                           = 'Ви впевнені, що хочете видалити всі форми?';
$_lang['formit.forms_clean']                                    = 'Очистити форми';
$_lang['formit.forms_clean_confirm']                            = 'Ви впевнені, що хочете очистити всі старі форми?';
$_lang['formit.forms_export']                                   = 'Експорт форм';
$_lang['formit.form_encrypt']                                   = 'Зашифрувати форму(и)';
$_lang['formit.form_encrypt_confirm']                           = 'Ви впевнені, що хочете зашифрувати форму(и)?';
$_lang['formit.form_decrypt']                                   = 'Скасувати шифрування форм(и)';
$_lang['formit.form_decrypt_confirm']                           = 'Ви впевнені, що хочете скасувати шифрування форм(и)?';
$_lang['formit.view_ip']                                        = 'Подивитися всі форми з цього IP';

$_lang['formit.encryption']                                     = 'Зашифрована форма';
$_lang['formit.encryptions']                                    = 'Зашифровані форми';
$_lang['formit.encryptions_desc']                               = 'Подивитися всі зашифровані і не зашифровані форми.';

$_lang['formit.label_form_name']                                = 'Назва';
$_lang['formit.label_form_name_desc']                           = 'Назва форми.';
$_lang['formit.label_form_values']                              = 'Значення';
$_lang['formit.label_form_values_desc']                         = 'Значення форми.';
$_lang['formit.label_form_ip']                                  = 'IP адреса';
$_lang['formit.label_form_ip_desc']                             = 'IP адреса відвідувачів, які відправляли форми.';
$_lang['formit.label_form_date']                                = 'Дата';
$_lang['formit.label_form_date_desc']                           = 'Дата відправлення форми';
$_lang['formit.label_form_encrypted']                           = 'Зашифрована';
$_lang['formit.label_form_encrypted_desc']                      = '';
$_lang['formit.label_form_decrypted']                           = 'Не зашифрована';
$_lang['formit.label_form_decrypted_desc']                      = '';
$_lang['formit.label_form_total']                               = 'Всього';
$_lang['formit.label_form_total_desc']                          = '';

$_lang['formit.label_clean_label']                              = 'Видалити форми старше';
$_lang['formit.label_clean_desc']                               = 'днів';

$_lang['formit.label_export_form']                              = 'Форма';
$_lang['formit.label_export_form_desc']                         = 'Виберіть форми, які необхідно експортувати.';
$_lang['formit.label_export_start_date']                        = 'Дата від';
$_lang['formit.label_export_start_date_desc']                   = 'Виберіть дату, з якої будуть експортуватися форми.';
$_lang['formit.label_export_end_date']                          = 'Дата до';
$_lang['formit.label_export_end_date_desc']                     = 'Виберіть дату, до якої будуть експортуватися форми.';
$_lang['formit.label_export_delimiter']                         = 'CSV роздільник';
$_lang['formit.label_export_delimiter_desc']                    = 'Роздільник для стовпців в CSV. За замовчуванням ";".';

$_lang['formit.filter_form']                                    = 'Виберіть форму';
$_lang['formit.filter_start_date']                              = 'Виберіть початкову дату';
$_lang['formit.filter_end_date']                                = 'Виберіть кінцеву дату';
$_lang['formit.encryption_unavailable']                         = 'PHP OpenSSL функції openssl_encrypt і openssl_decrypt недоступні. Будь ласка, встановіть PHP OpenSSL на ваш сервер. Дивіться <a href="https://www.php.net/manual/en/openssl.requirements.php" target="_blank">https://www.php.net/manual/en/openssl.requirements.php</a> для більш детальної інформації.';
$_lang['formit.encryption_unavailable_warning']                 = '<strong>Увага:</strong> PHP OpenSSL функції openssl_encrypt і openssl_decrypt недоступні. Це означає, що ви не можете використовувати шифрування в ваших формах. Будь ласка, встановіть PHP OpenSSL на ваш сервер. Відвідайте <a href="https://www.php.net/manual/en/openssl.requirements.php" target="_blank">https://www.php.net/manual/en/openssl.requirements.php</a> для більш детальної інформації.';
$_lang['formit.forms_clean_desc']                               = 'Європейський <a href="https://ec.europa.eu/info/law/law-topic/data-protection/eu-data-protection-rules_en" target="_blank">Загальний регламент про захист даних (GDPR)</a> вимагає, щоб особисті дані, які більше не використовуються, були видалені. Цей інструмент дозволяє видалити ці форми старше зазначених нижче днів. Цю дію не можна скасувати!';
$_lang['formit.forms_clean_executing']                          = 'Очищення форм';
$_lang['formit.forms_clean_success']                            = 'Видалено форм: [[+amount]].';
$_lang['formit.export_failed']                                  = 'Не вдалось експортувати форми, будь ласка, спробуйте ще раз.';
$_lang['formit.export_dir_failed']                              = 'Сталася помилка під час експорту форми, не вдалося створити папку експорту.';

$_lang['formit.contains']                                       = 'Поле має містити фразу "[[+value]]".';
$_lang['formit.email_invalid']                                  = 'Будь ласка, введіть правильну адресу електронної пошти.';
$_lang['formit.email_invalid_domain']                           = 'Ваша електронна адреса не має дійсного доменного імені.';
$_lang['formit.email_no_recipient']                             = 'Будь ласка, вкажіть одержувача або одержувачів електронної пошти.';
$_lang['formit.email_not_sent']                                 = 'Сталася помилка під час спроби відправити пошту.';
$_lang['formit.email_tpl_nf']                                   = 'Будь ласка, вкажіть шаблон листа.';
$_lang['formit.field_not_empty']                                = 'Це поле повинно бути порожнім.';
$_lang['formit.field_required']                                 = 'Це поле є обов\'язковим для заповнення.';
$_lang['formit.math_incorrect']                                 = 'Неправильна відповідь!';
$_lang['formit.math_field_nf']                                  = '[[+field]] поле введення не вказано в формі.';
$_lang['formit.max_length']                                     = 'Це поле не може містити більше [[+length]] символ(а/ів).';
$_lang['formit.max_value']                                      = 'Це поле не може бути більше [[+value]].';
$_lang['formit.min_length']                                     = 'Це поле має містити не меньше [[+length]] символ(а/ів).';
$_lang['formit.min_value']                                      = 'Це поле не може бути менше [[+value]].';
$_lang['formit.not_date']                                       = 'Це поле повинно бути дійсною датою.';
$_lang['formit.not_lowercase']                                  = 'Всі символи в цьому полі повинні бути в нижньому регістрі.';
$_lang['formit.not_number']                                     = 'Це поле повинно бути дійсним числом.';
$_lang['formit.not_uppercase']                                  = 'Всі символи в цьому полі повинні бути в верхньому регістрі';
$_lang['formit.password_dont_match']                            = 'Паролі не співпадають.';
$_lang['formit.password_not_confirmed']                         = 'Будь ласка, підтвердіть свій пароль.';
$_lang['formit.prioritized_group_text']                         = 'Постійні відвідувачі';
$_lang['formit.range_invalid']                                  = 'Невірний діапазон.';
$_lang['formit.range']                                          = 'Ваше значення має бути між [[+min]] і [[+max]].';
$_lang['formit.recaptcha_err_load']                             = 'Неможливо завантажити клас reCAPTCHA.';
$_lang['formit.spam_blocked']                                   = 'Ваше повідомлення було заблоковано спам фільтром: ';
$_lang['formit.spam_marked']                                    = ' - позначено як спам.';
$_lang['formit.username_taken']                                 = 'Ім\'я користувача вже зайнято. Будь ласка, виберіть інше.';
$_lang['formit.not_regexp']                                     = 'Ваше значення не збігається з очікуваним форматом.';
$_lang['formit.all_group_text']                                 = 'Усі країни';
$_lang['formit.storeAttachment_mediasource_error']              = 'Джерело медіа (Media Source) не знайдено! ID джерела: ';
$_lang['formit.storeAttachment_access_error']                   = 'Папка недоступна для завантаження! Перевірте права на папку: ';

$_lang['formit.migrate']                                        = 'Перенесення даних з зашифрованих форм';
$_lang['formit.migrate_desc']                                   = 'Оновлення до FormIt 3.0 також оновить метод шифрування, який використовується для шифрування відправлених даних форм. FormIt 2.x використовує mcrypt для шифрування і дешифрування, а в 3.0 використовує методи openssl. Для правильної роботи вже зашифровані форми необхідно перенести з mcrypt в openssl.';
$_lang['formit.migrate_alert']                                  = 'FormIt успішно оновлений, але відправлені вами зашифровані форми необхідно перенести. Натисніть сюди, щоб почати передачу.';
$_lang['formit.migrate_status']                                 = 'Статус';
$_lang['formit.migrate_running']                                = 'В цей час виконується процес перенесення даних у фоновому режимі. Будь ласка, тримайте цю сторінку відкритою в вашому браузері. <strong>НЕ ЗАКРИВАЙТЕ ЦЮ СТОРІНКУ!</strong>';
$_lang['formit.migrate_success']                                = 'Перенесення завершено';
$_lang['formit.migrate_success_msg']                            = 'Всі ваші зашифровані форми були успішно перенесені.';
