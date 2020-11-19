<?php

namespace Utility;

trait Calculate{

	static function groupAddMultiply($data, $sumThis, $groupBy, $multiplier){
		$sum = [];
		foreach($data as $row){
//echo "<!-- ".var_export($row[])." -->";
			$amount = floatval($row[$multiplier]);
			$value = $amount*floatval($row[$sumThis]);
			
			if(empty($sum[$row[$groupBy]])){
				$sum[$row[$groupBy]] = [$groupBy=>$row[$groupBy], $multiplier=>$amount, $sumThis=>$value];
			}
			else{
				$sum[$row[$groupBy]] = [$groupBy=>$row[$groupBy], $multiplier=>$sum[$row[$groupBy]][$multiplier]+$amount, $sumThis=>$sum[$row[$groupBy]][$sumThis]+$value];
			}
		}
		return $sum;
	}
	static function totalCash($data, $cash = 'cash'){
		$total = 0;
		foreach($data as $row){
			$total += floatval($row[$cash]);
		}
		return number_format($total, 2);
	}
	static function groupProducts($data, $by = 'product_id', $name = 'product', $qty = 'amount', $sum = 'cash'){//TODO: consider rewriting functionally
		$newData = [];
		foreach($data as $row){
			if(empty($newData[$row[$by]])){
				$newData[$row[$by]] = [$name=>$row[$name], $qty=>intval($row[$qty]), $sum=>floatval($row[$sum])] ;
			}
			else {
				$newData[$row[$by]][$sum] += floatval($row[$sum]);
				$newData[$row[$by]][$qty] += intval($row[$qty]);
			}
		}
		return array_map(function($prod)use($sum){
			$prod[$sum] = number_format($prod[$sum], 2);//TODO: correct! floats should be handled by translator
			return $prod;
		}, $newData);
	}
}
