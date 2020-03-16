<?php

/**
 * Class eventHandlers
 */
class eventHandlers
{
    /**
     * @param array $aGlobalMenu
     * @param array $aModuleMenu
     */
    public static function onBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu): void
    {
        self::addGlobalMenu($aGlobalMenu);
    }

    /**
     * @param array $aGlobalMenu
     */
    private static function addGlobalMenu(&$aGlobalMenu): void
    {
        $menuItem = array(
            'menu_id' => 'airtable',
            'text' => 'Airtable',
            'title' => 'Airtable',
            'url' => 'airtable_import.php',
            'sort' => 1000,
            'items_id' => 'global_menu_airtable',
            'help_section' => 'airtable',
            'items' => array(
                array(
                    'text' => 'Настройки',
                    'title' => 'Настройки',
                    'url' => 'airtable_settings.php',
                    'icon' => '',
                    'page_icon' => '',
                    'module_id' => 'manom.airtable',
                    'items_id' => 'airtable_settings',
                    'items' => array(),
                ),
                array(
                    'text' => 'Импорт',
                    'title' => 'Импорт',
                    'url' => 'airtable_import.php',
                    'icon' => '',
                    'page_icon' => '',
                    'module_id' => 'manom.airtable',
                    'items_id' => 'airtable_import',
                    'items' => array(),
                ),
            ),
        );

        $aGlobalMenu[] = $menuItem;
    }
}
