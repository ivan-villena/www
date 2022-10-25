<?php

  // Documento 
  class _doc {
    
    // letra : ( n, c )
    static function let( string $dat, array $ele=[] ) : string {
      global $_api;
      $_let = $_api->tex_let;
      $_pal = [];
      foreach( explode(' ',$dat) as $pal ){
        // numero completo
        if( is_numeric($pal) ){
          //if( preg_match("/,/",$pal) ) $pal = str_replace(",","<c>,</c>",$pal);
          //if( preg_match("/\./",$pal) ) $pal = str_replace(".","<c>.</c>",$pal);
          $_pal []= "<n>{$pal}</n>";
        }// caracteres
        else{
          $let = [];
          foreach( _tex::let($pal) as $car ){
            if( is_numeric($car) ){
              $let []= "<n>{$car}</n>";
            }elseif( isset($_let[$car]) ){
              //_ele::cla($ele_let,"{$_let[$car]->var}",'ini');
              $let []= "<c>{$car}</c>";        
            }else{
              $let []= $car;
            }
          }
          $_pal []= implode('',$let);
        }
      }
      return implode(' ',$_pal);
    }
  }
  // Contenedor
  class _doc_val {

    static string $IDE = "_doc_val-";
    static string $EJE = "_doc_val.";


    // filtro por contenido
    static function ver( string | array $dat = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      
      // opciones de filtro por texto
      $_ .= _app::ope(['ver','tex'],[
        'ite'=>[ 
          'dat'=>"()($)dat()" 
        ],
        'eti'=>[ 
          'name'=>"ope", 'title'=>"Seleccionar un operador de comparación...", 'val'=>'**', 
          'class'=>isset($dat['ele_ope']['class']) ? $dat['ele_ope']['class'] : "mar_hor-1", 'onchange'=>$dat['eje']
        ]
      ]);

      // ingreso de valor a filtrar
      $_ .= _doc_tex::ope('ora', isset($dat['val']) ? $dat['val'] : '', [ 
        'id'=>isset($dat['ide']) ? $dat['ide'] : NULL, 
        'name'=>"val",
        'title'=>"Introducir un valor de búsqueda...", 
        'oninput'=>!empty($dat['eje']) ? $dat['eje'] : NULL,
        'class'=>isset($ele['class']) ? $ele['class'] : NULL,
        'style'=>isset($ele['style']) ? $ele['class'] : NULL
      ]);

      // agrego totales
      if( isset($dat['cue']) ){ $_ .= "
        <c class='sep'>(</c><n name='tot' title='Items totales'>".( is_array($dat['cue']) ? count($dat['cue']) : $dat['cue'] )."</n><c class='sep'>)</c>";
      }
      return $_;
    }
    // pestaña con secciones : nav + * > ...[nav="ide"]
    static function nav( string $tip, array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['lis','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_eje = self::$EJE."nav";
      $_ = "";
      
      $val_sel = isset($ele['sel']) ? $ele['sel'] : '';

      // navegador 
      _ele::cla($ele['lis'], $tip, 'ini');
      $_ .= "
      <nav"._htm::atr($ele['lis']).">";    
      foreach( $dat as $ide => $val ){

        if( is_object($val) ) $val = _obj::nom($val);

        if( isset($val['ide']) ) $ide = $val['ide'];

        $ele_nav = isset($val['nav']) ? $val['nav'] : [];

        $ele_nav['eti'] = 'a';

        if( !isset($val['ico']) && !isset($ele_nav['htm']) && !empty($val['nom']) ) $ele_nav['htm'] = $val['nom'];

        _ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

        if( $val_sel == $ide ) _ele::cla($ele_nav,FON_SEL);
    
        $_ .= _htm::val($ele_nav);
      }$_.="
      </nav>";
      // contenido
      $eti_sec = isset($ele['sec']['eti']) ? $ele['sec']['eti'] : 'div';
      $eti_ite = isset($ele['ite']['eti']) ? $ele['ite']['eti'] : 'section';
      $_ .= "
      <$eti_sec"._htm::atr($ele['sec']).">";
        foreach( $dat as $ide => $val ){
          $ele_ite = $ele['ite'];
          $ele_ite['data-ide'] = $ide;
          if( $val_sel != $ide ) _ele::cla($ele_ite,DIS_OCU); 
          $_ .= "
          <$eti_ite"._htm::atr($ele_ite).">
            ".( isset($val['htm']) ? ( is_array($val['htm']) ? _ele::val($val['htm']) : $val['htm'] ) : '' )."
          </$eti_ite>";
        }$_.="
      </$eti_sec>";

      return $_;
    }
    // carteles 
    static function tex( string $tip='', string | array $val, array $ope = [] ) : string {
      foreach( ['sec','ico','tex'] as $i ){ if( !isset($ope[$i]) ) $ope[$i] = []; }
      _ele::cla($ope['sec'],"val_tex".( !empty($tip) ? " -$tip" : "" ),'ini');

      $_ = "
      <div"._htm::atr($ope['sec']).">";

        if( !empty($ope['cab']) ){
          $_ .= "
          <div class='ite esp-ara'>
            <span></span>
            "._doc::let($ope['cab'])."
            <span></span>
          </div>";
        }

        if( !empty($tip) ){
          switch( $tip ){
          case 'err': $ope['ico']['title'] = "Error..."; break;
          case 'adv': $ope['ico']['title'] = "Advertencia..."; break;
          case 'opc': $ope['ico']['title'] = "Consultas..."; break;
          case 'val': $ope['ico']['title'] = "Notificación..."; break;
          }
          $_ .= _app::ico("val_tex-{$tip}", $ope['ico']);
        }

        $_ .= ( is_string($val) ? "<p"._htm::atr($ope['tex']).">"._doc::let($val)."</p>" : _ele::val($val) )."

      </div>";
      return $_;
    }
    // contenido visible/oculto
    static function tog( string | array $dat = NULL, array $ele = [] ) : string {
      $_eje = self::$EJE."tog";
      foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
      
      // contenido textual
      if( is_string($dat) ) $dat = [
        'eti'=>"p", 'class'=>"let-enf let-cur", 'htm'=>_doc::let($dat) 
      ];

      // contenedor : icono + ...elementos          
      _ele::eje( $dat,'cli',"$_eje(this);",'ini');

      return "
      <div"._htm::atr( _ele::cla( $ele['val'],"val tog",'ini') ).">
      
        "._doc_val::tog_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

        ".( isset($ele['htm_ini']) ? _htm::val($ele['htm_ini']) : '' )."
        
        "._htm::val( $dat )."

        ".( isset($ele['htm_fin']) ? _htm::val($ele['htm_fin']) : '' )."

      </div>";
    }// icono de toggle
    static function tog_ico( array $ele = [] ) : string {

      $_eje = self::$EJE."tog";

      return _app::ico('val_tog', _ele::eje($ele,'cli',"$_eje(this);",'ini'));
    }// operadores: expandir / contraer
    static function tog_ope( array $ele = [], ...$opc ) : string {
      $_ide = self::$IDE."tog";
      $_eje = self::$EJE."tog";      

      if( !isset($ele['ope']) ) $ele['ope'] = [];
      _ele::cla($ele['ope'],"ope",'ini');

      $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";
      return "
      <fieldset"._htm::atr($ele['ope']).">
        "._app::ico('val_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
        "._app::ico('val_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
      </fieldset>";
    }
  }
  // Listado
  class _doc_lis {

    static string $IDE = "_doc_lis-";
    static string $EJE = "_doc_lis.";
        
    // operadores : tog + filtro
    static function ope( string $tip, array $opc = [], array $ele = [] ) : string {
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      
      $_ = "";

      $tod = empty($opc);
      
      if( $tod || in_array('tog',$opc) ){        
        
        $_ .= _doc_val::tog_ope( $tip == 'ite' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
      }
      if( $tod || in_array('ver',$opc) ){
        $_ .= _app_var::ope('val','ver',[ 
          'des'=>"Filtrar...",
          'htm'=>_doc_val::ver([ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"{$_eje}_ver(this);" ])
        ]);
      }

      if( !empty($_) ){ $_ = "
        <form"._htm::atr( isset($ele['ope']) ? $ele['ope'] : [] ).">
          {$_}
        </form>";        
      }      
      return $_;
    }
    // horizontal con barra de desplazamiento por item
    static function bar( array $dat, array $ope = [] ) : string {
      $_ide = self::$IDE."bar";
      $_eje = self::$EJE."bar";      
      $_ = "";

      $pos = 0;
      $pos_ver = ( !empty($ope['pos_ver']) ? $ope['pos_ver'] : 1 );
      if( !isset($ope['lis']) ) $ope['lis']=[];
      $_.="
      <ul"._htm::atr(_ele::cla($ope['lis'],"bar",'ini')).">";
        foreach( $dat as $ite ){ 
          $pos++;
          $ope['ite']['data-pos'] = $pos;
          $ope['ite']['class'] = ( $pos == $pos_ver ) ? "" : DIS_OCU;
          $_.="
          <li"._htm::atr($ope['ite']).">";
            // contenido html
            if( is_string($ite) ){
              $_ .= $ite;
            }// elementos html
            elseif( is_array($ite) ){
              $_ .= _ele::val($ite);
            }// modelo : titulo + detalle + imagen
            elseif( is_object($ite) ){

            } $_.= "
          </li>";
        }$_.="
      </ul>";
      // operadores
      $min = $pos == 0 ? 0 : 1;
      $max = $pos;
      $_eje .= "_ite";
      $_ .= "
      <form class='ope anc-100 jus-cen mar_ver-2'>

        "._doc_num::ope('val',$min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."
                
        "._app::ico('lis_pre',['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('val',this);"])."

        "._doc_num::ope('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('val',this);" ])."

        "._app::ico('lis_pos',['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('val',this);"])."            

        "._doc_num::ope('val',$max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."          

      </form>";
      return $_;
    }
    // indices : a[href] > ...a[href]
    static function nav( array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_eje = self::$EJE."nav_";// val | ver
      $_ = "";

      // operador
      _ele::cla( $ele['ope'], "ite", 'ini' );
      $_ .= "
      <form"._htm::atr($ele['ope']).">

        "._doc_val::tog_ope()."

        "._doc_val::ver([ 'cue'=>0, 'ele_val'=>['class'=>"anc-100"], 'eje'=>"{$_eje}ver(this);" ])."

      </form>";
      // dependencias
      $tog_dep = FALSE;
      if( in_array('tog_dep',$opc) ){
        _ele::cla( $ele['ope_dep'], "ite", 'ini' ); $tog_dep = "
        <form"._htm::atr($ele['ope_dep']).">
  
          "._doc_val::tog_ope()."
  
        </form>";
      }
      // armo listado de enlaces
      $_lis = [];
      $opc_ide = in_array('ide',$opc);
      _ele::cla( $ele['lis'], "nav", 'ini' );
      foreach( $dat[1] as $nv1 => $_nv1 ){
        $ide = $opc_ide ? $_nv1->ide : $nv1;
        $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv1->nom}") ];
        if( !isset($dat[2][$nv1]) ){
          $_lis []= _htm::val($eti_1);
        }
        else{
          $_lis_2 = [];
          foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
            $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
            $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv2->nom}") ];
            if( !isset($dat[3][$nv1][$nv2])  ){
              $_lis_2 []= _htm::val($eti_2);
            }
            else{
              $_lis_3 = [];              
              foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
                $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
                $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv3->nom}") ];
                if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                  $_lis_3 []= _htm::val($eti_3);
                }
                else{
                  $_lis_4 = [];                  
                  foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                    $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                    $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv4->nom}") ];
                    if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                      $_lis_4 []= _htm::val($eti_4);
                    }
                    else{
                      $_lis_5 = [];                      
                      foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                        $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                        $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv5->nom}") ];
                        if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                          $_lis_5 []= _htm::val($eti_5);
                        }
                        else{
                          $_lis_6 = [];
                          foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                            $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                            $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_doc::let("{$_nv6->nom}") ];
                            if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                              $_lis_6 []= _htm::val($eti_6);
                            }
                            else{
                              $_lis_7 = [];
                              // ... continuar ciclo
                              $_lis_6 []= [ 'ite'=>$eti_6, 'lis'=>$_lis_7 ];                              
                            }
                          }
                          $_lis_5 []= [ 'ite'=>$eti_5, 'lis'=>$_lis_6 ];
                        }
                      }
                      $_lis_4 []= [ 'ite'=>$eti_4, 'lis'=>$_lis_5 ];
                    }
                  }
                  $_lis_3 []= [ 'ite'=>$eti_3, 'lis'=>$_lis_4 ];
                }
              }
              $_lis_2 []= [ 'ite'=>$eti_2, 'lis'=>$_lis_3 ];  
            }
          }
          $_lis []= [ 'ite'=>$eti_1, 'lis'=>$_lis_2 ];
        }
      }
      // pido listado
      _ele::cla($ele['dep'],DIS_OCU);
      $ele['opc'] = [];
      $_ .= _doc_lis::val($_lis,$ele);
      return $_;
    }
    // posicion : dl, ul, ol
    static function ite( array $dat, array $ope = [] ) : string {
      foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
      $_ = "";
      $_eje = self::$EJE."ite";
      // operador
      if( isset($ope['opc']) ) $_ .= _doc_lis::ope('ite', $ope['opc'] = _lis::ite($ope['opc']), $ope);
      // por punteo o numerado
      if( _obj::pos($dat) ){
        $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul'; 
        $_ .= "
        <{$eti}"._htm::atr($ope['lis']).">";
          foreach( $dat as $pos => $val ){
            $_ .= _doc_lis::ite_pos( 1, $pos, $val, $ope, $eti );
          }$_.="
        </{$eti}>";
      }
      // por términos
      else{
        // agrego toggle del item
        _ele::eje($ope['ite'],'cli',"{$_eje}_val(this);",'ini');
        $_ .= "
        <dl"._htm::atr($ope['lis']).">";
          foreach( $dat as $nom => $val ){ 

            $ope_ite = $ope['ite'];

            if( empty($ope_ite['id']) ) $ope_ite['id'] = "_doc-tex ".str_replace(' ','_',mb_strtolower($nom));
            $_ .= "
            <dt"._htm::atr($ope_ite).">
              "._doc::let($nom)."
            </dt>";
            foreach( _lis::ite($val) as $ite ){ $_ .= "
              <dd"._htm::atr($ope['val']).">
                "._doc::let($ite)."
              </dd>";
            }
          }$_.="
        </dl>";
      }
      return $_;
    }
    static function ite_pos( int $niv, int | string $ide, mixed $val, array $ope, string $eti = "ul" ) : string {
      $_ = "
      <li"._htm::atr($ope['ite']).">";
        if( is_string($val) ){ 
          $_ .= $val;
        }// sublistas
        else{
          $niv++;
          $_.="
          <$eti data-niv='$niv'>";
          if( isset($ope['opc']) ){
            $opc = [];
            if( in_array('tog_dep',$ope['opc']) ) $opc []= "tog";
            $_ .= "<li>"._doc_lis::ope('ite',$opc,$ope)."</li>";
          }
          foreach( $val as $ide => $val ){
            $_ .= _doc_lis::ite_pos( $niv, $ide, $val, $ope, $eti );
          }$_.="
          </$eti>";
        }
        $_ .= "
      </li>";
      return $_;
    }
    // valores : ul > ...li > .val(.ico + tex-tit) + lis/htm
    static function val( array $dat, array $ope = [] ) : string {
      foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
      $_ = "";
      // elementos        
      _ele::cla($ope['lis'],"lis",'ini');
      _ele::cla($ope['ite'],"ite",'ini');
      _ele::cla($ope['dep'],"lis",'ini');
      _ele::cla($ope['ope'],"ite",'ini');      
      // operadores
      if( isset($ope['opc']) ) $_ .= _doc_lis::ope('val', _lis::ite($ope['opc']), $ope);
      // listado
      $_ .= "
      <ul"._htm::atr($ope['lis']).">";
      foreach( $dat as $ide => $val ){

        $_ .= _doc_lis::val_pos( 1, $ide, $val, $ope );
      }$_ .= "
      </ul>";
      return $_;
    }    
    static function val_pos( int $niv, int | string $ide, string | array $val, array $ope ) : string {
    
      $ope_ite = $ope['ite'];      
      // con dependencia : evalúo rotacion de icono
      if( $val_lis = is_array($val) ){
        $ope_ico = $ope['ico'];
        $ele_dep = isset($ope["lis-$niv"]) ? _ele::jun($ope['dep'],$ope["lis-$niv"]) : $ope['dep'];
        if( isset($ele_dep['class']) && preg_match("/".DIS_OCU."/",$ele_dep['class']) ) _ele::cla($ope_ico,"ocu");
        if( !isset($val['ite_ope']) ) $val['ite_ope'] = [];
        $val['ite_ope']['ico'] = $ope_ico;
      }// sin dependencias : separo item por icono vacío
      else{
        _ele::cla($ope_ite,"sep");
      }
      $_ = "
      <li"._htm::atr( isset($ope["ite-$niv"]) ? _ele::jun($ope_ite  ,$ope["ite-$niv"]) : $ope_ite  ).">

        ".( $val_lis ? _doc_val::tog( isset($val['ite']) ? $val['ite'] : $ide, $val['ite_ope'] ) : $val );
        
        if( $val_lis ){
          // sublista
          if( isset($val['lis']) ){
            $ope['dep']['data-niv'] = $niv;
            $_ .= "
            <ul"._htm::atr($ele_dep).">";

            if( is_array($val['lis'])  ){
              // operador por dependencias : 1° item de la lista
              if( isset($ope['opc'])){
                $opc = [];
                foreach( $val['lis'] as $i => $v ){ $lis_dep = is_array($v); break; }                
                if( in_array('tog_dep',$ope['opc']) && $lis_dep ) $opc []= "tog";
                if( !empty($opc) ) $_ .= "
                <li>"._doc_lis::ope('val',$opc,$ope)."</li>";
              }
              foreach( $val['lis'] as $i => $v ){

                $_ .= _doc_lis::val_pos( $niv+1, $i, $v, $ope );
              }
            }
            // listado textual
            elseif( is_string($val['lis']) ){

              $_ .= $val['lis'];
            }$_ .= "
            </ul>";
          }// contenido html directo ( asegurar elemento único )
          elseif( isset($val['htm']) ){

            $_ .= is_string($val['htm']) ? $val['htm'] : _ele::val($val['htm']);
          }
        }$_ .= "          
      </li>";        
      return $_;
    }
  }
  // Tabla
  class _doc_est {

    static string $IDE = "_doc_est-";
    static string $EJE = "_doc_est.";
    static array $OPE = [
      // identificador : esq.est
      'ide'=>"",
      // opciones
      'opc'=>[
        'cab_ocu',  // ocultar cabecera de columnas
        'ite_ocu',  // oculto items: en titulo + detalle
        'det_cit',  // en detalle: agrego comillas
        'ima',      // buscar imagen para el dato
        'var',      // mostrar variable en el dato
        'htm'       // convertir texto html en el dato
      ],
      // columnas del listado
      'atr'=>[],
      'atr_tot'=>0,// columnas totales
      'atr_ocu'=>[],// columnas ocultas
      'atr_dat'=>[],// datos de las columnas
      // estructuras por esquema => [ ...$esq =>[ ...$est ] ]
      'est'=>[],
      'est_dat'=>[],// datos y operadores por estructura
      // filas: valores por estructura [...{...$}]
      'dat'=>[],
      'dat_ite'=>[],// titulos + detalles
      'dal_val'=>[],// datos por fila
      // Valores : acumulado + posicion principal
      'val'=>[ 'acu'=>[], 'pos'=>[] ],
      // titulos: por base {'cic','gru','des'} o por operador [$pos]
      'tit'=>[],
      'tit_cic'=>[],// titulos por ciclos
      'tit_gru'=>[],// titulos por agrupaciones
      // detalles: por base {'cic','gru','des'} o por operador [$pos]
      'det'=>[],
      'det_cic'=>[],// detalle por ciclos
      'det_gru'=>[],// detalle por agrupaciones
      'det_des'=>[] // detalle por descripciones
    ];

    // listado : thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td )
    static function lis( string | array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      $_ide = self::$IDE."lis";
      if( !isset($ope['opc']) ) $ope['opc']=[];
      foreach( [ 'lis','cue','tit_ite','tit_val','dat_ite','dat_val','det_ite','det_val','val'] as $i ){ 
        if( !isset($ele[$i]) ) $ele[$i]=[]; 
      }      
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide = "_$esq-$est $_ide";
      }// por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide = "_$esq-$est $_ide";
        }
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::get($esq,$est);
      // aseguro estructura
      if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];            
      
      // identificadores de la base        
      if( isset($esq) ){
        $ele['lis']['data-esq'] = $esq;
        $ele['lis']['data-est'] = $est;
      }
      if( !isset($ele['lis']['class']) ) $ele['lis']['class'] = "mar-aut";
      $_ = "
      <table"._htm::atr($ele['lis']).">";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';

        // columnas:
        if( $dat_val_lis = is_array($dat) ){
          // datos de atributos
          if( !isset($ope['atr_dat']) ){
            $ope['atr_dat'] = _doc_est::atr_ver($dat);
          }
          // listado de columnas
          if( !isset($ope['atr']) ){
            $ope['atr'] = array_keys($ope['atr_dat']);
          }
        }
        // caclulo total de columnas
        $ope['atr_tot'] = _doc_est::atr_cue($dat,$ope);

        // cabecera
        if( !in_array('cab_ocu',$ope['opc']) ){ 
          foreach( [ 'cab','cab_ite','cab_val' ] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } 
          $_ .= "
          <thead"._htm::atr($ele['cab']).">
            "._doc_est::atr($dat,$ope,$ele)."
          </thead>";
        }
        // cuerpo
        $_.="
        <tbody"._htm::atr($ele['cue']).">";
          // recorro: por listado $dat = []
          $pos_val = 0;
          if( $dat_val_lis ){
            
            foreach( $ope['dat'] as $pos => $_dat ){
              // titulos
              if( !empty($ope['tit'][$pos]) ) $_ .= _doc_est::pos('tit',$pos,$ope,$ele);

              // fila-columnas
              $pos_val++;
              $ope['dat_val'] = $_dat; 
              $ele['dat_ite']['pos'] = $pos_val; $_.="
              <tr"._htm::atr($ele['dat_ite']).">
                "._doc_est::ite($dat,$ope,$ele)."
              </tr>";

              // detalles
              if( !empty($ope['det'][$pos]) ) $_ .= _doc_est::pos('det',$pos,$ope,$ele);                    
            }
          }
          // estructuras de la base esquema
          else{

            // valido item por objeto-array
            foreach( $ope['dat'] as $_dat ){ $_val_dat_obj = is_object($_dat); break; }
            
            // valido contenido : titulos y detalles por estructura de la base
            $ele_ite = [];
            foreach( [ 'tit'=>['cic','gru'], 'det'=>['des','cic','gru'] ] as $i => $v ){ 
              $_val[$i] = isset($ope[$i]);
              foreach( $v as $e ){

                if( !is_numeric($e) && ( $_val["{$i}_{$e}"] = isset($ope["{$i}_{$e}"]) ) || ( $_val[$i] && in_array($e,$ope[$i]) ) ){

                  $ele_ite["{$i}_{$e}"] = [ 'ite'=>[ 'data-opc'=>$i, 'data-ope'=>$e ], 'atr'=>[ 'colspan'=>$ope['atr_tot'] ] ]; 
                }
              }            
            }

            // contenido previo : titulos por agrupaciones
            if( isset($ele_ite['tit_gru']) ){

              foreach( $ope['est'] as $esq => $est_lis ){

                foreach( $est_lis as $est ){

                  $_ .= _doc_est::tit('gru', "{$esq}.{$est}", $ope, $ele_ite['tit_gru']);
                }
              }
            }

            // recorro datos
            $ope['opc'] []= "det_cit";
            foreach( $ope['dat'] as $pos => $_dat ){
              // titulos
              foreach( $ope['est'] as $esq => $est_lis ){
                // recorro referencias
                foreach( $est_lis as $est){
                  // cargo relaciones                  
                  if( $dat_opc_est = _app::dat($esq,$est,'est') ){

                    foreach( $dat_opc_est as $atr => $ref ){

                      $ele['ite']["{$esq}-{$ref}"] = $_val_dat_obj ? $_dat->$atr : $_dat["{$esq}-{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos                
                  if( $_val['tit'] || $_val['tit_cic'] ){

                    $ope['dat_val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];

                    $_ .= _doc_est::tit('cic', "{$esq}.{$est}", $ope, $ele_ite['tit_cic']);
                  }
                }
              }
              // cargo item por esquema.estructuras
              $pos_val ++;
              $ele['ite']['pos'] = $pos_val; $_ .= "
              <tr"._htm::atr($ele['ite']).">";
              foreach( $ope['est'] as $esq => $est_lis ){
      
                foreach( $est_lis as $est ){
                  
                  $ope['dat_val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                  $_ .= _doc_est::ite("{$esq}.{$est}", $ope, $ele);
                } 
              }$_ .= "
              </tr>";
              // cargo detalles
              foreach( $ope['est'] as $esq => $est_lis ){

                foreach( $est_lis as $est ){

                  foreach( ['des','cic','gru'] as $ide ){

                    if( isset($ele_ite["det_{$ide}"]) ){

                      $ope['dat_val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                      $_ .= _doc_est::det($ide, "{$esq}.{$est}", $ope, $ele_ite["det_{$ide}"] );
                    }
                  }                  
                } 
              }                    
            }
          }$_ .= "              
        </tbody>";
        // pie
        if( !empty($ope['pie']) ){
          foreach( ['pie','pie_ite','pie_val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
          $_.="
          <tfoot"._htm::atr($ele['pie']).">";
            // fila de operaciones
            $_.="
            <tr"._htm::atr($ele['pie_ite']).">";
              foreach( $_atr as $atr ){ $_.="
                <td data-atr='{$atr->ide}' data-ope='pie'></td>";
              }$_.="
            </tr>";
            $_.="
          </tfoot>";
        }
        $_.="
      </table>";
      return $_;
    }
    // columnas : por atributos
    static function atr( string | array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";

      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }
      // por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
        }
      }
      
      // por muchos      
      if( isset($ope['est']) ){

        $ope_est = $ope['est'];
        unset($ope['est']);

        foreach( $ope_est as $esq => $est_lis ){

          foreach( $est_lis as $est ){

            $_ .= _doc_est::atr("{$esq}.{$est}",$ope,$ele);
          }
        }
      }
      // por 1: esquema.estructura
      else{
        $_val['dat'] = isset($esq);

        $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
        // cargo datos
        $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val['dat'] ? _dat::atr($esq,$est) : _doc_est::atr_ver($dat) );
        // ocultos por estructura
        $atr_ocu = isset($_est->atr_ocu) ? $_est->atr_ocu : [];
        // genero columnas
        foreach( ( !empty($ope['atr']) ? $ope['atr'] : ( !empty($_est->atr) ? $_est->atr : array_keys($ope['atr_dat']) ) ) as $atr ){
          $e = [];
          if( $_val['dat'] ){
            $e['data-esq'] = $esq;
            $e['data-est'] = $est;
          } 
          $e['data-atr'] = $atr;
          if( in_array($atr,$atr_ocu) ) _ele::cla($e,"dis-ocu");
          // poner enlaces
          $htm = _doc::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
          if( !$ope_nav ){
            $htm = "<p>{$htm}</p>";
          }else{
            $htm = "<a href='' target='_blank'>{$htm}</a>";
          }$_ .= "
          <th"._htm::atr($e).">
            {$htm}
          </th>";
        }         
      }   

      return $_;
    }// genero atributos
    static function atr_ver( string | array $dat, string $ope = "" ) : array {
      $_ = [];
      if( empty($ope) ){
        // de la base
        if( is_string($dat) ){        
          $ide = _dat::ide($dat);
          $_ = _dat::atr($ide['esq'],$ide['est']);
        }
        // del entorno 
        else{
          
          foreach( $dat as $ite ){

            foreach( $ite as $ide => $val ){ 
              $atr = new stdClass;
              $atr->ide = $ide;
              $atr->nom = $ide;
              $atr->var = _dat::tip($val);
              // cargo atributo
              $_ [$ide] = $atr;
            }
            break;
          }        
        }
      }
      return $_;
    }// cuento columnas totales
    static function atr_cue( string | array $dat, array $ope=[] ) : int {
      $_ = 0;
      if( isset($ope['atr']) ){
        
        $_ = count($ope['atr']);
      }
      // 1 estructura de la base
      elseif( !( $obj_tip = _obj::tip($dat) ) ){

        $ide = _dat::ide($dat);

        $dat_est = _app::est($ide['esq'],$ide['est']);

        $_ = isset($dat_est->atr) ? count($dat_est->atr) : 0;

      }
      // n estructuras de la base
      elseif( $obj_tip == 'nom' ){

        foreach( $dat as $esq => $est_lis ){
  
          foreach( $est_lis as $est ){

            $dat_est = _app::est($esq,$est);

            $_ += count($dat_est->atr);
          }
        }
      }
      // por listado                    
      elseif( $obj_tip == 'pos' ){

        foreach( $dat as $ite ){

          foreach( $ite as $val ){ 
            $_ ++; 
          }
          break;
        }
      }
      return $_;
    }
    // posicion : titulo + detalle
    static function pos( string $tip, int $ide, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      if( isset($ope[$tip][$ide]) ){

        foreach( _lis::ite($ope[$tip][$ide]) as $val ){ 
          $_.="
          <tr"._htm::atr($ele["{$tip}_ite"]).">
            <td"._htm::atr(_ele::jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">
              ".( is_array($val) ? _htm::val($val) : "<p class='{$tip} tex_ali-izq'>"._doc::let($val)."</p>" )."
            </td>
          </tr>";
        }        
      }
      return $_;
    }
    // titulo : posicion + ciclos + agrupaciones
    static function tit( string $tip, string | array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }
      // 1 titulo : nombre + detalle
      if( $tip == 'pos' ){
        $atr = $ope['dat_ite'][0];
        $ide = $ope['dat_ite'][1];
        $val = $ope['dat_ite'][2];
        $ele['ite']['data-atr'] = $atr;
        $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
        $htm = "";
        if( !empty($htm_val = _app_dat::val('nom',"{$esq}.{$ide}",$val)) ){ $htm .= "
          <p class='tit'>"._doc::let($htm_val)."</p>";
        }
        if( !empty($htm_val = _app_dat::val('des',"{$esq}.{$ide}",$val)) ){ $htm .= "
          <q class='mar_arr-1'>"._doc::let($htm_val)."</q>";
        }
        if( in_array('ite_ocu',$ope['opc']) ) _ele::cla($ele['ite'],'dis-ocu');
        $_ .="
        <tr"._htm::atr($ele['ite']).">
          <td"._htm::atr($ele['atr']).">{$htm}</td>
        </tr>";
      }
      // ciclos + agrupaciones
      else{
        if( empty($ele['ite']['data-esq']) ){
          $ele['ite']['data-esq'] = $esq;
          $ele['ite']['data-est'] = $est;
        }
        if( !isset($ele['atr']['colspan']) ){
          $ele['atr']['colspan'] = 1;
          _ele::cla($ele['atr'],"anc-100");
        }
        // por ciclos : secuencias
        if( $tip == 'cic' ){        
          // acumulo posicion actual, si cambia -> imprimo ciclo        
          if( isset($_est->cic_val) ){          
            $val = _dat::get($esq,$est,$ope['dat_val']);
            foreach( $_est->cic_val as $atr => &$pos ){
              
              if( !empty($ide = _dat::rel($esq,$est,$atr) ) && $pos != $val->$atr ){

                if( !empty($val->$atr) ){
                  $ope['dat_ite'] = [$atr,$ide,$val->$atr];
                  $_ .= _doc_est::tit('pos',$dat,$ope,$ele);
                }
                $pos = $val->$atr;
              }
            }
          }        
        }
        // por agrupaciones : relaciones
        elseif( $tip == 'gru' ){
          if( isset($_est->$tip) ){
            foreach( $_est->$tip as $atr ){

              if( !empty($ide = _dat::rel($esq,$est,$atr)) ){

                foreach( _dat::get($esq,$ide) as $val ){
                  $ope['dat_ite'] = [$atr,$ide,$val];
                  $_ .= _doc_est::tit('pos',$dat,$ope,$ele);
                }
              }
            }
          }
        }        
      }
      return $_;
    }
    // item : datos de la estructura
    static function ite( string | array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }
      
      $dat = $ope['dat_val'];
      $opc_ima = !in_array('ima',$ope['opc']);
      $opc_var = in_array('var',$ope['opc']);
      $opc_htm = in_array('htm',$ope['opc']);

      // identificadores
      if( $_val['dat'] = isset($esq) ){
        $ele['dat_val']['data-esq'] = $esq;
        $ele['dat_val']['data-est'] = $est;
      }
      // datos de la base
      if( isset($_est) ){

        $_atr    = _dat::atr($esq,$est);
        $est_ima = _app::dat($esq,$est,'opc.ima');
        $atr_ocu = isset($_est->atr_ocu) ? $_est->atr_ocu : FALSE;
        
        foreach( ( isset($ope['atr']) ? $ope['atr'] : $_est->atr ) as $atr ){
          $ele_dat = $ele['dat_val'];
          $ele_dat['data-atr'] = $atr;         
          //ocultos
          if( $atr_ocu && in_array($atr,$atr_ocu) ) _ele::cla($ele_dat,'dis-ocu');
          // contenido
          $ele_val = $ele['val'];
          
          if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
            _ele::cla($ele_val,"tam-3");
            $ide = 'ima';
          }
          // variables
          else{
            $ide = $opc_var ? 'var' : 'tip';
            // adapto estilos por tipo de valor
            if( !empty($_atr[$atr]) ){
              $var_dat = $_atr[$atr]->var_dat;
              $var_val = $_atr[$atr]->var_val;
            }
            elseif( !empty( $_var = _dat::tip( $dat ) ) ){
              $var_dat = $_var->dat;
              $var_val = $_var->val;
            }
            else{
              $var_dat = "val";
              $var_val = "nul";
            }
            // - limito texto vertical
            if( $var_dat == 'tex' ){
              if( $var_dat == 'par' ) _ele::css($ele_val,"max-height:4rem; overflow-y:scroll");
            }
          }$_ .= "
          <td"._htm::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? _ele::cla($ele_dat,'dis-ocu') : $ele_dat ).">      
            "._app_dat::ver($ide,"{$esq}.{$est}.{$atr}",$dat,$ele_val)."
          </td>";
        }
      }
      // por listado del entorno
      else{
        $_atr = $ope['atr_dat'];
        $_val_dat_obj = is_object($dat);
        foreach( $ope['atr'] as $ide ){
          // valor
          $dat_val = $_val_dat_obj ? $dat->{$ide} : $dat[$ide];
          // html
          if( $opc_htm ){
            $htm = $dat_val;
          }// variable por tipo
          elseif( $opc_var ){
            $htm = "";
          }// elementos
          elseif( is_array( $dat_val ) ){
            $htm = isset($dat_val['htm']) ? $dat_val['htm'] : _htm::val($dat_val);
          }// textos
          else{
            $htm = _doc::let($dat_val);
          }
          $ele['dat_val']['data-atr'] = $ide;
          $_.="
          <td"._htm::atr($ele['dat_val']).">
            {$htm}
          </td>";
        }
      }      

      return $_;
    }
    // detalles : posicion + descripciones + lecturas
    static function det( string $tip, string | array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }      
      // 1 detalle
      if( $tip == 'pos' ){
        $atr = $ope['dat_ite'][0];
        $val = $ope['dat_ite'][1];
        $ele['ite']['data-atr'] = $atr;
        $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
        if( in_array('ite_ocu',$ope['opc']) ) _ele::cla($ele['ite'],'dis-ocu');
        $_ = "
        <tr"._htm::atr($ele['ite']).">
          <td"._htm::atr($ele['atr']).">
            ".( in_array('det_cit',$ope['opc']) ? "<q>"._doc::let($val->$atr)."</q>" : _doc::let($val->$atr) )."
          </td>
        </tr>";
      }
      // por tipos : descripciones + ciclos + agrupaciones
      elseif( isset($_est->{"det_$tip"}) ){
        if( empty($ele['ite']['data-esq']) ){
          $ele['ite']['data-esq'] = $esq;
          $ele['ite']['data-est'] = $est;
        }
        if( !isset($ele['atr']['colspan']) ){
          $ele['atr']['colspan'] = 1;
          _ele::cla($ele['atr'],"anc-100");
        }        
        $val = _dat::get($esq,$est,$ope['dat_val']);
        foreach( $_est->{"det_$tip"} as $atr ){
          $ope['dat_ite'] = [$atr,$val];
          $_ .= _doc_est::det('pos',$dat,$ope,$ele);
        }
      }

      return $_;
    }
  }
  // Opcion
  class _doc_opc {

    static string $IDE = "_doc_opc-";
    static string $EJE = "_doc_opc.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;
      
      switch( $tip ){
      // vacío : null
      case 'vac':
        $ope['type'] = 'radio'; 
        $ope['disabled'] = '1';
        if( is_nan($dat) ){ 
          $ope['val']="non";
        }elseif( is_null($dat) ){ 
          $ope['val']="nov";
        }                    
        break;
      // binario : input[checkbox]
      case 'bin':
        $ope['type']='checkbox';
        if( !empty($dat) ){ $ope['checked']='checked'; }
        break;
      // único : div > input[radio]
      case 'uni':
        if( isset($ope['dat']) ){
          $_dat = $ope['dat'];
          unset($ope['dat']); 
          $_ .= "
          <div var='opc_uni'>";
          $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
          foreach( $_dat as $ide => $val ){ $_ .= "
            <div class='val'>
              <label for='{$ope_ide}-{$ide}'>{$val}<c>:</c></label>
              <input id='{$ope_ide}-{$ide}' type='radio' name='{$ide}' value='{$ide}'>
            </div>";
          }$_ .= "
          </div>";
        }
        break;
      // múltiple : div > ...input[checkbox]
      case 'mul':
        if( isset($ope['dat']) ){
          $_dat = $ope['dat'];
          unset($ope['dat']); 
          $_ .= "
          <div var='opc_mul'>";
          $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
          foreach( $_dat as $ide => $val ){ $_ .= "
            <div class='val'>
              <label for='{$ope_ide}-{$ide}'>{$val}<c>:</c></label>
              <input id='{$ope_ide}-{$ide}' type='checkbox' name='{$ide}' value='{$ide}'>
            </div>";
          }$_ .= "
          </div>";
        }
        break;          
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._htm::atr($ope).">";            
      }
      return $_;
    }
    // opciones : select > ...option[value]
    static function val( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";

      $ope_eti = !empty($ope['eti']) ? _obj::dec($ope['eti'],[],'nom') : [];

      if( isset($ope_eti['data-opc']) ){
        $opc = array_merge($opc,is_array($ope_eti['data-opc']) ? $ope_eti['data-opc'] : explode(',',$ope_eti['data-opc']) );
      }

      // etiqueta del contenedor
      $eti = isset($ope_eti['eti']) ? $ope_eti['eti'] : 'select';

      // aseguro valor
      $val = NULL;
      if( isset($ope['val']) ){
        $val = $ope['val'];
      }
      elseif( isset($ope_eti['val']) ){
        $val = $ope_eti['val'];
        unset($ope_eti['val']);
      }
      
      $_ = "
      <{$eti}"._htm::atr($ope_eti).">";

        if( in_array('nad',$opc) ){ $_ .= "
          <option default value=''>{-_-}</option>"; 
        }
        // items
        $ope_ite = isset($ope['ite']) ? $ope['ite'] : [];
        if( !empty($ope['gru']) ){

          foreach( $ope['gru'] as $ide => $nom ){ 

            if( isset($dat[$ide]) ){ $_.="
              <optgroup data-ide='{$ide}' label='{$nom}'>
                "._doc_opc::lis( $dat[$ide], $val, $ope_ite, ...$opc )."                
              </optgroup>";
            }
          }
        }
        else{                        
          $_ .= _doc_opc::lis( $dat, $val, $ope_ite, ...$opc );
        }
        $_ .= "
      </{$eti}>";

      return $_;
    }
    // opciones : ...option[value]
    static function lis( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
      $_ = "";
      
      $val_ite = !empty($val);
      $val_arr = $val_ite && is_array($val);
      $opc_ide = in_array('ide',$opc);

      $obj_tip = FALSE;
      foreach( $dat as $i => $v){ 
        $obj_tip = _obj::tip($v);
        break;
      }

      foreach( $dat as $i => $v){ 
        $atr=''; 
        $htm=''; 
        $e = $ope;

        // literal
        if( !$obj_tip ){  
          $e['value'] = $i;
          $htm = !!$opc_ide ? "{$i}: ".strval($v) : strval($v) ;
          $atr = _htm::atr($e);
        }
        // elemento
        elseif( $obj_tip == 'nom' ){
          $e = _ele::jun($e,$v);
          if( !isset($e['value']) ) $e['value'] = $i;
          $htm = isset($e['htm']) ? $e['htm'] : $i;
          $atr = _htm::atr($e);
        }
        // objeto ( ide + nom + des + tit )
        elseif( $obj_tip == 'atr' ){
          $_ide = isset($v->ide) ? $v->ide : FALSE ;
          $_htm = isset($v->nom) ? $v->nom : FALSE ;
          // valor
          if( isset($e['value']) ){ 
            $e['value'] = _obj::val($v,$e['value']); 
          }else{ 
            $e['value'] = $i;
            if( $_ide ){ $e['value'] = $_ide; }elseif( $_htm ){ $e['value'] = $_htm; }
          }
          // titulo con descripcion
          if( !isset($e['title']) ){ 
            if( isset($v->des) ){ 
              $e['title'] = $v->des; 
            }elseif( isset($v->tit) ){ 
              $e['title'] = $v->tit; 
            }
          }
          // contenido
          if( isset($e['htm']) ){
            $htm = _obj::val($v,$e['htm']);
          }else{
            if( !!$opc_ide && $_ide && $_htm ){
              $htm = "{$_ide}: {$_htm}";
            }elseif( $_htm ){
              $htm = $_htm;
            }else{
              $htm = $_ide; 
            }
          }
          $atr = _htm::atr($e,$v);            
        }// por posiciones
        else{
          $htm = "( \"".implode( '", "', $v )."\" )" ;
          $atr = _htm::atr($e);
        }
        // agrego atributo si está en la lista
        if( $val_ite ){ 
          if( $val_arr ){
            if( in_array($e['value'],$val) ) $atr .= " selected";
          }
          elseif( $val == $e['value'] ){

            $atr .= " selected";
          }
        }
        $_ .= "<option{$atr}>{$htm}</option>";
      }   
      return $_;
    }
  }
  // Numero
  class _doc_num {

    static string $IDE = "_doc_num-";
    static string $EJE = "_doc_num.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      if( $tip == 'val' ){

        if( !isset($ope['htm']) ) $ope['htm'] = $dat;

        $_ = "<n"._htm::atr($ope).">{$ope['htm']}</n>";
      }
      else{

        if( $tip != 'bit' ){
  
          if( !isset($ope['value']) && isset($dat) ) $ope['value'] = $dat;
  
          if( !empty($ope['num_ran']) ){ $tip = 'ran'; }else{ $ope['type']='number'; }
  
          if( isset($ope['max']) && !isset($ope['maxlength']) ) $ope['maxlength'] = strlen( strval( $ope['max'] ) );          
        }
  
        // controlo valores al actualizar
        _ele::eje($ope,'inp',"$_eje"."act(this);",'ini');
        // seleccion automática
        _ele::eje($ope,'foc',"this.select();",'ini');        
        
        switch( $tip ){
        case 'bit':
  
          $ope['type']='text';  
          break;          
        case 'int':
  
          if( !isset($ope['step']) ) $ope['step']='1'; 
  
          if( !empty($ope['value']) ){
  
            if( !empty($ope['num_pad']) ){
              
              if( !empty($ope['maxlength']) ){ 
                $tam = $ope['maxlength']; 
              }
              elseif( !empty($ope['max']) ){ 
                $tam = count(explode('',$ope['max'])); 
              }
            }
            if( !empty($tam) ){ 
              $ope['value'] = _num::val($ope['value'],$tam); 
            }
            if( !empty($ope['num_sep']) ){ 
              $ope['value'] = _num::int($ope['value']); 
            }
          }
          break;
        case 'dec':
          if( !empty($ope['value']) ){
  
            $ope['value'] = floatval($ope['value']);
  
            if( !empty($ope['maxlength']) ){
              $tam = explode(',',$ope['maxlength']); 
              $int = $tam[0]; 
              $dec = isset($tam[1]) ? $tam[1] : 0;
            }
            else{
  
              if( !empty($ope['num_dec']) ){ 
  
                $dec = $ope['num_dec']; 
              }
              elseif( isset($ope['step']) ){ 
                $dec = explode('.',$ope['step']); 
                $dec = isset($dec[1]) ? strlen($dec[1]) : 0;
              }
              if( isset($ope['num']) ){ 
  
                $int = strlen($ope['num']); 
              }
              elseif( isset($ope['max']) ){ 
  
                $int = strlen($ope['max']); 
              }
            }
            $tam = intval($int) + 1;
  
            if( empty($dec) ) $dec = 2;
  
            $ope['num_dec'] = $dec;
  
            if( !isset($ope['step']) ) $ope['step'] = '0.'._num::val('1',$dec);
  
            if( !empty($ope['num_pad']) && !empty($tam) ) $ope['value'] = _num::val($ope['value'],$tam);
  
            if( !empty($ope['num_sep']) ) $ope['value'] = _num::dec($ope['value']);
          }
          break;
        case 'ran':
          $ope['type']='range'; 
          if( !isset($ope['step']) ) $ope['step']=1; 
          if( !isset($ope['min']) )  $ope['min']=0; 
          if( !isset($ope['max']) )  $ope['max']=$ope['step'];
          // armo bloques : min < --- val --- > max / output
          if( !in_array('ver',$opc) ){            
            $cla = "";
            if( isset($ope['class']) ){ 
              $cla = "{$ope['class']}"; 
              unset($ope['class']); 
            }
            if( !isset($ope['id']) ){ 
              $ope['id'] = "_num_ran-"._app_var::ide('_num-ran');
            }
            $htm_out = "";
            if( !in_array('val-ocu',$opc) ){ $htm_out = "
              <output for='{$ope['id']}'>
                <n class='_val'>{$ope['value']}</n>
              </output>";
            }
            $_ = "
            <div var='num_ran' class='{$cla}'>
            
              <div class='val'>
                <n class='_min'>{$ope['min']}</n>
                <c class='sep'><</c>
                <input"._htm::atr($ope).">
                <c class='sep'>></c>
                <n class='_max'>{$ope['max']}</n>
              </div>

              {$htm_out}

            </div>";
          }
          break;
        }

        if( empty($_) && !empty($ope['type']) ){
          $_ = "<input"._htm::atr($ope).">";
        }
      }        

      return $_;
    }
  }
  // Texto
  class _doc_tex {

    static string $IDE = "_doc_tex-";

    static string $EJE = "_doc_tex.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      // valor
      if( $tip == 'val' ){

        $ope['eti'] = !empty($ope['eti']) ? $ope['eti'] : 'font';

        $ope['htm'] = _doc::let( is_null($dat) ? '' : strval($dat) );

        $_ = _htm::val($ope);

      }// por tipos
      else{

        if( !is_string($dat) ) $dat = strval( is_iterable($dat) ? _obj::cod($dat) : $dat );

        $ope['value'] = str_replace('"','\"',$dat);

        if( $tip == 'par' ){

          if( empty($ope['rows']) ) $ope['rows']="2";      
        }
        else{
          $ope['type']='text';
        }
        if( isset($ope['type']) ){
          $lis_htm = "";
          if( isset($ope['lis']) || isset($ope['dat']) ){
            if( isset($ope['lis']) ){
              $dat_lis = _obj::dec($ope['lis']);
              unset($ope['lis']);          
            }else{
              $dat_lis = [];
            }        
            if( empty($ope['id']) ){ 
              $ope['id']="_tex-{$tip}-"._app_var::ide("_tex-{$tip}-");
            }
            $ope['list'] = "{$ope['id']}-lis";
            $lis_htm = "
            <datalist id='{$ope['list']}'>";
              foreach( $dat_lis as $pos => $ite ){ $lis_htm .= "
                <option data-ide='{$pos}' value='{$ite}'></option>";
              }$lis_htm .="
            </datalist>";
          }
          // seleccion automática
          _ele::eje($ope,'foc',"this.select();",'ini');  
          $_ = "<input"._htm::atr($ope).">".$lis_htm;
        }
        else{
          $_ = "<textarea"._htm::atr($ope).">{$dat}</textarea>";
        }
      }      

      return $_;
    }
  }
  // Figura
  class _doc_fig {

    static string $IDE = "_doc_fig-";
    static string $EJE = "_doc_fig.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;
      
      switch( $tip ){
      case 'ima': 
        $_ = "<img src=''>";
        break;
      // color
      case 'col':
        $ope['type']='color';
        $ope['value'] = empty($dat) ? $dat : '#000000';
        break;
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._htm::atr($ope).">";            
      }
      return $_;
    }
  }
  // Fecha
  class _doc_fec {

    static string $IDE = "_doc_fec-";

    static string $EJE = "_doc_fec.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      

      switch( $tip ){
      case 'val':
        $ope['value'] = $dat; $_ = "
        <time"._htm::atr($ope).">
          "._doc::let(_fec::var($dat))."
        </time>";
      case 'tie': 
        $ope['value'] = intval($dat);
        $ope['type']='numeric';
        break;
      case 'dyh': 
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='datetime-local';
        break;
      case 'hor':
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='time';
        break;
      case 'dia':
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='date';
        break;
      case 'sem':
        $ope['value'] = intval($dat);
        $ope['type']='week';
        break;
      case 'mes':
        $ope['value'] = intval($dat);
        $ope['type']='number';
        break;
      case 'año': 
        $ope['value'] = intval($dat);
        $ope['type']='number';
        break;
      }

      if( empty($_) && !empty($ope['type']) ){
        // seleccion automática
        _ele::eje($ope,'foc',"this.select();",'ini');
        $_ = "<input"._htm::atr($ope).">";
      }      

      return $_;
    }        
  }  
  // Archivo
  class _doc_arc {

    static string $IDE = "_doc_arc-";

    static string $EJE = "_doc_arc.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      if( isset($ope['tip']) ) $ope['accept'] = _arc::tip($ope['tip']);

      switch( $tip ){
      case 'val':
        $ope['type']='file';
        if( isset($ope['multiple']) ) unset($ope['multiple']);
        break;
      case 'lis':
        $ope['type']='file';
        $ope['multiple'] = '1';
        break;
      case 'url':
        $ope['type']='url';
        break;
      // ima - vid - mus
      default:
        $ope['type']='file';
        $ope['accept'] = _arc::tip($tip);
        break;      
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._htm::atr($ope).">";
      }
      return $_;
    }    
    
  }
  // Obejeto
  class _doc_obj {

    static string $IDE = "_doc_obj-";
    static string $EJE = "_doc_obj.";

    // operadores 
    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      // texto : json
      if( !isset($dat) || is_string($dat) ){
        $ope['value'] = strval($dat); $_ = "
        <textarea"._htm::atr($ope).">{$dat}</textarea>";
      }
      // por tipos: pos - nom - atr
      elseif( $tip = _obj::tip($dat) ){
        $cue = 0; 
        $htm = '';
        $cla_agr = ''; 
        $cla_tog = ' dis-ocu';
        if( in_array('ocu',$opc) ){ 
          $cla_agr = ' dis-ocu'; 
          $cla_tog = ''; 
        }
        $atr_agr = in_array('dat',$opc) ? '' : " disabled";
        // separadores
        if( $tip=='pos' ){ $ini='('; $fin=')'; }elseif( $tip=='nom' ){ $ini='['; $fin=']'; }else{ $ini='{'; $fin='}'; }
        // conteido
        if( is_object($dat) ){
          // ... incluir metodos
        }
        foreach( $dat as $i=>$v ){ 
          $cue++; 
          $htm .= _doc_obj::ite( $i, $v, $tip, ...$opc);
        }
        $ope['var']= "obj_{$tip}";
        $_ = "
        <div"._htm::atr($ope).">
          <div class='jus-ini mar_ver-1'>
            <p>
              <c>(</c> <n class='sep'>{$cue}</n> <c>)</c> <c class='sep'>=></c> <c class='_lis-ini'>{$ini}</c>
            </p>
            "._app::ico('dat_ver',['onclick'=>"$_eje.val(this,'tog');"])."
            <ul class='ope _tog{$cla_agr}'>"; 
              if( empty($atr_agr) ){ $_.="
              "._app::ico('dat_tod',['eti'=>"li",'onclick'=>"$_eje.val(this,'tod');"])."
              "._app::ico('dat_nad',['eti'=>"li",'onclick'=>"$_eje.val(this,'nad');"])."
              "._app::ico('dat_agr',['eti'=>"li",'onclick'=>"$_eje.val(this,'agr');"])."
              "._app::ico('dat_eli',['eti'=>"li",'onclick'=>"$_eje.val(this,'eli');"])."
              ";
              }$_.="
            </ul>
            <p class=' _tog{$cla_tog}'>
              <c class='sep _lis-fin'>{$fin}</c>
            </p>
          </div>
          <ul class='lis _atr ali-ini _tog{$cla_agr}'> 
            {$htm}
          </ul>
          <p class='_tog{$cla_agr}'>
            <c class='_lis-fin'>{$fin}</c>
          </p>
        </div>";
      }

      return $_;
    }
    // item por tipo
    static function ite( mixed $ide, mixed $dat = NULL, string $tip = 'pos', array $ope = [], ...$opc ) : string {
      $_ = "";

      $ope['ent']=isset($ope['ent'])?$ope['ent']:'alm';
      
      $ope['eti']=isset($ope['eti'])?$ope['eti']:[]; 
      $ope['ite']=isset($ope['ite'])?$ope['ite']:[];      

      if( is_null($dat) ){ 
        $dat=''; 
        $tip_dat='val'; 
        $tip_val='vac'; 
        $_="<input type='radio' disabled>";
      }
      else{ 
        $tip = _dat::tip($dat); 
        $tip_dat = $tip['dat']; 
        $tip_val = $tip['val']; 
      }

      $ite = "";
      if( in_array('dat',$opc) && $tip != 'val' ){ 
        $ite = "<input type='checkbox'>"; 
      }
      // items de lista -> // reducir dependencias
      $cla_ide = "_doc_{$tip_dat}";
      if( in_array($tip_dat,[ 'lis' ]) ){

        if( $ite != "" ){          
          $_ = $cla_ide::ope( $tip_val, $dat, [ 'ide'=>"{$ope['ent']}.{$ide}", 'eti'=>$ope['ite'] ] );
        }
        else{
          $_ = _doc_opc::val( $dat, [ 'eti'=>$ope['eti'], 'ite'=>$ope['ite'] ] );
        }
      }// controladores
      else{

        $dat = is_string($dat) ? $dat : strval($dat); 
        $_ = !empty($ope) ? $cla_ide::ope( $tip_val, $dat, $ope['ite'] ) : "<p"._htm::atr($ope['ite']).">{$dat}</p>";
      }
      $ide='';
      if( !empty($ite) ){ 
        $agr = "";
        if( $tip == 'pos' ){
          $agr = " tam='2'";
          $tip = "number";
        }else{
          $tip = "text";
        }
        $ide="<input class='ide' type='{$tip}'{$agr} value='{$ide}' title='{$ide}'>";
      }
      else{ 
        $ide="<c class='sep'>[</c><n>{$ide}</n><c class='sep'>]</c>";
      }
      if( $tip == 'pos' ){
        $sep='='; 
      }else{ 
        $sep=( $tip == 'nom' ) ? '=>' : ':' ; 
      }  
      $sep = "<c class='sep'>{$sep}</c>"; 

      return "
      <li class='atr' data-ide='{$ide}'>
        {$ite}{$ide}{$sep}{$_}
      </li>";  
    }
  }
  // Ejecucion
  class _doc_eje {

    static string $IDE = "_doc_eje-";

    static string $EJE = "_doc_eje.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      return $_;
    }    
    
  }
  // Elemento
  class _doc_ele {

    static string $IDE = "_doc_ele-";

    static string $EJE = "_doc_ele.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      return $_;
    }    
    
  }