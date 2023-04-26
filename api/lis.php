<?php
// array [ ... ] : listado / tabla
class Lis {

  static string $IDE = "Lis-";
  static string $EJE = "Lis.";

  function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    
    $_ = $_dat = Dat::get_est('lis',$ide,'dat');
    
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

  /* Valores */
  static function val( mixed $dat, mixed $ope = NULL ) : bool | array {
    $_ = [];
    if( empty($ope) ){ 
      $_ = is_array($dat) && array_keys($dat) === range( 0, count( array_values($dat) ) - 1 );
    }
    // ejecuto funciones
    elseif( is_array($dat) && is_callable($ope) ){
      
      foreach( $dat as $pos => $val ){

        $_ []= $ope( $val, $pos );
      }
    }  
    return $_;
  }// aseguro iteraciones
  static function val_ite( mixed $dat ) : array {

    return Lis::val($dat) ? $dat : [ $dat ];
    
  }// convierto a listado : [ ...$$ ]
  static function val_cod( array | object $dat ) : array {
    $_ = $dat;
    if( Obj::val_tip($dat) ){
      $_ = [];
      foreach( $dat as $v ){
        $_[] = $v;
      }
    }
    return $_;
  }// decodifica : "" => {} , []
  static function val_dec( array $dat, array $atr, ...$opc ) : array {
    foreach( $dat as &$ite ){

      if( is_object($ite) ){

        foreach( $atr as $ide ){
          
          if( isset($ite->$ide) ) $ite->$ide = Obj::val_dec( preg_replace("/\n/", '', $ite->$ide) , $ite, ...$opc);
        }
      }
    }
    return $dat;
  }// proceso estructura
  static function val_est( array $dat, array $ope=[], ...$opc ) : array | object {    
    
    // junto estructuras
    if( isset($ope['jun']) ){
      if( is_array($ope['jun']) ){
        $dat = in_array('ini',$opc) ? array_merge($ope['jun'], $dat) : array_merge($dat, $ope['jun']);
      }
    }

    // ejecuto filtro
    if( isset($ope['ver']) ){
      $_ = [];
      foreach( $dat as $pos => $ite ){ 
  
        $val_ite = [];

        foreach( $ite as $atr => $val ){
  
          foreach( $ope['ver'] as $ver ){
  
            if( $atr == $ver[0] ) $val_ite []= Dat::ope_ver( $val, $ver[1], $ver[2] );
          }
        }
        // evaluo resultados
        if( count($val_ite) > 0 && !in_array(FALSE,$val_ite) ) $_[] = $ite;
  
      }      
      $dat = $_;
    }

    /* Decodifico atributos : ( "" => {} / [] ) */
    // - elementos
    if( isset($ope['ele']) ){ $dat = Lis::val_dec($dat, Lis::val_ite($ope['ele']), 'nom'); }
    // - objetos
    if( isset($ope['obj']) ){ $dat = Lis::val_dec($dat, Lis::val_ite($ope['obj']) ); }

    // nivelo estructura
    if( isset($ope['niv']) ){
      $_ = [];
      $ide = $ope['niv'];
      // numérica => pos
      if( is_numeric($ide) ){
        $k = intval($ide);
        foreach( $dat as $val ){ 
          $_[$k++]=$val; 
        }
      }
      // Literal => nom
      elseif( is_string($ide) ){
        $k = explode('(.)',$ide);
        foreach( $dat as $i => $val ){ 
          $i=[]; 
          foreach( $k as $ide ){ $i []= $val[$ide]; }
          $_[ implode('(.)',$i) ] = $val; 
        }
      }
      // Clave Múltiple => keys-[ [ [ [],[ {-_-} ],[], ] ] ]
      elseif( is_array($ide) ){
        $k = array_values($ide);
        switch( count($k) ){
        case 1: foreach( $dat as $v ){ $_[$v->{$k[0]}]=$v; } break;
        case 2: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}]=$v; } break;
        case 3: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}]=$v; } break;
        case 4: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}]=$v; } break;
        case 5: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}][$v->{$k[4]}]=$v; } break;
        case 6: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}][$v->{$k[4]}][$v->{$k[5]}]=$v; } break;
        case 7: foreach( $dat as $v ){ $_[$v->{$k[0]}][$v->{$k[1]}][$v->{$k[2]}][$v->{$k[3]}][$v->{$k[4]}][$v->{$k[5]}][$v->{$k[6]}]=$v; } break;
        }
      }
      $dat = $_;
    }// armar navegacion por posicion para indice : xx-xx-xx
    elseif( isset($ope['nav']) && is_string($ope['nav']) ){
      $_ = [];
      $atr = $ope['nav'];
      // creo subniveles
      for( $nav=1; $nav<=7; $nav++ ){
        $_[$nav] = [];
      }
      // cargo por subnivel
      foreach( $dat as $val ){

        switch( $cue = count( $niv = explode('-', is_object($val) ? $val->$atr : $val[$atr] ) ) ){
          case 1: $_[$cue][$niv[0]] = $val; break;
          case 2: $_[$cue][$niv[0]][$niv[1]] = $val; break;
          case 3: $_[$cue][$niv[0]][$niv[1]][$niv[2]] = $val; break;
          case 4: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]] = $val; break;
          case 5: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]] = $val; break;
          case 6: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]] = $val; break;
          case 7: $_[$cue][$niv[0]][$niv[1]][$niv[2]][$niv[3]][$niv[4]][$niv[5]][$niv[6]] = $val; break;
        }
      }  
      $dat = $_;
    }

    // reducir elemento a un atributo
    if( isset($ope['red']) && is_string($ope['red']) ){
      $atr = $ope['red'];
      foreach( $dat as &$n_1 ){
        if( is_object($n_1) ){ if( isset($n_1->$atr) ) $n_1 = $n_1->$atr;
        }
        elseif( is_array($n_1) ){  
          foreach( $n_1 as &$n_2 ){  
            if( is_object($n_2) ){ if( isset($n_2->$atr) ) $n_2 = $n_2->$atr;
            }
            elseif( is_array($n_2) ){  
              foreach( $n_2 as &$n_3 ){  
                if( is_object($n_3) ){ if( isset($n_3->$atr) ) $n_3 = $n_3->$atr;
                }
                elseif( is_array($n_3) ){  
                  foreach( $n_3 as &$n_4 ){  
                    if( is_object($n_4) ){ if( isset($n_4->$atr) ) $n_4 = $n_4->$atr;
                    }
                    elseif( is_array($n_4) ){  
                      foreach( $n_4 as &$n_5 ){  
                        if( is_object($n_5) ){ if( isset($n_5->$atr) ) $n_5 = $n_5->$atr;
                        }
                        elseif( is_array($n_5) ){  
                          foreach( $n_5 as &$n_6 ){
                            if( is_object($n_6) ){ if( isset($n_6->$atr) ) $n_6 = $n_6->$atr;
                            }
                            elseif( is_array($n_6) ){  
                              foreach( $n_6 as &$n_7 ){  
                                if( is_object($n_7) ){ if( isset($n_7->$atr) ) $n_7 = $n_7->$atr;
                                }
                              }
                            }
                          }                   
                        }
                      }              
                    }
                  }  
                }
              }    
            }
          }
        }
      }
    }
    
    // resultado: uno / muchos
    if( isset($ope['opc']) ){
      // aseguro parámetro      
      $ope['opc'] = Lis::val_ite($ope['opc']);
      // valor unico
      if( in_array('uni',$ope['opc']) ){
        // primero, o el último
        if( in_array('fin',$ope['opc']) ) $dat = array_reverse($dat); 
        foreach( $dat as $i => $v ){ $dat = $dat[$i]; break; }
      }
    }

    return $dat;
  }

  /* Operadores */
  static function ope( string $tip, array $opc = [], array $ele = [] ) : string {
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";      
    $_ = "";

    $tod = empty($opc);
    
    // - expandir-contraer items
    if( $tod || in_array('tog',$opc) ){
      
      $_ .= Lis::ope_tog( $tip == 'dep' || $tip == 'nav' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
    }
    // - filtrar items
    if( $tod || in_array('ver',$opc) ){ 
      $_ .= Doc::var('val','ver',[ 
        'des'=> "Filtrar...",
        'ite'=> [ 'class'=>'tam-cre' ],
        'htm'=> Lis::ope_ver([ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"{$_eje}_ver(this);" ])
      ]);
    }

    if( !empty($_) ){ 
      if( !isset($ele['ope']) ) $ele['ope'] = [];
      Ele::cla($ele['ope'],"doc_ite"); 
      $ele['ope']['eti'] = "form";
      $ele['ope']['htm'] = $_;
      $_ = Ele::val($ele['ope']);
    }      
    return $_;
  }// - Filtros : operador + valor textual + ( totales )
  static function ope_ver( string | array $dat = [], array $ele = [], ...$opc ) : string {
    $_ = "";
    
    // opciones de filtro por texto
    $_ .= Dat::ope_opc(['ver','tex'],[
      'ite'=>[ 
        'dat'=>"()($)dat()" 
      ],
      'eti'=>[ 
        'name'=>"ope", 'title'=>"Seleccionar un operador de comparación...", 'val'=>'**', 
        'class'=>isset($dat['ele_ope']['class']) ? $dat['ele_ope']['class'] : "mar_hor-1", 'onchange'=>$dat['eje']
      ]
    ]);

    // ingreso de valor a filtrar
    $_ .= Tex::var('ora', isset($dat['val']) ? $dat['val'] : '', [ 
      'id'=>isset($dat['ide']) ? $dat['ide'] : NULL, 
      'name'=>"val",
      'title'=>"Introducir un valor de búsqueda...",
      'oninput'=>!empty($dat['eje']) ? $dat['eje'] : NULL,
      'class'=>isset($ele['class']) ? $ele['class'] : NULL,
      'style'=>isset($ele['style']) ? $ele['class'] : NULL
    ]);

    // agrego totales
    if( isset($dat['cue']) ){ $_ .= "
      <p class='mar_izq-1' title='Items totales'>
        <c>(</c><n name='tot'>".( is_array($dat['cue']) ? count($dat['cue']) : $dat['cue'] )."</n><c>)</c>
      </p>";
    }
    
    return $_;
  }// - expandir / contraer
  static function ope_tog( array $ele = [], ...$opc ) : string {
    $_eje = self::$EJE."val";      

    if( !isset($ele['ope']) ) $ele['ope'] = [];
    Ele::cla($ele['ope'], "doc_ope", 'ini');

    $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";
    return "
    <fieldset".Ele::atr($ele['ope']).">
      ".Fig::ico('val_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
      ".Fig::ico('val_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
    </fieldset>";
  }

  /* Controladores */
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."var";
    $_eje = self::$EJE."var";
    
    switch( $tip ){
    // único : div > input[radio]
    case 'uni':
      if( isset($ope['dat']) ){
        $_dat = $ope['dat'];
        unset($ope['dat']); 
        $_ .= "
        <div var='opc_uni'>";
        $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
        foreach( $_dat as $ide => $val ){ $_ .= "
          <div class='doc_val'>
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
        if( is_string($_dat) ){
          $_dat = Dat::get($_dat);
        }
        $_ .= "
        <div class='Opc mul'>";
        $ope_dat = Lis::val_ite($dat);
        $ope_ide = isset($ope['id']) ? $ope['id'] : "_opc_mul-".Doc::var_ide('opc_mul');
        $ope_nom = isset($ope['name']) ? $ope['name'] : FALSE;
        foreach( $_dat as $ide => $dat ){
          $ide = isset($dat->ide) ? $dat->ide : $ide;
          $val = isset($dat->nom) ? $dat->nom : $ide;
          $ope_var = $ope;
          $ope_var['eti']   = "input";
          $ope_var['type']  = "checkbox";
          $ope_var['id']    = "{$ope_ide}-{$ide}";
          $ope_var['name']  = $ope_nom ? $ope_nom : $ide;
          $ope_var['value'] = $ide;
          if( in_array($ide,$ope_dat) ) $ope_var['checked'] = 1;
          $_ .= "
          <div class='doc_val'>
            <label for='{$ope_var['id']}'>{$val}<c>:</c></label>
            ".Ele::eti($ope_var)."
          </div>";
        }$_ .= "
        </div>";
      }
      break;          
    }
    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".Ele::atr($ope).">";            
    }
    return $_;
  }    

  /* Selector de Opciones */
  static function opc( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";

    $_ite = function ( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
      $_ = "";
      
      $val_ite = !empty($val);
      $val_arr = $val_ite && is_array($val);
      $opc_ide = in_array('ide',$opc);
  
      $obj_tip = FALSE;
      foreach( $dat as $i => $v){ 
        $obj_tip = Obj::val_tip($v);
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
            }elseif( isset($v->tit) ){ 
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

  /* Barra: Desplazamiento Horizontal */
  static function bar( array $dat, array $ope = [] ) : string {
    $_ide = self::$IDE."bar";
    $_eje = self::$EJE."bar";      
    $_ = "";

    $pos = 0;
    $pos_ver = ( !empty($ope['pos_ver']) ? $ope['pos_ver'] : 1 );
    if( !isset($ope['lis']) ) $ope['lis']=[];
    $_.="
    <ul".Ele::atr(Ele::cla($ope['lis'],"lis bar",'ini')).">";
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
    $_eje .= "_ite";
    $_ .= "
    <form class='doc_ope anc-100 jus-cen mar_ver-2'>

      ".Num::var('val',$min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."
              
      ".Fig::ico('lis_pre',['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('val',this);"])."

      ".Num::var('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('val',this);" ])."

      ".Fig::ico('lis_pos',['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('val',this);"])."            

      ".Num::var('val',$max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."          

    </form>";
    return $_;
  }
  
  /* Posicion: puteos, numerados, términos */
  static function pos( string | array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
    $_ = "";
    $_eje = self::$EJE."pos";
    // operador
    if( isset($ope['opc']) ) 
      $_ .= Lis::ope('dep', $ope['opc'] = Lis::val_ite($ope['opc']), $ope);
    
    // listado
    $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul';
    // por saltos de línea
    if( is_string($dat) ){
      if( empty($ope['lis']['class']) ) $ope['lis']['class'] = "tex_ali-izq mar-0 mar_ver-2";
      $_ .= "
      <$eti".Ele::atr($ope['lis']).">";
      foreach( explode("\n",$dat) as $val ){ $_ .= "
        <li".Ele::atr($ope['ite']).">".Tex::let($val)."</li>";
      }$_ .= "
      </$eti>";
    }// por punteo o numerado
    elseif( Lis::val($dat) ){
      $_ .= "
      <{$eti}".Ele::atr($ope['lis']).">";
        foreach( $dat as $pos => $val ){
          $_ .= Lis::pos_ite( 1, $pos, $val, $ope, $eti );
        }$_.="
      </{$eti}>";
    }
    // por términos
    else{
      $eti = "dl";
      // agrego toggle del item
      Ele::eje($ope['ite'],'cli',"{$_eje}_val(this);",'ini');
      $_ .= "
      <$eti".Ele::atr($ope['lis']).">";
        foreach( $dat as $nom => $val ){ 
          $ope_ite = $ope['ite'];
          if( empty($ope_ite['id']) ) $ope_ite['id'] = "_doc-tex ".str_replace(' ','_',mb_strtolower($nom));
          $_ .= "
          <dt".Ele::atr($ope_ite).">
            ".Tex::let($nom)."
          </dt>";
          foreach( Lis::val_ite($val) as $ite ){ $_ .= "
            <dd".Ele::atr($ope['val']).">
              ".Tex::let($ite)."
            </dd>";
          }
        }$_.="
      </$eti>";
    }
    return $_;
  }
  static function pos_ite( int $niv, int | string $ide, mixed $val, array $ope, string $eti = "ul" ) : string {
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
          $_ .= "<li>".Lis::ope('dep',$opc,$ope)."</li>";
        }
        foreach( $val as $ide => $val ){
          $_ .= Lis::pos_ite( $niv, $ide, $val, $ope, $eti );
        }$_.="
        </$eti>";
      }
      $_ .= "
    </li>";
    return $_;
  }

  /* Contenedor : ul.lis > ...li > .val(.fig_ico + tex-tit) + lis/htm */
  static function dep( array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
    $_ = "";
    // elementos        
    Ele::cla($ope['lis'],"lis dep",'ini');
    Ele::cla($ope['dep'],"lis",'ini');
    // operadores
    if( isset($ope['opc']) ){ 
      $_ .= Lis::ope('dep', Lis::val_ite($ope['opc']), $ope);
    }else{
      $ope['opc'] = [];
    }
    // listado
    $_ .= "
    <ul".Ele::atr($ope['lis']).">";
    $ide = 0;
    foreach( $dat as $val ){
      $ide++;
      $_ .= Lis::dep_ite( 1, $ide, $val, $ope );
    }$_ .= "
    </ul>";
    return $_;
  }    
  static function dep_ite( int $niv, int | string $ide, string | array $val, array $ope ) : string {  
    $ope_ite = $ope['ite'];      
    Ele::cla($ope_ite,"pos ide-$ide",'ini');
    // con dependencia : evalúo rotacion de icono
    if( $val_lis = is_array($val) ){
      $ope_ico = $ope['ico'];
      $ele_dep = isset($ope["lis-$niv"]) ? Ele::val_jun($ope['dep'],$ope["lis-$niv"]) : $ope['dep'];
      if( isset($ele_dep['class']) && preg_match("/".DIS_OCU."/",$ele_dep['class']) ) Ele::cla($ope_ico,"ocu");
      if( !isset($val['ite_ope']) ) $val['ite_ope'] = [];
      $val['ite_ope']['ico'] = $ope_ico;
    }// sin dependencias : separo item por icono vacío
    else{
      if( !in_array('not-sep',$ope['opc']) ) Ele::cla($ope_ite,"sep");
    }
    $_ = "
    <li".Ele::atr( isset($ope["ite-$ide"]) ? Ele::val_jun($ope["ite-$ide"],$ope_ite) : $ope_ite  ).">

      ".( $val_lis ? Doc::val( isset($val['ite']) ? $val['ite'] : $ide, $val['ite_ope'] ) : $val );
      
      if( $val_lis ){
        // sublista
        if( isset($val['lis']) ){
          $ope['dep']['data-niv'] = $niv;
          $_ .= "
          <ul".Ele::atr($ele_dep).">";

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
                <li>".Lis::ope('dep',$opc,$ope)."</li>";
            }

            foreach( $val['lis'] as $i => $v ){
              $_ .= Lis::dep_ite( $niv+1, $i, $v, $ope );
            }
          }
          // listado textual
          elseif( is_string($val['lis']) ){

            $_ .= $val['lis'];
            
          }$_ .= "
          </ul>";
        }// contenido html directo ( asegurar elemento único )
        elseif( isset($val['htm']) ){

          $_ .= is_string($val['htm']) ? $val['htm'] : Ele::val_dec($val['htm']);
        }
      }
      $_ .= "          
    </li>";        
    return $_;
  }

  /* Indice: Enlaces Externos-Internos => a[href] > ...a[href] */
  static function nav( array $dat, array $ele = [], ...$opc ) : string {    
    $_ = "";
    $_eje = self::$EJE."nav";// val | ver
    foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // operador
    Ele::cla( $ele['ope'], "doc_ren", 'ini' );
    $_ .= Lis::ope('nav',['tog','ver'],$ele);

    // dependencias
    $tog_dep = FALSE;
    if( in_array('tog-dep',$opc) ){
      Ele::cla( $ele['ope_dep'], "ite", 'ini' ); $tog_dep = "
      <form".Ele::atr($ele['ope_dep']).">

        ".Lis::ope_tog()."

      </form>";
    }
    
    // armo listado de enlaces
    $_lis = [];
    $opc_ide = in_array('ide',$opc);
    Ele::cla($ele['lis'], "nav");
    foreach( $dat[1] as $nv1 => $_nv1 ){
      $ide = $opc_ide ? $_nv1->ide : $nv1;
      $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> Tex::let("{$_nv1->nom}") ];
      if( !isset($dat[2][$nv1]) ){
        $_lis []= Ele::val($eti_1);
      }
      else{
        $_lis_2 = [];
        foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
          $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
          $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> Tex::let("{$_nv2->nom}") ];
          if( !isset($dat[3][$nv1][$nv2])  ){
            $_lis_2 []= Ele::val($eti_2);
          }
          else{
            $_lis_3 = [];              
            foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
              $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
              $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> Tex::let("{$_nv3->nom}") ];
              if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                $_lis_3 []= Ele::val($eti_3);
              }
              else{
                $_lis_4 = [];                  
                foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                  $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                  $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> Tex::let("{$_nv4->nom}") ];
                  if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                    $_lis_4 []= Ele::val($eti_4);
                  }
                  else{
                    $_lis_5 = [];                      
                    foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                      $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                      $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> Tex::let("{$_nv5->nom}") ];
                      if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                        $_lis_5 []= Ele::val($eti_5);
                      }
                      else{
                        $_lis_6 = [];
                        foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                          $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                          $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> Tex::let("{$_nv6->nom}") ];
                          if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                            $_lis_6 []= Ele::val($eti_6);
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
    Ele::cla($ele['dep'],DIS_OCU);
    $ele['opc'] = [];
    $_ .= Lis::dep($_lis,$ele);
    return $_;
  }  
}