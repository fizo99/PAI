<?php

class Invoice
{
    private $buyerId;
    private $sellerId;
    private $place;
    private $date;
    private $number;
    private $paymentMethod;
    private $additionalInformations;
    private $userId;
    private $invoiceType;
    private $invoiceState;

    /**
     * @param $buyerId
     * @param $sellerId
     * @param $place
     * @param $date
     * @param $number
     * @param $paymentMethod
     * @param $additionalInformations
     * @param $userId
     */
    public function __construct($buyerId, $sellerId, $place, $date, $number, $paymentMethod, $additionalInformations, $userId, $invoiceType, $invoiceState)
    {
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->place = $place;
        $this->date = $date;
        $this->number = $number;
        $this->paymentMethod = $paymentMethod;
        $this->additionalInformations = $additionalInformations;
        $this->userId = $userId;
        $this->invoiceType = $invoiceType;
        $this->invoiceState = $invoiceState;
    }

    /**
     * @return mixed
     */
    public function getBuyerId()
    {
        return $this->buyerId;
    }

    /**
     * @return mixed
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return mixed
     */
    public function getAdditionalInformations()
    {
        return $this->additionalInformations;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getInvoiceState()
    {
        return $this->invoiceState;
    }

    /**
     * @return mixed
     */
    public function getInvoiceType()
    {
        return $this->invoiceType;
    }

}