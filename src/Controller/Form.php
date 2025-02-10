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
use Gm\Panel\Widget\InfoWindow;
use Gm\Panel\Controller\InfoController;

/**
 * Контроллер формы информации о языке.
 *   
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Controller
 * @since 1.0
 */
class Form extends InfoController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'Form';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router->route = Gm::alias('@match', '/form');
        $window->form->bodyPadding = 10;
        $window->form->autoScroll = true;
        $window->form->loadJSONFile('/form', 'items');

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'install';
        $window->width = 650;
        $window->autoHeight = true;
        $window->layout = 'fit';
        $window->resizable = false;
        $window->title = $this->t('{form.title}');
        $window->titleTpl = sprintf('%s <span>%s</span>', $this->t('{form.title}'),  $this->t('{form.subtitle}'));
        $window->iconCls = 'g-icon g-icon_size_32 g-icon-svg gm-languages__icon-info';
        return $window;
    }
}
