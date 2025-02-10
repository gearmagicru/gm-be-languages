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
use Gm\Panel\Data\Model\Combo\ComboModel;

/**
 * Модель данных выпадающего списка языков.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Model
 * @since 1.0
 */
class LanguageCombo extends ComboModel
{
    /**
     * {@inheritdoc}
     */
    public function getRows(): array
    {
        $languages = Gm::$app->language->available->getAll();
        foreach ($languages as $language) {
             $rows[] = [
                $language['code'],
                $language['shortName'] . ' (' . $language['slug'] . ')'
             ];
        }
        $rows = $this->afterFetchRows($rows);
        return [
            'total' => sizeof($rows),
            'rows'  => $rows
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function noneRow(): array
    {
        return [0, Gm::t(BACKEND, '[None]')];
    }
}
