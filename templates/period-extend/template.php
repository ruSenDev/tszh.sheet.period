<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CJSCore::Init(array('tszh_datepicker', 'tszh_tabs'));

if ($arParams["DISPLAY_TOP_PAGER"]) {
    echo $arResult['NAV_STRING'];
}

?>

<div class="tabs">
    <input id="tab1" type="radio"
           name="tabs" <?= $arResult["SHEET_TYPE"] == Citrus\Tszh\Types\ReceiptType::OVERHAUL ? '' : 'checked' ?>><label
            for="tab1" title="<? $title = GetMessage("TSZH_SHOW_TAB1_RECEIPT");
    echo $title; ?>"><a
                href="<?= ($arResult["SHEET_TYPE"] == Citrus\Tszh\Types\ReceiptType::OVERHAUL ? $arResult["MAIN_URL"] : "") ?>">
            <div><?= $title ?></div>
        </a></label>
    <? if ($arResult['USER_TSZH']["OVERHAUL_OFF"] == 'Y'): ?>
        <input id="tab2" type="radio"
               name="tabs" <?= $arResult["SHEET_TYPE"] == Citrus\Tszh\Types\ReceiptType::MAIN ? '' : 'checked' ?> >
        <label for="tab2" title="<?
        $title = GetMessage("TSZH_SHOW_TAB2_RECEIPT");
        echo $title; ?>"><a
                    href="<?= ($arResult["SHEET_TYPE"] == Citrus\Tszh\Types\ReceiptType::MAIN ? $arResult["OVERHAUL_URL"] : "") ?>">
                <div><?= $title ?></div>
            </a></label>
    <?endif;
    if ($arResult["SHEET_TYPE"] != Citrus\Tszh\Types\ReceiptType::MAIN) {
        ?>
        <section></section>
        <?
    }
    ?>
    <section>
        <div class="charges-history">
            <form method="post">
                <?= bitrix_sessid_post() ?>
                <div class="charges-history__header">
                    <?= GetMessage('TSZH_SHEET_PERIOD_CHOOSE', array('#period1#' => '<span class="tszh-datepicker"><input name="period1" type="hidden" submit="true"/><span class="tszh-datepicker__value">' . $arResult['PERIOD_START'] . '</span></span>',
                        '#period2#' => '<span class="tszh-datepicker"><input name="period2" type="hidden" submit="true"/><span class="tszh-datepicker__value">' . $arResult['PERIOD_END'] . '</span></span>'
                    )) ?>
                </div>
            </form>
            <div class="history-mobi hidden">
                <?
                if (count($arResult['PERIODS']) == 0) {
                    echo GetMessage('TSZH_SHEET_NO_DATA');
                }
                $i = 0;
                foreach ($arResult['PERIODS'] as $arPeriod):
                    if (!is_array($arPeriod["ACCOUNT_PERIOD"]))
                        continue;
                    if ($i++ > 0) {
                        ?>
                        <hr/>
                    <? } ?>
                    <div class="history-mobi__period">
                        <div class="history-mobi__block">
                            <div class="history-mobi__month"><?= $arPeriod['DISPLAY_NAME'] ?></div>
                            <div class="history-mobi__item">
                                <div class="history-mobi__name">
                                    <?= GetMessage('TSZH_SHEET_DEBT') . " " . GetMessage('TSZH_SHEET_END') ?>
                                </div>
                                <div class="history-mobi__value">
                                    <?= CTszhPublicHelper::FormatCurrency($arPeriod['TOTAL_DEBT_END']) ?>
                                </div>
                            </div>
                            <div class="history-mobi__item">
                                <div class="history-mobi__name">
                                    <?= GetMessage('TSZH_SHEET_SUMM') ?>
                                </div>
                                <div class="history-mobi__value">
                                    <?= CTszhPublicHelper::FormatCurrency($arPeriod['TOTAL_CHARGES']) ?>
                                </div>
                            </div>
                            <div class="history-mobi__item">
                                <div class="history-mobi__name">
                                    <?= GetMessage('TSZH_SHEET_SUMMPAYED') ?>
                                </div>
                                <div class="history-mobi__value">
                                    <?= CTszhPublicHelper::FormatCurrency($arPeriod['TOTAL_PAYED']) ?>
                                </div>
                            </div>
                            <div class="history-mobi__item">
                                <div class="history-mobi__name">
                                    <?= GetMessage('TSZH_SHEET_DEBT') . " " . GetMessage('TSZH_SHEET_BEG') ?>
                                </div>
                                <div class="history-mobi__value">
                                    <?= CTszhPublicHelper::FormatCurrency($arPeriod['TOTAL_DEBT_BEG']) ?>
                                </div>
                            </div>
                        </div>

                        <!--**********Start****************-->

                        <div class="mobi-table">

                            <div class="mobi-table-body">

                                <? foreach ($arPeriod['CHARGES'] as $arItem):
                                    if ($arItem["DEBT_ONLY"] == "Y" && $arItem["DEBT_END"] == 0) {
                                        continue;
                                    }
                                    ?>
                                    <div class="history-mobi__item-main">
                                        <div class="history-mobi__name-header"><?= $arItem['SERVICE_NAME'] ?></div>
                                        <div class="history-mobi__item-sub">
                                            <div class="history-mobi__item">
                                                <div class="history-mobi__name"><?= GetMessage('TSZH_SHEET_DEBT') . " " . GetMessage('TSZH_SHEET_BEG') ?></div>
                                                <div class="history-mobi__value"><?= CTszhPublicHelper::FormatCurrency($arItem['DEBT_BEG']) ?></div>
                                            </div>
                                            <div class="history-mobi__item">
                                                <div class="history-mobi__name"><?= GetMessage('TSZH_SHEET_SUMM') ?></div>
                                                <div class="history-mobi__value"><?= $arItem["DEBT_ONLY"] != "Y" ? CTszhPublicHelper::FormatCurrency($arItem['SUMM']) : '-' ?></div>
                                            </div>
                                            <div class="history-mobi__item">
                                                <div class="history-mobi__name"><?= GetMessage('TSZH_COMPENSATION') ?></div>
                                                <div class="history-mobi__value"><?= CTszhPublicHelper::FormatCurrency($arItem['COMPENSATION']) ?></div>
                                            </div>
                                            <? if ($arParams["SHOW_SERVICE_CORRECTIONS"]): ?>
                                                <div class="history-mobi__item">
                                                    <div class="history-mobi__name"><?= GetMessage('TSZH_SHEET_CORRECTION') ?></div>
                                                    <div class="history-mobi__value"><?= CTszhPublicHelper::FormatCurrency($arItem['CORRECTION']) ?></div>
                                                </div>
                                            <? endif; ?>
                                            <div class="history-mobi__item">
                                                <div class="history-mobi__name"><?= GetMessage('TSZH_SHEET_TO_PAY') ?></div>
                                                <div class="history-mobi__value"><?= CTszhPublicHelper::FormatCurrency($arItem['DEBT_END']) ?></div>
                                            </div>
                                        </div>
                                    </div>


                                <? endforeach; ?>

                            </div>
                        </div>

                        <!--**********End****************-->

                    </div>
                <? endforeach; ?>
            </div>
            <div class="charges-history__table">
                <table>
                    <thead>
                    <tr>
                        <td><?= GetMessage('TSZH_MONTH') ?></td>
                        <td><?= GetMessage('TSZH_SHEET_DEBT') . " " . GetMessage('TSZH_SHEET_BEG') ?></td>
                        <td><?= GetMessage('TSZH_SHEET_SUMM') ?></td>
                        <td><?= GetMessage('TSZH_TOTAL_FOR_PAYMENT') ?></td>
                        <td><?= GetMessage('TSZH_SHEET_SUMMPAYED') ?></td>
                        <td><?= GetMessage('TSZH_SHEET_DEBT') . " " . GetMessage('TSZH_SHEET_END') ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <? if (is_array($arResult['PERIODS']) && count($arResult['PERIODS']) == 0) {
                        ?>
                        <tr>
                            <td colspan="6">
                                <?= GetMessage('TSZH_SHEET_NO_DATA') ?>
                            </td>
                        </tr>
                        <?
                    } else {
                        if (is_array($arResult['PERIODS'])) {
                            foreach ($arResult['PERIODS'] as $arPeriod):
                                if (!is_array($arPeriod["ACCOUNT_PERIOD"])) {
                                    continue;
                                } ?>
                                <tr class="<?= (isset($arPeriod['ONLY_DEBT']) && $arPeriod['ONLY_DEBT'] == 'Y' ? ' charges-history__period_only_debt' : 'charges-history__period_with_debt') ?>">
                                    <td class="charges-history__period"><?= $arPeriod['DISPLAY_NAME'] ?></td>
                                    <td><?= CTszhPublicHelper::FormatCurrency($arPeriod['TOTAL_DEBT_BEG']) ?></td>
                                    <td><?= CTszhPublicHelper::FormatCurrency($arPeriod['TOTAL_CHARGES']) ?></td>
                                    <td><?= CTszhPublicHelper::FormatCurrency($arPeriod['TOTAL_PAYMENT']) ?></td>
                                    <td><?= CTszhPublicHelper::FormatCurrency($arPeriod['TOTAL_PAYED']) ?></td>
                                    <td><?= CTszhPublicHelper::FormatCurrency($arPeriod['TOTAL_DEBT_END']) ?></td>
                                </tr>
                                <? if (isset($arPeriod['ONLY_DEBT']) && $arPeriod['ONLY_DEBT'] != 'Y') : ?>
                                <tr class="charges-history__services">
                                    <td colspan="6">
                                        <div>


                                            <table>
                                                <thead>
                                                <tr>
                                                    <td><?= GetMessage('TSZH_SHEET_SERVICE') ?></td>
                                                    <td><?= GetMessage('TSZH_SHEET_DEBT') . " " . GetMessage('TSZH_SHEET_BEG') ?></td>
                                                    <td><?= GetMessage('TSZH_SHEET_SUMM') ?></td>
                                                    <td><?= GetMessage('TSZH_COMPENSATION') ?></td>
                                                    <? if ($arParams["SHOW_SERVICE_CORRECTIONS"]): ?>                        {
                                                        <td><?= GetMessage('TSZH_SHEET_CORRECTION') ?></td>
                                                    <? endif; ?>
                                                    <td><?= GetMessage('TSZH_SHEET_TO_PAY') ?></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <? foreach ($arPeriod['CHARGES'] as $arItem):
                                                    if ($arItem["DEBT_ONLY"] == "Y" && $arItem["DEBT_END"] == 0) {
                                                        continue;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?= $arItem['SERVICE_NAME'] ?></td>
                                                        <td><?= CTszhPublicHelper::FormatCurrency($arItem['DEBT_BEG']) ?></td>
                                                        <td><?= $arItem["DEBT_ONLY"] != "Y" ? CTszhPublicHelper::FormatCurrency($arItem['SUMM']) : '-' ?></td>
                                                        <td><?= CTszhPublicHelper::FormatCurrency($arItem['COMPENSATION']) ?></td>
                                                        <? if ($arParams["SHOW_SERVICE_CORRECTIONS"]): ?>
                                                            <td><?= CTszhPublicHelper::FormatCurrency($arItem['CORRECTION']) ?></td>
                                                        <? endif; ?>
                                                        <td><?= CTszhPublicHelper::FormatCurrency($arItem['DEBT_END']) ?></td>
                                                    </tr>
                                                <? endforeach; ?>
                                                </tbody>
                                            </table>
                                            <div class="charges-history__services-close"></div>
                                            <div class="charges-history__print"><a target="_blank"
                                                                                   href="<?= CComponentEngine::MakePathFromTemplate($arParams["RECEIPT_URL"], Array("ID" => $arPeriod["ID"])) ?>"><?= GetMessage('TSZH_PRINT_RECEIPT_LONG') ?></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <? endif; ?>
                            <?endforeach;
                        }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?
if ($arParams["DISPLAY_BOTTOM_PAGER"]) {
    echo $arResult["NAV_STRING"];
}

if ($arResult['TOTAL_PAYMENT'] > 0)
    echo '<div>' . GetMessage("CITRUS_TSZHPAYMENT_LINK", Array("#LINK#" => $arResult['PAYMENT_URL'] . '?summ=' . round($arResult['TOTAL_PAYMENT'], 2))) . '</div>'; ?>
