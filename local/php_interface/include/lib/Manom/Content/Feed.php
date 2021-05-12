<?php

namespace Manom\Content;


use Bitrix\Main\Config\Option;
use Manom\PreOrder;
use Manom\Price;
use Manom\Product;
use Manom\Store\StoreData;
use Yandex\Market\Result\XmlNode;

/**
 * Class Feed
 * @package Manom\Content
 */
class Feed
{
    /**
     * @var null|\SimpleXMLElement
     */
    private $element;
    /**
     * @var PreOrder
     */
    private $preOrder;
    /**
     * @var array
     */
    private $elementData = [];
    /**
     * @var array
     */
    private $ecommerceData = [];
    /**
     * @var array
     */
    private $tagList = [];
    /**
     * @var array
     */
    private $feedParams = [];

    /**
     * Feed constructor.
     * @param array $tagResultList
     * @param int $feedId
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \Manom\Exception
     */
    public function __construct(array &$tagResultList, array $context)
    {
        $offerIds = array_keys($tagResultList);

        $this->ecommerceData = (new Product())->getEcommerceData($offerIds, \Helper::CATALOG_IB_ID);
        $this->preOrder = new PreOrder($offerIds);
        $this->tagList = &$tagResultList;
        $this->setFeedParams($context);
    }

    /**
     * @return void
     */
    public function processElements(): void
    {
        foreach ($this->tagList as &$tagResult) {
            $this->modifyElement($tagResult);
        }

        unset($tagResult);
    }

    /**
     * @param XmlNode $tagResult
     * @return void
     */
    private function modifyElement(XmlNode &$tagResult): void
    {
        $element = $tagResult->getXmlElement();
        if (!$this->isXmlElement($element)) {
            return;
        }
        if (!$this->setElement($element)) {
            return;
        }
        $this->updateUrl();
        $this->updatePrice();
        $this->checkQuantity($tagResult);
        $this->addOutlets();
    }

    /**
     * @return void
     */
    private function updateUrl(): void
    {
        $shopUrl = $this->element->addChild("shop_url");
        $shopUrl[0] = $this->element->url;

        if ($this->useMirror()) {
            $this->element->url = str_replace("manom", "marketplace.manom", $this->element->url);
        }
    }

    /**
     * @return void
     */
    private function updatePrice(): void
    {
        if ($this->isYmMarketplace() && $this->getPriceYMarket() > 0) {
            $this->element->price = $this->getPriceYMarket();
        } elseif ($this->usePriceGoods() && $this->getPriceGoods() > 0) {
            $this->element->price = $this->getPriceGoods();
        }
    }

    /**
     * @param XmlNode $tagResult
     * @return void
     */
    private function checkQuantity(XmlNode &$tagResult): void
    {
        if ($this->elementData["isPreOrder"]) {
            if (!empty($this->element->attributes()->available)) {
                $this->element->attributes()->available = "false";
            } else {
                $this->element->addAttribute("available", "false");
            }
        }

        if (!$this->isYmMarketplace() && !$this->elementData["isPreOrder"] && $this->elementData["quantity"] <= 0) {
            $tagResult->invalidate();
        }
    }

    /**
     * @return void
     */
    private function addOutlets(): void
    {
        $outlets = $this->element->addChild("outlets");

        if (!$this->isXmlElement($outlets)) {
            return;
        }
        $outlet = $outlets->addChild("outlet");

        if (!$this->isXmlElement($outlets)) {
            return;
        }

        $outlet->addAttribute("id", 0);
        $outlet->addAttribute("instock", $this->elementData["quantity"]);
    }

    /**
     * @param \SimpleXMLElement $element
     * @return bool
     */
    private function setElement(\SimpleXMLElement &$element): bool
    {
        $this->element = &$element;
        $elementData["offerId"] = (int)$element->attributes()->id;
        if ($elementData["offerId"] <= 0) {
            return false;
        }
        /** @var StoreData $storeData */
        $storeData = $this->ecommerceData[$elementData["offerId"]]["storeData"];
        if (empty($storeData)) {
            return false;
        }
        $elementData["quantity"] = $storeData->getQuantityAllMain();

        if (empty($this->preOrder)) {
            return false;
        }
        $preOrder = $this->preOrder->getByProductId($elementData["offerId"]);
        $elementData["isPreOrder"] = $preOrder["active"];

        $this->elementData = $elementData;
        return true;
    }

    /**
     * @param $element
     * @return bool
     */
    private function isXmlElement($element): bool
    {
        return $element instanceof \SimpleXMLElement;
    }

    /**
     * @param int $feedId
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @return void
     */
    private function setFeedParams(array $context): void
    {
        $feedData = Option::get("yandex.market", "feed_data");
        $feedData = json_decode($feedData, true);
        if (!is_array($feedData)) {
            $feedData = [];
        }
        $this->feedParams = (array)$feedData[(int)$context["SETUP_ID"]];
        $this->feedParams["exportService"] = $context["EXPORT_SERVICE"];
    }

    /**
     * @return bool
     */
    private function useMirror(): bool
    {
        return (bool)$this->feedParams["USE_MIRROR"];
    }

    /**
     * @return bool
     */
    private function usePriceGoods(): bool
    {
        return (bool)$this->feedParams["USE_PRICE_GOODS"];
    }

    /**
     * @return bool
     */
    private function isYmMarketplace(): bool
    {
        return $this->feedParams["exportService"] === "Marketplace";
    }

    /**
     * @return float
     */
    private function getPriceGoods(): float
    {
        $prices = (array)$this->ecommerceData[$this->elementData["offerId"]]["prices"];
        $priceGoods = current(array_filter($prices, function ($price) {
            return (int)$price["CATALOG_GROUP_ID"] === (int)Price::GOODS_TYPE_ID;
        }));
        if (!is_array($priceGoods)) {
            $priceGoods = [];
        }

        return (float)$priceGoods['PRICE'];
    }

    /**
     * @return float
     */
    private function getPriceYMarket(): float
    {
        $prices = (array)$this->ecommerceData[$this->elementData["offerId"]]["prices"];
        $priceGoods = current(array_filter($prices, function ($price) {
            return (int)$price["CATALOG_GROUP_ID"] === (int)Price::YM_TYPE_ID;
        }));
        if (!is_array($priceGoods)) {
            $priceGoods = [];
        }

        $price = (float)$priceGoods['PRICE'];

        if ($price <= 0) {
            $price = $this->getPriceGoods();
        }

        return $price;
    }
}