<?php
/*-----------------------------------------------------------------------
  Start              : 24 februari 2009
  Door               : Mollie B.V. (RDF) © 2009

  Versie             : 1.02 (gebaseerd op de Mollie iDEAL class van
                       Concepto IT Solution - http://www.concepto.nl/)
  Laatste aanpassing : 21 april 2009
  Aard v. aanpassing : Functieaanroepen zonder hoofdletters verbeterd,
                       preg_match voor return- en reporturl aangepast
  Door               : RDF
  -----------------------------------------------------------------------*/

  class iDEAL_Payment {

    const     MIN_TRANS_AMOUNT = 118;

    protected $partner_id      = null;
    protected $testmode        = false;

    protected $bank_id         = null;
    protected $amount          = 0;
    protected $description     = null;
    protected $return_url      = null;
    protected $report_url      = null;

    protected $bank_url        = null;
    protected $transaction_id  = null;
    protected $paid_status     = false;
    protected $consumer_info   = array();

    public function __construct ($partner_id) {
      $this->partner_id = $partner_id;
    }

    public function getBanks () {
      $banks_xml = $this->_sendRequest('www.mollie.nl', '/xml/ideal/', 'a=banklist' . (($this->testmode) ? '&testmode=true' : ''));

      if (empty($banks_xml)) {
        return false;
      }

      $banks_object = $this->_XMLtoObject($banks_xml);

      if (!$banks_object) {
        return false;
      }

       $banks_array = array();
       foreach ($banks_object->bank as $bank) {
         $banks_array["{$bank->bank_id}"] = "{$bank->bank_name}";
       }

       return $banks_array;
    }

    public function createPayment ($bank_id, $amount, $description, $return_url, $report_url) {
//die($bank_id."--".$amount."--".$description."--".$return_url."--".$report_url);
      if (!$this->setBankId($bank_id) or
          !$this->setDescription($description) or
          !$this->setAmount($amount) or
          !$this->setReturnUrl($return_url) or
          !$this->setReportUrl($report_url)) {
      return false;
      }

      $create_xml = $this->_sendRequest(
                      'www.mollie.nl',
                      '/xml/ideal/',
                      'a=fetch' .
                        '&partnerid=' .   urlencode($this->getPartnerId()) .
                        '&bank_id=' .     urlencode($this->getBankId()) .
                        '&amount=' .      urlencode($this->getAmount()) .
                        '&reporturl=' .   urlencode($this->getReportURL()) .
                        '&description=' . urlencode($this->getDescription()) .
                        '&returnurl=' .   urlencode($this->getReturnURL()));

      if (empty($create_xml)) {
       die( $create_xml);
      }

      $create_object = $this->_XMLtoObject($create_xml);

      if (!$create_object) {
        die("can create XML object");
      }

      $this->transaction_id = $create_object->order->transaction_id;
      $this->bank_url       = $create_object->order->URL;

      return true;
    }

    public function checkPayment ($transaction_id) {
      if (!$this->setTransactionId($transaction_id)) {
        return false;
      }

      $check_xml = $this->_sendRequest(
                     'www.mollie.nl',
                     '/xml/ideal/',
                     'a=check' .
                       '&partnerid=' .      urlencode($this->getPartnerId()) .
                       '&transaction_id=' . urlencode($this->getTransactionId()) .
                       (($this->testmode) ? '&testmode=true' : ''));

      if (empty($check_xml)) {
        return false;
      }

      $check_object = $this->_XMLtoObject($check_xml);

      if (!$check_object) {
        return false;
      }

      $this->paid_status   = ($check_object->order->payed == 'true');
      $this->amount        = $check_object->order->amount;
      $this->consumer_info = (isset($check_object->order->consumer)) ? (array) $check_object->order->consumer : array();

      return true;
    }

/*
  PROTECTED FUNCTIONS
*/

    protected function _sendRequest ($host, $path, $data) {
      $fp = @fsockopen($host, 80);
      $buf = '';
      if ($fp) {
        @fputs($fp, "POST $path HTTP/1.0\n");
        @fputs($fp, "Host: $host\n");
        @fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        @fputs($fp, "Content-length: " . strlen($data) . "\n");
        @fputs($fp, "Connection: close\n\n");
        @fputs($fp, $data);

        while (!feof($fp)) {
          $buf .= fgets($fp, 128);
        }
        fclose($fp);
      }

      if (empty($buf)) {
        return false;
      }
      else {
        list($headers, $body) = preg_split("/(\r?\n){2}/", $buf, 2);

        return $body;
      }
    }

    protected function _XMLtoObject ($xml) {
      try {
        $xml_object = new SimpleXMLElement($xml);
        if ($xml_object == false) {
          return false;
        }
      }
      catch (Exception $e) {
        return false;
      }

      return $xml_object;
    }

/*
  SET AND GET FUNCTIONS
*/

    public function setPartnerId ($partner_id) {
      if (!is_numeric($partner_id)) {
        return false;
      }

      return ($this->partner_id = $partner_id);
    }

    public function getPartnerId () {
      return $this->partner_id;
    }

    public function setTestmode () {
      return ($this->testmode = true);
    }

    public function setBankId ($bank_id) {
      if (!is_numeric($bank_id)) {
        return false;
      }

      return ($this->bank_id = $bank_id);
    }

    public function getBankId () {
      return $this->bank_id;
    }

    public function setAmount ($amount) {
      if (!ereg('^[0-9]{0,}$', $amount)) {
        return false;
      }
      if (self::MIN_TRANS_AMOUNT > $amount) {
        return false;
      }

      return ($this->amount = $amount);
    }

    public function getAmount () {
      return $this->amount;
    }

    public function setDescription ($description) {
      $description = substr($description, 0, 29);

      return ($this->description = $description);
    }

    public function getDescription () {
      return $this->description;
    }

    public function setReturnURL ($return_url) {
      if (!preg_match('|(\w+)://([^/:]+)(:\d+)?(.*)|', $return_url)) {
        return false;
      }

      return ($this->return_url = $return_url);
    }

    public function getReturnURL () {
      return $this->return_url;
    }

    public function setReportURL ($report_url) {
      if (!preg_match('|(\w+)://([^/:]+)(:\d+)?(.*)|', $report_url)) {
        return false;
      }

      return ($this->report_url = $report_url);
    }

    public function getReportURL () {
      return $this->report_url;
    }

    public function setTransactionId ($transaction_id) {
      if (empty($transaction_id)) {
        return false;
      }

      return ($this->transaction_id = $transaction_id);
    }

    public function getTransactionId () {
      return $this->transaction_id;
    }

    public function getBankURL () {
      return $this->bank_url;
    }

    public function getPaidStatus () {
      return $this->paid_status;
    }

    public function getConsumerInfo () {
      return $this->consumer_info;
    }

  }
