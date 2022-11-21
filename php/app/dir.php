<?php

class app_dir {

  // armo peticion
  static function uri( string $esq = "" ) : object {    
    global $_api;

    if( empty($_api->app_dir->esq) ){

      $uri = explode('/', !empty($_REQUEST['uri']) ? $_REQUEST['uri'] : '');

      $_uri = new stdClass;
      $_uri->esq = !empty($uri[0]) ? $uri[0] : $esq;
      $_uri->cab = !empty($uri[1]) ? $uri[1] : FALSE;
      $_uri->art = !empty($uri[2]) ? $uri[2] : FALSE;

      if( $_uri->art ) $_val = explode('#',$_uri->art);

      if( isset($_val[1]) ){
        $_uri->art = $_val[0];
        $_uri->val = $_val[1];  
      }
      else{          
        $_uri->val = !empty($uri[3]) ? $uri[3] : FALSE;
      }

      $_api->app_dir = $_uri;
    }
    return $_api->app_dir;
  }
  // armo directorios
  static function uri_arc() : object {

    $_ = new stdClass();
    
    $_uri = app_dir::uri();
    
    $_->esq = SYS_NAV."{$_uri->esq}";
      
    $_->cab = "{$_uri->esq}/{$_uri->cab}";

    $_->ima = SYS_NAV."img/{$_->cab}/";

    if( !empty($_uri->art) ){

      $_->art = $_->cab."/{$_uri->art}";
    
      $_->ima .= "{$_uri->art}/";
    }

    return $_;
  }
}