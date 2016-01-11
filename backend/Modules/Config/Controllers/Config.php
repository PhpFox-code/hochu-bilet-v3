<?php
    namespace Backend\Modules\Config\Controllers;

    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\HTTP;
    use Core\View;
    use Core\Common;
    use Core\QB\DB;

    class Config extends \Backend\Modules\Base {

        public $tpl_folder = 'Config';
        public $tablename  = 'config';

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Настройки сайта';
            $this->_seo['title'] = 'Настройки сайта';
            $this->setBreadcrumbs('Настройки сайта', 'backend/'.Route::controller().'/index');
        }

        function editAction () {
            if ($_POST) {
                foreach($_POST['FORM'] as $key => $value) {
                    $res = Common::update($this->tablename, array('zna' => $value))->where('id', '=', $key)->execute();
                }
                Message::GetMessage(1, 'Вы успешно изменили данные!');
                HTTP::redirect( 'backend/'.Route::controller().'/edit' );
            }
            $result = DB::select()->from($this->tablename)->where('status', '=', 1)->order_by('sort')->find_all();

            $this->_toolbar = Widgets::get( 'Toolbar/EditSaveOnly' );

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder
                ), $this->tpl_folder.'/Edit');
        }
    }