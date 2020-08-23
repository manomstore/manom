<?php

namespace Manom\Airtable\Parser;


/**
 * Class Review
 * @package Manom\Airtable\Parser
 */
class Review
{
    /**
     * @var array
     */
    private $parseStepMapping = [
        "merits" => "Достоинства:",
        "disadvantages" => "Недостатки:",
        "comment" => "Комментарий:",
        "rating" => "Рейтинг:",
        "verified" => "Проверенный покупатель",
    ];

    /**
     * @var array
     */
    private $newReview = [
        "author" => "",
        "merits" => "",
        "disadvantages" => "",
        "comment" => "",
    ];

    /**
     * @var string
     */
    private $currentStep = "";
    /**
     * @var bool
     */
    private $newBlock = false;
    /**
     * @var bool
     */
    private $currentLine = false;

    /**
     * Review constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $reviews
     * @return array
     */
    public function parse($reviews)
    {
        if (empty($reviews)) {
            return [];
        }

        $reviews = explode("\n\n", $reviews);
        $resultReviews = [];

        foreach ($reviews as $review) {
            $reviewLines = $this->explode($review);
            $newReview = $this->newReview;
            $newReview["author"] = array_shift($reviewLines);
            $this->newBlock = false;
            foreach ($reviewLines as $line) {
                $this->currentLine = $line;
                if ($this->isVerifiedLine()) {
                    continue;
                }

                $this->checkToken("merits");
                $this->checkToken("disadvantages");
                $this->checkToken("comment");
                $this->checkToken("rating");

                if (strlen($this->currentLine) !== 0) {
                    $newReview[$this->currentStep] .= (!$this->newBlock ? "\n" : "") . $this->currentLine;
                }
            }
            $resultReviews[] = $this->prepareResult($newReview);
        }
        return $resultReviews;
    }

    /**
     * @param $tokenCode
     */
    private function checkToken($tokenCode)
    {
        if (strpos($this->currentLine, $this->parseStepMapping[$tokenCode]) === 0) {
            $this->currentStep = $tokenCode;
            $this->currentLine = str_replace($this->parseStepMapping[$this->currentStep], "", $this->currentLine);
            $this->newBlock = false;
        }
    }

    /**
     * @return bool
     */
    private function isVerifiedLine()
    {
        return strpos($this->currentLine, $this->parseStepMapping["verified"]) === 0;
    }

    /**
     * @param $review
     * @return array
     */
    private function explode($review)
    {
        $reviewLines = explode("\n", $review);
        return array_filter($reviewLines, function ($line) {
            return !empty($line);
        });
    }

    /**
     * @param $review
     * @return mixed
     */
    private function prepareResult($review)
    {
        foreach ($review as $field => &$value) {
            $value = trim($value);
            if ($field === "rating") {
                $value = (int)$value;
            }
        }
        unset($value);

        return $review;
    }
}