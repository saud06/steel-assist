<?php
    namespace Miscellaneous;

    class Miscellaneous{
        function __construct(){
        }

        // FETCH REQUIRED FOR BY SEARCH STRING
        function fetch_req_for_by_srch_str($search_str){
            $req_for_arr = [
    			(object)[
				    'id' => 1,
				    'req_for' => 'BCP-CCM'
				],
				(object)[
				    'id' => 2,
				    'req_for' => 'BCP-Furnace'
				],
				(object)[
				    'id' => 3,
				    'req_for' => 'Concast-CCM'
				],
				(object)[
				    'id' => 4,
				    'req_for' => 'Concast-Furnace'
				],
				(object)[
				    'id' => 5,
				    'req_for' => 'HRM'
				],
				(object)[
				    'id' => 6,
				    'req_for' => 'HRM Unit-2'
				],
				(object)[
				    'id' => 7,
				    'req_for' => 'Lal Masjid'
				],
				(object)[
				    'id' => 8,
				    'req_for' => 'Sonargaon'
				],
				(object)[
				    'id' => 9,
				    'req_for' => 'General'
				]
            ];

			$flag = 0;

			foreach($req_for_arr as $key => $value){
				if(strpos(strtolower($value->req_for), strtolower($search_str)) !== false){
			        $data[] = [
                        'id' => $value->id,
                        'req_for' => $value->req_for
                    ];

                    $flag = 1;
			    }
			}

			if($flag == 1){
				$reply = array(
	                'Type' => 'success',
	                'Reply' => $data
	            );

	            exit(json_encode($reply));
	        } else{
	        	$data = [];

                $reply = array(
                    'Type' => 'error',
                    'Reply' => $data
                );

                exit(json_encode($reply));
	        }
        }

        // FETCH STATUS BY SEARCH STRING
        function fetch_status_by_srch_str($search_str){
            $status_arr = [
    			(object)[
				    'id' => 1,
				    'status' => 'Repairable'
				],
				(object)[
				    'id' => 2,
				    'status' => 'Unusual'
				]
            ];

			$flag = 0;

			foreach($status_arr as $key => $value){
				if(strpos(strtolower($value->status), strtolower($search_str)) !== false){
			        $data[] = [
                        'id' => $value->id,
                        'status' => $value->status
                    ];

                    $flag = 1;
			    }
			}

			if($flag == 1){
				$reply = array(
	                'Type' => 'success',
	                'Reply' => $data
	            );

	            exit(json_encode($reply));
	        } else{
	        	$data = [];

                $reply = array(
                    'Type' => 'error',
                    'Reply' => $data
                );

                exit(json_encode($reply));
	        }
        }
    }
?>