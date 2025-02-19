<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации модуля.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'translator' => [
        'locale'   => 'auto',
        'patterns' => [
            'text' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'   => 'text-%s.php'
            ]
        ],
        'autoload' => ['text'],
        'external' => [BACKEND]
    ],

    'accessRules' => [
        // для авторизованных пользователей Панели управления
        [ // разрешение "Полный доступ" (any: view, read, add, edit, delete)
            'allow',
            'controllers' => [
                'Grid'      => ['data', 'view', 'update', 'delete', 'clear', 'filter'],
                'Form'      => ['data', 'view', 'add', 'update', 'delete'],
                'Uninstall' => ['package'],
                'Install'   => ['data', 'view', 'package'],
                'Manually'  => ['view', 'add'],
                'Trigger'   => ['combo'],
                'Search'    => ['data', 'view']
            ],
            'permission' => 'any',
            'users'      => ['@backend']
        ],
        [ // разрешение "Просмотр" (view)
            'allow',
            'permission'  => 'view',
            'controllers' => [
                'Grid'    => ['data', 'view', 'filter'],
                'Form'    => ['data', 'view'],
                'Trigger' => ['combo'],
                'Search'  => ['data', 'view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Чтение" (read)
            'allow',
            'permission'  => 'read',
            'controllers' => [
                'Grid'    => ['data', 'view', 'filter'],
                'Form'    => ['data', 'view'],
                'Trigger' => ['combo'],
                'Search'  => ['data', 'view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Добавление" (add)
            'allow',
            'permission'  => 'add',
            'controllers' => [
                'Form' => ['add']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Изменение" (edit)
            'allow',
            'permission'  => 'edit',
            'controllers' => [
                'Grid' => ['update'],
                'Form' => ['update']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Удаление" (delete)
            'allow',
            'permission'  => 'delete',
            'controllers' => [
                'Grid' => ['delete'],
                'Form' => ['delete']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Информация о модуле" (info)
            'allow',
            'permission'  => 'info',
            'controllers' => ['Info'],
            'users'       => ['@backend']
        ],
        [ // для всех остальных, доступа нет
            'deny'
        ]
    ],

    'viewManager' => [
        'id'          => 'gm-languages-{name}',
        'useTheme'    => true,
        'useLocalize' => true,
        'viewMap'     => [
            // информации о модуле
            'info' => [
                'viewFile'      => '//backend/module-info.phtml', 
                'forceLocalize' => true
            ],
            'form'          => '/form.json',
            'form-install'  => '/form-install.json',
            'form-manually' => '/form-manually.json'
        ]
    ]
];
