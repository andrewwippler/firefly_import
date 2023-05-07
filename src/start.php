#!/usr/bin/env php
<?php
namespace Andrew\FireflyImport;
require(__DIR__ . '/../vendor/autoload.php');

use \cebe\gnucash\GnuCash;
use \Money\Money;
use \Money\Currency;
use \Money\Currencies;
use \Money\Currencies\ISOCurrencies;
use \Money\Formatter\DecimalMoneyFormatter;

$currencies = new ISOCurrencies();
$moneyFormatter = new DecimalMoneyFormatter($currencies);


$fp = fopen(__DIR__ .'/all-transactions.csv', 'a');
$fp2 = fopen(__DIR__ .'/txt.json', 'a');
$write=[];
$json=[];

$xmlFile = __DIR__ ."/../firefly_gz.gnucash";

$gnucash = new GnuCash($xmlFile);

function getParent($item) {
  if ($item->name == "Root Account") {
    return;
  }
  return getParent($item->parent).":".$item->name;
}

foreach($gnucash->books as $book) {
  foreach($book->transactions as $t) {
    $arr = array(
      'id' => $t->id,
      'datePosted' => $t->datePosted,
      'description' => $t->description,
      'splits' => [],
    );
    foreach ($t->splits as $s) {

      $arr['splits'][] = [
        'account' => ltrim(getParent($s->account), ':'),
        'value' => $moneyFormatter->format($s->value),
        'memo' => $s->memo,
      ];
    }
    if (count($arr['splits']) != 2) {
      $json[] = $arr;
    } else {
      $write[] = [
        'id' => $arr['id'],
        'from' => $arr['splits'][1]['account'],
        'to' => $arr['splits'][0]['account'],
        'amount' => $arr['splits'][1]['value'],
        'description' => $arr['description'],
        'memo' => $arr['splits'][1]['memo'].'|'.$arr['splits'][0]['memo'],
        'datePosted' => date("m/d/Y", strtotime($arr['datePosted'])),
      ];
    }
  }
}

foreach ($write as $w) {
  fputcsv($fp, $w);
}
fclose($fp);

fwrite($fp2, json_encode($json));
fclose($fp2);
