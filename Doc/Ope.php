<?php

class Doc_Ope {

  static string $IDE = "Doc_Ope-";
  static string $EJE = "Doc_Ope.";
  static string $DAT = "doc_ope";

  // Seccion Principal : main > ...article
  static function sec( string | array $dat, array $ele = [] ) : string {
    $_ = "";
    // contenido directo
    if( is_string($dat) ){ 
      $_ .= $dat;
    }
    // listado de articulos
    else{
      foreach( $dat as $ide => $art ){
        
        if( isset($art['htm'])){
          Ele::cla($art,"ide-$ide",'ini');
          $_ .= "
          <article".Ele::atr($art).">
            {$art['htm']}
          </article>";
        }
      }
    }
    return $_;
  }
  
  // Cabecera : botones de acceso ( url + pan + win )
  static function cab( $dat ) : string {
    $_ = "";      
    $_eje = self::$EJE;
    
    foreach( $dat as $ide => $art ){
      
      $tip = isset($art['tip']) ? $art['tip'] : 'nav';

      $eje_tog = "{$_eje}{$tip}('$ide');";

      $ele = [ 'eti'=>"a", 'onclick'=>$eje_tog, 'data-ide'=>$ide  ];

      if( is_string($art) ){
        $_ .= Doc_Val::ico($art,$ele);
      }
      elseif( is_array($art) ){

        if( isset($art[0]) ){
          $ele['title'] = isset($art[1]) ? $art[1] : '';
          $_ .= Doc_Val::ico($art[0],$ele);
        }
        elseif( isset($art['ico']) ){
          $ele['title'] = isset($art['nom']) ? $art['nom'] : '';
          $_ .= Doc_Val::ico($art['ico'],$ele);
        }
      }
      elseif( is_object($art) && isset($art->ico) ){
        $ele['title'] = isset($art->nom) ? $art->nom : '';
        $_ .= Doc_Val::ico($art->ico,$ele);
      }
    }
    return $_;
  }

