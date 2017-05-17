<?php

namespace AppBundle\Entity;

/**
 * Product
 */
class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $description;


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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $specifications;

    /**
     * @var \AppBundle\Entity\ProductCategory
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->specifications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add specification
     *
     * @param \AppBundle\Entity\Specs\Value $specification
     *
     * @return Product
     */
    public function addSpecification(\AppBundle\Entity\Specs\Value $specification)
    {
        $specification->setProduct($this);
        $this->specifications[] = $specification;

        return $this;
    }

    /**
     * Remove specification
     *
     * @param \AppBundle\Entity\Specs\Value $specification
     */
    public function removeSpecification(\AppBundle\Entity\Specs\Value $specification)
    {
        $specification->setProduct(null);
        $this->specifications->removeElement($specification);
    }

    /**
     * Get specifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecifications()
    {
        return $this->specifications;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\ProductCategory $category
     *
     * @return Product
     */
    public function setCategory(\AppBundle\Entity\ProductCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\ProductCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
}
