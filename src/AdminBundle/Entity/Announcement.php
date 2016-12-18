<?php

namespace AdminBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="announcement")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\AnnouncementRepository")
 */
class Announcement
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=255)
     */
    private $product_name;

    /**
     * @var string
     *
     * @ORM\Column(name="car_maker", type="string", nullable=false)
     */
    private $car_maker;

    /**
     *
     * @ORM\Column(name="car_model", type="string" , length=255)
     */
    private $car_model;

    /**
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(name="car_year", type="string")
     */
    private $car_year;

    /**
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;

    /**
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="imageName", type="string", length=255, nullable=true)
     */
    protected $imageName;

    /**
     * @ORM\Column(type="datetime", name="created")
     */
    protected $created;

    /**
     * User class constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime();
    }

    function getCreated()
    {
        return $this->created;
    }

    function setCreated($created)
    {
        $this->created = $created;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    function getProductName()
    {
        return $this->product_name;
    }

    function setProductName($product_name)
    {
        $this->product_name = $product_name;
    }

    function getCarMaker()
    {
        return $this->car_maker;
    }

    function setCarMaker($car_maker)
    {
        $this->car_maker = $car_maker;
    }

    function getCarModel()
    {
        return $this->car_model;
    }

    function setCarModel($car_model)
    {
        $this->car_model = $car_model;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    function getCarYear()
    {
        return $this->car_year;
    }

    function setCarYear($car_year)
    {
        $this->car_year = $car_year;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function getStock()
    {
        return $this->stock;
    }

    function setStock($stock)
    {
        $this->stock = $stock;
    }

// IMAGE UPLOAD
    protected $file;

    function getImageName()
    {
        return $this->imageName;
    }

    function getFile()
    {
        return $this->file;
    }

    function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    function setFile($file)
    {
        $this->file = $file;
    }

    public function getAbsolutePath()
    {
        return null === $this->imageName ? null : $this->getUploadRootDir() . '/' . $this->imageName;
    }

    public function getWebPath()
    {
        return null === $this->imageName ? null : $this->getUploadDir() . '/' . $this->imageName;
    }

    protected function getUploadRootDir($basepath)
    {
        // the absolute directory path where uploaded documents should be saved
        return $basepath . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/products';
    }

    public function upload($basepath)
    {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        if (null === $basepath) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the target filename to move to
        $this->file->move($this->getUploadRootDir($basepath), $this->file->getClientOriginalName());

        // set the path property to the filename where you'ved saved the file
        $this->setImageName($this->file->getClientOriginalName());

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

}
