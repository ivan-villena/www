<?php 

// Interfaces
class api {
  
  // entornos
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
  // aplicacion  
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
    $app_tab = [];
    // peticion : esq/cab/art/val -
    public object $app_dir
  ;
  
  function __construct(){
    
    // variable: tipos + operaciones
    $this->dat_tip = api::dat('dat_tip', [ 'niv'=>['ide'], 'ele'=>['ope'] ]);
    $this->dat_ope = api::dat('dat_ope', [ 'niv'=>['ide'] ]);

    // textos
    $this->tex_let = api::dat('tex_let', [ 'niv'=>['ide'] ]);
    
    // fechas : mes + semana + dias
    foreach( ['mes','sem','dia'] as $ide ){

      $this->{"fec_$ide"} = api::dat("fec_$ide");
    }

    // aplicacion
    $this->app_dir = new stdClass;
    $this->app_ico = api::dat('app_ico', [ 'niv'=>['ide'] ]);

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

  // consola del sistema
  static function adm() : string {
    $_eje = "api_adm";  
    $_ide = "api-adm";
    return app::nav('bar', [

      'aja' => [ 'nom'=>"AJAX",
        'nav'=>[ 'onclick'=>"$_eje('aja',this);" ],
        'htm'=>"
  
        <nav class='lis'>
        </nav>
        "
      ],
      'ico' => [ 'nom'=>"Íconos", 
        'nav'=>[ 'onclick'=>"$_eje('ico',this);" ],
        'htm'=>"
        
        ".app::var('val','ver',['nom'=>"Filtrar",'ope'=>[ 
          '_tip'=>"tex_ora", 'id'=>"_adm-ico-ver", 'oninput'=>"$_eje('ico',this,'ver')" 
        ]])."
  
        <ul class='lis ite mar-2' style='height: 48vh;'>
        </ul>
        "
      ],
      'jso' => [ 'nom'=>"J.S.", 
        'htm'=>"
  
        <fieldset class='inf pad-3'>
          <legend>Ejecutar JavaScript</legend>      
  
          ".app::var('val','cod',[ 
            'ite'=>[ 'class'=>"tam-cre" ], 
            'ope'=>[ '_tip'=>"tex_par", 'rows'=>"10", 'class'=>"anc-100", 'oninput'=>"$_eje('jso',this)" ] 
          ])."
  
        </fieldset>
  
        <div class='ope_res mar-1'>
        </div>"
      ],  
      'php' => [ 'nom'=>"P.H.P.",
        'htm'=>"
  
        <fieldset class='inf ite pad-3'>
          <legend>Ejecutar en PHP</legend>
  
          ".app::var('val','ide',[ 'ope'=>[ '_tip'=>"tex_ora" ] ])."
          
          ".app::var('val','par',[ 
            'ite'=>['class'=>"tam-cre"], 
            'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_hor-1"], 
            'htm_ini'=>"<c>(</c>", 'htm_fin'=>"<c>)</c>"
          ])."
  
          ".app::var('val','htm',[
            'nom'=>"¿HTML?",
            'ope'=>[ '_tip'=>"opc_bin", 'val'=>1, 'id'=>"_adm-php-htm" ]
          ])."
          
          ".app::ico('dat_ope',[
            'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('php',this)"
          ])."
  
        </fieldset>
  
        <div class='ope_res mar-1' style='height: 40vh; overflow: auto;'>
        </div>
  
        <pre class='ope_res' style='height: 40vh; overflow: auto;'>
        </pre>
        "
      ],
      'sql' => [ 'nom'=>"S.Q.L",
        'htm'=>"
        <fieldset class='inf ite pad-3'>
          <legend>Ejecutar S.Q.L.</legend>
  
          ".app::var('val','cod',[ 
            'ite'=>[ 'class'=>"tam-cre" ], 
            'ope'=>[ '_tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1" ],
            'htm_fin'=> app::ico('dat_ope',[ 'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('sql',this,'cod')" ])
          ])."
  
        </fieldset>
  
        <div class='ope_res mar-1' var='est' style='height: 47vh;'>
        </div>"
      ],
      'htm' => [ 'nom'=>"D.O.M.",
        'htm'=>"
        <fieldset class='inf ite pad-3'>
          <legend>Ejecutar Selector</legend>
  
          ".app::var('val','cod',[ 
            'ite'=>['class'=>"tam-cre"], 
            'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1"],
            'htm_fin'=> app::ico('dat_ope',['eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('htm',this,'cod')"])
          ])."
  
        </fieldset>
  
        <div class='ele'>
        </div>
  
        <div class='nod mar-1'>
        </div>"
      ] 
    ], [
      'sel' => "php",
      'ite' => [ 'eti'=>"form" ]
    ]);
  }

  // sesion del usuario
  static function usu( string $tip ) : string {
    switch( $tip ){
      // datos del usuario
      case 'ses':
        $esq = 'api'; 
        $est = 'usu';
        global $_usu;
        $_kin = api_hol::_('kin',$_usu->kin);
        $_psi = api_hol::_('psi',$_usu->psi);
        $_ = "
        <form class='api_dat' data-esq='{$esq}' data-est='{$est}'>

          <fieldset class='ren'>

            ".app::var('atr', [$esq,$est,$atr='nom'], [ 'val'=>$_usu->$atr  ], 'eti')."

            ".app::var('atr', [$esq,$est,$atr='ape'], [ 'val'=>$_usu->$atr  ], 'eti')."                        
          
          </fieldset>

          <fieldset class='ren'>

            ".app::var('atr', [$esq,$est,$atr='mai'], [ 'val'=>$_usu->$atr  ],'eti')."

            ".app::var('atr', [$esq,$est,$atr='fec'], [ 'val'=>$_usu->$atr, 'ite'=>[ 'class'=>"tam-ini" ]  ], 'eti')."

          </fieldset>

        </form>";
        break;
      // inicio de sesion por usuario
      case 'ini':
        $_ = "
        <form class='api_dat' action=''>

          <fieldset>

            <label for=''>Email</label>
            <input type='mail'>

          </fieldset>

          <fieldset>

            <label for=''>Password</label>
            <input type='password'>

          </fieldset>

        </form>";
        break;
      // finalizo sesion del usuario
      case 'fin': 
        $_ = "    
        <form class='api_dat' action=''>

        </form>";
        break;
    }  
    return $_;
  }
  
}