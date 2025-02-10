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
class ManuallyForm extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'baseLanguage' => 'baseLanguage',
            'language'     => 'language'
        ];
    }

    public function getAllLocales()
    {
        $iso = Gm::$app->locale->getISO();
        $locales = Gm::$app->locale->getSupportedLocales(true);
        // если нет расширения PHP intl или локали не поддерживаются,
        // то все допустимые локали из ISO
        if (empty($locales)) {
            $locales = $iso->locales->getAll();
        }
        // является ли текущий язык русским
        $isoInfo = Gm::$app->language->getISOInfo();
        $isLanguageRu = $isoInfo !== null && $isoInfo['language'] === 'ru';

        $rows = [];
        foreach ($locales as $locale => $info) {
            if (empty($info) || empty($info['numeric']) || empty($info['name'])) continue;
            // имя скрипта
            $infoScript = $info['script'] ?? '';
            $scriptName = '';
            if ($infoScript) {
                $script     = $iso->scripts->get($infoScript);
                $scriptName = $infoScript;
                if ($script) {
                    if ($script['alias'])
                        $scriptName = $script['alias'];
                    if ($isLanguageRu && $script['rusName'])
                        $scriptName = $script['rusName'];
                }
            }
            // язык и регион (англ.)
            $infoName = $info['name'] ?? '';
            // язык и регион (рус.)
            $infoRusName = $info['rusName'] ?? '';
            // язык и регион (родной)
            $infoNatName = $info['nativeName'] ?? '';
            // имя языка
            $language = '';
            if ($infoName && $infoName['language'])
                $language =  $infoName['language'];
            if ($infoNatName && $infoNatName['language'])
                $language =  $infoNatName['language'];
            if ($isLanguageRu && $infoRusName && $infoRusName['language'])
                $language =  $infoRusName['language'];
            // имя региона
            $region = '';
            if ($infoName && $infoName['region'])
                $region =  $infoName['region'];
            if ($infoNatName && $infoNatName['region'])
                $region =  $infoNatName['region'];
            if ($isLanguageRu && $infoRusName && $infoRusName['region'])
                $region =  $infoRusName['region'];
            $rows[] = [
                'id'     => $info['numeric'],
                'name'   => $language,
                'region' => $region ? ' / ' . $region : '',
                'script' => $scriptName ? $scriptName . ', ' : '',
                'locale' => str_replace('_', '-', $locale)
            ];
        }
        // сортировка по имени языка
        $name = array_column($rows, 'name');
        array_multisort($name, SORT_ASC, $rows);
        $result = [];
        foreach ($rows as $row) {
            $result[] = [$row['id'], $row['name'], $row['region'], $row['script'], $row['locale']];
        }
        return $result;
    }
}
