<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;

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
        include __DIR__ . '/version.php';

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
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . $this->MODULE_ID)) {
            $this->modulePath = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . $this->MODULE_ID;
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID)) {
            $this->modulePath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID;
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
        $connection = Application::getConnection();

        RegisterModule($this->MODULE_ID);
        $this->createEventTable($connection);
        \CAgent::RemoveModuleAgents($this->MODULE_ID);
        \CAgent::AddAgent("\Manom\Moysklad\Agent::handleEvents();", $this->MODULE_ID, "N", 60);

        return true;
    }

    /**
     * @param \Bitrix\Main\DB\Connection $connection
     * @return void
     */
    public function createEventTable($connection): void
    {
        $tableName = 'ma_moysklad_event';
        $fields = array(
            'id' => '`id` int unsigned not null auto_increment',
            'href_change' => '`href_change` text collate utf8_unicode_ci',
        );

        $tableExits = false;
        $sql = "show tables like '" . $tableName . "'";
        $recordset = $connection->query($sql);
        while ($record = $recordset->fetch()) {
            $tableExits = true;
        }

        if ($tableExits) {
            foreach ($fields as $name => $fieldSql) {
                $fieldExist = false;
                $sql = 'show columns from `' . $tableName . "` where `Field` = '" . $name . "'";
                $recordset = $connection->query($sql);
                while ($record = $recordset->fetch()) {
                    $fieldExist = true;
                }

                if (!$fieldExist) {
                    $connection->query('alter table `' . $tableName . '` add ' . $fieldSql);
                }
            }
        } else {
            $sql = 'create table if not exists ' . $tableName . ' (';

            foreach ($fields as $name => $fieldSql) {
                $sql .= $fieldSql . ',';
            }

            $sql .= 'primary key (id)
) engine = InnoDB collate utf8_unicode_ci;';

            $connection->query($sql);
        }
    }

    /**
     * @param \Bitrix\Main\DB\Connection $connection
     * @return void
     */
    public function removeEventTable($connection): void
    {
        $sql = "drop table if exists ma_airtable_properties_link;";
        $connection->query($sql);
    }

    /**
     * @param array $aParams
     * @return bool
     */
    public function UnInstallDB($aParams = array()): bool
    {

        UnRegisterModule($this->MODULE_ID);
        \CAgent::RemoveModuleAgents($this->MODULE_ID);
//        $connection = Application::getConnection();
//        $this->removeEventTable($connection);

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
