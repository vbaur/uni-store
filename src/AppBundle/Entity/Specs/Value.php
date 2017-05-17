<?php

namespace AppBundle\Entity\Specs;

/**
 * Value
 */
class Value
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * @var \AppBundle\Entity\Specs\Property
     */
    private $property;


    /**
     * Set property
     *
     * @param \AppBundle\Entity\Specs\Property $property
     *
     * @return Value
     */
    public function setProperty(\AppBundle\Entity\Specs\Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \AppBundle\Entity\Specs\Property
     */
    public function getProperty()
    {
        return $this->property;
    }
    /**
     * @var \AppBundle\Entity\Product
     */
    private $product;


    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Value
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
