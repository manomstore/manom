<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>

<?foreach($arResult["GROUPED_ITEMS"] as $arrValue):?>
	<?if(is_array($arrValue["PROPERTIES"]) && count($arrValue["PROPERTIES"])>0):?>
    <div class="redesign-characteristics col-12">
		<strong class="name-properties"><?=$arrValue["GROUP"]["NAME"]?>:</strong><br>
			<?$count = 0;$secondCol = false;?>
			<?foreach($arrValue['PROPERTIES'] as $i => $prop):
				$count++?>
				<div class="row table-padding">
					<span class="col-6"><?=$prop['NAME']?>: </span>
					<span class="col-6">
                        <?if (is_array($prop['DISPLAY_VALUE'])) {?>
                            <?=implode( '; ',$prop['DISPLAY_VALUE'])?>
                        <?}else{?>
                            <?=$prop['DISPLAY_VALUE']?>
                        <?}?>
                    </span>
				</div>
				<?if(((count($arResult['DISPLAY_PROPERTIES'])/2) <= $count) and $secondCol == false){
					$secondCol = true;
					?><?
				}?>
			<?endforeach;?>
    </div>
	<?endif;?>
<?endforeach;?>

<?if(is_array($arResult["NOT_GROUPED_ITEMS"]) && count($arResult["NOT_GROUPED_ITEMS"])>0):?>
    <div class="redesign-characteristics col-12">
        <strong class="name-properties">Другие:</strong><br>
            <?$count = 0;$secondCol = false;?>
            <?foreach($arResult["NOT_GROUPED_ITEMS"] as $i => $prop):
                $count++?>
                <div class="row table-padding">
                    <span class="col-6"><?=$prop['NAME']?>: </span>
                    <span class="col-6">
                        <?if (is_array($prop['DISPLAY_VALUE'])) {?>
                            <?=implode( '; ',$prop['DISPLAY_VALUE'])?>
                        <?}else{?>
                            <?=$prop['DISPLAY_VALUE']?>
                        <?}?>
                    </span>
                </div>
                <?if(((count($arResult['DISPLAY_PROPERTIES'])/2) <= $count) and $secondCol == false){
                    $secondCol = true;
                    ?><?
                }?>
            <?endforeach;?>
    </div>
<?endif;?>
