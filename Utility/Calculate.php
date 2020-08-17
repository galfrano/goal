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

}
