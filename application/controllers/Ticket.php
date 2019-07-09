<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Ticket extends REST_Controller {
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        parent::__construct();
        $this->load->model('ticket_model','tickets');
        $this->load->model('user_model','user');
        $this->load->model('diagnose_model','diagnose');
        $this->load->model('solution_model','solution');
    }

    public function index_get() {
        $id = $this->get('id');
        
        if($id == ''){
            $tickets = $this->tickets->get();
        }else{
            $tickets = $this->tickets->get($id);
        }

        if (!is_null($tickets)) {
            $this->response($tickets, 200);
        } else {
            $this->response(array('error' => 'There are no data in database...'), 404);
        }
    }

    public function index_post() {
        if (!$this->post('ticket')) {
            $this->response(null, 400);
        }

        $ticket = $this->post('ticket');
        $id = $this->tickets->save($this->post('ticket'));
        
        if (!is_null($id)) {
            $this->send_mail_new_ticket($ticket,$id);
            $this->response(array('id' => $id), 200);
        } else {
            $this->response(array('error', 'Fail to post ticket...'), 400);
        }
    }

    public function index_put() {
        if (!$this->put('ticket')) {
            $this->response("No Data Sent", 400);
        }
        
        $ticket = $this->put('ticket');
        $update = $this->tickets->update($this->put('ticket'));

        if (!is_null($update)) {
            if($ticket['stat'] == 'C'){
                $this->send_mail_completed_ticket($ticket,$ticket['id_ticket']);
            }
            $this->response(array('response' => 'Ticket Updated!'), 200);
        } else {
            $this->response(array('error', 'Fail to update ticket data...'.$update), 400);
        }
    }

    public function index_delete($id) {
        if (!$id) {
            $this->response(null, 400);
        }

        $delete = $this->tickets->delete($id);

        if (!is_null($delete)) {
            $this->response(array('response' => 'Ticket Canceled!'), 200);
        } else {
            $this->response(array('error', 'Fail to cancel ticket...'), 400);
        }
    }

    function send_mail_new_ticket($msg,$id){
        $email = "testkktphp@gmail.com";
        //$mod = "ramadany@kariangauterminal.co.id";
        $mod = $this->user->getModEmail();
        $user = $this->user->getUserEmail($msg['id_user']);

        $to = $user['email'];
        $cc = $mod['email'];

        $date = $this->getDatetimeNow();
        $subject = 'New Ticket #'.$id.' from '.$msg['user'].' ( '.$date.' )';

        //$headers = "From: " . strip_tags($_POST['req-email']) . "\r\n";
        //$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
        $headers  = "From: IT Helpdesk PT KKT\r\n";
        $headers .= "Reply-To: ". strip_tags($email) . "\r\n";
        $headers .= "CC: ".$cc."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $message = '<html><body>';
        $message .= "<p><strong>New Ticket From ".$msg['user']."</strong></p>\n";
        $message .= "<p><strong>Ticket ID  : #".$id."</strong></p>\n";
        $message .= "<p><strong>Problems : </strong></p>\n";
        $message .= $msg['problems']."<br>";
        $message .= "<br><br><br><i>
                        Note : <br>
                        This email is sent automatically. Don't reply to this email.
                    </i>";
        $message .= '</body></html>';

        $message = wordwrap($message,76);
        mail($to,$subject,$message,$headers);

        return null;
    }

    function send_mail_completed_ticket($msg,$id){
        $i = 0;
        $j = 0;
        $email = "testkktphp@gmail.com";
        //$mod = "ramadany@kariangauterminal.co.id";
        $user = $this->user->getUserEmail($msg['id_user']);
        $mod = $this->user->getModEmail();
        $pic = $this->user->getPICEmail($msg['id_pic']);
        $trello = '';
        $to = $user['email'];
        $cc = $mod['email'].','.$pic['email'];

        $diagnose = $this->diagnose->getDiagnoseData($id);
        $solution = $this->solution->getSolutionData($id);
        $pic_name = $this->user->getPICName($msg['id_pic']);
        $diagnose_str = "";
        $solution_str = "";
        
        if($diagnose != NULL || $diagnose != ''){
            foreach($diagonse as $data){
                $i++;
                $diagnose_str .= "\r\n".$i.". ".$data['str_diagnose'];
            }
        } 

         if($solution != NULL || $solution != ''){
            foreach($solution as $data){
                $j++;
                $solution_str .= "\r\n".$j.". ".$data['str_diagnose'];
            }
        }

        $date = $this->getDatetimeNow();
        $subject = 'Ticket #'.$id.' completed'.' ('.$date.')';

        $headers  = "From: IT Helpdesk PT KKT\r\n";
        $headers .= "Reply-To: ". strip_tags($email) . "\r\n";
        $headers .= "CC: ".$cc."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                
        $message = '<html><body>';
        $message.= '<h3>Your ticket completed : </h3><br>';
        $message.= "<p>Ticket ID : #".$id." </p><br>";
        $message .= "<p>Problems : </p><br>";
        $message .= $msg['problems']."<br>";
        $message .= "<p>Diagnose : </p><br>";
        $message .= $diagnose_str."<br>";
        $message .= "<p>Solutions : </p><br>";
        $message .= $solution_str."<br>";
        $message .= "<p>PIC : </p><br>";
        $message .= $pic_name['username']."<br>";
        $message.= "<i>
                        Note : <br>
                        This email is sent automatically. Don't reply to this email.
                    </i>";
        $message .= '</body></html>';

        $message = wordwrap($message,76);
        mail($to,$subject,$message,$headers);

        return null;
    }

    function send_mail_pic($msg,$id){
        $i = 0;
        $j = 0;
        $email = "testkktphp@gmail.com";
        //$mod = "ramadany@kariangauterminal.co.id";
        $user = $this->user->getUserEmail($msg['id_user']);
        $mod = $this->user->getModEmail();
        $pic = $this->user->getPICEmail($msg['id_pic']);
        $trello = '';
        $to = $pic['email'];
        $cc = $mod['email'].','.$user['email'];

        $diagnose = $this->diagnose->getDiagnoseData($id);
        $solution = $this->solution->getSolutionData($id);
        $pic_name = $this->user->getPICName($msg['id_pic']);
        $diagnose_str = "";
        $solution_str = "";
        
        if($diagnose != NULL || $diagnose != ''){
            foreach($diagonse as $data){
                $i++;
                $diagnose_str .= "\r\n".$i.". ".$data['str_diagnose'];
            }
        } 

         if($solution != NULL || $solution != ''){
            foreach($solution as $data){
                $j++;
                $solution_str .= "\r\n".$j.". ".$data['str_diagnose'];
            }
        }

        $date = $this->getDatetimeNow();
        $subject = 'Ticket Created By '.$msg['user'].' At '.$date;

        $headers  = "From: IT Helpdesk PT KKT\r\n";
        $headers .= "Reply-To: ". strip_tags($email) . "\r\n";
        $headers .= "CC: ".$cc."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $message = '<html><body>';
        $message.= '<h3>Ticket Information: </h3><br>';
        $message.= "<p>Ticket ID : #".$id." </p><br>";
        $message .= "<p>Problems : </p><br>";
        $message .= $msg['problems']."<br>";
        $message .= "<p>Diagnose : </p><br>";
        $message .= $diagnose_str."<br>";
        $message .= "<p>Solutions : </p><br>";
        $message .= $solution_str."<br>";
        $message .= "<p>PIC : </p><br>";
        $message .= $pic_name['username']."<br>";
        $message.= "<br><br><br><i>
                        Note : <br>
                        This email is sent automatically. Don't reply to this email.
                    </i>";
        $message .= '</body></html>';

        $message = wordwrap($message,76);
        mail($to,$subject,$message,$headers);

        return null;
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('Asia/Makassar');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('d\-m\-Y\ H:i:s');
    }

    function send_email($msg){
        $date = $this->getDatetimeNow();
        $subject = 'Ticket Created By '.$msg['user'].' At '.$date;
        $message = $msg['problems'];

        // Get full html:
        /*
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
            <title>' . html_escape($subject) . '</title>
            <style type="text/css">
                body {
                    font-family: Arial, Verdana, Helvetica, sans-serif;
                    font-size: 16px;
                }
            </style>
        </head>
        <body>
        ' . $message . '
        </body>
        </html>';
        */
        // Also, for getting full html you may use the following internal method:
        //$body = $this->email->full_html($subject, $message);
        $result = $this->email
            ->from('testkktphp@gmail.com','IT Helpdesk PT KKT')
            ->reply_to('testkktphp@gmail.com')    // Optional, an account where a human being reads.
            ->to('fendy24kwan@gmail.com')
            //->cc()
            ->subject($subject)
            ->message($message)
            ->send();

        //var_dump($result);
        //echo '<br />';
        //echo $this->email->print_debugger();
    }
}