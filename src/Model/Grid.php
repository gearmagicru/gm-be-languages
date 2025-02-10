<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Languages\Model;

use Gm;
use Gm\Helper\Url;
use Gm\Panel\Data\Model\ArrayGridModel;

/**
 * Модель данных списка языков.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Model
 * @since 1.0
 */
class Grid extends ArrayGridModel
{
    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SET_FILTER, function ($filter) {
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'fields' => [
                ['id'],
                ['code'],
                ['shortName'],
                ['name'],
                ['country'],
                ['tag'],
                ['slug'],
                ['locale'],
                ['default'],
                [FRONTEND],
                ['urlFrontend'],
                [BACKEND],
                ['urlBackend'],
                ['status']
            ],
            'filter' => [
                'status' => ['operator' => '=']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     * 
     * @return \Gm\Backend\Languages\Helper\Helper
     */
    public function getRowsBuilder()
    {
        return $this->module->getHelper();
    }

    /**
     * {@inheritdoc}
     * 
     * @return array
     */
    public function buildQuery($builder): array
    {
        $status = (int) ($this->directFilter ? ($this->directFilter['status']['value'] ?? 0) : 1);
        switch ($status) {
            // все языки (установленные + не установленные)
            case 0: 
                $this->directFilterSize = 0; // чтобы не проверять значения
                return $builder->getAllLanguages();

            // установленные языки
            case 1: 
                return $builder->getInstalledLanguages();

            // не установленные языки
            case 2: 
                return $builder->getNoneInstalledLanguages();
        }
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeFetchRow(mixed $row, int|string $rowKey): ?array
    {
        if (isset($row['slug'])) {
            $row['default']  = $row['slug'] == Gm::$app->language->default ? 1 : 0;
        }
        // не установлен
        if ($row['status'] == 2) {
            $row[BACKEND]  = -1;
            $row[FRONTEND] = -1;
            $row['default']  = -1;
            $row['urlFrontend'] = '#';
            $row['urlBackend']  = '#';
        }

        return [
            'id'        => $row['code'],
            'code'      => $row['code'],
            'shortName' => $row['shortName'],
            'name'      => $row['name'],
            'country'   => $row['country'],
            'tag'       => $row['tag'],
            'slug'      => $row['slug'],
            'locale'    => $row['locale'],
            'default'   => $row['default'],
            FRONTEND    => (int) $row[FRONTEND],
            'urlFrontend' => Url::to(['', 'langSlug' => $row['slug']]),
            BACKEND       => (int) $row[BACKEND],
            'urlBackend'  => Url::toBackend(['workspace', 'langSlug' => $row['slug']]),
            'status'      => $row['status'],
            'popupMenuTitle' => $row['name']
        ];
    }
}
