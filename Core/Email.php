<?php
    namespace Core;
    
    class Email {
        
        /**
         *      Send email
         *      @param $email - E-Mail пользователя
         *      @param $subject - Заголовок письма
         *      @param $text - Содержание письма
         *      @param $_to - если 0, то отправляем пользователю от админа, а если 1 - админу от пользователя
         */
        public static function send($subject, $text, $email = NULL) {
            $from = Config::get( 'admin_email' );
            if( !$email ) {
                $to = $from;
            } else {
                $to = $email;
            }
            if (Config::get('mail.smtp')) {
                return (boolean) Email::sendBody($from, $to, $subject, $text);
            } else {
                return (boolean) Email::sendBodySimple($from, $to, $subject, $text);
            }
        }


        /**
         *  PHPMailer
         */
        public static function sendBody($from, $to, $subject, $message) {
            $mail = new \Plugins\phpMailer\PHPMailer;
            $mail->isSMTP();
            $mail->Host = Config::get('mail.host');
            $mail->SMTPAuth = Config::get('mail.auth');
            $mail->Username = Config::get('mail.username');
            $mail->Password = Config::get('mail.password');
            $mail->SMTPSecure = Config::get('mail.secure');
            $mail->Port = Config::get('mail.port');
            $mail->From = $from;
            $mail->FromName = 'Info';
            $mail->addAddress($to);
            $mail->addReplyTo($from, 'info');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
            return $mail->send();
            // $mail->ErrorInfo;
        }


        /**
         *  Simple mail
         */
        public static function sendBodySimple($from, $to, $subject, $message) {
            $to = Email::mime_header_encode($to, 'utf-8', 'utf-8'). ' <' . $to . '>';
            $subject = Email::mime_header_encode($subject, 'utf-8', 'utf-8');
            $from =  Email::mime_header_encode(Config::get('name_site'), 'utf-8', 'utf-8') . ' <' . $from . '>';
            // exit;
            $headers = "Mime-Version: 1.0\r\n";
            $headers .= "From: $from\r\n";
            $headers .= "Reply-To: $from\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            return mail($to, $subject, $message, $headers);   
        }

        public static function mime_header_encode($str, $data_charset, $send_charset) {
            if($data_charset != $send_charset) {
                $str = iconv($data_charset, $send_charset.'//IGNORE', $str);
            }
            return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
        }
        
    }