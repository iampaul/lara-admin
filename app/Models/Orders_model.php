<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Orders_model extends Model
{
	/* Orders */
    public static function checkOrderExists($condition=array()) 
    {
    	if(count($condition) > 0)
    	{       	 	
       	 	return DB::table('orders')->where($condition)->count(); 
        }	
    }

    public static function insertOrder($insertData) 
    {        
        if(count($insertData) > 0)
        {
    		$id = DB::table('orders')->insertGetId($insertData);
    		return $id;
        }	
    }

    public static function getOrders($params)
    {    	
    	$query = DB::table('orders');
    	
    	//Fields
    	if(!empty($params['fields']))
    	{
    		$fields = $params['fields'];
    	}
    	else
    	{
    		$fields = array('orders.*');	
    	}	

    	if(!empty($params['order_id']))
    	{
    		$query->where('orders.order_id',$params['order_id']);
    	}

        if(!empty($params['user_id']))
        {
            $query->where('orders.user_id',$params['user_id']);
        }

        if(!empty($params['not_equal_user_id']))
        {
            $query->where('orders.user_id','!=',$params['user_id']);
        }

        if(!empty($params['order_action']))
        {
            $query->where('orders.order_action',$params['order_action']);
        }

        if(!empty($params['pair']))
        {
            $query->where('orders.pair',$params['pair']);
        }

        if(!empty($params['price_less_and_equal']))
        {
            $query->where('orders.price','<=',$params['price_less_and_equal']);
        }

        if(!empty($params['price_greater_and_equal']))
        {
            $query->where('orders.price','>=',$params['price_greater_and_equal']);
        }

        if(!empty($params['stop_price_less_and_equal']))
        {
            $query->where('orders.stop_price','<=',$params['stop_price_less_and_equal']);
        }

        if(!empty($params['stop_price_greater_and_equal']))
        {
            $query->where('orders.stop_price','>=',$params['stop_price_greater_and_equal']);
        }

        if(!empty($params['status']))
        {
            $query->where('orders.status',$params['status']);
        }

        if(!empty($params['status_in']))
        {
            $query->whereIn('orders.status',$params['status_in']);            
        }

        if(!empty($params['type_of_order']))
        {
            $fields[] = DB::raw('(CASE WHEN '. DB::getTablePrefix() .'orders.order_type = "INSTANT" THEN "Instant" WHEN '. DB::getTablePrefix() .'orders.order_type = "LIMIT" and '. DB::getTablePrefix() .'orders.status = "ACTIVE" THEN "Limit" WHEN '. DB::getTablePrefix() .'orders.order_type = "LIMIT" and '. DB::getTablePrefix() .'orders.status = "STOPPED" THEN "Stop-Limit" END) as type_of_order');
        }

        if(!empty($params['is_sum_trade_values']))
        {
            
            if(!empty($params['order_action']))
            {    

                if($params['order_action'] == "BUY")
                {    
                   $fields[] = DB::raw('IFNULL((SELECT SUM(trade_amount) as total_trade_amount FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.buyer_order_id = '. DB::getTablePrefix() .'orders.order_id),0) as total_trade_amount');
                   $fields[] = DB::raw('IFNULL((SELECT SUM(trade_total) as total_trade_price FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.buyer_order_id = '. DB::getTablePrefix() .'orders.order_id),0) as total_trade_price');
                   $fields[] = DB::raw('ABS(IFNULL((SELECT SUM(trade_amount) as total_trade_amount FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.buyer_order_id = '. DB::getTablePrefix() .'orders.order_id),0) - '. DB::getTablePrefix() .'orders.amount) as remain_order_amount');
                }  
                else
                {
                    $fields[] = DB::raw('IFNULL((SELECT SUM(trade_amount) as total_trade_amount FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.seller_order_id = '. DB::getTablePrefix() .'orders.order_id),0) as total_trade_amount');
                    $fields[] = DB::raw('IFNULL((SELECT SUM(trade_total) as total_trade_price FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.seller_order_id = '. DB::getTablePrefix() .'orders.order_id),0) as total_trade_price');
                    $fields[] = DB::raw('ABS(IFNULL((SELECT SUM(trade_amount) as total_trade_amount FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.seller_order_id = '. DB::getTablePrefix() .'orders.order_id),0) - '. DB::getTablePrefix() .'orders.amount) as remain_order_amount');
                }    
                
            }
            else
            {
                    $fields[] = DB::raw('(CASE WHEN '. DB::getTablePrefix() .'orders.order_action = "BUY" THEN IFNULL((SELECT SUM(trade_amount) as total_trade_amount FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.buyer_order_id = '. DB::getTablePrefix() .'orders.order_id),0) ELSE IFNULL((SELECT SUM(trade_amount) as total_trade_amount FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.seller_order_id = '. DB::getTablePrefix() .'orders.order_id),0) END) as total_trade_amount');

                    $fields[] = DB::raw('(CASE WHEN '. DB::getTablePrefix() .'orders.order_action = "BUY" THEN IFNULL((SELECT SUM(trade_total) as total_trade_price FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.buyer_order_id = '. DB::getTablePrefix() .'orders.order_id),0) ELSE IFNULL((SELECT SUM(trade_total) as total_trade_price FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.seller_order_id = '. DB::getTablePrefix() .'orders.order_id),0) END)as total_trade_price');
                    $fields[] = DB::raw('(CASE WHEN '. DB::getTablePrefix() .'orders.order_action = "BUY" THEN ABS(IFNULL((SELECT SUM(trade_amount) as total_trade_amount FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.buyer_order_id = '. DB::getTablePrefix() .'orders.order_id),0) - '. DB::getTablePrefix() .'orders.amount) ELSE ABS(IFNULL((SELECT SUM(trade_amount) as total_trade_amount FROM '. DB::getTablePrefix() .'orders_trading where '. DB::getTablePrefix() .'orders_trading.seller_order_id = '. DB::getTablePrefix() .'orders.order_id),0) - '. DB::getTablePrefix() .'orders.amount) END) as remain_order_amount');
            }    
        }   

    	//Order By
    	if(!empty($params['order_by']))
    	{
    		foreach($params['order_by'] as $column => $order)
    		{
    			$query->orderBy($column, $order);		
    		}	    		
    	}	
    	else
    	{
    		$query->orderBy('orders.order_id', 'DESC');    		
    	}

    	//Limit
    	if(!empty($params['per_page']))
    	{
    		if(!empty($params['offset']))
    		{
    			$offset = $params['offset'];
    		}	
    		else
    		{
    			$offset = 0;
    		}

    		$query->offset($offset);
    		$query->limit($params['per_page']);
    	}	
    	
        //Result
        if(!empty($params['result_type']))
        {    
           if($params['result_type'] = 'FIRST')
                $result = $query->select($fields)->first(); 
           else if($params['result_type'] = 'COUNT')
                $result = $query->select($fields)->count();
           else 
                $result = $query->select($fields)->get();    
        }
        else
        {
           $result = $query->select($fields)->get();    
        }   

    	return $result;
    }

    public static function updateOrder($condition,$updateData)
    {
    	if(count($condition) > 0 && count($updateData) > 0)
    	{
    		DB::table('orders')->where($condition)->update($updateData);
    	}	
    }

    public static function deleteOrder($condition)
    {
    	if(count($condition) > 0)
    	{
    		DB::table('orders')->where($condition)->delete();			
    	}	
    }

    /* Orders Trading */
    public static function checkOrderTradingExists($condition=array()) 
    {
        if(count($condition) > 0)
        {               
            return DB::table('orders_trading')->where($condition)->count(); 
        }   
    }

    public static function insertOrderTrading($insertData) 
    {        
        if(count($insertData) > 0)
        {
            $id = DB::table('orders_trading')->insertGetId($insertData);
            return $id;
        }   
    }

    public static function getOrdersTrading($params)
    {       
        $query = DB::table('orders_trading');
        
        //Fields
        if(!empty($params['fields']))
        {
            $fields = $params['fields'];
        }
        else
        {
            $fields = array('orders_trading.*');    
        }   

        if(!empty($params['trade_id']))
        {
            $query->where('orders_trading.trade_id',$params['trade_id']);
        }

        if(!empty($params['pair']))
        {
            $query->where('orders_trading.pair',$params['pair']);
        }

        if(!empty($params['user_id']))
        {
            $query->where(function($query)use($params){
                $query->where('orders_trading.buyer_id',$params['user_id'])->orWhere('orders_trading.seller_id',$params['user_id']);
            });            
        }

        if(!empty($params['buyer_id']))
        {
            $query->where('orders_trading.buyer_id',$params['buyer_id']);
        }

        if(!empty($params['buyer_order_id']))
        {
            $query->where('orders_trading.buyer_order_id',$params['buyer_order_id']);
        }

        if(!empty($params['seller_order_id']))
        {
            $query->where('orders_trading.seller_order_id',$params['seller_order_id']);
        }

        if(!empty($params['seller_id']))
        {
            $query->where('orders_trading.seller_id',$params['seller_id']);
        }

        if(!empty($params['trigger_action']))
        {
            $query->where('orders_trading.trigger_action',$params['trigger_action']);
        }

        if(!empty($params['status']))
        {
            $query->where('orders_trading.status',$params['status']);
        }

        if(!empty($params['created_at']))
        {
            $query->where('orders_trading.created_at',$params['created_at']);
        }

        if(!empty($params['is_sum_trade_value']))
        {
            $fields = array(DB::raw('SUM(trade_amount) as total_trade_amount'),DB::raw('SUM(trade_total) as total_trade_price'));
        }

        //Order By
        if(!empty($params['order_by']))
        {
            foreach($params['order_by'] as $column => $order)
            {
                $query->orderBy($column, $order);       
            }               
        }   
        else
        {
            $query->orderBy('orders_trading.trade_id', 'DESC');         
        }

        //Limit
        if(!empty($params['per_page']))
        {
            if(!empty($params['offset']))
            {
                $offset = $params['offset'];
            }   
            else
            {
                $offset = 0;
            }

            $query->offset($offset);
            $query->limit($params['per_page']);
        }   
        
        //Result
        if(!empty($params['result_type']))
        {    
           if($params['result_type'] = 'FIRST')
                $result = $query->select($fields)->first(); 
           else if($params['result_type'] = 'COUNT')
                $result = $query->select($fields)->count();
           else 
                $result = $query->select($fields)->get();    
        }
        else
        {
           $result = $query->select($fields)->get();    
        }

        return $result;
    }

    public static function updateOrderTrading($condition,$updateData)
    {
        if(count($condition) > 0 && count($updateData) > 0)
        {
            DB::table('orders_trading')->where($condition)->update($updateData);
        }   
    }

    public static function deleteOrderTrading($condition)
    {
        if(count($condition) > 0)
        {
            DB::table('orders_trading')->where($condition)->delete();           
        }   
    }
}
