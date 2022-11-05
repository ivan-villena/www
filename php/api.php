<?php    
// Base de datos
class _api {

  // Interfaces
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
  // Aplicacion
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
    $this->app_ico = _dat::get('app_ico', [ 'niv'=>['ide'] ]);
    
    // variable: tipos + operaciones
    $this->dat_tip = _dat::get('dat_tip', [ 'niv'=>['ide'], 'ele'=>['ope'] ]);
    $this->dat_ope = _dat::get('dat_ope', [ 'niv'=>['ide'] ]);

    // textos
    $this->tex_let = _dat::get('tex_let', [ 'niv'=>['ide'] ]);
    
    // fechas : mes + semana + dias
    foreach( ['mes','sem','dia'] as $ide ){

      $this->{"fec_$ide"} = _dat::get("fec_$ide");
    }
  }
  // get : estructura-objetos
  static function _( string $ide, $val = NULL ) : string | array | object {
    global $_api;
    $_ = [];
    // aseguro carga      
    if( !isset($_api->$ide) ) $_api->$ide = _dat::ini(DAT_ESQ,$ide);      
    // cargo datos
    $_dat = $_api->$ide;
    
    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        case 'fec':
          $_ = _fec::dat($val);
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
}