<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Languages\Controller;

use Gm\Panel\Widget\EditWindow;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер формы главного меню панели управления.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Controller
 * @since 1.0
 */
class Manually extends FormController
{
    /**
     * {@inheritDoc}
     */
    protected string $defaultModel = 'ManuallyForm';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var \Gm\Backend\Language\Model\ManuallyForm $form */
        $form = $this->getModel($this->defaultModel);

        /** @var EditWindow $window */
        $window = parent::createWidget();

       // панель формы (Gm.view.form.Panel GmJS)
       $window->form->autoScroll = true;
       $window->form->bodyPadding = 10;
       $window->form->loadJSONFile('/form-manually', 'items', [
           '@storeData' => $form->getAllLocales()
       ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->width = 550;
        $window->height = 440;
        $window->layout = 'fit';
        $window->resizable = false;
        return $window;
    }
}
