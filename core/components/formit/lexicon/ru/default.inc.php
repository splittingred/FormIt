<?php

/**
 * FormIt
 *
 * Copyright 2020 by Sterc <modx@sterc.nl>
 */

$_lang['formit']                                                = 'FormIt';
$_lang['formit.desc']                                           = 'Посмотреть все отправленные формы';

$_lang['area_formit']                                           = 'FormIt';
$_lang['area_formit_recaptcha']                                 = 'FormIt reCAPTCHA';

$_lang['formit.form']                                           = 'Форма';
$_lang['formit.forms']                                          = 'Формы';
$_lang['formit.forms_desc']                                     = 'Посмотреть все отправленные формы';
$_lang['formit.form_view']                                      = 'Посмотреть форму';
$_lang['formit.form_remove']                                    = 'Удалить форму';
$_lang['formit.form_remove_confirm']                            = 'Вы уверены, что хотите удалить эту форму?';
$_lang['formit.forms_remove']                                   = 'Удалить формы';
$_lang['formit.forms_remove_confirm']                           = 'Вы уверены, что хотите удалить все формы?';
$_lang['formit.forms_clean']                                    = 'Очистить формы';
$_lang['formit.forms_clean_confirm']                            = 'Вы уверены, что хотите очистить все старые формы?';
$_lang['formit.forms_export']                                   = 'Экспорт Формы';
$_lang['formit.form_encrypt']                                   = 'Зашифровать форму(ы)';
$_lang['formit.form_encrypt_confirm']                           = 'Вы уверены, что хотите зашифровать форму(ы)?';
$_lang['formit.form_decrypt']                                   = 'Отменить шифрование форм(ы)';
$_lang['formit.form_decrypt_confirm']                           = 'Вы уверены, что хотите отменить шифрование форм(ы)?';
$_lang['formit.view_ip']                                        = 'Посмотреть все формы с этого IP';

$_lang['formit.encryption']                                     = 'Зашифрованная форма';
$_lang['formit.encryptions']                                    = 'Зашифрованные формы';
$_lang['formit.encryptions_desc']                               = 'Посмотреть все зашифрованные и не зашифрованные формы.';

$_lang['formit.label_form_name']                                = 'Название';
$_lang['formit.label_form_name_desc']                           = 'Название формы.';
$_lang['formit.label_form_values']                              = 'Значения';
$_lang['formit.label_form_values_desc']                         = 'Значения формы.';
$_lang['formit.label_form_ip']                                  = 'IP адрес';
$_lang['formit.label_form_ip_desc']                             = 'IP адрес посетителей, которые отправляли формы.';
$_lang['formit.label_form_date']                                = 'Дата';
$_lang['formit.label_form_date_desc']                           = 'Дата отправления формы';
$_lang['formit.label_form_encrypted']                           = 'Зашифрована';
$_lang['formit.label_form_encrypted_desc']                      = '';
$_lang['formit.label_form_decrypted']                           = 'Не зашифрована';
$_lang['formit.label_form_decrypted_desc']                      = '';
$_lang['formit.label_form_total']                               = 'Всего';
$_lang['formit.label_form_total_desc']                          = '';

$_lang['formit.label_clean_label']                              = 'Удалить формы старше';
$_lang['formit.label_clean_desc']                               = 'дней';

$_lang['formit.label_export_form']                              = 'Форма';
$_lang['formit.label_export_form_desc']                         = 'Выберите формы, которые необходимо экспортировать.';
$_lang['formit.label_export_start_date']                        = 'Дата от';
$_lang['formit.label_export_start_date_desc']                   = 'Выберите дату, с которой будут экспортироваться формы.';
$_lang['formit.label_export_end_date']                          = 'Дата до';
$_lang['formit.label_export_end_date_desc']                     = 'Выберите дату, до которой будут экспортироваться формы.';
$_lang['formit.label_export_delimiter']                         = 'CSV разделитель';
$_lang['formit.label_export_delimiter_desc']                    = 'Разделитель для столбцов в CSV. По умолчанию ";".';

$_lang['formit.filter_form']                                    = 'Выберите форму';
$_lang['formit.filter_start_date']                              = 'Выберите начальную дату';
$_lang['formit.filter_end_date']                                = 'Выберите конечную дату';
$_lang['formit.encryption_unavailable']                         = 'PHP OpenSSL функции openssl_encrypt и openssl_decrypt недоступны. Пожалуйста, установите PHP OpenSSL на ваш сервер. Смотрите <a href="https://www.php.net/manual/ru/openssl.requirements.php" target="_blank">https://www.php.net/manual/ru/openssl.requirements.php</a> для более подробной информации.';
$_lang['formit.encryption_unavailable_warning']                 = '<strong>Внимание:</strong> PHP OpenSSL функции openssl_encrypt и openssl_decrypt недоступны. Это означает, что вы не можете использовать шифрование в ваших формах. Пожалуйста, установите PHP OpenSSL на ваш сервер. Посетите <a href="https://www.php.net/manual/ru/openssl.requirements.php" target="_blank">https://www.php.net/manual/ru/openssl.requirements.php</a> для более подробной информации.';
$_lang['formit.forms_clean_desc']                               = 'Европейский <a href="https://ec.europa.eu/info/law/law-topic/data-protection/eu-data-protection-rules_en" target="_blank">Общий регламент по защите данных (GDPR)</a> требует, чтобы личные данные, которые больше не используются, были удалены. Этот инструмент позволяет удалять сохраненные формы старше указанных ниже дней. Это действие не может быть отменено!';
$_lang['formit.forms_clean_executing']                          = 'Очистка форм';
$_lang['formit.forms_clean_success']                            = 'Удалено форм: [[+amount]].';
$_lang['formit.export_failed']                                  = 'Не удалось экспортировать формы, пожалуйста, попробуйте еще раз.';
$_lang['formit.export_dir_failed']                              = 'Произошла ошибка при экспорте формы, не удалось создать папку экспорта.';

