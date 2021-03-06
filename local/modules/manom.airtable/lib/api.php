<?php

namespace Manom\Airtable;

use \TANIOS\Airtable\Airtable;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentOutOfRangeException;

/**
 * Class Api
 * @package Manom\Airtable
 */
class Api
{
    private $airtableId;
    private $apiKey;
    private $airtable;
    private $sections;
    public $scanTables = false;
    public $allRecords = false;

    /**
     * Api constructor.
     * @throws SystemException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public function __construct()
    {
        $tools = new Tools;

        $this->airtableId = $tools->getAirtableId();
        if (empty($this->airtableId)) {
            throw new SystemException('Не указан Airtable ID');
        }

        $this->apiKey = $tools->getApiKey();
        if (empty($this->apiKey)) {
            throw new SystemException('Не указан API Key');
        }

        $this->initAirtable();
        if (!is_object($this->airtable)) {
            throw new SystemException('Не удалось создать объект airtable');
        }

        $this->sections = $tools->getSections();
    }

    /**
     *
     */
    private function initAirtable(): void
    {
        $this->airtable = new Airtable(
            array(
                'api_key' => $this->apiKey,
                'base' => $this->airtableId,
            )
        );
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $data = array();

        foreach ($this->sections as $section) {
            $data[$section] = $this->getFromSection($section);
        }

        return $data;
    }

    /**
     * @param string $section
     * @return array
     */
    public function getFromSection($section): array
    {
        $data = array();

        $params = array(
            'filterByFormula' => "AND({Статус 1} = 'ОДОБРЕНО', {Статус 2} = 'ОДОБРЕНО', {Внешний код} != '')",
        );

        if ($this->scanTables) {
            unset($params['filterByFormula']);
            if (!$this->allRecords) {
                $params['maxRecords'] = 10;
            }
        }

        $request = $this->airtable->getContent(rawurlencode($section), $params);
        do {
            $response = $request->getResponse();

            if ($response['records'] !== null) {
                $data = json_decode(json_encode($response['records']), true);
            }
        } while ($request = $response->next());

        return $data;
    }

    /**
     * @param string $section
     * @param array $itemsId
     * @return bool
     */
    public function setStatus($section, $itemsId): bool
    {
        $items = array();

        $i = 0;
        $tmpItems = array();
        foreach ($itemsId as $id) {
            if (empty($id)) {
                continue;
            }

            $i++;

            $tmpItems[] = array(
                'id' => $id,
                'fields' => array(
                    'Статус 1' => 'ВЫГРУЖЕНО В БИТРИКС',
                    'Статус 2' => 'ВЫГРУЖЕНО В БИТРИКС',
                ),
            );

            if ($i === 10) {
                $items[] = $tmpItems;
                $i = 0;
                $tmpItems = array();
            }
        }

        if (!empty($tmpItems)) {
            $items[] = $tmpItems;
        }

        $result = true;
        foreach ($items as $stepItems) {
            $response = $this->airtable->updateContent(rawurlencode($section), $stepItems);
            $result = !empty($response['records']);
        }

        return $result;
    }
}
