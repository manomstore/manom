<?

use \Bitrix\Highloadblock\HighloadBlockTable;

IncludeModuleLangFile(__FILE__);

class hozberg_characteristics extends CModule
{
    const MODULE_ID = 'hozberg.characteristics';
    var $MODULE_ID = 'hozberg.characteristics';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;
    var $MODULE_GROUP_RIGHTS = "Y";
    public $NEED_MODULES = ["main" => "14.0.0"];

    /**
     * Инициализация модуля для страницы 'Управление модулями'
     */
    public function __construct()
    {
        include(__DIR__ . '/version.php');
        /** @var array $arModuleVersion */
        $this->MODULE_NAME = GetMessage('HOZBERG_CHARACTERISTICS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('HOZBERG_CHARACTERISTICS_MODULE_DESC');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->PARTNER_NAME = "Hozberg";
        $this->PARTNER_URI = "http://hozberg.com";
    }

    /**
     * Устанавливаем модуль
     */
    public function DoInstall()
    {
        if (!\Bitrix\Main\Loader::includeModule("highloadblock")) {
            return false;
        }

        if (!$this->InstallEvents()) {
            return false;
        }

        if (!$this->InstallHighLoad()) {
            return false;
        }

        if (!$this->InstallFiles()) {
            return false;
        }
        RegisterModule(self::MODULE_ID);

        \Bitrix\Main\Loader::includeModule(self::MODULE_ID);

        return true;
    }

    /**
     * Копируем файлы административной части
     *
     * @return bool
     */
    public function InstallFiles()
    {

        CopyDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true, true);

        return true;
    }

    public function InstallEvents()
    {
        RegisterModuleDependences("main", "OnBuildGlobalMenu", $this->MODULE_ID,
            \Hozberg\Characteristics::class, "HandlerOnBuildGlobalMenu", 100000);

        RegisterModuleDependences("iblock", "OnAfterIBlockPropertyDelete", $this->MODULE_ID,
            \Hozberg\Characteristics::class, "HandlerOnAfterIBlockPropertyDelete", 100000);
        return true;
    }

    public function UnInstallEvents()
    {
        UnRegisterModuleDependences("main", "OnBuildGlobalMenu", $this->MODULE_ID,
            \Hozberg\Characteristics::class, "HandlerOnBuildGlobalMenu");

        UnRegisterModuleDependences("iblock", "OnAfterIBlockPropertyDelete", $this->MODULE_ID,
            \Hozberg\Characteristics::class, "HandlerOnAfterIBlockPropertyDelete");
        return true;
    }

    public function InstallHighLoad()
    {
        $data = [
            'NAME' => "HozbergCharacteristics",
            'TABLE_NAME' => "b_hlbd_hozberg_characteristics",
        ];

        try {
            $result = HighloadBlockTable::add($data);
        } catch (\Exception $e) {
            return false;
        }

        if (!$result->isSuccess()) {
            return false;
        }

        $this->setHLId($result->getId());

        $this->createHLFields();

        return true;
    }

    private function createHLFields()
    {
        $HLBlockId = $this->getHLId();
        if (!$HLBlockId) {
            return false;
        }

        $oUserTypeEntity = new CUserTypeEntity();
        $oUserTypeEntity->Add(
            [
                'ENTITY_ID' => "HLBLOCK_{$HLBlockId}",
                'FIELD_NAME' => 'UF_PROPERTY_ID',
                'USER_TYPE_ID' => 'integer',
                'XML_ID' => 'XML_ID_PROPERTY_ID',
                'SORT' => 500,
                'MULTIPLE' => 'N',
                'MANDATORY' => 'N',
                'SHOW_FILTER' => 'N',
                'SHOW_IN_LIST' => '',
                'EDIT_IN_LIST' => 'N',
                'IS_SEARCHABLE' => 'N',
                'EDIT_FORM_LABEL' => [
                    'ru' => 'ID Свойства',
                    'en' => 'Property ID',
                ],
                'LIST_COLUMN_LABEL' => [
                    'ru' => 'ID Свойства',
                    'en' => 'Property ID',
                ],
            ]
        );
    }

    private function getHLId()
    {
        $characteristicsHLId = (int)\Bitrix\Main\Config\Option::get($this->MODULE_ID, "characteristics_hl_id", 0);

        if ($characteristicsHLId < 0) {
            $characteristicsHLId = $this->getHLIdByName();
        }

        return $characteristicsHLId;
    }

    private function setHLId($HLBlockId)
    {
        \Bitrix\Main\Config\Option::set($this->MODULE_ID, "characteristics_hl_id", (int)$HLBlockId);
    }

    public function UnInstallHighLoad()
    {
        $HLBlockId = $this->getHLId();
        $result = HighloadBlockTable::delete($HLBlockId);

        if (!$result->isSuccess()) {
            return false;
        }

        return true;
    }

    /**
     * Удаляем модуль
     */
    public function DoUninstall()
    {
        if (!$this->checkVersion()) {
            return false;
        }

        if (!\Bitrix\Main\Loader::includeModule("highloadblock")) {
            return false;
        }

        $this->UnInstallFiles();
        $this->UnInstallHighLoad();
        $this->UnInstallEvents();

        UnRegisterModule(self::MODULE_ID);

        COption::RemoveOption(self::MODULE_ID);

        return true;
    }

    private function checkVersion()
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;
        if (is_array($this->NEED_MODULES) && !empty($this->NEED_MODULES)) {
            foreach ($this->NEED_MODULES as $module => $version) {
                $module = strtolower($module);
                if (!IsModuleInstalled($module)) {
                    $APPLICATION->ThrowException(
                        GetMessage('HOZBERG_CHARACTERISTICS_NEED_INSTALL',
                            [
                                '#MODULE#' => $module,
                                '#VERSION#' => $version
                            ]
                        )
                    );

                    return false;
                } else {
                    $info = \CModule::CreateModuleObject($module);
                    if (!$info || !CheckVersion($info->MODULE_VERSION, $version)) {
                        $APPLICATION->ThrowException(
                            GetMessage('HOZBERG_CHARACTERISTICS_NEED_UPDATE',
                                [
                                    '#MODULE#' => $module,
                                    '#VERSION#' => $version
                                ]
                            )
                        );

                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function getHLIdByName()
    {
        $HLBlock = HighloadBlockTable::getList([
            "filter" => [
                "NAME" => "HozbergCharacteristics"
            ]
        ])->fetch();

        return (int)$HLBlock["ID"];
    }

    /**
     * Удаляем файлы административной части
     *
     * @return bool
     */
    function UnInstallFiles()
    {
        DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        DeleteDirFiles(__DIR__ . "/themes", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes");

        return true;
    }

//	function GetModuleRightList() {
//		$arr = [
//			"reference_id" => ["D", "R", "S", "W"],
//			"reference"    => [
//				"[D] " . GetMessage("HOZBERG_CHARACTERISTICS_RIGHTS_D"),
//				"[R] " . GetMessage("HOZBERG_CHARACTERISTICS_RIGHTS_R"),
//				"[S] " . GetMessage("HOZBERG_CHARACTERISTICS_RIGHTS_S"),
//				"[W] " . GetMessage("HOZBERG_CHARACTERISTICS_RIGHTS_W"),
//			],
//		];
//
//		return $arr;
//	}

}
