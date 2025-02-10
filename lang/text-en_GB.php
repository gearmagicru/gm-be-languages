<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Languages',
    '{description}' => 'Managing system interface languages',
    '{permissions}' => [
        'any'    => ['Full access', 'View and change system languages'],
        'view'   => ['View', 'View languages'],
        'read'   => ['Reading', 'Reading languages'],
        'add'    => ['Adding', 'Adding languages'],
        'edit'   => ['Editing', 'Editing languages'],
        'delete' => ['Deleting', 'Deleting languages']
    ],

    // Grid: панель инструментов
    'Language information' => 'Language information',
    'Installation' => 'Installation',
    'Installation language' => 'Installation language',
    'Install' => 'Install',
    'Install language' => 'Install language',
    'Install selected language' => 'Install selected language',
    'Uninstall' => 'Uninstall',
    'Uninstall language' => 'Uninstall language',
    'Uninstall selected language' => 'Uninstall selected language',
    'Are you sure you want to uninstall your language?' => 'Are you sure you want to uninstall your language?',
    // Grid: поля
    'Package language' => 'Package language',
    'Language' => 'Language',
    'Code' => 'ID',
    'Attributes' => 'Attributes',
    'Alternative' => 'Alternative',
    'Language code' => 'Language code',
    'Country code' => 'Country code',
    'Identifier' => 'Identifier',
    'Identifiers' => 'Identifiers',
    'Identifier in the database for determining the recording language' => 'Identifier in the database for determining the recording language',
    'Name' => 'Name',
    'Full name' => 'Full name',
    'Country' => 'Country',
    'Slug' => 'Slug',
    'Part of the language definition URL' => 'Part of the language definition URL',
    'Tag' => 'Tag',
    'Locale' => 'Locale',
    'Available for' => 'Available for',
    'Available (selectable) for' => 'Available (selectable) for',
    'Available for site' => 'Available for site',
    'Site' => 'Site',
    'Control panel' => 'Control panel',
    'Panel' => 'Panel',
    'Available for control panel' => 'Available for control panel',
    'By default' => 'By default',
    'Yes' => 'Yes',
    'No' => 'No',
    'Version' => 'Version',
    'Number' => 'Number',
    'Author' => 'Author',
    'Translated' => 'Translated',
    'Date' => 'Date',
    '{0} (determined from {1})' => '{0} (determined from {1})',
    'not available to anyone' => 'not available to anyone',
    'Go to website with language {shortName}' => 'Go to website with language - {shortName}',
    'Go to control panel with language {shortName}' => 'Go to control panel with language - {shortName}',
    'Status' => 'Status',
    'All' => 'All',
    'Installed' => 'Installed',
    'None installed' => 'None installed',
    'installed' => 'installed',
    'not installed' => 'not installed',
    // Grid: сообщения / ошибки
    'In the "By default" column, you can only select the default language with the switch, but not disable' 
        => 'In the "By default" column, you can only select the default language with the switch, but not disable.',
    'The language you have selected is already installed or it is not among the installed ones' 
        => 'The language you have selected is already installed or it is not among the installed ones.',
    'Cannot install the language you selected' => 'Cannot install the language you selected.',
    'Language installation error: {0}' => 'Language installation error: <br>{0}',
    'option is required and must be set' => 'option is required and must be set.',
    'Unable to uninstall language' => 'Unable to uninstall language.',
    'Unable to delete the language (your chosen language is not installed)' 
        => 'Unable to delete the language (your chosen language is not installed).',
    'Unable to perform language deletion (your chosen language is the default language)' 
        => 'Unable to perform language deletion (your chosen language is the default language).',
    'Successfully installed "{0}" language' => 'Successfully installed "{0}" language',
    'Successfully uninstalled "{0}" language' => 'Successfully uninstalled "{0}" language',
    'The language cannot be removed because it depends on: {0}' 
        => 'The language cannot be removed because it depends on: {0}.',

    // Grid: сообщения / заголовки
    'Language accessibility' => 'Language accessibility',
    // Grid: сообщения / текст
    'Language changed by default to "{0}"' => 'Language changed by default to "{0}"',
    'The language "{0}" is not available for the site' => 'The language "{0}" is not available for the site.',
    'The language "{0}" is available for the site' => 'The language "{0}" is available for the site.',
    'The language "{0}" is not available for the control panel' => 'The language "{0}" is not available for the control panel.',
    'The language "{0}" is available for the control panel' => 'The language "{0}" is available for the control panel.',
    'To install, you must select a language from the list' => 'To install, you must select a language from the list.',
    'To uninstall, you must select a language from the list' => 'To uninstall, you must select a language from the list.',

    // Form
    '{form.title}' => 'Language information',
    '{form.titleTpl}' => 'Language information',
    '{form.subtitle}' => 'language pack &laquo;{name}&raquo;',

    // Install
    '{install.title}' => 'Language install',
    '{install.titleTpl}' => 'Install language "{shortName}"'
];
