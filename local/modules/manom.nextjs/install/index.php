<?php

use Bitrix\Main\Localization\Loc;

/**
 * Class manom_nextjs
 */
class manom_nextjs extends CModule
{
    public $MODULE_ID = 'manom.nextjs';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    protected $modulePath;

    /**
     * manom_nextjs constructor.
     */
    public function __construct()
    {
        Loc::loadMessages(__FILE__);

        $arModuleVersion = array();
        include __DIR__.'/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('NEXTJS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('NEXTJS_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('NEXTJS_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('NEXTJS_PARTNER_URI');
    }

    /**
     *
     */
    public function DoInstall()
    {
        $this->InstallDB();
    }

    /**
     *
     */
    public function DoUninstall()
    {
        $this->UnInstallDB();
    }

    /**
     * @param array $aParams
     * @return bool
     */
    public function InstallDB($aParams = array())
    {
//        $this->setOrderProperties();

        RegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @param array $aParams
     * @return bool
     */
    public function UnInstallDB($aParams = array())
    {
        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     *
     */
    public function setOrderProperties()
    {
        $orderProperties = array(
            array(
                'NAME' => 'Ид пункта самовывоза',
                'CODE' => 'SELF_DELIVERY_POINT_ID',
                'PERSON_TYPE_ID' => '1',
                'PROPS_GROUP_ID' => '1',
                'TYPE' => 'STRING',
                'USER_PROPS' => 'Y',
            ),
            array(
                'NAME' => 'Ид пункта доставки',
                'CODE' => 'DELIVERY_POINT_ID',
                'PERSON_TYPE_ID' => '1',
                'PROPS_GROUP_ID' => '1',
                'TYPE' => 'STRING',
                'USER_PROPS' => 'Y',
            ),
        );
        foreach ($orderProperties as $fields) {
            $filter = array(
                'CODE' => $fields['CODE'],
                'PERSON_TYPE_ID' => $fields['PERSON_TYPE_ID'],
                'PROPS_GROUP_ID' => $fields['PROPS_GROUP_ID'],
            );
            $oDbRes = \CSaleOrderProps::GetList(array(), $filter, false, false, array('ID'));
            if (!$aDbRes = $oDbRes->fetch()) {
                \CSaleOrderProps::Add($fields);
            }
        }
    }
}
