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
use Closure;
use Gm\Helper\Str;
use Gm\Db\Sql\Where;
use Gm\Backend\Languages\Exception;
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля записи языка.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Model
 * @since 1.0
 */
class GridRow extends FormModel
{
    /**
     * Если запросом зайдействован столбец "По умолчанию".
     * 
     * @var bool
     */
    public bool $isDefaultColumn = false;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                // если был изменен столбец "По умолчанию"
                if ($this->isDefaultColumn) {
                    if ($message['success']) {
                        $message['message'] = $this->module->t('Language changed by default to "{0}"', [$this->shortName]);
                    }
                    /** @var \Gm\Panel\Controller\GridController $controller */
                    $controller = $this->controller();
                    // обновить список
                    $controller->cmdReloadGrid();
                // если изменены другие столбцы
                } else {
                    if ($message['success']) {
                        $message['title'] = $this->t('Language accessibility');
                        if (isset($columns['frontend'])) {
                            $message['message'] = $this->module->t(
                                (int) $columns[FRONTEND] ? 'The language "{0}" is available for the site' : 'The language "{0}" is not available for the site',
                                [$this->shortName]
                            );
                        } else
                        if (isset($columns['backend'])) {
                            $message['message'] = $this->module->t(
                                (int) $columns[BACKEND] ? 'The language "{0}" is available for the control panel' : 'The language "{0}" is not available for the control panel',
                                [$this->shortName]
                            );
                        }
                    }
                }
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
            });
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'order'     => 'order', // порядковый номер
            'tag'       => 'tag', // тег языка
            'code'      => 'code', // код языка
            'name'      => 'name', // полное имя
            'shortName' => 'shortName', // имя языка
            'country'   => 'country', // страна
            'slug'      => 'slug', // слаг
            'locale'    => 'locale', // имя локали
            BACKEND     => BACKEND, // доступен для панели управления
            FRONTEND    => FRONTEND, // доступен для сайта
            'alternative' => 'alternative',
            // поля которые не хранятся в конфигурации языка
            'default'   => 'default' // язык по умолчанию
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate(array &$attributes): bool
    {
        // если попытка столбцу "По умолчанию" вернуть флаг в false (это не логично, 
        // но и заблокировать не возможно)
        if (isset($attributes['default']) && $attributes['default'] == 0) {
            throw new Exception\ColumnException($this->t('In the "By default" column, you can only select the default language with the switch, but not disable'));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeUpdate(array &$columns): void
    {
        $this->isDefaultColumn = isset($columns['default']);
    }

    /**
     * {@inheritdoc}
     */
    public function updateRecord(array $columns, Where|Closure|string|array $condition = null): false|int
    {
        // если клик по столбцу "По умолчанию"
        if ($this->isDefaultColumn) {
            $this->updateDefaultLanguage($this->slug);
        // если клики по другим столбцам
        } else {
            $this->frontend = Str::toBool($this->frontend);
            $this->backend = Str::toBool($this->backend);
            Gm::$app->language->available->set($this->locale, $this->getAttributes());
            Gm::$app->language->available->save();
        }
        return 1;
    }

    /**
     * Сохраняет опцию "default" конфигурации языка.
     * 
     * @param string $slug Слаг языка, например: 'ru', 'en'.
     * 
     * @return void
     */
    public function updateDefaultLanguage(string $slug): void
    {
        $service = Gm::$app->language->getObjectName();

        $language = Gm::$app->unifiedConfig->get($service, []);
        $language['default'] = $slug;

        Gm::$app->unifiedConfig->{$service} = $language;
        Gm::$app->unifiedConfig->save();
    }

    /**
     * {@inheritdoc}
     */
    public function get(mixed $identifier = null): ?static
    {
        if ($identifier === null)
            $identifier = $this->getIdentifier();
        if ($identifier) {
            $row = Gm::$app->language->available->getBy($identifier, 'code');
            if ($row) {
                $this->reset();
                $this->afterSelect();
                $this->populate($this, $row);
                $this->afterPopulate();
                return $this;
            } else
                return null;
        }
        return null;
    }
}
