<?php

namespace app\controller\api;

use core\Controller;

class TimeController extends Controller
{
    public function weekDaysAction()
    {
        $week_offset = $this->args['id'];
        $week_offset = ($week_offset > 0) ? "+$week_offset" : $week_offset;
        $suffix = ($week_offset == 0) ? '' : " $week_offset week";
        $param_str = " this week $suffix";        
        // $response_arr = [
            
        //     '0' => date('d-m-Y', strtotime('monday' . $param_str)),
        //     '1' => date('d-m-Y', strtotime('tuesday' . $param_str)),
        //     '2' => date('d-m-Y', strtotime('wednesday' . $param_str)),
        //     '3' => date('d-m-Y', strtotime('thursday' . $param_str)),
        //     '4' => date('d-m-Y', strtotime('friday' . $param_str)),
        //     '5' => date('d-m-Y', strtotime('saturday' . $param_str)),
        //     '6' => date('d-m-Y', strtotime('sunday' . $param_str)),
        // ];

        $response_arr = [
            '1' => [
                'class_name' => 'pazartesi',
                'inner_html' => 'Pts',
                'date_value' => date('Y-m-d', strtotime('monday' . $param_str))
            ],
            '2' => [
                'class_name' => 'sali',
                'inner_html' => 'Sal',
                'date_value' => date('Y-m-d', strtotime('tuesday' . $param_str))
            ],
            '3' => [
                'class_name' => 'carsamba',
                'inner_html' => 'Çarş',
                'date_value' => date('Y-m-d', strtotime('wednesday' . $param_str))
            ],
            '4' => [
                'class_name' => 'persembe',
                'inner_html' => 'Perş',
                'date_value' => date('Y-m-d', strtotime('thursday' . $param_str))
            ],
            '5' => [
                'class_name' => 'cuma',
                'inner_html' => 'Cu',
                'date_value' => date('Y-m-d', strtotime('friday' . $param_str))
            ],
            '6' => [
                'class_name' => 'cumartesi',
                'inner_html' => 'Cts',
                'date_value' => date('Y-m-d', strtotime('saturday' . $param_str))
            ],
            '7' => [
                'class_name' => 'pazar',
                'inner_html' => 'Pzr',
                'date_value' => date('Y-m-d', strtotime('sunday' . $param_str))
            ],
        ];
        echo json_encode($response_arr);
    }
}