  // Panel : nav|article[ide] > header + section
  static function pan( string $ide, array $ope = [] ) : string {
    foreach( ['nav','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
    $_eje = self::$EJE."pan";
    $_ = "";
    $cab_ico = "";
    if( !empty($ope['ico']) ) $cab_ico = Doc_Val::ico($ope['ico'],['class'=>"mar_hor-1"]);

    $cab_tit = "";
    if( !empty($ope['nom']) ) $cab_tit = "
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>
    ";
    // etiquetas
    $eti_nav = 'nav';
    if( isset($ope['nav']['eti']) ){
      $eti_nav = $ope['nav']['eti'];
      unset($ope['nav']['eti']);
    }
    $eti_sec = 'div';
    if( isset($ope['sec']['eti']) ){
      $eti_sec = $ope['sec']['eti'];
      unset($ope['sec']['eti']);
    }

    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = Ele::val_dec( $ope['htm'] );
    }

    // imprimo con identificador
    Ele::cla($ope['nav'],"ide-$ide",'ini');
    Ele::cla($ope['nav'],DIS_OCU);
    $_ = "
    <$eti_nav".Ele::atr($ope['nav']).">

      <header".Ele::atr($ope['cab']).">
      
        {$cab_ico} {$cab_tit} ".Doc_Val::ico('val-fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'onclick'=>"$_eje();" ])."

      </header>

      <$eti_sec".Ele::atr($ope['sec']).">

        {$ope['htm']}

      </$eti_sec>

    </$eti_nav>";
    
    return $_;
  }

  // Modal : #sis > article[ide] > header + section
  static function win( string $ide, array $ope = [] ) : string {
    foreach( ['art','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
    $_eje = self::$EJE."win";
    $_ = "";
    // icono de lado izquierdo
    $cab_ico = "";
    if( isset($ope['ico']) ){
      if( is_string($ope['ico']) ){
        $cab_ico = Doc_Val::ico($ope['ico'],['class'=>"mar_hor-1"]);
      }// con menú
      else{
        $_ .= "
        <div class='ini'>";
          $_.="
        </div>";
      }
    }
    // titulo al centro
    $cab_tit = "";
    if( isset($ope['nom']) ) $cab_tit = "
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? Doc_Val::let($ope['nom']) : "" )."</h2>
    ";
    // botones de flujo
    $cab_bot = "
    <div class='ope_bot'>
      ".Doc_Val::ico('val-fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'data-ope'=>"fin", 'onclick'=>"$_eje(this);" ])."
    </div>";
    // contenido 
    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = Ele::val_dec( $ope['htm'] );
    }      
    // imprimo con identificador
    Ele::cla($ope['art'],"ide-$ide",'ini');
    Ele::cla($ope['art'],DIS_OCU);
    $_ = "
    <article".Ele::atr($ope['art']).">

      <header".Ele::atr($ope['cab']).">      
        {$cab_ico} 
        {$cab_tit} 
        {$cab_bot}
      </header>

      <div".Ele::atr($ope['sec']).">
        {$ope['htm']}
      </div>
    </article>";
    return $_;
  }    

  /* Menu : click derecho */
  static function men( array $ope = [], array $ele = [] ) : string {
    $_ = "";
    return $_;        
  }

  /* Carteles : advertencia + confirmacion */
  static function tex( array $ope = [], array $ele = [] ) : string {

    foreach( ['sec','ico','tex'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    
    Ele::cla($ele['sec'],'ope_tex'.( !empty($ope['tip']) ? " -{$ope['tip']}" : "" ),'ini');

    $_ = "
    <div".Ele::atr($ele['sec']).">";

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
            Ele::cla($ele['ico'],"mar-1");
            $_ .= Doc_Val::ico("tex-{$ope['tip']}", $ele['ico']);
          }
          if( isset($ope['tit']) ){
            if( is_string($ope['tit']) ) $_.="<p class='tit'>".Doc_Val::let($ope['tit'])."</p>";
          }
          $_.="
        </header>";
      }

      // contenido: texto
      if( isset($ope['tex']) ){
        $ope_tex = [];
        if( is_array($ope['tex']) ){
          foreach( $ope['tex'] as $tex ){
            $ope_tex []= Doc_Val::let($tex);
          }
        }else{
          $ope_tex []= $ope['tex'];
        }
        $_ .= "<p".Ele::atr($ele['tex']).">".implode('<br>',$ope_tex)."</p>" ;
      }

      // elementos
      if( isset($ope['htm']) ){

        $_ .= Ele::val( ...Obj::pos_ite($ope['htm']) );
      }

      // botones: aceptar - cancelar
      if( isset($ope['opc']) ){
        $_ .= "
        <form class='ope_bot'>";
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

  /* Contenedores : nav(pes,tex,bar,bot) + * > ...[nav="ide"] */
  static function nav( string $tip, array $dat, array $ele = [], ...$opc ) : string {
    $_ = "";
    $_eje = self::$EJE."nav";
    foreach( ['lis','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // por botones de enlaces y contenido
    if( $tip == 'bot' ){

      Ele::cla($ele['lis'],"ope_nav $tip",'ini');

      $ele['lis']['eti'] = "nav";
      $ele['lis']['htm'] = "";
  
      foreach( $dat as $bot ){
  
        if( isset($bot[0]) && isset($bot[1]) ){
          
          $bot_ele = isset($bot[2]) ? $bot[2] : [];
  
          $bot_ele['htm'] = $bot[1];
  
          $bot_ele['eti'] = 'button';
  
          if( preg_match("/^url:/",$bot[0]) ){
  
            $bot_ele['htm'] = Ele::eti([ 'eti'=>"a", 'href'=>SYS_NAV.substr($bot[0],4), 'class'=>"tex", 'htm'=>$bot[1] ]);
          }
          else{
            
            Ele::eje($bot_ele,'cli',"{$_eje}(this);".$bot[0]);
          }
  
          // cargo boton al contenedor
          $ele['lis']['htm'] .= Ele::eti($bot_ele);
        }
      }
      
      $_ = Ele::val($ele['lis']);

    }
    // pes, bar, ico
    else{

      $opc_ico = in_array('ico',$opc);
      $val_sel = isset($ele['sel']) ? $ele['sel'] : FALSE;
      
      // navegador 
      Ele::cla($ele['lis'],"ope_nav $tip",'ini');
  
      $_ .= "
      <nav".Ele::atr($ele['lis']).">";    
      foreach( $dat as $ide => $val ){
  
        if( is_object($val) ) $val = Obj::nom($val);
  
        if( isset($val['ide']) ) $ide = $val['ide'];
  
        $ele_nav = isset($val['nav']) ? $val['nav'] : [];
  
        $ele_nav['eti'] = 'a';
        Ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');
  
        if( $val_sel && $val_sel == $ide ) Ele::cla($ele_nav,FON_SEL);
  
        if( $opc_ico && isset($val['ico']) ){
          $ele_nav['title'] = $val['nom'];
          Ele::cla($ele_nav,"mar-0 pad-1 tam-4 bor_cir-1",'ini');
          $_ .= Doc_Val::ico($val['ico'],$ele_nav);
        }
        else{
          $ele_nav['htm'] = $val['nom'];
          $_ .= Ele::val($ele_nav);
        }        
      }$_.="
      </nav>";
      
      // contenido por secciones
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
  
      if( $tip != 'pes' && !$val_sel ) Ele::cla($ele['sec'],DIS_OCU);
  
      $_ .= "
      <$eti_sec".Ele::atr($ele['sec']).">";
  
        foreach( $dat as $ide => $val ){
  
          $ele_ite = $ele['ite'];
          
          Ele::cla($ele_ite,"ide-$ide",'ini');
          
          if( !$val_sel || $val_sel != $ide ) Ele::cla($ele_ite,DIS_OCU);
          
          $_ .= "
          <$eti_ite".Ele::atr($ele_ite).">
            ".( isset($val['htm']) ? ( is_array($val['htm']) ? Ele::val_dec($val['htm']) : $val['htm'] ) : '' )."
          </$eti_ite>";
  
        }$_.="
      </$eti_sec>";
  
      // Contenedor de la barra con secciones
      if( $tip == 'bar' ){
        $_ = "
        <div>
          {$_}
        </div>";
      }
    }

    return $_;
  }

  /* Bloque de Contenido : visible/oculto */
  static function val( string | array $dat = NULL, array $ele = [] ) : string {

    $_eje = self::$EJE."val";    
    foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
    
    // contenido textual
    if( is_string($dat) ) $dat = [ 'eti'=>"p", 'class'=>"tex-enf tex-cur", 'htm'=> Doc_Val::let($dat) ];

    // contenedor : icono + ...elementos          
    Ele::eje( $dat,'cli',"$_eje(this);",'ini');

    return "
    <div".Ele::atr( Ele::cla( $ele['val'],"ope_val",'ini') ).">
    
      ".Doc_Ope::val_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

      ".( isset($ele['htm_ini']) ? Ele::val($ele['htm_ini']) : '' )."
      
      ".Ele::val( $dat )."

      ".( isset($ele['htm_fin']) ? Ele::val($ele['htm_fin']) : '' )."

    </div>";
  }// - icono de toggle
  static function val_ico( array $ele = [] ) : string {

    $_eje = self::$EJE."val";

    return Doc_Val::ico('val_tog', Ele::eje($ele,'cli',"$_eje(this);",'ini'));

  }  
  
  /* Listado : ul.ope_lis */
  static function lis( string $tip, mixed $dat, array $ope = [] ) : string {
    $_ = "";
    $_eje = self::$EJE."lis";

    switch( $tip ){
    // valores de listas y sublistas
    case 'dep':
      foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }

      // elementos        
      Ele::cla($ope['lis'],"ope_lis $tip",'ini');
      Ele::cla($ope['dep'],"ope_lis",'ini');
      // operadores
      if( isset($ope['opc']) ){
        $_ .= Doc_Ope::lis_ope('dep', Obj::pos_ite($ope['opc']), $ope);  
      }
      else{
        $ope['opc'] = [];
      }
      // listado
      $_ .= "
      <ul".Ele::atr($ope['lis']).">";
      $ide = 0;
      foreach( $dat as $val ){
        $ide++;
        $_ .= Doc_Ope::lis_ite_dep( 1, $ide, $val, $ope );
      }$_ .= "
      </ul>";      
      break;
    // punteos en vertical
    case 'pos':
      foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
      
      // operador
      if( isset($ope['opc']) ) 
        $_ .= Doc_Ope::lis_ope('dep', $ope['opc'] = Obj::pos_ite($ope['opc']), $ope);
      
      // listado
      $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul';
  
      // por saltos de línea
      if( is_string($dat) ){
        if( empty($ope['lis']['class']) ) $ope['lis']['class'] = "tex_ali-izq mar-0 mar_ver-2";
        Ele::cla($ope['lis'],"ope_lis $tip tex",'ini');
        $_ .= "
        <$eti".Ele::atr($ope['lis']).">";
        foreach( explode("\n",$dat) as $val ){ $_ .= "
          <li".Ele::atr($ope['ite']).">".Doc_Val::let($val)."</li>";
        }$_ .= "
        </$eti>";
      }
      // por punteo o numerado
      elseif( Obj::pos_val($dat) ){
        Ele::cla($ope['lis'],"ope_lis $tip pun",'ini');
        $_ .= "
        <{$eti}".Ele::atr($ope['lis']).">";
          foreach( $dat as $pos => $val ){
            $_ .= Doc_Ope::lis_ite_pos( 1, $pos, $val, $ope, $eti );
          }$_.="
        </{$eti}>";
      }
      // por términos
      else{
        $eti = "dl";
        Ele::cla($ope['lis'],"ope_lis $tip let",'ini');
        // agrego toggle del item
        Ele::eje($ope['ite'],'cli',"{$_eje}('{$tip}',this);",'ini');
        $_ .= "
        <$eti".Ele::atr($ope['lis']).">";
          foreach( $dat as $nom => $val ){ 
            $ope_ite = $ope['ite'];
            if( empty($ope_ite['id']) ) $ope_ite['id'] = "doc_ope_lis ".str_replace(' ','_',mb_strtolower($nom));
            $_ .= "
            <dt".Ele::atr($ope_ite).">
              ".Doc_Val::let($nom)."
            </dt>";
            foreach( Obj::pos_ite($val) as $ite ){ $_ .= "
              <dd".Ele::atr($ope['val']).">
                ".Doc_Val::let($ite)."
              </dd>";
            }
          }$_.="
        </$eti>";
      }
      
      break;
    // por desplazamiento horizontal
    case 'bar':
      $pos = 0;
      $pos_ver = ( !empty($ope['pos_ver']) ? $ope['pos_ver'] : 1 );
      if( !isset($ope['lis']) ) $ope['lis']=[];
      
      $_.="
      <ul".Ele::atr(Ele::cla($ope['lis'],"ope_lis $tip",'ini')).">";
        if( !isset($ope['ite']) ) $ope['ite'] = [];
        foreach( $dat as $ite ){ 
          $pos++;
          $ele_ite = $ope['ite'];
          $ele_ite['data-pos'] = $pos;
          Ele::cla($ele_ite,"pos ide-$pos",'ini');
          if( $pos != $pos_ver ) Ele::cla($ele_ite,DIS_OCU);
          $_.="
          <li".Ele::atr($ele_ite).">";
            // contenido html
            if( is_string($ite) ){
              $_ .= $ite;
            }// elementos html
            elseif( is_array($ite) ){
              $_ .= Ele::val_dec($ite);
            }// modelo : titulo + detalle + imagen
            elseif( is_object($ite) ){
  
            } $_.= "
          </li>";
        }$_.="
      </ul>";
      
      // operadores
      $min = $pos == 0 ? 0 : 1;
      $max = $pos;
      $_ .= "
      <form class='ope_bot anc-100 jus-cen mar_ver-2'>
  
        ".Doc_Val::num($min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('$tip',this,'val');" ])."
                
        ".Doc_Val::ico('lis_pre',['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('$tip',this,'val');"])."
  
        ".Doc_Var::num('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('$tip',this,'val');" ])."
  
