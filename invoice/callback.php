<?php
define('AREA', 'C');
require(dirname(__FILE__) . '/../init.php');

class Callback {
    private $tranId, $id, $notification, $status, $amount, $signature;

    public function __construct()
    {
        $this->notification = $this->getData();
        $this->tranId = $this->notification['id'];
        $this->id = $this->notification["order"]["id"];
        $this->amount = $this->notification["order"]["amount"];
        $this->status = $this->notification["status"];
        $this->signature = $this->notification["signature"];

        switch ($this->notification["notification_type"]) {
            case "pay" :
                switch ($this->status) {
                    case "successful":
                        $this->pay();
                        break;
                    case "error":
                        $this->error();
                        break;
                }
                break;
        }
    }

    /**
     * @param string $id - Payment ID
     * @param string $status - Payment status
     * @param string $key - API Key
     * @return string Payment signature
     */
    public function getSignature($id, $status, $key) {
        return md5($id.$status.$key);
    }

    /**
     * @return array
     */
    private function getData() {
        $postData = file_get_contents('php://input');
        return json_decode($postData, true);
    }

    private function check() {
        $payment_id = db_get_field("SELECT payment_id FROM ?:orders WHERE order_id = ?i", $this->id);
        $processor_data = fn_get_payment_method_data($payment_id);
        if($payment_id == null or empty($payment_id) or $processor_data == null or empty($processor_data)) {
            return false;
        }

        if($this->signature != $this->getSignature($this->tranId, $this->status,  $processor_data['processor_params']['invoice_api_key'])) {
            return false;
        }

        return true;
    }

    private function pay() {
        if(!$this->check()) return;
        fn_change_order_status($this->id, 'P');
    }

    private function error() {
        if(!$this->check()) return;
        fn_change_order_status($this->id, 'F');
    }
}
new Callback();