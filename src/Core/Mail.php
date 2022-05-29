<?php

class Mail 
{
    private array|string $to;
    private string $subject;
    private string $message;
    private array|string $additional_headers = [];
    private string $additional_params = "";

    private bool $is_html = false;

    public function __construct($to = "", $subject = "", $message = "", $headers = [], $params = "", $is_html = false)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->additional_headers = $headers;
        $this->additional_params = $params;

        $this->is_html = $is_html;
        $this->handleHtml();
    }

    public function setReceiver($to)
    {
        $this->to = $to;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setHeaders($headers)
    {
        $this->additional_headers = $headers;
    }

    public function setParams($params)
    {
        $this->additional_params = $params;
    }

    public function send()
    {
        if(is_array($this->to))
        {
            foreach($this->to as $receiver)
            {
                mail($receiver, $this->subject, $this->message, $this->additional_headers, $this->additional_params);
            }
        }
        else
        {
            mail($this->to, $this->subject, $this->message, $this->additional_headers, $this->additional_params);
        }
    }

    private function handleHtml()
    {
        array_push($this->additional_headers, "Content-type: text/".($this->is_html ? "html" : "plain")."; charset=iso-8859-1");
    }
}