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
	static function groupProducts($data, $by = 'product_id', $name = 'product', $sum = 'cash'){//TODO: consider rewriting functionally
		$newData = [];
		foreach($data as $row){
			empty($newData[$row[$by]]) ? $newData[$row[$by]] = [$name=>$row[$name], $sum=>floatval($row[$sum])] : $newData[$row[$by]][$sum] += floatval($row[$sum]);
		}
		return array_map(function($prod)use($sum){
			$prod[$sum] = number_format($prod[$sum], 2);
			return $prod;
		}, $newData);
	}
}
