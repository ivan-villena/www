<?php

// Documento
class api_doc {

  static string $IDE = "api_doc-";
  static string $EJE = "api_doc.";

  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {

    $_ = $_dat = sis_dat::est('doc',$ide,'dat');
    
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

  // Conenedor : visible/oculto
  static function val( string | array $dat = NULL, array $ele = [] ) : string {
    $_eje = self::$EJE."val";
    foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
    
    // contenido textual
    if( is_string($dat) ) $dat = [ 'eti'=>"p", 'class'=>"tex-enf tex-cur", 'htm'=> api_tex::let($dat) ];

    // contenedor : icono + ...elementos          
    api_ele::eje( $dat,'cli',"$_eje(this);",'ini');

    return "
    <div".api_ele::atr( api_ele::cla( $ele['val'],"doc_ite",'ini') ).">
    
      ".api_doc::val_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

      ".( isset($ele['htm_ini']) ? api_ele::val($ele['htm_ini']) : '' )."
      
      ".api_ele::val( $dat )."

      ".( isset($ele['htm_fin']) ? api_ele::val($ele['htm_fin']) : '' )."

    </div>";
  }// - icono de toggle
  static function val_ico( array $ele = [] ) : string {

    $_eje = self::$EJE."val";

    return api_fig::ico('val_tog', api_ele::eje($ele,'cli',"$_eje(this);",'ini'));

  }

  // Navegador : nav + * > ...[nav="ide"]
  static function nav( string $tip, array $dat, array $ele = [], ...$opc ) : string {
    $_ = "";
    $_eje = self::$EJE."nav";
    foreach( ['lis','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    $opc_ico = in_array('ico',$opc);
    $val_sel = isset($ele['sel']) ? $ele['sel'] : FALSE;
    // navegador 
    api_ele::cla($ele['lis'], "doc_nav $tip", 'ini');
    $_ .= "
    <nav".api_ele::atr($ele['lis']).">";    
    foreach( $dat as $ide => $val ){

      if( is_object($val) ) $val = api_obj::val_nom($val);

      if( isset($val['ide']) ) $ide = $val['ide'];

      $ele_nav = isset($val['nav']) ? $val['nav'] : [];

      $ele_nav['eti'] = 'a';
      api_ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

      if( $val_sel && $val_sel == $ide ) api_ele::cla($ele_nav,FON_SEL);

      if( $opc_ico && isset($val['ico']) ){
        $ele_nav['title'] = $val['nom'];
        api_ele::cla($ele_nav,"mar-0 pad-1 tam-4 bor_cir-1",'ini');
        $_ .= api_fig::ico($val['ico'],$ele_nav);
      }
      else{
        $ele_nav['htm'] = $val['nom'];
        $_ .= api_ele::val($ele_nav);
      }        
    }$_.="
    </nav>";
    
    // contenido
    $eti_sec = 'div';
    if( isset($ele['sec']['eti'])  ){
      $eti_sec = $ele['sec']['eti'];
      unset($ele['sec']['eti']);
    }
    $eti_ite = 'section';
    if( isset($ele['ite']['eti']) ){
      $eti_ite = $ele['ite']['eti'];
      unset($ele['ite']['eti']);
    }
    if( $tip != 'pes' && !$val_sel ) api_ele::cla($ele['sec'],DIS_OCU);
    $_ .= "
    <$eti_sec".api_ele::atr($ele['sec']).">";
      foreach( $dat as $ide => $val ){
        $ele_ite = $ele['ite'];
        api_ele::cla($ele_ite,"ide-$ide",'ini');
        if( !$val_sel || $val_sel != $ide ) api_ele::cla($ele_ite,DIS_OCU);
        $_ .= "
        <$eti_ite".api_ele::atr($ele_ite).">
          ".( isset($val['htm']) ? ( is_array($val['htm']) ? api_ele::val_dec($val['htm']) : $val['htm'] ) : '' )."
        </$eti_ite>";
      }$_.="
    </$eti_sec>";

    // agrupo contenedor del operador
    if( $tip == 'ope' ){
      $_ = "
      <div class='doc_ope-nav'>
        {$_}
      </div>";
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

      if( !empty($_atr = sis_dat::est($esq,$est,'atr',$atr)) ) $_var = [ 
        'nom'=>$_atr->nom, 
        'ope'=>$_atr->var 
      ];
    }
    // carga operadores: esquema - dato - valor
    elseif( $tip != 'val' ){ 

      $_var = sis_dat::var($tip,$esq,$est,$atr);
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

  // Carteles : advertencia + confirmacion
  static function tex( array $ope = [], array $ele = [] ) : string {
    foreach( ['sec','ico','tex'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    api_ele::cla($ele['sec'],"doc_tex".( !empty($ope['tip']) ? " -{$ope['tip']}" : "" ),'ini');

    $_ = "
    <div".api_ele::atr($ele['sec']).">";

      // cabecera: icono + titulo
      if( isset($ope['tip']) || $ope['tit'] ){
        $_.="
        <header>";
          if( isset($ope['tip']) ){
            switch( $ope['tip'] ){
            case 'err': $ele['ico']['title'] = "Error..."; break;
            case 'adv': $ele['ico']['title'] = "Advertencia..."; break;
            case 'opc': $ele['ico']['title'] = "Consultas..."; break;
            case 'val': $ele['ico']['title'] = "Notificación..."; break;
            }
            api_ele::cla($ele['ico'],"mar-1");
            $_ .= api_fig::ico("val_tex-{$ope['tip']}", $ele['ico']);
          }
          if( isset($ope['tit']) ){
            if( is_string($ope['tit']) ) $_.="<p class='tit'>".api_tex::let($ope['tit'])."</p>";
          }
          $_.="
        </header>";
      }

      // contenido: texto
      if( isset($ope['tex']) ){
        $ope_tex = [];
        if( is_array($ope['tex']) ){
          foreach( $ope['tex'] as $tex ){
            $ope_tex []= api_tex::let($tex);
          }
        }else{
          $ope_tex []= $ope['tex'];
        }
        $_ .= "<p".api_ele::atr($ele['tex']).">".implode('<br>',$ope_tex)."</p>" ;
      }

      // elementos
      if( isset($ope['htm']) ){
        $_ .= api_ele::val(...api_lis::val_ite($ope['htm']));
      }

      // botones: aceptar - cancelar
      if( isset($ope['opc']) ){
        $_ .= "
        <form class='doc_ope'>";
        if( isset($ope['opc']['ini']) ){
          
        }
        if( isset($ope['opc']['fin']) ){
        
        }
        $_ .= "
        </form>";
      }

      $_.="
    </div>";
    return $_;
  }

  // Menú de opciones
  static function opc( array $ope = [], array $ele = [] ) {
  }

}