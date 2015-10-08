<?php

namespace SodaStorageSystem\SodaManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="soda")
 */
class Soda {

  /**
  * @ORM\Column(type="integer")
  * @ORM\Id
  * @ORM\GeneratedValue(strategy="AUTO")
  */
  protected $id;

  /**
  * @ORM\Column(type="string",length=25)
  *
  * @Assert\NotBlank()
  * @Assert\Length(min=2)
  */
  protected $title;

  /**
  * @ORM\Column(type="text")
  *
  * @Assert\NotBlank()
  * @Assert\Length(min=5, max=500)
  */
  protected $description;

  /**
  * @ORM\Column(type="integer")
  *
  * @Assert\NotBlank()
  * @Assert\Type(type="integer")
  * @Assert\GreaterThan(value=0)
  */
  protected $quantity;
  /**
  * @ORM\Column(type="integer")
  *
  * @Assert\NotBlank()
  * @Assert\Type(type="integer")
  * @Assert\GreaterThan(value=0)
  */
  protected $price;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Soda
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Soda
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Soda
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Soda
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }
}
