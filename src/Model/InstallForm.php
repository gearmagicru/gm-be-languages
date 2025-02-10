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
 * Модель данных установки языка.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Model
 * @since 1.0
 */
class InstallForm extends FormModel
{
    /**
     * @const Событие, возникшее после установки языка.
     */
    const EVENT_AFTER_INSTALL = 'afterInstall';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_INSTALL, function ($language, $message, $success) {
                if ($success) {
                    /** @var \Gm\Panel\Controller\FormController $controller */
                    $controller = $this->controller();
                    // обновить список
                    $controller->cmdReloadGrid();
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
            'tag'           => 'tag',
            'code'          => 'code',
            'languageCode'  => 'languageCode',
            'countryCode'   => 'countryCode',
            'name'          => 'name',
            'shortName'     => 'shortName',
            'country'       => 'country',
            'slug'          => 'slug',
            'alternative'   => 'alternative',
            'locale'        => 'locale',
            'translated'    => 'translated',
            'translatedFor' => 'translatedFor',
            'versionNumber' => 'versionNumber',
            'versionDate'   => 'versionDate',
            'versionAuthor' => 'versionAuthor',
            BACKEND         => BACKEND,
            FRONTEND        => FRONTEND
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function get(mixed $identifier = null): ?static
    {
        if ($identifier === null)
            $identifier = $this->getIdentifier();
        if ($identifier) {
            /** @var \Gm\Backend\Language\Helper\Helper $helper */
            $helper = $this->module->getHelper();
            $row = $helper->getBy($identifier, 'code', 'noneInstalled');
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
        // идентификатор
        $this->iCode = $this->code;
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

    /**
     * {@inheritdoc}
     */
    public function afterValidate(bool $isValid): bool
    {
        $mask = $this->maskedAttributes();
        $options = array_values($mask);
        foreach ($options as $name) {
            if (!isset($this->attributes[$name]) || $this->attributes[$name] === null) {
                $this->addError($this->errorFormatMsg($this->t('option is required and must be set'), $name));
                return false;
            }
        }

        // альтернативная локализация если файлы устанавлеваемого языка отсутствуют
        $alternative = $this->attributes['alternative'];
        /** @var array|null $language */
        $language = Gm::$app->language->available->getBy($alternative, 'locale');
        // проверяем, есть ли доступная альтернатива
        if (empty($language)) {
            $this->addError($this->errorFormatMsg($this->t('option is required and must be set'), 'alternative ("' . $alternative . '" not exists)'));
            return false;
        }
        return $isValid;
    }

    /**
     * Возвращает сообщение полученное при установки языка
     * события {@see EVENT_AFTER_INSTALL} метода {@see afterInstall()}.
     *
     * @param bool $success Если true, язык успешно установлен.
     * @param array $result Конфигурация устанавливаемого языка.
     * 
     * @return array Сообщение имеет вид:
     *     [
     *         "success" => true,
     *         "message" => "Successfully installed",
     *         "title"   => "Installation language",
     *         "type"    => "accept"
     *     ]
     */
    public function installMessage(array $result, bool $success): array
    {
        if ($success) {
            $type     = 'accept';
            $message  = $this->module->t('Successfully installed "{0}" language', [$result['shortName'] . ' (' . $result['country'] . ')']);
        } else {
            $type     = 'error';
            $message  = $this->t('Cannot install the language you selected');
        }
        return [
            'success'  => $success,
            'message'  => $message,
            'title'    => $this->t('Installation language'),
            'type'     => $type
        ];
    }

    /**
     * Этот событие вызывается после установки языка.
     * 
     * @param array $language Конфигурация устанавливаемого языка.
     * 
     * @return void
     */
    public function afterInstall(array $language, bool $success): void
    {
        $this->trigger(
            self::EVENT_AFTER_INSTALL, 
            [
                'language' => $language, 
                'message'  => $this->installMessage($language, $success), 
                'success'  => $success
            ]
        );
    }

    /**
     * Добавить язык в установленные.
     * 
     * @return bool
     */
    public function install(): bool
    {
        $language = $this->getAttributes();
        Gm::$app->language->available->set($this->locale, $language);
        Gm::$app->language->available->save();
        $this->afterInstall($language, true);
        return true;
    }
}
