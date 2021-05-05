<?

use Manom\Content;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$aMenuLinks = array_merge($aMenuLinks, Content::getMainMenuLinks());
?>
