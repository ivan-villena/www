<?php
// Dato : (api).esq.est[ide].atr
class api_dat {

  static string $IDE = "api_dat-";
  static string $EJE = "api_dat.";

  function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {

    $_ = $_dat = api_app::est('dat',$ide,'dat');
    
    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        default:
          if( is_numeric($val) ) $val = intval($val) - 1;
          if( isset($_dat[$val]) ) $_ = $_dat[$val];
          break;
        }
      }
    }
    return $_;
  }

  // Variable : div.dat_var > label + (select,input,textarea,button)[name]
  static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
    $_VAR = [ 
      'ico'=>"", 
      'nom'=>"", 
      'des'=>"", 
      'ite'=>[], 
      'eti'=>[], 
      'ope'=>[], 
      'htm'=>"", 
      'htm_pre'=>"", 
      'htm_med'=>"", 
      'htm_pos'=>"" 
    ];
    // identificadores
    $dat_ide = is_string($ide) ? explode('.',$ide) : $ide;
    if( isset($dat_ide[2]) ){
      $esq = $dat_ide[0]; 
      $est = $dat_ide[1];
      $atr = $dat_ide[2];
    }
    elseif( isset($dat_ide[1]) ){
      $est = $dat_ide[0];
      $atr = $dat_ide[1];
    }
    else{
      $atr = $dat_ide[0];
    }

    // por atributi de la base
    if( $tip == 'atr' ){

      if( !empty($_atr = api_app::est($esq,$est,'atr',$atr)) ) $_var = [ 
        'nom'=>$_atr->nom, 
        'ope'=>$_atr->var 
      ];
    }
    // carga operadores: esquema - dato - valor
    elseif( $tip != 'val' ){ 

      $_var = api_app::var($tip,$esq,$est,$atr);
    }

    // combino operadores
    if( !empty($_var) ){

      if( !empty($_var['ope']) ){
        $ele['ope'] = api_ele::val_jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
        unset($_var['ope']);
      }
      $ele = api_obj::val_jun($ele,$_var);
    }
    // identificadores
    if( empty($ele['ope']['id'])  && !empty($ele['ide']) ){
      $ele['ope']['id'] = $ele['ide'];
    }
    // nombre en formulario
    if( empty($ele['ope']['name']) ){
      $ele['ope']['name'] = $atr;
    }      
    // proceso html + agregados
    $agr = api_ele::htm($ele);

    // etiqueta
    $eti_htm='';
    if( !isset($ele['eti']) ) $ele['eti'] = [];
    if( !in_array('eti',$opc) ){
      // icono o texto
      if( !empty($ele['ico']) ){
        $eti_htm = api_fig::ico($ele['ico']);
      }elseif( !empty($ele['nom']) ){    
        $eti_htm = api_tex::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
      }
      // agrego for/id e imprimo
      if( !empty($eti_htm) ){    
        if( isset($ele['id']) ){
          $ele['eti']['for'] = $ele['id'];
        }elseif( isset($ele['ope']['id']) ){
          $ele['eti']['for'] = $ele['ope']['id'];
        }
        $eti_htm = "<label".api_ele::atr($ele['eti']).">{$eti_htm}</label>";
      }
    }

    // contenido medio
    if( !in_array('eti_fin',$opc) ){
      $eti_ini = $eti_htm.( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ); 
      $eti_fin = "";
    }else{
      $eti_ini = ""; 
      $eti_fin = ( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ).$eti_htm;
    }

    // valor: hmtl o controlador
    if( isset($agr['htm']) ){
      $val_htm = $agr['htm'];
    }else{
      if( isset($ele['val']) ){
        $ele['ope']['val'] = $ele['val'];
      }
      if( empty($ele['ope']['name']) && isset($ele['ide']) ){
        $ele['ope']['name'] = $ele['ide'];
      }
      $val_htm = api_ele::val($ele['ope']);
    }

    // contenedor
    if( !isset($ele['ite']) ) $ele['ite']=[];      
    if( !isset($ele['ite']['title']) ) $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';

    return "
    <div".api_ele::atr(api_ele::cla($ele['ite'],"dat_var",'ini')).">
      ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
      {$eti_ini}
      {$val_htm}
      {$eti_fin}
      ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
    </div>
    ";   
  } 
  
  // Listado
  static function lis( string | array $dat, string $ide, array $ele = [] ) : string {
    $_ = $dat;
    if( is_array($dat) ){
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      $ele['lis']['data-dat'] = $ide;
      // tipos: pos + ite + tab
      $tip = "dep";
      if( isset($ele['lis_tip']) ){
        $tip = $ele['lis_tip'];
        unset($ele['lis_tip']);
      }
      $_ = api_lis::$tip($dat, $ele);
    }
    return $_;
  }// Glosario : palabras por esquema
  static function lis_ide( string | array $ide, array $ele = [] ) : string {

    $_ = [];
    $_ide = explode('.',$ide);
    
    if( is_array( $tex = api_app::dat('app_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

      foreach( $tex as $pal ){
        $_[ $pal->nom ] = $pal->des;
      }
    }
    // operadores : toggle + filtro
    if( !isset($ele['opc']) ) $ele['opc'] = [];

    return api_lis::pos($_,$ele);

  }// Atributos : item => Atributo: "valor"
  static function lis_atr( string $esq, string $est, array $atr, mixed $dat, array $ope = [] ) : string {
    $_ = "";
    $_opc = isset($ope['opc']) ? $ope['opc'] : [];
    $_opc_des = in_array('des',$_opc);
    // cargo dato
    if( !is_object($dat) ) $dat = api_app::dat($esq,$est,$dat);
    // cargo atributos
    $dat_atr = api_app::est($esq,$est,'atr');      
    $ite = [];
    foreach( ( !empty($atr) ? $atr : array_keys($dat_atr) ) as $atr ){       
      if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ 
        $_atr = $dat_atr[$atr];
        $val = $dat->$atr;
        if( is_numeric($val) && isset($_atr->var['dat']) ){
          // busco nombres /o/ iconos
          $atr_ide = explode('_',$_atr->var['dat']);
          $atr_esq = array_shift($atr_ide);
          $atr_est = implode('_',$atr_ide);
          $atr_dat = api_app::est($atr_esq,$atr_est,'val');
          $atr_obj = [];
          if( class_exists($atr_cla = $atr_esq) && method_exists($atr_cla,'_') ){
            $atr_obj = $atr_cla::_("{$atr_est}", $val);
          }
          if( isset($atr_dat['nom']) ){
            $val = api_tex::let( api_obj::val($atr_obj,$atr_dat['nom']) );
            if( isset($atr_dat['des']) && !$_opc_des ){
              $val .= "<br>".api_tex::let(api_obj::val($atr_obj,$atr_dat['des']));
            }
          }
          $val = str_replace($dat_atr[$atr]->nom,"<b class='let-ide'>{$dat_atr[$atr]->nom}</b>",$val);
        }
        else{
          $val = "<b class='let-ide'>{$dat_atr[$atr]->nom}</b><c>:</c> ".api_tex::let($val);
        }
        $ite []= $val;
      }
    }

    $_ = api_lis::pos($ite,$ope);          

    return $_;
  }// Descripciones : imagen + nombre > ...atributos
  static function lis_des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
    $_ = [];

    // tipos de lista
    $tip = !empty($ele['tip']) ? $ele['tip'] : 'dep';

    // atributos de la estructura
    $atr = api_lis::val_ite($atr);

    // descripciones : cadena con " ()($)atr() "
    $des = !empty($ele['des']) ? $ele['des'] : FALSE;

    // elemento de lista
    if( !isset($ele['lis']) ) $ele['lis'] = [];
    api_ele::cla($ele['lis'],"ite",'ini');
    $ele['lis']['data-ide'] = "$esq.$est";

    if( class_exists($cla = "api_{$esq}") ){

      foreach( $cla::_($est) as $pos => $_dat ){ 
        $htm = 
        api_fig::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
        <dl>
          <dt>".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " ".api_obj::val($_dat,$des) : "" )."</dt>";
          foreach( $atr as $ide ){ 
            if( isset($_dat->$ide) ){ $htm .= "
              <dd>".api_tex::let($_dat->$ide)."</dd>";
            }
          }$htm .= "
        </dl>";
        $_ []= $htm;
      }
    }
    return api_lis::$tip( $_, $ele, ...$opc );

  }// Posiciones : ficha + nombre ~ descripcion ~ posicion
  static function lis_pos( string $esq, string $est, array $dat, array $ele = [] ) : string {
    $_ = [];

    foreach( api_app::est($esq,$est,'pos') as $ite ){
      $var = [ 'ite'=>$ite['nom'], 'lis'=>[] ];
      extract( api_app::est_ide($ite['ide']) );
      $ope_atr = api_app::est($esq,$est,'atr');

      foreach( $ite['atr'] as $atr ){
        $val = isset($dat[$est]->$atr) ? $dat[$est]->$atr : NULL;        
        $_atr = isset($ope_atr[$atr]->var) ? $ope_atr[$atr]->var : [];
        
        // identificadores
        $_ide = explode('_', isset($_atr['dat']) ? $_atr['dat'] : "{$esq}_{$est}_{$atr}" );
        $esq_ide = array_shift($_ide);
        $est_ide = implode('_',$_ide);
        $dat_ide = "{$esq_ide}.{$est_ide}";
        
        // datos
        $_dat = api_app::dat($esq_ide,$est_ide,$val);
        $_val = api_app::est($esq_ide,$est_ide,'val');
        
        $htm = isset($_val['ima']) ? api_fig::ima($esq_ide ,$est_ide, $_dat, [ 'class'=>"tam-3 mar_der-1" ]) : "";
        $htm .= "
        <div class='tam-cre'>";
          if( isset($_val['nom']) ) $htm .= "
            <p class='tit'>".api_tex::let( api_est::val('nom',$dat_ide,$_dat) )."</p>";
          if( isset($_val['des']) ) $htm .= "
            <p class='des'>".api_tex::let( api_est::val('des',$dat_ide,$_dat) )."</p>";
          if( isset($_val['num']) ) $htm .= 
            api_num::var('ran',$val,[ 'min'=>1, 'max'=>$_val['num'], 'disabled'=>"", 'class'=>"anc-100"],'ver');
          $htm .= "
        </div>";
        $var['lis'] []= $htm;
      }
      $_ []= $var;
    }
    $ele['lis-1'] = [ 'class'=>"ite" ];
    $ele['opc'] = [ "tog","ver","not-sep" ];
    $ele['ope'] = [ 'class'=>"mar_arr-1" ];

    return api_lis::dep($_,$ele);
  }
}