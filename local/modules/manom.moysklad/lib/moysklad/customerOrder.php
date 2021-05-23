<?php

namespace Manom\Moysklad\Moysklad;

use Bitrix\Main\Loader;
use Bitrix\Sale\Order;
use GuzzleHttp\Client;
use Manom\Moysklad\Tools;

/**
 * Class CustomerOrder
 * @package Manom\Moysklad\Moysklad
 */
class CustomerOrder
{
    /**
     * @var \stdClass|null
     */
    private $orderData = null;
    /**
     * @var array
     */
    private $idByXmlId = [];
    /**
     * @var bool
     */
    public $errorRequest = false;

    /**
     * CustomerOrder constructor.
     * @param String $orderUrl
     */
    public function __construct(String $orderUrl)
    {
        try {
            $this->orderData = Tools::sendRequest($orderUrl);
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return;
            }

            $this->errorRequest = true;
            file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/local/modules/manom.moysklad/handler.log", "[" . date("d.m.Y H:i:s") . "] " . $e->getMessage() . "\n");
        }

        try {
            $this->setState();
            $this->setPositions();
            $this->setXmlMapping();
        } catch (\Exception $e) {
            $this->errorRequest = true;
            file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/local/modules/manom.moysklad/handler.log", "[" . date("d.m.Y H:i:s") . "] " . $e->getMessage() . "\n");
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getId(): int
    {
        $extCode = $this->orderData->externalCode;
        $extCode = !is_numeric($extCode) ? 0 : $extCode;
        return (int)$extCode;
    }

    /**
     *
     */
    private function setState(): void
    {
        if (empty($this->orderData->state->meta->href)) {
            $this->orderData->state = null;
            return;
        }
        $stateData = Tools::sendRequest($this->orderData->state->meta->href);
        $this->orderData->state = $stateData;
    }

    /**
     *
     */
    private function setPositions(): void
    {
        if (empty($this->orderData->positions->meta->href)) {
            $this->orderData->positions = [];
            return;
        }
        $positionsData = Tools::sendRequest($this->orderData->positions->meta->href);
        $this->orderData->positions = $positionsData->rows;
        if (is_array($this->orderData->positions)) {
            $this->idByXmlId = [];
            foreach ($this->orderData->positions as &$position) {
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
        return $this->orderData->state;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getPositions()
    {
        if (empty($this->orderData->positions)) {
            $this->orderData->positions = [];
        }

        return $this->orderData->positions;
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
    public function resetReserves(): bool
    {
        Loader::includeModule("germen.settings");
        $testOrders = array_map(function ($item) {
            return (int)$item;
        }, \UniPlug\Settings::get("TEST_ORDERS"));

        if ($this->orderData->state->name !== "[PO] Предзаказ") {
            return false;
        }

        if (!$this->getId() || empty(Order::load($this->getId()))) {
            return false;
        }

        foreach ($this->getPositions() as $position) {
            $meta = $position->positionMeta;
            if ($meta->type !== "customerorderposition") {
                continue;
            }

            try {
                Tools::sendRequest($meta->href, "PUT", '{"reserve" : 0.0}');
            } catch (\Exception $e) {
                $this->errorRequest = true;
                file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/local/modules/manom.moysklad/handler.log", "[" . date("d.m.Y H:i:s") . "] " . $e->getMessage() . "\n");
            }
        }
        return true;
    }
}