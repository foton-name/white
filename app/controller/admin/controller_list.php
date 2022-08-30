<?php
class Controller_list extends Model_list implements \Foton\Face{
   public function parent(){
       return 'default';
   }
   
    public function js_i(){
        return true;
    }
    public function css_i(){
        return true;
    }
    public function h1(){
        return true;
    }
    public function ready(){
        return true;
    }
    public function callback(){
        return true;
    }
   
}