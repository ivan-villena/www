<?php

class Doc_Val {

  static string $IDE = "Doc_Val-";
  static string $EJE = "Doc_Val.";
  static string $DAT = "doc_val";

  // icono : .val_ico.$ide
  static function ico( string $ide, array $ele=[] ) : string {
    
    $_ = "<span class='val_ico'></span>";    
    
    $ico = Dat::_("var.tex_ico");
    
    if( isset($ico[$ide]) ){

      // identificador del boton
      Ele::cla($ele,"val_ico ide-$ide",'ini');

      $ele['htm'] = $ico[$ide]->val;
      
      $_ = Ele::val($ele);
    }
    return $_;
  }  

  // fondo-imagen : .val_ima.$ide
  static function ima( ...$dat ) : string {
    $_ = "";
    // por aplicacion
    if( isset($dat[2]) ){

      $_ = Doc_Dat::val('ima', "{$dat[0]}.{$dat[1]}", $dat[2], isset($dat[3]) ? $dat[3] : [] );
    }
    // por directorio
    else{
      $ele = isset($dat[1]) ? $dat[1] : [];
      $dat = $dat[0];
      
      // por estilos : bkg
      if( is_array($dat) ){

        $ele = Ele::val_jun( $dat, $ele );          
      }
      // por directorio : localhost/_img/esquema/image
      elseif( is_string($dat)){
        $ima = explode('.',$dat);
        $dat = $ima[0];
        $tip = isset($ima[1]) ? $ima[1] : 'png';
        $dir = SYS_NAV."_img/{$dat}";
        Ele::css( $ele, Ele::css_fon($dir,['tip'=>$tip]) );
      }
      
      // contenido
      if( !empty($ele['htm']) ) Ele::cla($ele,'dis-fle dir-ver jus-cen ali-cen');

      // ide de imagen
      Ele::cla($ele,"val_ima",'ini');

      // Etiqueta
      $_ = Ele::val($ele);
    }
    return $_;
  }
  
  // Letra : ( n, c )
  static function let( string $dat, array $ele=[] ) : string {
    $_ = [];
    $pal = [];
    $tex_let = Dat::_("var.tex_let");
    
    // saltos de linea
    foreach( explode('\n',$dat) as $tex_pal ){

      $pal = [];

      // espacios
      foreach( explode(' ',$tex_pal) as $pal_val ){

        // numero completo
        if( is_numeric($pal_val) ){

          $pal []= "<n>{$pal_val}</n>";
        }
        // caracteres
        else{
          $let = [];
          foreach( Tex::let($pal_val) as $car ){

            if( is_numeric($car) ){
              $let []= "<n>{$car}</n>";
            }
            elseif( isset($tex_let[$car]) ){
              $let []= "<c>{$car}</c>";        
            }
            else{
              $let []= $car;
            }
          }
          $pal []= implode('',$let);
        }
      }
      $_ []= implode(' ',$pal);
    }

    return implode('<br>',$_);

  }
  
  // numero
  static function num( mixed $dat, array $ele = [] ) : string {
    $_ = "";

    $num = isset($dat) ? strval($dat) : ""; 

    $ele['eti'] = "n";

    if( isset($ele['val']) ) unset($ele['val']);

    $ele['htm'] = preg_match("/\./",$num) ? Num::dec($num) : Num::int($num);

    $_ = Ele::eti($ele);

    return $_;
  }

  // parrafo
  static function tex( mixed $dat, array $ele = [] ) : string {
    $_ = "";

    $tex = [];
      
    foreach( explode("\n",$dat) as $pal ){
      $tex []= Doc_Val::let($pal);
    }

    $ele['htm'] = implode("<br>",$tex);

    if( empty($ele['eti']) ){
      $ele['eti'] = "p";      
    }

    $_ = Ele::eti($ele);

    return $_;
  }

  // fecha
  static function fec( mixed $dat, array $ele = [] ) : string {

    $ele['eti'] = "time";

    $ele['value'] = Fec::val_var($dat);

    $ele['htm'] = Doc_Val::let($dat);

    return Ele::eti($ele);
  }

