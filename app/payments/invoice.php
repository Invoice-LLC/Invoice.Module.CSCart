<?php

use Tygh\Template\Document\Order\Order;

include "InvoiceSDK/RestClient.php";
include "InvoiceSDK/CREATE_PAYMENT.php";
include "InvoiceSDK/CREATE_TERMINAL.php";
include "InvoiceSDK/common/ORDER.php";
include "InvoiceSDK/common/SETTINGS.php";
include "InvoiceSDK/common/ITEM.php";

class InvoicePayments {
    private $amount;
    private $id;
    private $restClient;
    private $terminal;

    public $terminalName = "CS-Cart";
    public $terminalDescription = "CS-Cart module";

    /**
     * InvoicePayments constructor.
     * @param $amount - Order amount
     * @param $id - CS-Cart order ID
     * @param $login - Invoice Login
     * @param $apiKey - Invoice API Key
     * @param $terminal - Invoice Terminal ID(optional)
     */
    public function __construct($amount, $id, $login, $apiKey, $terminal = '')
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->restClient = new RestClient($login, $apiKey);

        if($terminal == null or empty($terminal)) {
            $this->terminal = $this->getTerminal();
        } else {
            $this->terminal = $terminal;
        }
    }

    /**
     * @return TerminalInfo
     */
    public function createTerminal() {
        $request = new CREATE_TERMINAL($this->terminalName);
        $request->description = $this->terminalDescription;
        $request->type = "dynamical";
        $request->defaultPrice = "1";

        $this->log("CreateTerminal_REQUEST", json_encode($request));
        $info = $this->restClient->CreateTerminal($request);
        $this->log("CreateTerminal_RESPONSE", json_encode($info));

        return $info;
    }

    /**
     * @return string - Payment URL
     * @throws Exception
     */
    public function createPayment() {
        if(!$this->checkOrCreateTerminal()) {
            throw new Exception("Не удалось создать терминал");
        }

        $request = new CREATE_PAYMENT();
        $request->order = $this->getOrder();
        $request->settings = $this->getSettings();
        $request->receipt = $this->getReceipt();

        $this->log("CREATE_PAYMENT_REQUEST", json_encode($request));
        $info = $this->restClient->CreatePayment($request);
        $this->log("CREATE_PAYMENT_RESPONSE", json_encode($info));

        if($info == null or empty($info) or isset($info->error)) {
            throw new Exception("Не удалось создать платеж");
        }

        return $info->payment_url;
    }

    /**
     * @return INVOICE_ORDER
     */
    private function getOrder() {
        $order = new INVOICE_ORDER();
        $order->amount = $this->amount;
        $order->id = $this->id;
        $order->currency = "RUB";
        
        return $order;
    }

    /**
     * @return SETTINGS
     */
    private function getSettings() {
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

        $settings = new SETTINGS();
        $settings->terminal_id = $this->terminal;
        $settings->success_url = $url;
        $settings->fail_url = $url;

        return $settings;
    }

    /**
     * @return ITEM
     */

    private function getReceipt() {
        $receipt = array();
        $order = new Order($this->id);
        $basket = $order->getProducts();

        foreach ($basket as $basketItem) {
            $item = new ITEM();
            $item->name = $basketItem['product'];
            $item->price = $basketItem['price'];
            $item->resultPrice = $basketItem['subtotal'];
            $item->quantity = $basketItem['amount'];

            array_push($receipt, $item);
        }

        return $receipt;
    }

    /**
     * @return boolean
     */
    private function checkOrCreateTerminal() {
        if($this->terminal == null or empty($this->terminal)) {
            $terminal = $this->createTerminal();
            if($terminal == null or empty($terminal) or isset($terminal->error)) {
                return false;
            }
            $this->terminal = $terminal->id;
            $this->setTerminal($terminal->id);
        }
        return true;
    }

    private function setTerminal($value) {
       file_put_contents('invoice_tid', $value);
    }

    /**
     * @return string - Terminal ID
     */
    private function getTerminal() {
        $name = "invoice_tid";

        if(!file_exists('invoice_tid')) file_put_contents('invoice_tid', '');

        $tid = file_get_contents('invoice_tid');

        return $tid;
    }

    private function log($key,$str) {
        if(!file_exists('invoice_payment.log')) file_put_contents('invoice_payment.log', '');
        $fp = fopen('invoice_payment.log', 'a+');
        fwrite($fp, "============$key=========="."\n\n");
        fwrite($fp, $str."\n\n");
        fwrite($fp, "======================"."\n\n");
        fclose($fp);
    }
}

$amount = $order_info['total'];
$id = $order_id;
$key =  $processor_data['processor_params']['invoice_api_key'];
$login =  $processor_data['processor_params']['invoice_login'];

$paymentProcessor = new InvoicePayments($amount, $id, $login, $key);

try {
    $payment_url = $paymentProcessor->createPayment();
    fn_change_order_status($order_id, 'O');
    fn_create_payment_form($payment_url, null, 'Invoice', false, 'get');
} catch (Exception $e) {
    $msg = $e->getMessage();
    echo "<script>alert('$msg');</script>";
}

