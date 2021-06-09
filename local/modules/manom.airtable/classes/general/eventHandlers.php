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
                array(
                    'text'      => 'Инструменты',
                    'title'     => 'Инструменты',
                    'url'       => '',
                    'icon'      => '',
                    'page_icon' => '',
                    'module_id' => 'manom.airtable',
                    'items_id'  => 'airtable_tools',
                    'items'     => array(
                        array(
                            "parent_menu" => "airtable_tools",
                            "text"        => "Поиск товаров",
                            "title"       => "Поиск товаров",
                            "url"         => "airtable_search_products.php?lang=" . LANGUAGE_ID,
                            "icon"        => "",
                            "sort"        => 100,
                        ),
                        array(
                            "parent_menu" => "airtable_tools",
                            "text"        => "Поиск полей",
                            "title"       => "Поиск полей",
                            "url"         => "airtable_search_fields.php?lang=" . LANGUAGE_ID,
                            "icon"        => "",
                            "sort"        => 100,
                        ),
                    ),
                ),
                array(
                    'text' => 'Привязка полей',
                    'title' => 'Привязка полей',
                    'url' => 'airtable_fields_link.php',
                    'icon' => '',
                    'page_icon' => '',
                    'module_id' => 'manom.airtable',
                    'items_id' => 'airtable_fields_link',
                    'items' => array(),
                ),
            ),
        );

        $aGlobalMenu[] = $menuItem;
    }
}
