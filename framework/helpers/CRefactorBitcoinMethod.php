<?php
/**
 * CRefactorBitcoinMethod helper class file
 * Last Update : Note here ... (Please put desc under each method)
 */	  


class CRefactorBitcoinMethod
{

	private static function getBetween($string, $start, $end){
		$string = " ".$string;
		$ini = strpos($string,$start);
		if ($ini == 0) return "";
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	}
	public static function btcReceiveAddress($my_address){
		$response = file_get_contents('https://blockchain.info/api/receive?method=create&address=' . $my_address .'&shared=false&callback=http://wikishop.sx/webapp/');
		$object = json_decode($response);
		return $object->input_address;
	}
	private static function parseDataFollowBTCReceive($btc_receive_address){
		$data = file_get_contents("https://blockchain.info/address/".$btc_receive_address);
		return $data;
	}
	public static function dataReceive($data,$btc_receive_address){
		$btc_amount = self::getBetween($data, "<span data-c=", " BTC</span>");
		$btc_amount = explode(">",$btc_amount);
		$str=array("\r\n","\r","\n","=");
		$btc_amount = str_replace($str,"",$btc_amount);
		return json_encode(array(
				'btc_amount'=>trim($btc_amount[1]),
				'hash_code'=>$btc_receive_address,
		));
	}
	public static function BitcoinUsage($btc_receive_address){
		$data = self::parseDataFollowBTCReceive($btc_receive_address);
		if(!$data){
			return json_encode(array(
					'error'=> 'false',
				));
		}
		else
		{
			if(strpos($data,$btc_receive_address)){
				return self::dataReceive($data,$btc_receive_address);
			}else{
				return json_encode(array(
					'error'=> 'wrong address',
				));
			}
		}
	
	}
	public static function usdToBtc($dollar){
		$feed = file_get_contents('https://btc-e.com/api/2/btc_usd/ticker');
		$BTC_API = json_decode($feed, true);
		$rate = $BTC_API["ticker"]["low"];
		$total = $dollar / $rate;
		return number_format($total ,7);
	}
	
	public static function BtcToUsd($btc){
		$feed = file_get_contents('https://btc-e.com/api/2/btc_usd/ticker');
		$BTC_API = json_decode($feed, true);
		$rate = $BTC_API["ticker"]["low"];
		$result =  $btc * number_format($rate,2);
		return number_format($result,2);
	}
	
}