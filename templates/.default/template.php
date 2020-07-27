<?
/**
 * User: mxaleev
 * Date: 08.07.2020
 * Time: 20:34
 * @author Mikhail Khaleev <info@xaleev.ru>
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(false);

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/com.purchase-v1.0.css");

//dump_r($arResult);
?>

<div class="purchase-widget">

    <?if($arResult):?>

        <?foreach ($arResult as $key => $item):?>

            <div class="purchase">
                <div class="row">
                    <div class="col-xs-30 col-md-32">
                        <p class="pay-date"><?=$item['UF_PAYMENT_DATE_VIEW'];?></p>
                        <h3><?=$item['UF_GOOD_NAME']?> <span>(x<?=$item['UF_QUANTITY']?>)</span></h3>
                    </div>
                    <div class="col-xs-18 col-md-16 details">
                        <p class="price"><span><?=$item['UF_TOTAL_NUMBER']?>,</span><?=$item['UF_TOTAL_DECIMALS']?> ₽</p>
                        <span><?=$item['UF_PAYMENT_NAME']?></span>
                    </div>
                </div>
            </div>

            <?if(count($arResult) > $key+1):?>
                <hr>
            <?endif;?>

        <?endforeach;?>

    <?else:?>

        <div class="purchase">
            <div class="row">
                <div class="col-48">
                    <p>Вы еще ничего не покупали</p>
                </div>
            </div>
        </div>

    <?endif;?>

</div>
