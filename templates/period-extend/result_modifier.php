<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams ���������, ������, ���������. �� ����������� ����������� ���� ����������, �� ��������� ��� ������ ��  � ����� template.php. */
/** @var array $arResult ���������, ������/���������. ����������� ����������� ���� ������ ����������. */
/** @var CBitrixComponentTemplate $this ������� ������ (������, ����������� ������) */

if (!empty($arResult['PERIODS'])) {
    foreach ($arResult['PERIODS'] as &$period) {
        if ($period["ONLY_DEBT"] != "Y" && is_array($period["CHARGES"]) && !empty($period["CHARGES"])) {
            foreach ($period["CHARGES"] as $key => &$charge) {
                if ($charge["DEBT_END"] == 0) // ��� ������������� �� ������� ���������� ������, ��� ���� debtend ��� ���������� (<item>) �� �����������
                    $period["CHARGES"][$key]["DEBT_END"] = $charge["SUMM2PAY"];
                if ($charge["CORRECTION"] == 0 && $charge["DEBT_END"] == 0) // ���� �� ������ ��� ������� �������� �� ���� ��������, ��������� ��� �������
                    unset($period["CHARGES"][$key]);
            }
            if (isset($charge))
                unset($charge);
        }
    }
}
if (isset($period))
    unset($period);

$arResult['TOTAL_PAYMENT'] = 0;
if (is_array($arResult['PERIODS']))
{
	foreach ($arResult['PERIODS'] as $arPeriod)
	{
		if (!is_array($arPeriod["ACCOUNT_PERIOD"]) || $arPeriod["ONLY_DEBT"] == "Y")
		{
			continue;
		}
		$debtEnd = $arPeriod["ACCOUNT_PERIOD"]["DEBT_END"];
		break;
	}
}

$arResult['TOTAL_PAYMENT'] = $debtEnd;
if (COption::GetOptionString("citrus.tszh", "pay_to_executors_only", "N") == "Y")
{
	$arResult['TOTAL_PAYMENT'] = 0;
	foreach ($arResult["PERIODS"] as $period)
	{
		if ($period["ACCOUNT_PERIOD"])
		{
			$arResult['TOTAL_PAYMENT'] = CTszhAccountContractor::GetList(array(), array(
				"ACCOUNT_PERIOD_ID" => $period["ACCOUNT_PERIOD"]["ID"],
				"!CONTRACTOR_EXECUTOR" => "N"
			), array("SUMM"))->Fetch();
			$arResult['TOTAL_PAYMENT'] = is_array($arResult['TOTAL_PAYMENT']) ? $arResult['TOTAL_PAYMENT']['SUMM'] - $period["ACCOUNT_PERIOD"]["PREPAYMENT"] : 0;
			break;
		}
	}
}
