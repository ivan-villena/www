<?php 

// Interfaces
class api {
  
  public array
    // Dato
    $dat_tip = [],
    $dat_ope = [],
    $dat_est = [],
    $dat_atr = [],
    // Numero
    $num = [],
    // Texto
    $tex = [],
    $tex_let = [],
    // Fecha
    $fec = [],
    // Holon
    $hol = []
  ;
  public object 
    // peticion : esq/cab/art/val -
    $app_uri;

    public array
    // iconos      
    $app_ico = [],
    // formularios
    $app_var = [],
    $app_var_ide = [],// id de variables
    $app_var_ope = [],// selector de operadores      
    // datos
    $app_dat = [],
    // valores
    $app_val = [],
    // tablas
    $app_est = [],
    // tableros
    $app_tab = []
  ;

  function __construct(){

    // aplicacion
    $this->app_uri = new stdClass;
    $this->app_ico = api::dat('app_ico', [ 'niv'=>['ide'] ]);
    
    // variable: tipos + operaciones
    $this->dat_tip = api::dat('dat_tip', [ 'niv'=>['ide'], 'ele'=>['ope'] ]);
    $this->dat_ope = api::dat('dat_ope', [ 'niv'=>['ide'] ]);

    // textos
    $this->tex_let = api::dat('tex_let', [ 'niv'=>['ide'] ]);
    
    // fechas : mes + semana + dias
    foreach( ['mes','sem','dia'] as $ide ){

      $this->{"fec_$ide"} = api::dat("fec_$ide");
    }
  }
  // getter por estructura en memoria
  static function _( string $ide, $val = NULL ) : string | array | object {
    global $_api;
    $_ = [];
    // aseguro carga      
    if( !isset($_api->$ide) ) $_api->$ide = api_dat::ini(DAT_ESQ,$ide);      
    // cargo datos
    $_dat = $_api->$ide;
    
    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        case 'fec':
          $_ = api_fec::dat($val);
          break;
        default:
          if( is_numeric($val) ){
            $ide = intval($val)-1;
            if( isset($_dat[$ide]) ) $_ = $_dat[$ide];
          }
          elseif( isset($_dat[$val]) ){ 
            $_ = $_dat[$val];
          }
          break;
        }
      }
    }// toda la lista
    elseif( isset($_dat) ){
      $_ = $_dat;
    }
    return $_;
  }  
  // getter por objeto | consulta
  static function dat( mixed $dat, mixed $ope = NULL, mixed $val = NULL ) : array | object {

    // objeto->propiedad 
    if( is_string($dat) && is_string($ope) ){

      $esq = $dat;
      $est = $ope;        
      // busco datos por $clase::_($identificador)
      $_ = isset($val) ? $val : new stdClass;
      if( ( !isset($val) || !api_obj::tip($val) ) && class_exists($_cla = "api_{$esq}") && method_exists($_cla,'_') ){

        $_ = !isset($val) ? $_cla::_($est) : $_cla::_($est,$val);
      }
    }// estructuras de la base
    else{
      $_ = $dat;
      // datos de la base 
      if( is_string($ide = $dat) ){

        // ejecuto consulta
        $_ = api_sql::reg('ver',$ide,isset($ope) ? $ope : []);

        if( isset($ope) ){
          // elimino marcas
          foreach( ['ver','jun','gru','ord','lim'] as $i ){

            if( isset($ope[$i]) ) unset($ope[$i]);
          }
          // busco clave primaria
          if( isset($ope['niv']) && ( empty($ope['niv']) || in_array($ope['niv'],['_uni','_mul']) ) ){
            
            $ope['niv'] = api_sql::ind($ide,'ver','pri');
          }
        }
      }
      // resultados y operaciones
      if( isset($ope) && ( is_array($dat) || !isset($_['err']) ) ) api_lis::ope($_,$ope);

    }
    return $_;
  }  
}