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
            'url' => 'airtable_settings.php',
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
                    'text' => 'Импорт разделов',
                    'title' => 'Импорт разделов',
                    'url' => 'airtable_sections.php',
                    'icon' => '',
                    'page_icon' => '',
                    'module_id' => 'manom.airtable',
                    'items_id' => 'airtable_sections',
                    'items' => array(),
                ),
                array(
                    'text' => 'Импорт элементов',
                    'title' => 'Импорт элементов',
                    'url' => 'airtable_elements.php',
                    'icon' => '',
                    'page_icon' => '',
                    'module_id' => 'manom.airtable',
                    'items_id' => 'airtable_elements',
                    'items' => array(),
                ),
            ),
        );

        $aGlobalMenu[] = $menuItem;
    }
}