$_lang['formit.contains']                                       = 'Поле должно содержать фразу "[[+value]]".';
$_lang['formit.email_invalid']                                  = 'Пожалуйста, введите правильный адрес электронной почты.';
$_lang['formit.email_invalid_domain']                           = 'Ваш адрес электронной почты не является допустимым именем домена.';
$_lang['formit.email_no_recipient']                             = 'Пожалуйста, укажите получателя или получателей электронной почты.';
$_lang['formit.email_not_sent']                                 = 'Произошла ошибка при попытке отправить почту.';
$_lang['formit.email_tpl_nf']                                   = 'Пожалуйста, укажите шаблон письма.';
$_lang['formit.field_not_empty']                                = 'Это поле должно быть пустым.';
$_lang['formit.field_required']                                 = 'Это поле обязательно для заполнения.';
$_lang['formit.math_incorrect']                                 = 'Неправильный ответ!';
$_lang['formit.math_field_nf']                                  = '[[+field]] поле ввода не указано в форме.';
$_lang['formit.max_length']                                     = 'Это поле не может быть длиннее, чем [[+length]] символ(а/ов).';
$_lang['formit.max_value']                                      = 'Это поле не может быть больше, чем [[+value]].';
$_lang['formit.min_length']                                     = 'Это поле должно быть не меньше [[+length]] символов.';
$_lang['formit.min_value']                                      = 'Это поле не может быть меньше, чем [[+value]].';
$_lang['formit.not_date']                                       = 'Это поле должно быть действительной датой.';
$_lang['formit.not_lowercase']                                  = 'Все символы в этом поле должны быть в нижнем регистре.';
$_lang['formit.not_number']                                     = 'Это поле должно быть допустимым числом.';
$_lang['formit.not_uppercase']                                  = 'Все символы в этом поле должны быть заглавными.';
$_lang['formit.password_dont_match']                            = 'Пароли не совпадают.';
$_lang['formit.password_not_confirmed']                         = 'Пожалуйста, подтвердите свой пароль';
$_lang['formit.prioritized_group_text']                         = 'Постоянные посетители';
$_lang['formit.range_invalid']                                  = 'Неверный диапазон.';
$_lang['formit.range']                                          = 'Ваше значение должно быть между [[+min]] и [[+max]].';
$_lang['formit.recaptcha_err_load']                             = 'Невозможно загрузить класс reCAPTCHA.';
$_lang['formit.spam_blocked']                                   = 'Ваше сообщение было заблокировано спам фильтром: ';
$_lang['formit.spam_marked']                                    = ' - помечено как спам.';
$_lang['formit.username_taken']                                 = 'Имя пользователя уже занято. Пожалуйста, выберите другое.';
$_lang['formit.not_regexp']                                     = 'Ваше значение не совпадает с предполагаемым форматом.';
$_lang['formit.all_group_text']                                 = 'Все страны';
$_lang['formit.storeAttachment_mediasource_error']              = 'Источник медиа (Media Source) не найден! ID источника: ';
$_lang['formit.storeAttachment_access_error']                   = 'Папка не доступна для загрузки! Проверьте права на папку: ';

$_lang['formit.migrate']                                        = 'Перенос данных с зашифрованных форм';
$_lang['formit.migrate_desc']                                   = 'Обновление до FormIt 3.0 также обновит метод шифрования, используемый для шифрования отправленных данных форм. FormIt 2.x использует mcrypt для шифрования и дешифрования, а в 3.0 использует методы openssl. Для правильной работы зашифрованные в настоящее время формы необходимо перенести из mcrypt в openssl.';
$_lang['formit.migrate_alert']                                  = 'FormIt успешно обновлен, но отправленные вами зашифрованные формы необходимо перенести. Нажмите сюда, чтобы начать перенос.';
$_lang['formit.migrate_status']                                 = 'Статус';
$_lang['formit.migrate_running']                                = 'В настоящее время выполняется процесс переноса данных в фоновом режиме. Пожалуйста, держите эту страницу открытой в вашем браузере. <strong>НЕ ЗАКРЫВАЙТЕ ЭТУ СТРАНИЦУ!</strong>';
$_lang['formit.migrate_success']                                = 'Перенос завершен';
$_lang['formit.migrate_success_msg']                            = 'Все ваши зашифрованные формы были успешно перенесены.';
