<?php
    namespace Core;
    use Core\QB\DB;

/*
* core_2011
*/

// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------

class Message {

    static $layout = NULL;

    //find type output message
    public function __construct(){
        if(self::$layout === NULL){
            self::$layout = DB::select('zna')->from('config')->where('key', '=', DB::expr('"message_output"'))->as_object()->execute()->current()->zna;
            // self::$layout = Config::get('message_output');
        }
    }

	static function GetMessage ($type, $message) {
        $message = addslashes($message);

        if(self::$layout === NULL) new self;

        switch($type){
            case 1:
            $type='success';
            break;

            case 2:
            $type='danger';
            break;

            case 3:
            $type='information';
            break;

            default:
            $type='warning';
        }

        if( APPLICATION ) {
            $_SESSION['GLOBAL_MESSAGE']='<script type="text/javascript">
                                        $(document).ready(function(){
                                            $(document).alert2({
                                                message: "'.$message.'",
                                                headerCOntent: false,
                                                footerContent: false,
                                                typeMessage: "'.$type.'"
                                            });
                                        });
                                    </script>';
        } else {
            $_SESSION['GLOBAL_MESSAGE']='<script type="text/javascript">
                                        $(document).ready(function(){
                                            $(window).load(function(){
                                                noty({
                                                    layout: \''.self::$layout.'\',
                                                    text: \''.$message.'\',
                                                    timeout: 5000,
                                                    type: \''.$type.'\'
                                                });
                                            });
                                        });
                                    </script>';
        }
		
		
		//echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
		
		
	}	
	
	static function GetMessage2 ($message) {

        if(self::$layout === NULL) new self;

        $_SESSION['GLOBAL_MESSAGE']='<script type="text/javascript">
                                        $(document).ready(function(){
                                            $(window).load(function(){
                                                noty({
                                                    layout: \''.self::$layout.'\',
                                                    text: \''.$message.'\',
                                                    timeout: 5000
                                                });
                                            });
                                        });
                                    </script>';

	}
	
}


?>