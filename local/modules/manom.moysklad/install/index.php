<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;

/**
 * Class manom_moysklad
 */
class manom_moysklad extends CModule
{
    public $MODULE_ID = 'manom.moysklad';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    protected $modulePath;

    /**
     * manom_moysklad constructor.
     */
    public function __construct()
    {
        if (!$this->setModulePath()) {
            return;
        }

        Loc::loadMessages(__FILE__);

        $arModuleVersion = array();
        include __DIR__.'/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('MOYSKLAD_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MOYSKLAD_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('MOYSKLAD_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('MOYSKLAD_PARTNER_URI');
    }

    /**
     * @return bool
     */
    protected function setModulePath(): bool
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID)) {
            $this->modulePath = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID;
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID)) {
            $this->modulePath = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID;
        } else {
            return false;
        }

        return true;
    }

    /**
     *
     */
    public function DoInstall(): void
    {
        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallEvents();
    }

    /**
     *
     */
    public function DoUninstall(): void
    {
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();
    }

    /**
     * @param array $aParams
     * @return bool
     */
    public function InstallDB($aParams = array()): bool
    {
        RegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @param array $aParams
     * @return bool
     */
    public function UnInstallDB($aParams = array()): bool
    {
        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @param array $arParams
     * @return bool
     */
    public function InstallFiles($arParams = array()): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function UnInstallFiles(): bool
    {
        return true;
    }

    /**
     *
     */
    public function InstallEvents(): void
    {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler(
            'catalog',
            'OnSuccessCatalogImport1C',
            $this->MODULE_ID,
            'eventHandlers',
            'OnSuccessCatalogImport1C'
        );
    }

    /**
     *
     */
    public function UnInstallEvents(): void
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            'catalog',
            'OnSuccessCatalogImport1C',
            $this->MODULE_ID,
            'eventHandlers',
            'OnSuccessCatalogImport1C'
        );
    }
}
