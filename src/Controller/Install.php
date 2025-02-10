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
use Gm\Panel\Helper\ExtForm;
use Gm\Panel\Widget\EditWindow;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер установки выбранного языка.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Languages\Controller
 * @since 1.0
 */
class Install extends FormController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'InstallForm';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var EditWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->router->route = Gm::alias('@match', '/install');
        $window->form->router->state = $window->form::STATE_CUSTOM;
        $window->form->router->rules['add'] = '{route}/package';
        $window->form->bodyPadding = 10;
        $window->form->buttons = ExtForm::buttons([
            'info',
            'add' => [
                'iconCls' => 'g-icon-svg g-icon_size_14 g-icon-m_save-1',
                'text'    => $this->t('Install')
            ],
            'cancel'
        ]);
        $window->form->defaults = [
            'labelWidth' => 100,
            'labelAlign' => 'right'
        ];
        $window->form->loadJSONFile('/form-install', 'items');

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->title = '#{install.title}';
        $window->titleTpl = '#{install.titleTpl}';
        $window->ui = 'install';
        $window->iconCls = 'g-icon-svg g-icon_language-install';
        $window->width = 500;
        $window->height = 600;
        $window->layout = 'fit';
        return $window;
    }

    /**
     * {@inheritdoc}
     */
    public function dataAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var \Gm\Backend\Language\Model\InstallForm $model модель данных */
        $model = $this->getModel($this->defaultModel);
        if ($model === false) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"',[$this->defaultModel]));
            return $response;
        }
         /** @var \Gm\Backend\Language\Model\InstallForm $form запись по идентификатору в запросе */
        $form = $model->get();
        if ($form === null) {
            $response
                ->meta
                    ->cmdComponent($this->module->viewId('window'), 'close')
                    ->error($this->t('The language you have selected is already installed or it is not among the installed ones'));
            return $response;
        }
        // предварительная обработка перед возвратом ёё атрибутов
        $form->processing();
        return $response->setContent($form->getAttributes());;
    }

    /**
     * Действие "package" установливает выбранный язык.
     * 
     * @return Response
     */
    public function packageAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var \Gm\Http\Request $request */
        $request = Gm::$app->request;

        /** @var \Gm\Backend\Languages\Model\InstallForm $model модель данных */
        $model = $this->getModel($this->defaultModel);
        if ($model === false) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', [$this->defaultModel]));
            return $response;
        }
        $code = $request->getPost('code');
        if (empty($code)) {
            $response
                ->meta->error(Gm::t(BACKEND, 'No data to perform action'));
            return $response;
        }
        /** @var \Gm\Backend\Languages\Model\InstallForm $form запись по идентификатору в запросе */
        $form = $model->get($code);
        if ($form === null) {
            $response
                ->meta->error(Gm::t(BACKEND, 'No data to perform action'));
            return $response;
        }
        // валидация атрибутов модели
        if (!$model->validate()) {
            $response
                ->meta->error($this->t('Language installation error: {0}', [$model->getError()]));
            return $response;
        }
        // установка языка
        if (!$form->install()) {
            $response
                ->meta->error($this->t('Cannot install the language you selected'));
            return $response;
        }
        return $response;
    }
}
