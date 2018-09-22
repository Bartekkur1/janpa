<?php 

class Time
{

    public $date_time;

    function __construct()
    {
        date_default_timezone_set("Europe/Warsaw");
        $this->date_time = new DateTime();
    }

    /**
     * returs data time in given order
     * @param string $order default ("Y-m-d")
     */
    public function get_date($order = "Y-m-d")
    {
        return $this->date_time->format($order);
    }

    /**
     * returns curent time H:i:s
     */
    public function get_time()
    {
        return $this->date_time->format("H:i:s");
    }

    /**
     * return curent date time Y-m-d H:i:s
     */
    public function get_datetime() {
        return $this->date_time->format("Y-m-d H:i:s");
    }

}