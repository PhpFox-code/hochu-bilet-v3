<?php
    namespace Backend\Modules\Seo\Controllers;

    use Core\Route;
    use Core\Widgets;
    use Core\View;
    use Core\Message;
    use Core\HTTP;
    use Core\QB\DB;
    use Core\Arr;
    use Core\Common;

    class Templates extends \Backend\Modules\Base {
        
        public $tpl_folder = 'Seo';
        public $tablename = 'seo';  

        function editAction () {
            if ($_POST) {
                $post = $_POST['FORM'];
                $post['updated_at'] = time();
                $res = Common::update($this->tablename, $post)->where('id', '=', Arr::get($_POST, 'id'))->execute();
                if($res) {
                    Message::GetMessage(1, 'Вы успешно изменили данные!');
                    HTTP::redirect('backend/seo/'.Route::controller().'/'.Route::param('id'));
                } else {
                    Message::GetMessage(0, 'Не удалось изменить данные!');
                }
                $post['id'] = Arr::get($_POST, 'id');
                $result     = Arr::to_object($post);
            } else {
                $result = DB::select()->from($this->tablename)->where('id', '=', (int) Route::param('id'))->find();
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Edit' );

            $this->_seo['h1'] = 'Редактирование';
            $this->_seo['title'] = 'Редактирование';
            $this->setBreadcrumbs('Редактирование', 'backend/'.Route::controller().'/edit/'.Route::param('id'));

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Form');
        }
        
    } 