<?php

namespace Manom\Moysklad\Moysklad;

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
     * CustomerOrder constructor.
     * @param String $orderUrl
     */
    public function __construct(String $orderUrl)
    {
        try {
            $this->orderData = $this->sendRequest($orderUrl);
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return false;
            }
        }
        $this->setState();
        $this->setPositions();
        $this->setXmlMapping();
        return;
    }

    /**
     * @param String $url
     * @return \stdClass|null
     */
    private function sendRequest(String $url)
    {
        $client = new Client();

        $authData = Tools::getAuthData();
        return json_decode($client->get($url, [
            'auth' => [$authData["login"], $authData["password"]]
        ])->getBody()->getContents());
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
        $stateData = $this->sendRequest($this->orderData->state->meta->href);
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
        $positionsData = $this->sendRequest($this->orderData->positions->meta->href);
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
        $response = $this->sendRequest($position->assortment->meta->href);

        if (!empty($position->quantity) && !empty($response)) {
            $response->quantity = $position->quantity;
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
}