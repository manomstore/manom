<?php

namespace Manom\Moysklad\Moysklad\Entity;

use Manom\Moysklad\Tools;

/**
 * Class CustomerOrder
 * @package Manom\Moysklad\Moysklad\Entity
 */
class CustomerOrder extends BaseEntity
{
    /**
     * @var array
     */
    protected $idByXmlId = [];

    /**
     * CustomerOrder constructor.
     * @param String $orderUrl
     */
    public function __construct(String $url)
    {
        parent::__construct($url);

        if ($this->hasError()) {
            return;
        }

        try {
            $this->setState();
            $this->setPositions();
            $this->setXmlMapping();
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getId(): int
    {
        $extCode = $this->entityData->externalCode;
        $extCode = !is_numeric($extCode) ? 0 : $extCode;
        return (int)$extCode;
    }

    /**
     *
     */
    private function setState(): void
    {
        if (empty($this->entityData->state->meta->href)) {
            $this->entityData->state = null;
            return;
        }
        $stateData = Tools::sendRequest($this->entityData->state->meta->href);
        $this->entityData->state = $stateData;
    }

    /**
     *
     */
    private function setPositions(): void
    {
        if (empty($this->entityData->positions->meta->href)) {
            $this->entityData->positions = [];
            return;
        }
        $positionsData = Tools::sendRequest($this->entityData->positions->meta->href);
        $this->entityData->positions = $positionsData->rows;
        if (is_array($this->entityData->positions)) {
            $this->idByXmlId = [];
            foreach ($this->entityData->positions as &$position) {
                $this->setPositionData($position);
                if (!empty($position->externalCode)) {
                    $this->idByXmlId[] = $position->externalCode;
                }
            }
            unset($position);
        }
    }

    /**
     * @param $position
     */
    private function setPositionData(&$position): void
    {
        $response = Tools::sendRequest($position->assortment->meta->href);

        if (!empty($position->quantity) && !empty($response)) {
            $response->quantity = $position->quantity;
            $response->positionMeta = $position->meta;
        }
        $position = $response;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getStatus()
    {
        return $this->entityData->state;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getPositions()
    {
        if (empty($this->entityData->positions)) {
            $this->entityData->positions = [];
        }

        return $this->entityData->positions;
    }

    /**
     *
     */
    private function setXmlMapping(): void
    {
        if (empty($this->idByXmlId)) {
            return;
        }

        $xmlIds = $this->idByXmlId;

        $productsIds = \CIBlockElement::GetList(
            [],
            [
                "EXTERNAL_ID" => $xmlIds
            ],
            false,
            false,
            [
                "IBLOCK_ID",
                "ID",
                "EXTERNAL_ID",
            ]
        );

        $this->idByXmlId = [];
        while ($productsId = $productsIds->Fetch()) {
            $this->idByXmlId[$productsId["EXTERNAL_ID"]] = $productsId["ID"];
        }
    }

    /**
     * @param string $xmlId
     * @return int
     */
    public function getIdByXmlId(string $xmlId): int
    {
        return (int)$this->idByXmlId[$xmlId];
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existProductId(int $id): bool
    {
        return array_search($id, $this->idByXmlId) === false
            ? false : true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function changeReserves(string $action): bool
    {
        if (!in_array($action, ["set", "reset"])) {
            return false;
        }

        foreach ($this->getPositions() as $position) {
            $meta = $position->positionMeta;
            if ($meta->type !== "customerorderposition" || empty($position->quantity)) {
                continue;
            }

            $quantityReserve = $action === "set" ? $position->quantity : 0;

            $body = [
                "reserve" => (float)$quantityReserve,
            ];

            try {
                Tools::sendRequest($meta->href, "PUT", json_encode($body));
            } catch (\Exception $e) {
                $this->handleError($e);
            }
        }
        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isPreOrder(): bool
    {
        $status = $this->getStatus();
        if (empty($status) || !is_object($status)) {
            return false;
        }

        return $status->name === "[PO] Предзаказ";
    }
}