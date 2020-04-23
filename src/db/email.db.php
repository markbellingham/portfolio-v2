<?php

require_once 'vendor/autoload.php';
$configs = require_once 'config/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email extends PHPMailer {

    private $settings;
    protected $customSettings = array (
        'replyTo' => null,
        'attachments' => null
    );

    public function __construct($mailHost)
    {
        global $configs;
        parent::__construct();
        $emailConfig = $configs[$mailHost];

        $this->isSMTP();
        $this->Host = $emailConfig['host'];
        $this->Username = $emailConfig['username'];
        $this->Password = $emailConfig['password'];
        $this->SMTPAuth = $emailConfig['SMTPAuth'];
        $this->SMTPSecure = $emailConfig['SMTPSecure'];
        $this->Port = $emailConfig['port'];
        $this->SMTPDebug = 0;
    }

    public function send(array $addresses = [], string $subject = '', string $body = '', array $files = [])
    {
        try{
            if($this->customSettings['replyTo']) {
                $replyTo = json_decode($this->customSettings['replyTo']);
                $this->setFrom($this->Username, $replyTo->name);
                $this->addReplyTo($replyTo->email, $replyTo->name);
            } else {
                $this->setFrom($this->Username, 'Portfolio Website');
            }
            foreach($addresses as $a) {
                $this->addAddress($a->email ?? '', $a->name ?? '');
            }
        } catch(Exception $e) {
            return 'Message could not be sent. Mailer error: ' . $this->ErrorInfo;
        }
    }
}