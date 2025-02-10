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
 * Модель данных удаления языка.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Model
 * @since 1.0
 */
class UninstallForm extends FormModel
{
    /**
     * @var string Событие, возникшее после удаления языка {@see uninstall()}.
     */
    public const EVENT_AFTER_UNINSTALL = 'afterUninstall';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_UNINSTALL, function ($language, $message, $success) {
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
    public function get(mixed $identifier = null): ?static
    {
        if ($identifier === null)
            $identifier = $this->getIdentifier();
        if ($identifier) {
            /** @var \Gm\Backend\Language\Helper\Helper $helper */
            $helper = $this->module->getHelper();
            $row = $helper->getBy($identifier, 'code', 'installed');
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
     * Возвращает сообщение полученное после удаления языка
     * события {@see EVENT_AFTER_UNINSTALL} метода {@see afterUninstall()}.
     *
     * @param bool $success Если true, язык успешно удалён.
     * @param array $result Конфигурация удаляемого языка.
     * 
     * @return array Сообщение имеет вид:
     *     [
     *         "success" => true,
     *         "message" => "Uninstall uninstalled",
     *         "title"   => "Installation language",
     *         "type"    => "accept"
     *     ]
     */
    public function UninstallMessage(array $result, bool $success): array
    {
        if ($success) {
            $type     = 'accept';
            $message  = $this->module->t('Successfully uninstalled "{0}" language', [$result['shortName'] . ' (' . $result['country'] . ')']);
        } else {
            $type     = 'error';
            $message  = $this->t('Unable to uninstall language');
        }
        return [
            'success'  => $success,
            'message'  => $message,
            'title'    => $this->t('Uninstall language'),
            'type'     => $type
        ];
    }

    /**
     * Этот событие вызывается после удаления языка.
     * 
     * @param array $language Конфигурация удаляемого языка.
     * @param bool $success Если true, язык успешно удалён.
     * 
     * @return void
     */
    public function afterUninstall(array $language, bool $success): void
    {
        $this->trigger(
            self::EVENT_AFTER_UNINSTALL, 
            [
                'language' => $language, 
                'message' => $this->uninstallMessage($language, $success), 
                'success' => $success
            ]
        );
    }

    /**
     * Удаления языка из установленных.
     * 
     * @return bool|string
     */
    public function uninstall(): bool|string
    {
        /** @var \Gm\Language\Language */
        $language = Gm::$app->language;
        /** @var array $dependencies Зависимые языки от удаляемого  */
        $dependencies = $language->getDependencies($this->locale, 'alternative');
        // если есть зависимость
        if ($dependencies) {
            return $this->module->t(
                'The language cannot be removed because it depends on: {0}', 
                [$language->getShortNames(', ', $dependencies)]
            );
        }

        $error = false;
        // если удаляемый язык, является языком по умолчанию
        if ($language->code == $this->code) {
            $error = 'Unable to perform language deletion (your chosen language is the default language)';
        }
        if (!$error) {
            $language->available->remove($this->locale);
            $language->available->save();
        }
        $this->afterUninstall($this->getAttributes(), !$error);
        return $error === false ? true : $error;
    }
}
