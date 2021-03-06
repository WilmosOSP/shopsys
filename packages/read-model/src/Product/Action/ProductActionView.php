<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Product\Action;

/**
 * @experimental
 *
 * Class representing products actions in lists in frontend
 *
 * @see \Shopsys\ReadModelBundle\Product\Listed\ListedProductView
 * @see https://docs.shopsys.com/en/latest/model/introduction-to-read-model/
 */
class ProductActionView
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var bool
     */
    protected $sellingDenied;

    /**
     * @var bool
     */
    protected $isMainVariant;

    /**
     * @var string
     */
    protected $detailUrl;

    /**
     * @param int $id
     * @param bool $sellingDenied
     * @param bool $isMainVariant
     * @param string $detailUrl
     */
    public function __construct(int $id, bool $sellingDenied, bool $isMainVariant, string $detailUrl)
    {
        $this->id = $id;
        $this->sellingDenied = $sellingDenied;
        $this->isMainVariant = $isMainVariant;
        $this->detailUrl = $detailUrl;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isSellingDenied(): bool
    {
        return $this->sellingDenied;
    }

    /**
     * @return bool
     */
    public function isMainVariant(): bool
    {
        return $this->isMainVariant;
    }

    /**
     * @return string
     */
    public function getDetailUrl(): string
    {
        return $this->detailUrl;
    }
}
