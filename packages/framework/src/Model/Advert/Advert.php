<?php

namespace Shopsys\FrameworkBundle\Model\Advert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="adverts")
 * @ORM\Entity
 */
class Advert
{
    public const TYPE_IMAGE = 'image';
    public const TYPE_CODE = 'code';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $domainId;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $type;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $link;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $positionName;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $hidden;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Advert\AdvertData $advert
     */
    public function __construct(AdvertData $advert)
    {
        $this->setData($advert);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Advert\AdvertData $advert
     */
    public function edit(AdvertData $advert)
    {
        $this->setData($advert);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Advert\AdvertData $advert
     */
    protected function setData(AdvertData $advert): void
    {
        $this->domainId = $advert->domainId;
        $this->name = $advert->name;
        $this->type = $advert->type;
        $this->code = $advert->code;
        $this->link = $advert->link;
        $this->positionName = $advert->positionName;
        $this->hidden = $advert->hidden;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @return string|null
     */
    public function getPositionName()
    {
        return $this->positionName;
    }
}
