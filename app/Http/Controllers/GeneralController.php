<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class GeneralController extends Controller
{
    use HttpResponses;

    public function getCountries()
    {
        $data = json_decode(file_get_contents(base_path("/mockup/countries.json")), null, 512, JSON_THROW_ON_ERROR);
        return $this->success($data, 'Country List');
    }

    public function getRegions($id)
    {
        $data = json_decode(file_get_contents(base_path("/mockup/regions.json")), null, 512, JSON_THROW_ON_ERROR);
        $filtered_arr = array_filter(
            $data,
            function ($obj) use ($id) {
                return $obj->country_id == $id;
            }
        );
        return $this->success(array_values($filtered_arr), 'Region List');
    }

    public function getCities($id)
    {
        $data = json_decode(file_get_contents(base_path("/mockup/cities.json")), null, 512, JSON_THROW_ON_ERROR);
        $filtered_arr = array_filter(
            $data,
            function ($obj) use ($id) {
                return $obj->region_id == $id;
            }
        );
        return $this->success(array_values($filtered_arr), 'City List');
    }

    public function getTownships($id)
    {
        $data = json_decode(file_get_contents(base_path("/mockup/townships.json")), null, 512, JSON_THROW_ON_ERROR);
        $filtered_arr = array_filter(
            $data,
            function ($obj) use ($id) {
                return $obj->city_id == $id;
            }
        );
        return $this->success(array_values($filtered_arr), 'Township List');
    }
}
