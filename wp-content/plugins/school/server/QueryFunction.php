<?php


class QueryFunction{

    public $wp_obj;

    public function __construct($wpdb){
        $this->wp_obj=$wpdb;   
    }

    // function to get all countries...
    public function getCountries(){
        return $this->wp_obj->get_results("select * FROM countries"); 
    }

    // function to get states by country id...
    public function getStates($cntry_id){
        return $this->wp_obj->get_results("select * FROM state where countries_id=".$cntry_id);    
    }

        // function to get cities by state id...
        public function getCities($state_id){
            return $this->wp_obj->get_results("select * FROM cities where state_id=".$state_id);    
        }

}
?>