<?php 

use App\Models\Users_model;
use App\Models\General_model;
use App\Models\Auth_model;
use App\Models\Currencies_model;
use App\Models\Orders_model;


if ( ! function_exists('getCryptoCurrencyFormat'))
{
    function getCryptoCurrencyFormat($price,$currency='CRYPTO')
    {

      $currency = strtoupper($currency);
      	
      if(is_numeric($price))
      {
      	if($currency == 'USD')
      		return number_format(abs($price),2,'.','');
      	else			
      		return number_format(abs($price),8,'.','');
      }	
      else
      {	
      	if($currency == 'USD')
      		return number_format(0,2,'.','');
      	else
      		return number_format(0,8,'.','');

      }	
    }   
}

if ( ! function_exists('getIPAddress'))
{
    function getIPAddress()
    {

      return $ip = $_SERVER['HTTP_HOST'];


    }   
}


if ( ! function_exists('getCurrencyName'))
{
    function getCurrencyName($currency_code)
    {
      $params = array('currency_code' => $currency_code, 'result_type' => 'FIRST');
      $currency = Currencies_model::getCurrencies($params);

      if(empty($currency))
      {	
      	return "";
      }	
      else
      {
      	return $currency->currency_name;
      }	
    }   
}

if ( ! function_exists('getUserAccountBalance'))
{
    function getUserAccountBalance($user_id,$currency)
    {
       $currency = strtolower($currency);

       $params = array('user_id'=>$user_id, 'result_type' => 'FIRST');
       $result = Users_model::getUserAccountBalances($params);	

       if(empty($result))
       		return getCryptoCurrencyFormat(0,$currency);

       $account_balance = (isset($result->$currency))?getCryptoCurrencyFormat($result->$currency,$currency):getCryptoCurrencyFormat(0,$currency);

       return $account_balance;
    }   
}


if ( ! function_exists('getLowestAsk'))
{
    function getLowestAsk($first_currency,$second_currency)
    {       
       $lowest_ask = getCoinMarketPrice($first_currency,$second_currency);
       $lowest_ask = getCryptoCurrencyFormat($lowest_ask,$second_currency);

	   	//Get All Sell Orders ascending by price
	    $pair = strtolower($first_currency)."_".strtolower($second_currency);
		$params = array('status' => 'ACTIVE', 'order_action' => 'SELL', 'pair' => $pair,'order_by'=>array('price'=>'ASC'),'per_page' => 1, 'result_type' => 'FIRST', 'result_type' => 'FIRST');

		if(isLogin())
		{
			//$params['not_equal_user_id'] = Session::Get('user_id');
		}	

		$sell_orders = Orders_model::getOrders($params);

		if(!empty($sell_orders))
		{
			$lowest_ask = $sell_orders->price;	
		}	
       
       return $lowest_ask;
    }   
}


if ( ! function_exists('getHighestBid'))
{
    function getHighestBid($first_currency,$second_currency)
    {
       $highest_bid = getCoinMarketPrice($first_currency,$second_currency);

       $highest_bid = getCryptoCurrencyFormat($highest_bid,$second_currency);

       //Get All Sell Orders ascending by price
	    $pair = strtolower($first_currency)."_".strtolower($second_currency);
		$params = array('status' => 'ACTIVE', 'order_action' => 'BUY', 'pair' => $pair,'order_by'=>array('price'=>'DESC'),'per_page' => 1, 'result_type' => 'FIRST');

		if(isLogin())
		{
			//$params['not_equal_user_id'] = Session::Get('user_id');
		}

		$buy_orders = Orders_model::getOrders($params);

		if(!empty($buy_orders))
		{
			$highest_bid = $buy_orders->price;	
		}

       return $highest_bid;
    }   
}

