<?php

namespace Manom\Store;


/**
 * Class StoreItem
 * @package Manom\Store
 */
class StoreItem
{

    /**
     * @var int
     */
    private $id;
    /**
     * @var mixed
     */
    private $code;
    /**
     * @var int
     */
    private $sort;
    /**
     * @var bool
     */
    private $asMain;
    /**
     * @var
     */
    private $priceCode;
    /**
     * @var int
     */
    private $assemblyTime;
    /**
     * @var string
     */
    private $schedule;

    /**
     * StoreItem constructor.
     * @param array $itemData
     */
    public function __construct(array $itemData)
    {
        if (isset($itemData["ID"])) {
            $this->id = (int)$itemData["ID"];
        }

        if (isset($itemData["UF_CODE"])) {
            $this->code = $itemData["UF_CODE"];
        }

        if (isset($itemData["SORT"])) {
            $this->sort = (int)$itemData["SORT"];
        }

        if (isset($itemData["UF_AS_MAIN"])) {
            $this->asMain = (bool)$itemData["UF_AS_MAIN"];
        }

        if (isset($itemData["SCHEDULE"])) {
            $this->schedule = (string)$itemData["SCHEDULE"];
        }

        $this->assemblyTime = (int)$itemData["UF_TIME"];
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->id) || empty(!empty($this->getCode()) ? $this->getCode() : $this->asMain);
    }

    /**
     * @param array $priceTypes
     * @return void
     */
    public function setTypePrice(array $priceTypes): void
    {
        if ($this->isMain()) {
            $priceCode = $priceTypes["main"];
        } else {
            $priceCode = $priceTypes[$this->getCode()];
        }

        if ($priceCode) {
            $this->priceCode = $priceCode;
        }
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->code === "main" || $this->asMain;
    }

    /**
     * @return bool
     */
    public function isRrc(): bool
    {
        return $this->code === "rrc";
    }

    /**
     * @return bool
     */
    public function isDefects(): bool
    {
        return $this->code === "defects";
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this->code;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @return string|null
     */
    public function getPriceCode(): ?string
    {
        return $this->priceCode;
    }

    /**
     * @return int
     */
    public function getAssemblyTime(): int
    {
        return (int)$this->assemblyTime;
    }

    /**
     * @return string
     */
    public function getSchedule(): string
    {
        return (string)$this->schedule;
    }
}