        ".Doc_Val::ico('lis_pos',['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('$tip',this,'val');"])."            
  
        ".Doc_Val::num($max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('$tip',this,'val');" ])."          
  
      </form>";      
      break;
    }

    return $_;
  }// - Item por sublistas con contenido
  static function lis_ite_dep( int $niv, int | string $ide, string | array $val, array $ope ) : string {  

    $ope_ite = $ope['ite'];      

    Ele::cla($ope_ite,"pos ide-$ide",'ini');
    // con dependencia : evalúo rotacion de icono
    if( $val_lis = is_array($val) ){
      $ope_ico = $ope['ico'];
      $ele_dep = isset($ope["lis-$niv"]) ? Ele::val_jun($ope['dep'],$ope["lis-$niv"]) : $ope['dep'];
      if( isset($ele_dep['class']) && preg_match("/".DIS_OCU."/",$ele_dep['class']) ) Ele::cla($ope_ico,"ocu");
      if( !isset($val['ite_ope']) ) $val['ite_ope'] = [];
      $val['ite_ope']['ico'] = $ope_ico;
    }
    // sin dependencias : separo item por icono vacío
    else{
      if( !in_array('not-sep',$ope['opc']) ) Ele::cla($ope_ite,"sep");
    }
    $_ = "
    <li".Ele::atr( isset($ope["ite-$ide"]) ? Ele::val_jun($ope["ite-$ide"],$ope_ite) : $ope_ite  ).">

      ".( $val_lis ? Doc_Ope::val( isset($val['ite']) ? $val['ite'] : $ide, $val['ite_ope'] ) : $val );
      
      if( $val_lis ){
        // sublista
        if( isset($val['lis']) ){

          $ope['dep']['data-niv'] = $niv;
          $_ .= "
          <ul".Ele::atr($ele_dep).">";
            // por elementos
            if( is_array($val['lis'])  ){
              // operador de la dependencia : 1° item de la lista
              if( isset($ope['opc'])){
                $opc = [];
                foreach( $val['lis'] as $i => $v ){ 
                  $lis_dep = is_array($v); 
                  break; 
                }
                if( in_array('tog-dep',$ope['opc']) && $lis_dep ) $opc []= "tog";
                if( !empty($opc) ) $_ .= "
                  <li>".Doc_Ope::lis_ope('dep',$opc,$ope)."</li>";
              }
              // recorro sublista
              foreach( $val['lis'] as $i => $v ){
                
                $_ .= Doc_Ope::lis_ite_dep( $niv+1, $i, $v, $ope );
              }
            }
            // listado textual
            elseif( is_string($val['lis']) ){

              $_ .= $val['lis'];
              
            }$_ .= "
          </ul>";
        }
        // contenido html directo ( asegurar elemento único )
        elseif( isset($val['htm']) ){

          $_ .= is_string($val['htm']) ? $val['htm'] : Ele::val_dec($val['htm']);
        }
      }
      $_ .= "          
    </li>";

    return $_;
  }// - Item por punteos
  static function lis_ite_pos( int $niv, int | string $ide, mixed $val, array $ope, string $eti = "ul" ) : string {
    $_ = "
    <li".Ele::atr($ope['ite']).">";
      if( is_string($val) ){ 
        $_ .= $val;
      }// sublistas
      else{
        $niv++;
        $_.="
        <$eti data-niv='$niv'>";
        if( isset($ope['opc']) ){
          $opc = [];
          if( in_array('tog-dep',$ope['opc']) ) $opc []= "tog";
          $_ .= "<li>".Doc_Ope::lis_ope('dep',$opc,$ope)."</li>";
        }
        foreach( $val as $ide => $val ){
          $_ .= Doc_Ope::lis_ite_pos( $niv, $ide, $val, $ope, $eti );
        }$_.="
        </$eti>";
      }
      $_ .= "
    </li>";
    return $_;
  }// - Operadores
  static function lis_ope( string $tip, array $opc = [], array $ele = [] ) : string {
    $_ = "";
    
    if( in_array($tip,['dep','pos','bar']) ){
      $_eje = self::$EJE."lis_$tip";
      $_ide = self::$IDE."lis_$tip";
    }else{
      $_eje = $tip;
      $_ide = $tip;
    }      

    $tod = empty($opc);
    
    // - expandir-contraer items
    if( $tod || in_array('tog',$opc) ){
      
      $_ .= Doc_Ope::lis_tog([
        'eje'=>"{$_eje}('tog',this," 
      ]);
    }
    // - filtrar items
    if( $tod || in_array('ver',$opc) ){ 
      $ide = "{$_ide}-".Doc_Ope::var_ide($_ide);
      $_ .= Doc_Ope::var('_','ver',[ 
        'ide'=>$ide,
        'nom'=> "Filtrar",
        'ite'=> [ 'class'=>'tam-cre' ],
        'htm'=> Doc_Ope::lis_ver([ 
          'ide'=>$ide,
          'cue'=>in_array('cue',$opc) ? 0 : NULL, 
          'eje'=>"{$_eje}('ver',this);" 
        ])
      ]);
    }

    if( !empty($_) ){ 

      if( !isset($ele['ope']) ) $ele['ope'] = [];

      Ele::cla($ele['ope'],"-ite"); 

      $ele['ope']['eti'] = "form";
      $ele['ope']['htm'] = $_;

      $_ = Ele::val($ele['ope']);
    }      
    return $_;
    
  }// - expandir / contraer
  static function lis_tog( array $ele = [], ...$opc ) : string {
    
    $_eje = self::$EJE."lis_tog";      

    if( !isset($ele['ope']) ) $ele['ope'] = [];

    Ele::cla($ele['ope'], "ope_bot", 'ini');

    $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";

    return "
    <fieldset".Ele::atr($ele['ope']).">
      ".Doc_Val::ico('val_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
      ".Doc_Val::ico('val_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
    </fieldset>";

  }// - Filtros : operador + valor textual + ( totales )
  static function lis_ver( string | array $dat = [], array $ele = [], ...$opc ) : string {
    $_ = "";
    
    // opciones de filtro por texto
    if( isset($dat['ope']) ){
      
      if( empty($dat['ope']) ) $dat['ope'] = "**";

      $_ .= Dat::ope_opc(['ver','tex'],[
        'ite'=>[ 
          'dat'=>"()($)dat()" 
        ],
        'eti'=>[ 
          'name'    =>"ope", 
          'title'   =>"Seleccionar un operador de comparación...", 
          'val'     =>$dat['ope'], 
          'class'   =>isset($ele['ope']['class']) ? $ele['ope']['class'] : "mar_hor-1", 
          'onchange'=>$dat['eje']
        ]
      ]);
    }

    // ingreso de valor a filtrar
    $_ .= Doc_Var::tex('ora', isset($dat['val']) ? $dat['val'] : '', [ 
      'id'    =>isset($dat['ide']) ? $dat['ide'] : NULL, 
      'name'  =>"val",
      'title' =>"Introducir un valor de búsqueda...",
      'oninput'=>!empty($dat['eje']) ? $dat['eje'] : NULL,
      'class' =>isset($ele['tex']['class']) ? $ele['tex']['class'] : NULL,
      'style' =>isset($ele['tex']['style']) ? $ele['tex']['class'] : NULL
    ]);

    // agrego totales
    if( isset($dat['cue']) ){ $_ .= "
      <p class='mar_izq-1' title='Items totales'>
        <c>(</c><n name='tot'>".( is_array($dat['cue']) ? count($dat['cue']) : $dat['cue'] )."</n><c>)</c>
      </p>";
    }
    
    return $_;
  }

  /* Variable en Formulario */
  static array $Var = [
    '$'=>[
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
    ],    
    'ide'=>[]
  ];// div.ope_var > label + ( select,input,textarea,button )[name]
  static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
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

    // por atributo de la base
    if( $tip == 'dat.atr' ){

      if( !empty($_atr = Dat::est($esq,$est,'atr',$atr)) ) $_var = [ 
        'nom'=>$_atr->nom, 
        'ope'=>$_atr->var 
      ];
    }
    // carga operadores: esquema - dato - valor
    elseif( $tip != '_' ){ 

      $_var = Dat::var($tip,$esq,$est,$atr);
    }

    // combino operadores
    if( !empty($_var) ){

      if( !empty($_var['ope']) ){
        $ele['ope'] = Ele::val_jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
        unset($_var['ope']);
      }
      $ele = Obj::val_jun($ele,$_var);
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
    $agr = Ele::htm($ele);

    // etiqueta
    $eti_htm='';
    if( !isset($ele['eti']) ) $ele['eti'] = [];
    // opcion desactivada
    if( !in_array('eti',$opc) ){
      // icono o texto
      if( !empty($ele['ico']) ){
        $eti_htm = Doc_Val::ico($ele['ico']);
      }
      elseif( !empty($ele['nom']) ){    
        $eti_htm = Doc_Val::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
      }
      // agrego for/id e imprimo
      if( !empty($eti_htm) ){
        $ele['eti']['for'] =  "";

        if( isset($ele['id']) ){
          $ele['eti']['for'] = $ele['id'];
        }
        elseif( isset($ele['ope']['id']) ){
          $ele['eti']['for'] = $ele['ope']['id'];
        }

        $eti_htm = "<label".Ele::atr($ele['eti']).">{$eti_htm}</label>";
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
    }
    else{

      // actualizo valor variable
      if( isset($ele['val']) ){
        $ele['ope']['val'] = $ele['val'];
      }

      // aseguro identificador de variable
      if( empty($ele['ope']['name']) && isset($ele['ide']) ){
        $ele['ope']['name'] = $ele['ide'];
      }

      $val_htm = Ele::val($ele['ope']);
    }

    // contenedor
    if( !isset($ele['ite']) ) $ele['ite']=[];      
    if( !isset($ele['ite']['title']) ) $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';

    return "
    <div".Ele::atr(Ele::cla($ele['ite'],"ope_var",'ini')).">
      ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
      {$eti_ini}
      {$val_htm}
      {$eti_fin}
      ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
    </div>
    ";   
  }// - id secuencial
  static function var_ide( string $val ) : string {

    if( !isset(self::$Var['ide'][$val]) ) self::$Var['ide'][$val] = 0;

    self::$Var['ide'][$val]++;

    return self::$Var['ide'][$val];
  }
}