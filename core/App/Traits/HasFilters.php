<?php

namespace Core\App\Traits;

trait HasFilters{
    public function scopeFilter($q,$filters,$values){
        foreach ($filters as $filter) {
          $column = $filter['column'];
          $value = null;
  
          if(preg_match("*\.*",$filter['column'])){
            $rel  = explode('.',$filter['column'])[0];
            $col  = explode('.',$filter['column'])[1];
  
            $q->whereHas($rel,function($q) use($col, $filter, $values){
              $column = $col;
              $value=null;
              if(array_key_exists($filter['name'],$values)){
                $value = $values[$filter['name']];
              }
              if($value){
                if($filter['filter']=='exact'){
                  if(array_key_exists('multiple',$filter)&&$filter['multiple']){
                    $q->whereIn($column,$value);
                  }
                  else{
                    $q->where($column,$value);
                  }
                }
                else if($filter['filter']=='like'){
                  $search_str = "%" . str_replace(" ", "%", trim($value)) . "%";
                  $q->where($column,'like',$search_str);
                }
                else if($filter['filter']=='date_from'){
                  $q->whereDate($column,'>=',$value);
                }
                else if($filter['filter']=='date_to'){
                  $q->whereDate($column,'<=',$value);
                }
              }
            });
          }
          else{
            if(array_key_exists($filter['name'],$values)){
              $value = $values[$filter['name']];
            }
            if($value){
              if($filter['filter']=='exact'){
                if(array_key_exists('multiple',$filter)&&$filter['multiple']){
                  $q->whereIn($column,$value);
                }
                else{
                  $q->where($column,$value);
                }
              }
              else if($filter['filter']=='like'){
                $q->where($column,'like','%'.$value.'%');
              }
              else if($filter['filter']=='date_from'){
                $q->whereDate($column,'>=',$value);
              }
              else if($filter['filter']=='date_to'){
                $q->whereDate($column,'<=',$value);
              }
            }
          }
  
        }
     }
}