if ( ! function_exists('getCoinMarketPrice'))
{
    function getCoinMarketPrice($first_currency,$second_currency='USD')
    {
       
       //$json = file_get_contents('https://api.coinmarketcap.com/v2/ticker');
	   //$results = json_decode($json, true);	

	   $results = array();

	   if($second_currency == 'USD' && $first_currency == 'BTC')
	   	$market_price = getCryptoCurrencyFormat(3784.58,$second_currency);
	   else if($second_currency == 'USD' && $first_currency == 'LTC')
	   	$market_price = getCryptoCurrencyFormat(44.36,$second_currency);
	   else if($second_currency == 'LTC' && $first_currency == 'BTC')
	   	$market_price = getCryptoCurrencyFormat(85.26,$second_currency);
	   else if($second_currency == 'BTC' && $first_currency == 'LTC')
	   	$market_price = getCryptoCurrencyFormat(0.012,$second_currency);
	   else
	   	$market_price = getCryptoCurrencyFormat(0,$second_currency);

	  /* if(count($results) > 0)
	   {	
		   foreach($results['data'] as $result)
		   {
		   		if($result['symbol'] == strtoupper($first_currency))
		   		{
		   			$first_currency_market_price = $result['quotes']['USD']['price'];	
		   		}	
		   }

		   $market_price = $first_currency_market_price;

			if($second_currency != 'USD')
			{

			   $second_currency_market_price = 1;
			   		
			   foreach($results['data'] as $result)
			   {
			   		if($result['symbol'] == strtoupper($second_currency))
			   		{
			   			$second_currency_market_price = $result['quotes']['USD']['price'];	
			   		}	
			   }

			    $market_price = $first_currency_market_price / $second_currency_market_price;
			    $market_price = getCryptoCurrencyFormat($market_price,$second_currency);	
			}
		}
		*/

		return $market_price;	   
    }   
}


if ( ! function_exists('getTradeValues'))
{
    function getTradeValues($order_id,$action)
    {

       $params = array('is_sum_trade_value' => 'Y','status'=>'COMPLETED', 'result_type' => "FIRST");

       if($action == "BUY")
       {
       	 $params['buyer_order_id'] = $order_id;
       }
       else
       {
       	 $params['seller_order_id'] = $order_id;
       }	

       $result = Orders_model::getOrdersTrading($params);	       

       $totals = new stdClass();
       if(empty($result))
       {
       		$totals->total_trade_amount = 0;
			    $totals->total_trade_price 	= 0;       		
       }
       else
       {
       		$totals = $result;
       }	

       return $totals;
    }   
}


if ( ! function_exists('getLastTradePrice'))
{
    function getLastTradePrice($pair)
    {
        $params = array('pair' => $pair, 'result_type' => 'FIRST');
        $pair_detail = Currencies_model::getPairs($params);

        $params = array('status' => 'COMPLETED', 'order_by' => array('trade_id'=>'DESC'),'per_page' => 1,'result_type' => 'FIRST');
        $result = Orders_model::getOrdersTrading($params);

        if(empty($result))
        	return getCryptoCurrencyFormat(0,$pair_detail->second_currency);
        else
        	return getCryptoCurrencyFormat($result->trade_price,$pair_detail->second_currency);
    }   
}

if ( ! function_exists('get24hrChange'))
{
    function get24hrChange($pair)
    {
    	$current_time = date('Y-m-d H:i:s');
    	$before_time = date("Y-m-d H:i:s",strtotime("-1 day",strtotime($current_time)));
        
        $before_result = DB::table("orders_trading")->where('trade_id', DB::raw(" ( select max(trade_id) from ". DB::getTablePrefix() ."orders_trading where pair ='".$pair."' AND  created_at <='".$before_time."' AND status = 'COMPLETED' ) "))->first();

        $current_result = DB::table("orders_trading")->where('trade_id', DB::raw(" ( select max(trade_id) from ". DB::getTablePrefix() ."orders_trading where pair ='".$pair."' AND  created_at <='".$current_time."' AND status = 'COMPLETED' ) "))->first();

        
        if(empty($before_result) || empty($current_result))
        {	
        	$result['percentage'] = 0;
        	return $result;
        }	
        else
        {
        	$before 	= $before_result;
        	$current 	= $current_result;

        	if($before->trade_price > $current->trade_price)
        	{
        		//Decrease
        		$result['status'] = 'DECREASE';
        		$decrease = $before->trade_price - $current->trade_price;
        		$percentage_decrease = $decrease / $before->trade_price * 100;

        		$result['percentage'] = $percentage_decrease;	
        	}
        	else
        	{
        		//Increase
        		$result['status'] = 'INCREASE';
        		$increase = $current->trade_price - $before->trade_price;
        		$percentage_increase = $increase / $current->trade_price * 100;

        		$result['percentage'] = $percentage_increase;
        	}	

        	return $result;
       	}	
    }   
}

