<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Languages\Controller;

use Gm;
use Gm\Helper\Url;
use Gm\Panel\Widget\TabGrid;
use Gm\Panel\Helper\ExtGrid;
use Gm\Panel\Helper\HtmlGrid;
use Gm\Panel\Controller\GridController;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;

/**
 * Контроллер списка доступных языков.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Controller
 * @since 1.0
 */
class Grid extends GridController
{
    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabGrid
    {
        /** @var TabGrid $tab Сетка данных (Gm.view.grid.Grid GmJS) */
        $tab = parent::createWidget();

        // столбцы (Gm.view.grid.Grid.columns GmJS)
        $tab->grid->columns = [
            ExtGrid::columnNumberer(),
            ExtGrid::columnAction(),
            [
                'text'    => '#Language',
                'columns' => [
                    [
                        'text'      => '#Code',
                        'dataIndex' => 'code',
                        'cellTip'   => '{code}',
                        'filter'    => ['type' => 'numeric'],
                        'sortable'  => true,
                        'width'     => 70
                    ],
                    [
                        'text'      => ExtGrid::columnInfoIcon($this->t('Name')),
                        'dataIndex' => 'shortName',
                        'cellTip'   => HtmlGrid::tags([
                            HtmlGrid::header('{shortName}'),
                            HtmlGrid::fieldLabel($this->t('Code'), '{code}'),
                            HtmlGrid::fieldLabel($this->t('Full name'), '{name}'),
                            HtmlGrid::fieldLabel($this->t('Country'), '{country}'),
                            HtmlGrid::fieldLabel($this->t('Tag'), '{tag}'),
                            HtmlGrid::fieldLabel($this->t('Slug'), '{slug}'),
                            HtmlGrid::fieldLabel($this->t('Locale'), '{locale}')
                        ]),
                        'filter'    => ['type' => 'string'],
                        'sortable'  => true,
                        'width'     => 120
                    ],
                    [
                        'text'      => '#Full name',
                        'dataIndex' => 'name',
                        'filter'    => ['type' => 'string'],
                        'sortable'  => true,
                        'width'     => 140
                    ],
                    [
                        'text'      => '#Country',
                        'dataIndex' => 'country',
                        'sortable'  => true,
                        'width'     => 120
                    ],
                    [
                        'text'      => '#Tag',
                        'dataIndex' => 'tag',
                        'sortable'  => true,
                        'width'     => 90
                    ],
                    [
                        'text'      => '#Slug',
                        'tooltip'   => '#Part of the language definition URL',
                        'dataIndex' => 'slug',
                        'sortable'  => true,
                        'width'     => 90
                    ],
                    [
                        'text'      => '#Locale',
                        'dataIndex' => 'locale',
                        'sortable'  => true,
                        'width'     => 90
                    ],
                    [
                        'text'      => '#By default',
                        'xtype'     => 'g-gridcolumn-switch',
                        'dataIndex' => 'default',
                        'width'     => 110
                    ]
                ]
            ],
            [
                'text'    => '#Available (selectable) for',
                'columns' => [
                    [
                        'text'      => '#Site',
                        'xtype'     => 'g-gridcolumn-switch',
                        'dataIndex' => 'frontend',
                        'width'     => 90
                    ],
                    [
                        'xtype'    => 'templatecolumn',
                        'sortable' => false,
                        'width'    => 45,
                        'align'    => 'center',
                        'tpl'      => HtmlGrid::tplIf(
                            "urlFrontend=='#'",
                            '', 
                            HtmlGrid::a(
                                '', 
                                '{urlFrontend}',
                                [
                                    'title' => $this->t('Go to website with language {shortName}'),
                                    'class' => 'g-icon g-icon-svg g-icon_size_14 g-icon-m_link g-icon-m_color_default g-icon-m_is-hover',
                                    'target' => '_blank'
                                ]
                            )
                        )
                    ],
                    [
                        'text'      => '#Panel',
                        'tooltip'   => '#Control panel',
                        'xtype'     => 'g-gridcolumn-switch',
                        'dataIndex' => 'backend',
                        'width'     => 90
                    ],
                    [
                        'xtype'    => 'templatecolumn',
                        'sortable' => false,
                        'width'    => 45,
                        'align'    => 'center',
                        'tpl'      => HtmlGrid::tplIf(
                            "urlBackend=='#'",
                            '',
                            HtmlGrid::a(
                                '', 
                                '{urlBackend}',
                                [
                                    'title' => $this->t('Go to control panel with language {shortName}'),
                                    'class' => 'g-icon g-icon-svg g-icon_size_14 g-icon-m_link g-icon-m_color_default g-icon-m_is-hover',
                                    'target' => '_blank'
                                ]
                            )
                        )
                    ]
                ]
            ],
            [
                'xtype'    => 'templatecolumn',
                'text'     => '#Status',
                'sortable' => false,
                'width'    => 120,
                'align'    => 'center',
                'tpl'      => HtmlGrid::tplIf(
                    'status==1',
                    '<span class="gm-languages-grid__status gm-languages-grid__status_installed">' . $this->module->t('installed') . '</span>',
                    '<span class="gm-languages-grid__status gm-languages-grid__status_not_installed">' . $this->module->t('not installed') . '</span>'
                )
            ]
        ];

        
        // панель инструментов (Gm.view.grid.Grid.tbar GmJS)
        $tab->grid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                // группа инструментов "Установка"
                'install' => ExtGrid::buttonGroup([], [
                    'title' => '#Installation',
                    'items' => [
                        // инструмент "Установка"
                         ExtGrid::button([
                            'text'        => '#Install',
                            'tooltip'     => '#Install selected language',
                            'iconCls'     => 'g-icon-svg gm-languages__icon-install',
                            'minWidth'    => 73,
                            'handler'     => 'loadWidget',
                            'handlerArgs' => [
                                'pattern' => 'grid.selectedRow',
                                'route'   => Gm::alias('@match', '/install/view/{id}')
                            ],
                            'msgMustSelect' => '#To install, you must select a language from the list'
                        ]),
                        // инструмент "Демонтаж"
                         ExtGrid::button([
                            'text'        => '#Uninstall',
                            'tooltip'     => '#Uninstall selected language',
                            'iconCls'     => 'g-icon-svg gm-languages__icon-uninstall',
                            'confirm'     => true,
                            'handler'     => 'loadWidget',
                            'handlerArgs' => [
                                'pattern' => 'grid.selectedRow',
                                'route'   => Gm::alias('@match', '/uninstall/package/{id}')
                            ],
                            'msgConfirm'    => '#Are you sure you want to uninstall your language?',
                            'msgMustSelect' => '#To uninstall, you must select a language from the list'
                        ])
                    ]
                ]),
                'edit' => 'edit,-,refresh',
                'columns',
                // группа инструментов "Поиск"
                'search' => [
                    'items' => [
                        'help',
                        'search',
                        // инструмент "Фильтр"
                        'filter' => [
                            'form' => [
                                'cls'      => 'g-popupform-filter',
                                'width'    => 400,
                                'height'   => 'auto',
                                'action'   => Url::toMatch('grid/filter'),
                                'defaults' => ['labelWidth' => 100],
                                'items'    => [
                                    [
                                        'xtype'      => 'radio',
                                        'boxLabel'   => '#All',
                                        'name'       => 'status',
                                        'inputValue' => 0,
                                    ],
                                    [
                                        'xtype'      => 'radio',
                                        'boxLabel'   => '#Installed',
                                        'name'       => 'status',
                                        'inputValue' => 1,
                                        'checked'    => true
                                    ],
                                    [
                                        'xtype'      => 'radio',
                                        'boxLabel'   => '#None installed',
                                        'name'       => 'status',
                                        'inputValue' => 2,
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Grid.popupMenu GmJS)
        $tab->grid->popupMenu = [
            'items' => [
                [
                    'text'        => '#Language information',
                    'iconCls'     => 'g-icon-svg g-icon-m_info-circle g-icon-m_color_default',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/form/view/{id}'),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ]
            ]
        ];

        // 2-й клик по строке сетки
        $tab->grid->rowDblClickConfig = [
            'allow' => true,
            'route' => Gm::alias('@match', '/form/view/{id}')
        ];
        // выделять только 1-у строку
        $tab->grid->selModel['mode'] = 'single';
        // количество строк в сетке
        $tab->grid->store->pageSize = 50;
        // поле аудита записи
        $tab->grid->logField = 'code';
        // плагины сетки
        $tab->grid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $tab->grid->bodyCls = 'g-grid_background';

        // панель навигации (Gm.view.navigator.Info GmJS)
        $tab->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::header('{shortName}'),
            ['fieldset',
                [
                    HtmlNav::fieldLabel($this->t('Code'), '{code}'),
                    HtmlNav::fieldLabel($this->t('Full name'), '{name}'),
                    HtmlNav::fieldLabel($this->t('Country'), '{country}'),
                    HtmlNav::fieldLabel($this->t('Tag'), '{tag}'),
                    HtmlNav::fieldLabel($this->t('Slug'), '{slug}'),
                    HtmlNav::fieldLabel($this->t('Locale'), '{locale}'),
                    HtmlNav::fieldLabel(
                        $this->t('Available for site'),
                        HtmlNav::tpl($this->t('Yes') . '<tpl else>' . $this->t('No'), ['if' => 'frontend==1'])
                    ),
                    HtmlNav::fieldLabel(
                        $this->t('Available for control panel'),
                        HtmlNav::tpl($this->t('Yes') . '<tpl else>' . $this->t('No'), ['if' => 'backend==1'])
                    ),
                ]
            ],
            HtmlNav::tplIf('status=1',
                HtmlNav::widgetButton(
                    $this->t('Language information'),
                    ['route' => Gm::alias('@match', '/form/view/{id}'), 'long' => true],
                    ['title' => $this->t('Language information')]
                )
            )
        ]);

        $this->getResponse()
            // если открыто окно настройки служб (конфигурация), закрываем его
            ->meta
                ->cmdComponent('g-setting-window', 'close');
        $tab
            ->addCss('/grid.css')
            ->addRequire('Gm.view.grid.column.Switch');
        return $tab;
    }
}
