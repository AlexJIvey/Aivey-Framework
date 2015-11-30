<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2
 */

namespace Aivey\Form;

/**
 * Form extension class that sends form submissions via email
 *
 * @author Alex Ivey
 */
class Mail extends Base {
    protected $to = "";
    protected $from_name = "";
    protected $from_email = "";
    protected $subject = "";
    protected $message = "";
    
    public function send() {
        $to = filter_var($this->to, FILTER_SANITIZE_EMAIL);
        $from_name = $this->from_name;
        $from_email = filter_var($this->from_email, FILTER_SANITIZE_EMAIL);
        $subject = filter_var($this->subject, FILTER_SANITIZE_STRING);
        $message = $this->message;
        
        $from = "";
        if (strlen($from_name) > 0 && strlen($from_email) > 4) {
            $from = preg_replace('/[^\s\d\w]/i', '', $from_name) . " <$from_email>";
        } else if (strlen($from_email) > 4) {
            $from = $from_email;
        } else {
            return false;
        }
        
        $headers = 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from_email;
        
        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }
}
