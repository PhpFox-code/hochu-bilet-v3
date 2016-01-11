<?php
    namespace Backend\Modules\MailTemplates\Controllers;

    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\Common;
    use Core\View;
    use Core\QB\DB;

    class MailTemplates extends \Backend\Modules\Base {

        public $tpl_folder = 'MailTemplates';
        public $tablename  = 'mail_templates';

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Шаблоны писем';
            $this->_seo['title'] = 'Шаблоны писем';
            $this->setBreadcrumbs('Шаблоны писем', 'backend/'.Route::controller().'/index');
        }

        function indexAction () {
            $result = DB::select()->from($this->tablename)->order_by('id')->find_all();

            $this->_filter = View::widget( array(), 'Filter/Pages' );
            $this->_toolbar = View::widget( array(), 'Toolbar/List' );

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ),$this->tpl_folder.'/Index');
        }

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['status'] = Arr::get( $_POST, 'status', 0 );
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/'.Route::controller().'/edit/'.(int) Route::param('id'));
                } else {
                    Message::GetMessage(0, 'Не удалось изменить данные!');
                }

                $post['id'] = Arr::get($_POST, 'id');
                $result = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', (int) Route::param('id'))->find();
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );

            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/edit/'.(int) Route::param('id'));

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Form');
        }

    }