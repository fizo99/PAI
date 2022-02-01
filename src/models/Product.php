<?php

class Product
{
    private $productId;
    private $name;
    private $unit;
    private $nettoPrice;
    private $taxPercent;

    public function __construct($productId, $name, $unit, $nettoPrice, $taxPercent)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->unit = $unit;
        $this->nettoPrice = $nettoPrice;
        $this->taxPercent = $taxPercent;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @return mixed
     */
    public function getNettoPrice()
    {
        return $this->nettoPrice;
    }

    /**
     * @return mixed
     */
    public function getTaxPercent()
    {
        return $this->taxPercent;
    }


}