  // enlace
  static function url( string $htm = "", string $uri = "", array $opc = [ 'bla' ] ){
    
    $ele = [ 'eti'=>"a", 'href'=>$uri, 'htm'=>$htm ];

    if( in_array('bla',$opc) ){ 

      $ele['target'] = '_blank';
      $ele['rel']    = 'noreferer';
    }

    return Ele::eti($ele);
  }

  // Selector de Opciones
  static function opc( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";

    $_ite = function ( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
      $_ = "";
      
      $val_ite = !empty($val);
      $val_arr = $val_ite && is_array($val);
      $opc_ide = in_array('ide',$opc);
  
      $obj_tip = FALSE;
      foreach( $dat as $i => $v){ 
        $obj_tip = Obj::tip($v);
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
          $atr = Ele::atr($e);
        }
        // elemento
        elseif( $obj_tip == 'nom' ){
          $e = Ele::val_jun($e,$v);
          if( !isset($e['value']) ) $e['value'] = $i;
          $htm = isset($e['htm']) ? $e['htm'] : $i;
          $atr = Ele::atr($e);
        }
        // objeto ( ide + nom + des + tit )
        elseif( $obj_tip == 'atr' ){
          $_ide = isset($v->ide) ? $v->ide : FALSE ;
          $_htm = isset($v->nom) ? $v->nom : FALSE ;
          // valor
          if( isset($e['value']) ){ 
            $e['value'] = Obj::val($v,$e['value']); 
          }else{ 
            $e['value'] = $i;
            if( $_ide ){ $e['value'] = $_ide; }elseif( $_htm ){ $e['value'] = $_htm; }
          }
          // titulo con descripcion
          if( !isset($e['title']) ){ 
            
            if( isset($v->des) ){ 

              $e['title'] = $v->des; 
            }
            elseif( isset($v->tit) ){ 
              
              $e['title'] = $v->tit; 
            }
          }
          // contenido
          if( isset($e['htm']) ){
            $htm = Obj::val($v,$e['htm']);
          }else{
            if( !!$opc_ide && $_ide && $_htm ){
              $htm = "{$_ide}: {$_htm}";
            }elseif( $_htm ){
              $htm = $_htm;
            }else{
              $htm = $_ide; 
            }
          }
          $atr = Ele::atr($e,$v);            
        }// por posiciones
        else{
          $htm = "( \"".implode( '", "', $v )."\" )" ;
          $atr = Ele::atr($e);
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
    };

    // etiqueta del contenedor
    $ope_eti = !empty($ope['eti']) ? Obj::val_dec($ope['eti'],[],'nom') : [];
    $eti = isset($ope_eti['eti']) ? $ope_eti['eti'] : 'select';
    
    // opciones
    if( isset($ope_eti['data-opc']) ){
      $opc = array_merge($opc,is_array($ope_eti['data-opc']) ? $ope_eti['data-opc'] : explode(',',$ope_eti['data-opc']) );
    }
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
    <{$eti}".Ele::atr($ope_eti).">";

      if( in_array('nad',$opc) ){ $_ .= "
        <option default value=''>{-_-}</option>"; 
      }
      // items
      $ope_ite = isset($ope['ite']) ? $ope['ite'] : [];
      if( !empty($ope['gru']) ){

        foreach( $ope['gru'] as $ide => $nom ){ 

          if( isset($dat[$ide]) ){ $_.="
            <optgroup data-ide='{$ide}' label='{$nom}'>
              ".$_ite( $dat[$ide], $val, $ope_ite, ...$opc )."                
            </optgroup>";
          }
        }
      }
      else{                        
        $_ .= $_ite( $dat, $val, $ope_ite, ...$opc );
      }
      $_ .= "
    </{$eti}>";

    return $_;
  }

  /* Listado : ul.lis */
  static function lis( string $tip, mixed $dat, array $var = [] ) : string {
    $_ = "";
    $_eje = self::$EJE."lis";

    switch( $tip ){
    // valores de listas y sublistas
    case 'dep':
      foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($var[$e]) ){ $var[$e]=[]; } }

      // elementos        
      Ele::cla($var['lis'],"lis $tip",'ini');
      Ele::cla($var['dep'],"lis",'ini');
      
      // operadores
      if( isset($var['opc']) ){

        $_ .= Doc_Val::lis_ope('dep', Obj::pos_ite($var['opc']), $var);  
      }
      else{
        $var['opc'] = [];
      }
      // listado
      $_ .= "
      <ul".Ele::atr($var['lis']).">";
      $ide = 0;
      foreach( $dat as $val ){
        $ide++;
        $_ .= Doc_Val::lis_ite($tip, 1, $ide, $val, $var );
      }$_ .= "
      </ul>";      
      break;
    // punteos en vertical
    case 'pos':
      foreach( ['lis','ite','val'] as $i ){ if( !isset($var[$i]) ) $var[$i]=[]; }
      
      // operador
      if( isset($var['opc']) ) 
        $_ .= Doc_Val::lis_ope('dep', $var['opc'] = Obj::pos_ite($var['opc']), $var);
      
      // listado
      $eti = isset($var['lis']['eti']) ? $var['lis']['eti'] : 'ul';
  
      // por saltos de línea
      if( is_string($dat) ){
        if( empty($var['lis']['class']) ) $var['lis']['class'] = "tex_ali-izq mar-0 mar_ver-2";
        Ele::cla($var['lis'],"lis $tip tex",'ini');
        $_ .= "
        <$eti".Ele::atr($var['lis']).">";
        foreach( explode("\n",$dat) as $val ){ $_ .= "
          <li".Ele::atr($var['ite']).">".Doc_Val::let($val)."</li>";
        }$_ .= "
        </$eti>";
      }
      // por punteo o numerado
      elseif( Obj::pos_val($dat) ){
        Ele::cla($var['lis'],"ope_lis $tip pun",'ini');
        $_ .= "
        <{$eti}".Ele::atr($var['lis']).">";
          foreach( $dat as $pos => $val ){
            $_ .= Doc_Val::lis_ite($tip, 1, $pos, $val, $var, $eti );
          }$_.="
        </{$eti}>";
      }
      // por términos
      else{
        $eti = "dl";
        Ele::cla($var['lis'],"lis $tip let",'ini');
        // agrego toggle del item
        Ele::eje($var['ite'],'cli',"{$_eje}('{$tip}',this);",'ini');
        $_ .= "
        <$eti".Ele::atr($var['lis']).">";
          foreach( $dat as $nom => $val ){ 
            $var_ite = $var['ite'];
            if( empty($var_ite['id']) ) $var_ite['id'] = "doc_ope_lis ".str_replace(' ','_',mb_strtolower($nom));
            $_ .= "
            <dt".Ele::atr($var_ite).">
              ".Doc_Val::let($nom)."
            </dt>";
            foreach( Obj::pos_ite($val) as $ite ){ $_ .= "
              <dd".Ele::atr($var['val']).">
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
      $pos_ver = ( !empty($var['pos_ver']) ? $var['pos_ver'] : 1 );
      if( !isset($var['lis']) ) $var['lis']=[];
      
      $_.="
      <ul".Ele::atr(Ele::cla($var['lis'],"lis $tip",'ini')).">";
        if( !isset($var['ite']) ) $var['ite'] = [];
        foreach( $dat as $ite ){ 
          $pos++;
          $var_ite = $var['ite'];
          $var_ite['data-pos'] = $pos;
          Ele::cla($var_ite,"pos ide-$pos",'ini');
          if( $pos != $pos_ver ) Ele::cla($var_ite,"dis-ocu");
          $_.="
          <li".Ele::atr($var_ite).">";
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

        ".Ele::val([
          'eti'=>"button", 'name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"let-num", 
          'onclick'=>"$_eje('$tip',this,'val');",
          'htm'=>$min
        ])."

        ".Ele::val([
          'eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...", 
          'onclick'=>"$_eje('$tip',this,'val');",
          'htm'=>Doc_Val::ico('ope_lis-pre')
        ])."
  
        ".Doc_Var::num('int',$pos_ver,[ 
          'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 
          'oninput'=>"$_eje('$tip',this,'val');" 
        ])."

        ".Ele::val([
          'eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 
          'onclick'=>"$_eje('$tip',this,'val');",
          'htm'=>Doc_Val::ico('ope_lis-pos')
        ])."
        
        ".Ele::val([
          'eti'=>"button", 'name'=>"fin", 'title'=>"Ir al último...", 'class'=>"let-num", 
          'onclick'=>"$_eje('$tip',this,'val');",
          'htm'=>$max
        ])."        
  
      </form>";      
      break;
    }

    return $_;
  }// - items
  static function lis_ite( string $tip, int $niv, int | string $ide, mixed $val, array $var, string $eti = "ul" ) : string {
    $_ = "";
    // - Item por sublistas con contenido
    if( $tip == 'dep' ){

      $var_ite = $var['ite'];      

      Ele::cla($var_ite,"pos ide-$ide",'ini');
      
      // con dependencia : evalúo rotacion de icono
      if( $val_lis = is_array($val) ){
        
        $var_ico = $var['ico'];
        
        $ele_dep = isset($var["lis-$niv"]) ? Ele::val_jun($var['dep'],$var["lis-$niv"]) : $var['dep'];
        
        if( isset($ele_dep['class']) && preg_match("/dis-ocu"."/",$ele_dep['class']) ) Ele::cla($var_ico,"ocu");
        
        if( !isset($val['ite_ope']) ) $val['ite_ope'] = [];
        
        $val['ite_ope']['ico'] = $var_ico;
      }
      // sin dependencias : separo item por icono vacío
      else{
        
        if( !in_array('not-sep',$var['opc']) ) Ele::cla($var_ite,"sep");
      }

      $_ = "
      <li".Ele::atr( isset($var["ite-$ide"]) ? Ele::val_jun($var["ite-$ide"],$var_ite) : $var_ite  ).">
  
        ".( $val_lis ? Doc_Ope::val( isset($val['ite']) ? $val['ite'] : $ide, $val['ite_ope'] ) : $val );
        
        if( $val_lis ){
          // sublista
          if( isset($val['lis']) ){
  
            $var['dep']['data-niv'] = $niv;
  
            $_ .= "
            <ul".Ele::atr($ele_dep).">";
              // por elementos
              if( is_array($val['lis'])  ){
                // operador de la dependencia : 1° item de la lista
                if( isset($var['opc'])){
                  $opc = [];
                  foreach( $val['lis'] as $i => $v ){ 
                    $lis_dep = is_array($v); 
                    break; 
                  }
                  // agrego toogle
                  if( in_array('tog-dep',$var['opc']) && $lis_dep ){ 
                    $opc []= "tog";
                  }
                  // agrego filtro
                  if( in_array('tog-ver',$var['opc']) && $lis_dep ){ 
                    $opc []= "ver";
                  }
                  $var['ope'] = isset( $var['ope-dep'] ) ? $var['ope-dep'] : [];
                  if( !empty($opc) ) $_ .= "
                    <li>".Doc_Val::lis_ope('dep',$opc,$var)."</li>";
                }
                // recorro sublista
                foreach( $val['lis'] as $i => $v ){
                  
                  $_ .= Doc_Val::lis_ite($tip, $niv+1, $i, $v, $var );
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
    }
    // - Item por punteos
    elseif( $tip == 'pos' ){
      $_ = "
      <li".Ele::atr($var['ite']).">";

        if( is_string($val) ){ 

          $_ .= $val;
        }// sublistas
        else{
          $niv++;
          $_ .= "
          <$eti data-niv='$niv'>";
          if( isset($var['opc']) ){
            $opc = [];
            if( in_array('tog-dep',$var['opc']) ){ 
              $opc []= "tog";
            }
            if( in_array('tog-ver',$var['opc']) ){ 
              $opc []= "ver";
            }
            if( !empty($opc) ){
              $_ .= "<li>".Doc_Val::lis_ope('dep',$opc,$var)."</li>";
            }
          }
          foreach( $val as $ide => $val ){
  
            $_ .= Doc_Val::lis_ite($tip, $niv, $ide, $val, $var, $eti);
          }
          $_.="
          </$eti>";
        }
        $_ .= "
      </li>";
    }

    return $_;

  }// - Operadores
  static function lis_ope( string $tip, array $opc = [], array $var = [] ) : string {
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
      
      $_ .= Doc_Val::lis_tog([
        'eje'=>"{$_eje}('tog',this," 
      ]);
    }
    // - filtrar items
    if( $tod || in_array('ver',$opc) ){ 
      $ide = "{$_ide}-".Doc::ide($_ide);
      $_ .= Doc_Ope::var('_','ver',[ 
        'ide'=>$ide,
        'nom'=> "Filtrar",
        'ite'=> [ 'class'=>'tam-cre' ],
        'htm'=> Doc_Val::lis_ver([ 
          'ide'=>$ide,
          'cue'=>in_array('cue',$opc) ? 0 : NULL, 
          'eje'=>"{$_eje}('ver',this);" 
        ])
      ]);
    }

    if( !empty($_) ){ 

      if( !isset($var['ope']) ) $var['ope'] = [];

      Ele::cla($var['ope'],"-ite"); 

      $var['ope']['eti'] = "form";
      $var['ope']['htm'] = $_;

      $_ = Ele::val($var['ope']);
    }      
    return $_;
    
  }// - toggles: expandir / contraer
  static function lis_tog( array $var = [] ) : string {
    
    $_eje = self::$EJE."lis_tog";      

    if( !isset($var['ope']) ) $var['ope'] = [];

    Ele::cla($var['ope'], "ope_bot", 'ini');

    $_eje_val = isset($var['eje']) ? $var['eje'] : "$_eje(this,";

    return "
    <fieldset".Ele::atr($var['ope']).">
      ".Doc_Val::ico('ope_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
      ".Doc_Val::ico('ope_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
    </fieldset>";

  }// - Filtros : ( operador ) + valor textual + ( totales )
  static function lis_ver( string | array $var = [], array $ele = [] ) : string {
    $_ = "";
    
    // opciones de filtro por texto
    if( isset($var['ope']) ){
      
      if( empty($var['ope']) ) $var['ope'] = "**";

      $_ .= Dat::ope_opc(['ver','tex'],[
        'ite'=>[ 
          'dat'=>"()($)dat()" 
        ],
        'eti'=>[ 
          'name'    =>"ope", 
          'title'   =>"Seleccionar un operador de comparación...", 
          'val'     =>$var['ope'], 
          'class'   =>isset($ele['ope']['class']) ? $ele['ope']['class'] : "mar_hor-1", 
          'onchange'=>$var['eje']
        ]
      ]);
    }

    // ingreso de valor a filtrar
    $_ .= Doc_Var::tex('ora', isset($var['val']) ? $var['val'] : '', [ 
      'id'    =>isset($var['ide']) ? $var['ide'] : NULL, 
      'name'  =>"val",
      'title' =>"Introducir un valor de búsqueda...",
      'oninput'=>!empty($var['eje']) ? $var['eje'] : NULL,
      'class' =>isset($ele['tex']['class']) ? $ele['tex']['class'] : NULL,
      'style' =>isset($ele['tex']['style']) ? $ele['tex']['class'] : NULL
    ]);

    // agrego totales
    if( isset($var['cue']) ){ $_ .= "
      <p class='mar_izq-1' title='Items totales'>
        <c>(</c><n name='tot'>".( is_array($var['cue']) ? count($var['cue']) : $var['cue'] )."</n><c>)</c>
      </p>";
    }
    
    return $_;
  }  
}