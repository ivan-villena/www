<?php

class app_arc {

  
  static function lis( mixed $val, array $ele = [] ) : string {
    $_ = "";
    if( is_dir($val) ){
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      if( !isset($ele['ite']) ) $ele['ite'] = [];

      api_ele::cla($ele['lis'],"app_arc-dir",'ini');
      $_ .= "
      <ul".api_ele::atr($ele['lis']).">";
      foreach( api_arc::dir($val) as $arc ){
        $ele_ite = $ele['ite'];
        api_ele::cla($ele_ite,"{$arc['tip']}",'ini'); $_ .= "
        <li".api_ele::atr($ele_ite).">
          {$arc['nom']}";
          if( $arc['tip'] == 'dir' ){
            $_ .= app_arc::lis( $val."\\".$arc['nom'], [ 'lis'=>[ 'data-pos'=>isset($ele['lis']['data-pos']) ? $ele['lis']['data-pos']+1 : 1 ] ] );
          }
          $_ .= "
        </li>";
      }
      $_ .= "
      </ul>";
    }

    return $_;
  }
}