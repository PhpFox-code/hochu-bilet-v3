<?php
    namespace Backend\Modules\Statistics\Controllers;

    use Core\Config;
    use Core\Route;
    use Core\Arr;
    use Core\HTTP;
    use Core\View;
    use Core\QB\DB;
    use Core\User;
    use backend\Modules\Statistics\Models\Organizer as Model;


    class Organizer extends \Backend\Modules\Base {

        public $tpl_folder = 'Organizer';
        public $pay_statuses;
        public $seat_statuses;

        function before() {
            parent::before();
            $this->_seo['h1'] = 'Статистика по организаторам';
            $this->_seo['title'] = 'Статистика по организаторам';
            $this->setBreadcrumbs('Статистика по организаторам', 'backend/organizer/index');
            $this->limit = Config::get( 'limit_backend' );
            $this->pay_statuses = Config::get('order.pay_statuses');
            $this->seat_statuses = Config::get('order.seat_statuses');
            $this->page = (int) Route::param('page') ? (int) Route::param('page') : 1;
        }

        /*
         * Used for administrator
         *
         */
        function indexAction () {
            if (User::info()->role_id != 2) {
                HTTP::redirect('/backend/organizer/inner/'.(User::info()->id));
            }

//            Select all organizers
            $organizers = Model::getOrganizers(1, array('created_at', 'DESC'));

//            Rendering
            $this->_content = View::tpl(
                array(
                    'result' => $organizers,
                    'pageName' => 'Список всех активных организаторов',
                ),$this->tpl_folder.'/Index');
        }

        /*
         * Current Organizer statistics
         */
        function innerAction () {
            if (User::info()->role_id != 2 && User::info()->id != Route::param('id')) {
                $this->no_access();
            }

//            Select current user
            $organizer = Model::getOrganizerById(Route::param('id'), 1);
            if (!$organizer) {
                $this->no_access();
            }

            $this->_seo['h1'] = 'Отчет организатора: ' . $organizer->name;
            $this->_seo['title'] = 'Отчет организатора: ' . $organizer->name;
            $this->setBreadcrumbs('Отчет организатора: ' . $organizer->name);

//            Set filter vars
            $date_s = NULL; $date_po = NULL; $eventId = null; $status = null;
            if ( Arr::get($_GET, 'date_s') )
                $date_s = strtotime( Arr::get($_GET, 'date_s') );
            if ( Arr::get($_GET, 'date_po') )
                $date_po = strtotime( Arr::get($_GET, 'date_po') );
            if (Arr::get($_GET, 'event') != 0)
                $eventId = Arr::get($_GET, 'event');
            if (Arr::get($_GET, 'status') != 'null')
                $status = Arr::get($_GET, 'status');


            $filter = array(
                'date_s' => $date_s,
                'date_po' => $date_po,
                'status' => $status,
                'event_id' => $eventId,
                'organizer_id' => $organizer->id,
                'order' => array('created_at', 'DESC')
            );

            $posters = Model::getPosters($filter);

//            Make array with all need data
            $result = array();
            foreach ($posters as $poster) {
                $result[$poster->id]['poster'] = $poster;

                $result[$poster->id]['detailed'] = Model::getDetailed($poster);
            }

//            Rendering
            $this->_content = View::tpl(
                array(
                    'result' => $result,
                    'pay_statuses' => $this->pay_statuses,
                    'events' => DB::select()->from('afisha')->where('place_id', 'IS NOT', null)
                        ->where('organizer_id', '=', $organizer->id)->find_all(),
                    'tpl_folder' => $this->tpl_folder,
                ),$this->tpl_folder.'/Inner');
        }
    }
