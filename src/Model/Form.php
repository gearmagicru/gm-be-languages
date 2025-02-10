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
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных информации о языке.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Model
 * @since 1.0
 */
class Form extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'tag'           => 'tag',
            'code'          => 'code',
            'languageCode'  => 'languageCode',
            'countryCode'   => 'countryCode',
            'name'          => 'name',
            'shortName'     => 'shortName',
            'country'       => 'country',
            'slug'          => 'slug',
            'locale'        => 'locale',
            'translated'    => 'translated',
            'translatedFor' => 'translatedFor',
            'versionNumber' => 'versionNumber',
            'versionDate'   => 'versionDate',
            'versionAuthor' => 'versionAuthor',
            'backend'       => 'backend',
            'frontend'      => 'frontend',
            // поля которые не хранятся в конфигурации языка
            'default'   => 'default', // язык по умолчанию
            'available' => 'available' // доступен для
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function get(mixed $identifier = null): ?static
    {
        if ($identifier === null) {
            $identifier = $this->getIdentifier();
        }
        if ($identifier) {
            // среди установленных языков
            $row = Gm::$app->language->available->getBy($identifier, 'code');
            if ($row) {
                // метаинформация о языке
                $meta = Gm::$app->language->loadMeta($row['locale']);
                $row = array_merge($meta, $row);
            // среди не установленных языков
            } else {
                /** @var \Gm\Backend\Language\Helper\Helper $helper */
                $helper = $this->module->getHelper();
                $row = $helper->getBy($identifier, 'code', 'noneInstalled');
            }
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

    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        // переведено
        if ($this->translated && $this->translatedFor) {
            $this->translated = $this->translated . ' (' . $this->translatedFor .')';
        }
        // код языка
        if ($this->languageCode) {
            $this->languageCode = $this->module->t('{0} (determined from {1})', [$this->languageCode, '<a target="_blank" href="https://ru.wikipedia.org/wiki/Коды_языков">ГОСТа 7.75-97</a>']);
        }
        // кода страны
        if ($this->countryCode) {
            $this->countryCode = $this->module->t('{0} (determined from {1})', [$this->countryCode, '<a target="_blank" href="https://ru.wikipedia.org/wiki/ГОСТ_7.67">ГОСТ_7.67</a>']);
        }
        // доступен для
        $available = [];
        if ($this->backend > 0)
            $available[] = $this->t('Control panel');
        if ($this->frontend > 0)
            $available[] = $this->t('Site');
        if ($available)
            $available = implode(', ', $available);
        else
            $available = $this->t('not available to anyone');
        $this->available = $available;
    }
}