if ( ! function_exists('get24hrHighandLow'))
{
    function get24hrHighandLow($pair)
    {    	

    	$current_time = date('Y-m-d H:i:s');
    	$before_time = date("Y-m-d H:i:s",strtotime("-1 day",strtotime($current_time)));
        
        $high_result = DB::table("orders_trading")->where('trade_price', DB::raw(" (select max(trade_price) from osz_orders_trading where pair ='".$pair."' AND created_at >='".$before_time."' AND created_at <='".$current_time."' AND status = 'COMPLETED' )" ))->first();

        $low_result = DB::table("orders_trading")->where('trade_price', DB::raw(" (select min(trade_price) from osz_orders_trading where pair ='".$pair."' AND  created_at >='".$before_time."' AND created_at <='".$current_time."' AND status = 'COMPLETED' )" ))->first();

        $params = array('pair' => $pair, 'result_type' => 'FIRST');
        $pair_detail = Currencies_model::getPairs($params);

        if(empty($high_result) || empty($low_result))
        {	
        	$result['high'] = getHighestBid($pair_detail->first_currency,$pair_detail->second_currency);
        	$result['low'] = getLowestAsk($pair_detail->first_currency,$pair_detail->second_currency);
        }	
        else
        {
        	$result['high'] = getCryptoCurrencyFormat($high_result->trade_price,$pair_detail->second_currency);
        	$result['low'] 	= getCryptoCurrencyFormat($low_result->trade_price,$pair_detail->second_currency);

        	return $result;
       	}	

       	return $result;
    }   
}


if ( ! function_exists('get24hrVolume'))
{
    function get24hrVolume($pair)
    {
    	$params = array('pair' => $pair, 'result_type' => 'FIRST');
      $pair_detail = Currencies_model::getPairs($params);

    	$current_time = date('Y-m-d H:i:s');
    	$before_time = date("Y-m-d H:i:s",strtotime("-1 day",strtotime($current_time)));

      $first_currency_volume = DB::table('orders_trading')->select( DB::raw("IFNULL(sum(trade_amount),0) as total_trade_amount"))->where('pair',$pair)->where('status','COMPLETED')->first();

        if(empty($first_currency_volume))
        {
        	 $result['first_currency_volume'] = getCryptoCurrencyFormat(0,$pair_detail->first_currency);
       	}	
        else
        {
        	 $result['first_currency_volume'] = getCryptoCurrencyFormat($first_currency_volume->total_trade_amount,$pair_detail->first_currency);
        }

        $second_currency_volume = DB::table('orders_trading')->select( DB::raw("IFNULL(sum(trade_total),0) as total_trade_price"))->where('pair',$pair)->where('status','COMPLETED')->first();
	

        if(empty($second_currency_volume))
        {
        	 $result['second_currency_volume'] = getCryptoCurrencyFormat(0,$pair_detail->second_currency);
        }	
        else
        {
        	 $result['second_currency_volume'] = getCryptoCurrencyFormat($second_currency_volume->total_trade_price,$pair_detail->second_currency);
        }	

       	return $result;
    }   
}

?>