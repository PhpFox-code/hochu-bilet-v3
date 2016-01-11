<?php
    namespace Backend\Modules\Subscribe\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Widgets;
    use Core\Message;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\QB\DB;
    use Core\Pager\Pager;
    use Core\Email;
    use Core\Common;

    class Subscribe extends \Backend\Modules\Base {

        public $tpl_folder = 'Subscribe';
        public $tablename  = 'subscribe_mails';
        public $limit;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Рассылка писем';
            $this->_seo['title'] = 'Рассылка писем';
            $this->setBreadcrumbs('Рассылка писем', 'backend/'.Route::controller().'/index');
            $this->limit = Config::get('limit_backend');
        }

        function indexAction () {
            $date_s = NULL; $date_po = NULL; $status = NULL;
            if ( Arr::get($_GET, 'date_s') ) { $date_s = strtotime( Arr::get($_GET, 'date_s') ); }
            if ( Arr::get($_GET, 'date_po') ) { $date_po = strtotime( Arr::get($_GET, 'date_po') ); }

            $page = (int) Route::param('page') ? (int) Route::param('page') : 1;
            $count = DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename);
            if( $date_s ) { $count->where( 'created_at', '>=', $date_s ); }
            if( $date_po ) { $count->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            $count = $count->count_all();

            $result = DB::select()->from($this->tablename);
            if( $date_s ) { $result->where( 'created_at', '>=', $date_s ); }
            if( $date_po ) { $result->where( 'created_at', '<=', $date_po + 24 * 60 * 60 - 1 ); }
            $result = $result->order_by('id', 'DESC')->limit($this->limit)->offset(($page - 1) * $this->limit)->find_all();
            $pager = Pager::factory( $page, $count, $this->limit )->create();

            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                    'count' => DB::select(array(DB::expr('COUNT(id)'), 'count'))->from($this->tablename)->count_all(),
                    'pager' => $pager,
                    'pageName' => 'Рассылка писем',
                ), $this->tpl_folder.'/Index');
        }

        function sendAction () {
            $emails = array();
            $list = array();
            if ( $_POST ) {
                $post = $_POST['FORM'];
                $subscribers = DB::select('email', 'hash', 'name')->from('subscribers')->where('status', '=', 1)->find_all();
                foreach( $subscribers AS $obj ) {
                    if( filter_var( $obj->email, FILTER_VALIDATE_EMAIL ) AND !in_array( $obj->email, $emails ) ) {
                        $emails[] = $obj;
                        $list[] = $obj->email;
                    }
                }
                if( !trim(Arr::get($post, 'subject')) ) {
                    Message::GetMessage(0, 'Поле "Тема" не может быть пустым!');
                } else if(!trim(Arr::get($post, 'text'))) {
                    Message::GetMessage(0, 'Поле "Содержание" не может быть пустым!');
                } else if( empty( $emails ) ) {
                    Message::GetMessage(0, 'Список выбраных E-Mail для рассылки пуст!');
                } else {
                    $data = $post;
                    $data['count_emails'] = count( $list );
                    $data['emails'] = implode( ';', $list );

                    $res = Common::insert($this->tablename, $data)->execute();

                    foreach( $emails AS $obj ) {
                        $link = 'http://' . Arr::get( $_SERVER, 'HTTP_HOST' ) . '/unsubscribe/hash/' . $obj->hash;
                        $from = array( '{{unsubscribe}}', '{{user_name}}', '{{site}}', '{{date}}' );
                        $to = array( $link, $obj->name, Arr::get( $_SERVER, 'HTTP_HOST' ), date('d.m.Y') );
                        $message = str_replace( $from, $to, Arr::get( $post, 'text' ) );
                        $subject = str_replace( $from, $to, Arr::get( $post, 'subject' ) );
                        if( !Config::get('main.cron') ) {
                            Email::send( $subject, $message, $obj->email );
                        } else {
                            $data = array(
                                'subject' => $subject,
                                'text' => $message,
                                'email' => $obj->email,
                            );
                            $res = Common::insert(Config::get('main.tableCron'), $data)->execute();
                        }
                    }
                    Message::GetMessage(1, 'Письмо успешно разослано '.$data['count_emails'].' подписчикам!');
                    HTTP::redirect('backend/'.Route::controller().'/'.Route::action());
                }
                $result = Arr::to_object( $post );
            } else {
                $result = Arr::to_object( array( 'subscribers' => 1 ) );
            }

            $this->_toolbar = Widgets::get( 'Toolbar/Subscribe' );

            $this->_seo['h1'] = 'Отправка письма';
            $this->_seo['title'] = 'Отправка письма';
            $this->setBreadcrumbs('Отправка письма', 'backend/'.Route::controller().'/add');

            $this->_content = View::tpl(
                array(
                    'obj' => $result,
                    'tpl_folder' => $this->tpl_folder,
                    'tablename' => $this->tablename,
                ), $this->tpl_folder.'/Send');
        }
        
    }