<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$css = $APPLICATION->GetCSSArray();
if(!is_array($css) || !in_array("/bitrix/css/main/font-awesome.css", $css))
{
	$strReturn .= '<link href="'.CUtil::GetAdditionalFileURL("/bitrix/css/main/font-awesome.css").'" type="text/css" rel="stylesheet" />'."\n";
}

$strReturn .= '<div class="bx-breadcrumb" itemprop="http://schema.org/breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
$strReturn = '<section class="bread-crumb">
	<div class="container">';
$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > 0? '<span class="bread-crumb__separator">
		<svg version="1.1" width="6px" height="10px" fill-opacity="0.2" viewBox="0 0 6 10">
			<polygon points="112.814,0 91.566,21.178 273.512,203.718 91.566,386.258 112.814,407.436 315.869,203.718 "/>
		</svg>
	</span>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= $arrow.'
			<a href="'.$arResult[$index]["LINK"].'" class="bread-crumb__link">'.$title.'</a>
			';
	}
	else
	{
		$strReturn .= $arrow.'
			<a href="'.$arResult[$index]["LINK"].'" class="bread-crumb__link">'.$title.'</a>
			';
	}
}

$strReturn .= '</div></section>';

return $strReturn;
