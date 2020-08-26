<?php
/**
 * Created by PhpStorm.
 * User: Hozberg
 * Date: 2020-08-23
 * Time: 20:44
 */

namespace Manom\Content;


use Bitrix\Main\Loader;
use Manom\Airtable\Parser\Question;

class Questions
{
    /**
     * @var int
     */
    private $iBlockId = 12;
    /**
     * @var int
     */
    private $productId = null;
    /**
     * @var null|Question
     */
    private $parser = null;
    /**
     * @var array
     */
    private $questions = [];


    /**
     * Questions constructor.
     * @param $productId
     */
    public function __construct($productId)
    {
        $this->productId = (int)$productId;
        $this->initParser();
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    private function initParser()
    {
        if (Loader::includeModule("manom.airtable")) {
            $this->parser = new Question();
        }
    }

    /**
     * @param $questions
     */
    public function updateFromAT($questions)
    {
        if ($this->parser) {
            $this->questions = $this->parser->parse($questions);
        }

        $this->deleteAT();
        $this->createAT();
    }

    /**
     * @return bool
     */
    private function deleteAT()
    {
        if (!$this->productId) {
            return false;
        }

        $productData = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => \Helper::CATALOG_IB_ID,
                "ID" => $this->productId,
            ],
            false,
            false,
            [
                "ID",
                "IBLOCK_ID",
                "PROPERTY_A_N_Q",
            ]
        );

        $productData = $productData->GetNext();
        $qnaIds = $productData["PROPERTY_A_N_Q_VALUE"];

        if (empty($qnaIds)) {
            return false;
        }

        $questions = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => $this->iBlockId,
                "PROPERTY_FROM_AT" => "Y",
                "ID" => $qnaIds,
            ],
            false,
            false,
            [
                "ID"
            ]
        );

        while ($question = $questions->GetNext()) {
            \CIBlockElement::Delete($question["ID"]);
        }
        return true;
    }

    /**
     * @return bool
     */
    private function createAT()
    {
        if (!$this->productId) {
            return false;
        }

        $iBlockElement = new \CIBlockElement();
        $addedQuestion = [];

        foreach ($this->questions as $question) {
            if (empty($question["question"]) || empty($question["answer"])) {
                continue;
            }

            $questionFields = [
                "IBLOCK_ID" => $this->iBlockId,
                "PROPERTY_VALUES" => [
                    "FROM_AT" => "Y",
                ],
                "NAME" => $question["question"],
                "PREVIEW_TEXT" => $question["answer"],
            ];

            $addedQuestion[] = $iBlockElement->Add(
                $questionFields
            );
        }

        if (!empty($addedQuestion)) {
            \CIBlockElement::SetPropertyValuesEx(
                $this->productId, \Helper::CATALOG_IB_ID,
                [
                    "A_N_Q" => $addedQuestion,
                ]
            );
        }

        return true;
    }
}