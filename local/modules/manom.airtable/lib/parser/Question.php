<?php

namespace Manom\Airtable\Parser;


/**
 * Class Question
 * @package Manom\Airtable\Parser
 */
class Question
{
    /**
     * @var array
     */
    private $newQuestion = [
        "question" => "",
        "answer" => "",
    ];

    /**
     * Questions constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $questions
     * @return array
     */
    public function parse($questions)
    {
        if (empty($questions)) {
            return [];
        }

        $questions = explode("\n\n", $questions);
        $resultQuestions = [];

        foreach ($questions as $question) {
            $newQuestion = $this->newQuestion;
            $questionLines = $this->explode($question);
            $newQuestion["question"] = trim(array_shift($questionLines));
            $newQuestion["answer"] = trim(array_shift($questionLines));
            $resultQuestions[] = $newQuestion;
        }
        return $resultQuestions;
    }

    /**
     * @param $question
     * @return array
     */
    private function explode($question)
    {
        $questionLines = explode("\n", $question);
        return array_filter($questionLines, function ($line) {
            return !empty($line);
        });
    }
}