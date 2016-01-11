<?php
    namespace Modules\Unsubscribe\Controllers;

    use Core\QB\DB;
    use Core\Route;
    use Core\HTTP;
    use Core\Message;

    class Unsubscribe extends \Modules\Base {

        public function indexAction() {
            $subscriber = DB::select()->from('subscribers')->where('hash', '=', Route::param('hash'))->where('status', '=', 1)->as_object()->execute()->current();
            if( !$subscriber ) {
                Message::GetMessage(0, 'Вы не подписаны на рассылку с нашего сайта!');
                HTTP::redirect('/');
            }
            DB::update('subscribers')->set(array('status' => 0, 'updated_at' => time()))->where('id', '=', $subscriber->id)->execute();
            Message::GetMessage(1, 'Вы успешно отписались от рассылки новостей с нашего сайта!');
            HTTP::redirect('/');
        }

    }