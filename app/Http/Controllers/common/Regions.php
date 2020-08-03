<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Regions_model;
use Session;
use View;
use Redirect;
use Validator;
use Response;

class Regions extends Controller
{
    public function __construct()
    {
        $this->outputData = array();
    }

    public function postGetStates(Request $request) 
    {
        $input_data = $request->all();

        $country_id = $input_data['country_id'];

        if(!$request->has('country_id'))
        {
            $data['status']  = "ERROR";
            $data['message'] = "Invalide country ID";
            echo json_encode($data);
            exit;
        }   

        $params = array('country_id'=>$country_id,'order_by'=>array('country_id'=>'asc'));
        $states =  Regions_model::getStates($params); 

        $data['status'] = 'SUCCESS';
        $data['states'] = $states;

        echo json_encode($data);
        exit;
    }

    public function postGetCities(Request $request) 
    {
        $input_data = $request->all();
        
        $state_id = $input_data['state_id'];

        if(!$request->has('state_id'))
        {
            $data['status'] = "ERROR";
            echo json_encode($data);
            exit;
        }   

        $params = array('state_id' => $state_id, 'order_by'=>array('state_id'=>'asc'));
        $cities = Regions_model::getCities($params);

        $data['status'] = 'SUCCESS';
        $data['cities'] = $cities;

        echo json_encode($data);
        exit;
    }    
}    