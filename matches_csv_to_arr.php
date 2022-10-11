<?php
    //$file_handle = file opened using fopen()
    function matches_csv_to_arr($file_handle){
        $matches_arr = [];
        $row = 1;
        while(($data = fgetcsv($file_handle, 10000, ",")) !== FALSE){
            $num = count($data);

            if($row > 1){
                $match = [];
                $match["home_club"] = $data[0];
                $match["away_club"] = $data[1];
                $match["stadium"] = $data[2];
                $match["tournament"] = $data[3];
                $match["match_date"] = $data[4];
                $match["match_time"] = $data[5];
                $match["match_fixed"] = $data[6];
                $match["ticket_only_discount"] = $data[7];
                $match["cat_1_qty"] = $data[8];
                $match["cat_1_price"] = $data[9];
                $match["cat_2_qty"] = $data[10];
                $match["cat_2_price"] = $data[11];
                $match["cat_3_qty"] = $data[12];
                $match["cat_3_price"] = $data[13];
                $match["cat_4_qty"] = $data[14];
                $match["cat_4_price"] = $data[15];
                $match["hotel_price"] = $data[16];
                $match["package"] = $data[17];
                $match["description"] = $data[18];
                $match["available_before_days"] = $data[19];
                $match["id"] = $data[20];

                $matches_arr[$row - 2] = $match;
            }

            $row++;
            for($i = 0; $i < $num; $i++){
                
            }
            
        }

        return $matches_arr;
    }