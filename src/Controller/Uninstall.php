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
use Gm\Panel\Http\Response;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер удаления выбранного языка.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Controller
 * @since 1.0
 */
class Uninstall extends FormController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'UninstallForm';

    /**
     * Действие "package" удаляет выбранные языки.
     * 
     * @return Response
     */
    public function packageAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var \Gm\Backend\Language\Model\UninstallForm $model модель данных */
        $model = $this->getModel($this->defaultModel);
        if ($model === false) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', [$this->defaultModel]));
            return $response;
        }
        // проверка идентификатора в запросе
        if (!$model->hasIdentifier()) {
            $response
                ->meta->error($this->t('Unable to uninstall language'));
            return $response;
        }
        /** @var \Gm\Backend\Language\Model\UninstallForm $form язык по идентификатору запроса */
        $form = $model->get();
        if ($form === null) {
            $response
                ->meta->error($this->t('Unable to delete the language (your chosen language is not installed)'));
            return $response;
        }
        // удаление языка
        if (($error = $form->uninstall()) !== true) {
            $response
                ->meta->error($this->t($error));
            return $response;
        }
        return $response;
    }
}
