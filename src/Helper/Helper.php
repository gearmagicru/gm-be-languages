<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Languages\Helper;

use Gm;
use Gm\Stdlib\BaseObject;

/**
 * Модель помощника модуля.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Helper
 * @since 1.0
 */
class Helper extends BaseObject
{
    /**
     * Установленные языки.
     * 
     * @see Helper::getInstalledLanguages()
     * 
     * @var array
     */
    protected array $installedLanguages;

    /**
     * Не установленные языки.
     * 
     * @see Helper::getNoneInstalledLanguages()
     * 
     * @var array
     */
    protected array $noneInstalledLanguages;

    /**
     * Возвращает не установленные языки.
     * 
     * @return array
     */
    public function getNoneInstalledLanguages(): array
    {
        if (isset($this->noneInstalledLanguages)) {
            return $this->noneInstalledLanguages;
        }

        $this->noneInstalledLanguages = [];
        $items = new \DirectoryIterator(Gm::$app->language->path);
        $languages = Gm::$app->language->available->getAll();
        foreach ($items as $item)
        {
            if($item->isDot()) continue;
            if($item->isDir()) {
                $localeName = $item->getFilename();
                $filename = Gm::$app->language->path . "/$localeName/.language.php";
                if (file_exists($filename)) {
                    $meta = Gm::$app->language->loadMeta($localeName);
                    // если есть опция локали и ее нет в установленных языках
                    if (!isset($meta['locale']) || isset($languages[$meta['locale']])) continue;
                    $meta['status'] = 2;
                    $this->noneInstalledLanguages[] = $meta;
                }
            }
        }
        return $this->noneInstalledLanguages;
    }

    /**
     * Возвращает установленные языки.
     * 
     * @return array
     */
    public function getInstalledLanguages(): array
    {
        if (isset($this->installedLanguages)) {
            return $this->installedLanguages;
        }

        $this->installedLanguages = [];
        $languages = Gm::$app->language->available->getAll();
        foreach($languages as $language) {
            $language['status'] = 1;
             $this->installedLanguages[] = $language;
        }
        return $this->installedLanguages;
    }

    /**
     * Возвращает все (установленные + не установленные) языки.
     * 
     * @return array
     */
    public function getAllLanguages(): array
    {
        return array_merge($this->getNoneInstalledLanguages(), $this->getInstalledLanguages());
    }

    /**
     * Возвращает языки по указанным параметрам.
     * 
     * @param mixed $value Значение параметра для поиска.
     * @param string $option Название параметра.
     * @param string $where Поиск среди установленных (install) или не установленных 
     *     (noneInstalled). Для всех языков (all).
     * 
     * @return array|null
     */
    public function getBy(mixed $value, string $option, string $where = 'all'): ?array
    {
        switch ($where) {
            case 'installed': $languages = $this->getInstalledLanguages(); break;

            case 'noneInstalled': $languages = $this->getNoneInstalledLanguages(); break;

            case 'all': $languages = $this->getAllLanguages(); break;

            default:
                $languages = [];
        }

        foreach ($languages as $language) {
            if (!isset($language[$option])) continue;
            if ($language[$option] == $value) {
                return $language;
            }
        }
        return null;
    }
}
