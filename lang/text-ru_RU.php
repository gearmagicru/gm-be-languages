<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Языки',
    '{description}' => 'Управление языками интерфейса системы',
    '{permissions}' => [
        'any'    => ['Полный доступ', 'Просмотр и внесение изменений в языки системы'],
        'view'   => ['Просмотр', 'Просмотр языков'],
        'read'   => ['Чтение', 'Чтение языков'],
        'add'    => ['Добавление', 'Добавление языков'],
        'edit'   => ['Изменение', 'Изменение языков'],
        'delete' => ['Удаление', 'Удаление языков']
    ],

    // Grid: панель инструментов
    'Language information' => 'Информация о языке',
    'Installation' => 'Установка',
    'Installation language' => 'Установка языка',
    'Install' => 'Установить',
    'Install language' => 'Установить языки',
    'Install selected language' => 'Установить выбранный язык',
    'Uninstall' => 'Удалить',
    'Uninstall language' => 'Удаление языка',
    'Uninstall selected language' => 'Удаление выбранного языка',
    'Are you sure you want to uninstall your language?' => 'Вы действительно хотите удалить выбранный вами язык?',
    // Grid: поля
    'Package language' => 'Языковый пакет',
    'Language' => 'Язык',
    'Code' => 'ID',
    'Attributes' => 'Атрибуты',
    'Alternative' => 'Альтернативный',
    'Language code' => 'Код языка',
    'Country code' => 'Код страны',
    'Identifier' => 'Идентификатор',
    'Identifiers' => 'Идентификаторы',
    'Identifier in the database for determining the recording language' => 'Идентификатор в базе данных для определения языка записи',
    'Name' => 'Имя',
    'Full name' => 'Полное имя',
    'Country' => 'Страна',
    'Slug' => 'Слаг',
    'Part of the language definition URL' => 'Часть URL-адреса для определения языка',
    'Tag' => 'Тег',
    'Locale' => 'Локаль',
    'Available for' => 'Доступен для',
    'Available (selectable) for' => 'Доступен (возможность выбора) для',
    'Available for site' => 'Доступен для сайта',
    'Site' => 'Сайт',
    'Control panel' => 'Панель управления',
    'Panel' => 'Панель',
    'Available for control panel' => 'Доступен для панели управления',
    'By default' => 'По умолчанию',
    'Yes' => 'Да',
    'No' => 'Нет',
    'Version' => 'Версия',
    'Number' => 'Номер',
    'Author' => 'Автор',
    'Translated' => 'Переведено',
    'Date' => 'Дата',
    '{0} (determined from {1})' => '{0} (определяется из {1})',
    'not available to anyone' => 'никому не доступен',
    'Go to website with language {shortName}' => 'Перейти на сайт с языком - {shortName}',
    'Go to control panel with language {shortName}' => 'Перейти в панель управления с языком - {shortName}',
    'Status' => 'Статус',
    'All' => 'Все',
    'Installed' => 'Установленные',
    'None installed' => 'Не установленные',
    'installed' => 'установлен',
    'not installed' => 'не установлен',
    // Grid: сообщения / ошибки
    'In the "By default" column, you can only select the default language with the switch, but not disable' 
        => 'В столбце "По умолчанию", можно только установить переключателем выбранный язык по умолчанию, но не отключить его.',
    'The language you have selected is already installed or it is not among the installed ones' 
        => 'Выбранный Вами язык уже установлен или его нет среди устанавливаемых.',
    'Cannot install the language you selected' => 'Невозможно выполнить установку выбранного вами языка.',
    'Language installation error: {0}' => 'Ошибка установки языка: <br>{0}',
    'option is required and must be set' => 'опция является обязательной и должна быть установлена.',
    'Unable to uninstall language' => 'Невозможно выполнить удаление языка.',
    'Unable to delete the language (your chosen language is not installed)' 
        => 'Невозможно выполнить удаление языка (выбранный вами язык не установлен).',
    'Unable to perform language deletion (your chosen language is the default language)' 
        => 'Невозможно выполнить удаление языка (выбранный вами язык, является языком по умолчанию).',
    'Successfully installed "{0}" language' => 'Успешно установлен язык "{0}"',
    'Successfully uninstalled "{0}" language' => 'Успешно удалён язык "{0}"',
    'The language cannot be removed because it depends on: {0}' 
        => 'Невозможно выполнить удаление языка, так как от него зависят: {0}.',

    // Grid: сообщения / заголовки
    'Language accessibility' => 'Доступность языка',
    // Grid: сообщения / текст
    'Language changed by default to "{0}"' => 'Язык изменён по умолчанию на "{0}"',
    'The language "{0}" is not available for the site' => 'Язык "{0}" не доступен для сайта.',
    'The language "{0}" is available for the site' => 'Язык "{0}" доступен для сайта.',
    'The language "{0}" is not available for the control panel' => 'Язык "{0}" не доступен для панели управления.',
    'The language "{0}" is available for the control panel' => 'Язык "{0}" доступен для панели управления.',
    'To install, you must select a language from the list' => 'Для установки необходимо выбрать язык из списка.',
    'To uninstall, you must select a language from the list' => 'Чтобы удалить, вам необходимо выбрать язык из списка.',

    // Form
    '{form.title}' => 'Информация о языке',
    '{form.titleTpl}' => 'Информация о языке',
    '{form.subtitle}' => 'языковый пакет &laquo;{name}&raquo;',

    // Install
    '{install.title}' => 'Установка языка',
    '{install.titleTpl}' => 'Установка языка "{shortName}"'
];
