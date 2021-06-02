<?php

namespace Manom\Moysklad\Moysklad\Entity;


use Manom\Moysklad\Tools;

/**
 * Class BaseEntity
 * @package Manom\Moysklad\Moysklad\Entity
 */
abstract class BaseEntity
{
    /**
     * @var \stdClass|null
     */
    protected $entityData = null;

    /**
     * @var string|null
     */
    public $errorType = null;

    /**
     * @var string
     */
    protected $entityName = "";

    /**
     * BaseEntity constructor.
     * @param String $entityUrl
     */
    public function __construct(String $entityUrl)
    {
        try {
            $this->entityData = Tools::sendRequest($entityUrl);
            $this->setAgent();
            $this->setProject();
            $this->entityName = $this->entityData->meta->type;
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * @throws \Exception
     */
    protected function setAgent(): void
    {
        if (empty($this->entityData->agent->meta->href)) {
            $this->entityData->agent = null;
            return;
        }
        $agentData = Tools::sendRequest($this->entityData->agent->meta->href);
        if (empty($agentData)) {
            throw new \Exception("Пустой ответ");
        }
        $this->entityData->agent = $agentData;
    }

    /**
     * @throws \Exception
     */
    protected function setProject(): void
    {
        if (empty($this->entityData->project->meta->href)) {
            $this->entityData->project = null;
            return;
        }
        $projectData = Tools::sendRequest($this->entityData->project->meta->href);
        if (empty($projectData)) {
            throw new \Exception("Пустой ответ");
        }
        $this->entityData->project = $projectData;
    }

    /**
     * @return bool
     */
    public function isCreatedByYMarket(): bool
    {
        return $this->entityData->agent->code === $_ENV['AGENT_CODE_YM']
            && $this->entityData->project->code === $_ENV['PROJECT_CODE_YM']
            && empty($this->entityData->owner);
    }

    /**
     *
     */
    public function delete(): void
    {
        $id = $this->entityData->id;
        if (empty($id)) {
            return;
        }

        try {
            Tools::sendRequest("https://online.moysklad.ru/api/remap/1.2/entity/{$this->entityName}/{$id}", "DELETE");
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * @return bool
     */
    protected function hasError(): bool
    {
        return $this->errorType !== null;
    }

    /**
     * @param \Exception $e
     */
    protected function handleError(\Exception $e): void
    {
        if ($e->getCode() === 404) {
            $this->errorType = "notFound";
            return;
        }

        $this->errorType = "otherError";
        \Manom\Tools::errorToLog($e, "ms_api");
    }

    /**
     * @return bool
     */
    public function hasNotFoundError(): bool
    {
        return $this->errorType === "notFound";
    }

    /**
     * @return bool
     */
    public function hasOtherError(): bool
    {
        return $this->errorType === "otherError";
    }
}