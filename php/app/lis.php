<?php

// Listado
class app_lis {

  static string $IDE = "app_lis-";
  static string $EJE = "app_lis.";
      
  // operadores : tog + filtro
  static function ope( string $tip, array $opc = [], array $ele = [] ) : string {
    $_ide = self::$IDE."$tip";
    $_eje = self::$EJE."$tip";      
    $_ = "";

    $tod = empty($opc);
    
    if( $tod || in_array('tog',$opc) ){        
      
      $_ .= app::val_ope( $tip == 'ite' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
    }
    if( $tod || in_array('ver',$opc) ){ 
      $_ .= app::var('val','ver',[ 
        'des'=> "Filtrar...",
        'htm'=> app::val_ver([ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"{$_eje}_ver(this);" ])
      ]);
    }

    if( !empty($_) ){ $_ = "
      <form".api_ele::atr( isset($ele['ope']) ? $ele['ope'] : [] ).">
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
    <ul".api_ele::atr(api_ele::cla($ope['lis'],"bar",'ini')).">";
      if( !isset($ope['ite']) ) $ope['ite'] = [];
      foreach( $dat as $ite ){ 
        $pos++;
        $ele_ite = $ope['ite'];
        api_ele::cla($ele_ite,"pos ide-$pos",'ini');
        if( $pos != $pos_ver ) api_ele::cla($ele_ite,DIS_OCU);
        $_.="
        <li".api_ele::atr($ope['ite']).">";
          // contenido html
          if( is_string($ite) ){
            $_ .= $ite;
          }// elementos html
          elseif( is_array($ite) ){
            $_ .= api_ele::dec($ite);
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

      ".app_var::num('val',$min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."
              
      ".app::ico('lis_pre',['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('val',this);"])."

      ".app_var::num('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('val',this);" ])."

      ".app::ico('lis_pos',['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('val',this);"])."            

      ".app_var::num('val',$max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."          

    </form>";
    return $_;
  }

  // tabla
  static function tab( array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    if( !isset($ope['opc']) ) $ope['opc'] = [];
    foreach( ['lis','ite','dat_val'] as $i ){ 
      if( !isset($ele[$i]) ) $ele[$i]=[]; 
    }
    // cargo datos de columnas    
    if( !isset($ope['atr_dat']) ) $ope['atr_dat'] =  api_dat::atr_ver($dat);
    if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['dat_atr']);
    
    // imprimo cabecera
    if( in_array('cab',$ope['opc']) ){ $_ .= "
      <thead>";
      foreach( $ope['atr'] as $atr ){ $_ .= "
        <th scope='col' data-atr='$atr'>
          <p>".app::let( isset($ope['atr_dat'][$atr]->nom) ? $ope['atr_dat'][$atr]->nom : $atr )."</p>
        </th>";
      }$_ .= "
      </thead>";
    }    
    // imprimo contenido
    $_ .= "
    <tbody>
      ".app_lis::tab_ite($dat,$ope,$ele)."
    </tbody>";
    // armo pie
    if( isset($ope['pie']) ){

    }
    return $_;
  }// filas con datos
  static function tab_ite( array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    if( !isset($ope['opc']) ) $ope['opc'] = [];
    $opc_htm = in_array('htm',$ope['opc']);
    // aseguro columnas
    foreach( $dat as $pos => $val ){
      $_val_obj = is_object($val);
      break;
    }
    // cargo columnas
    if( !isset($ope['atr_dat']) ) $ope['atr_dat'] =  api_dat::atr_ver($dat);
    if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['dat_atr']);
    if( !isset($ope['atr_tot']) ) $ope['atr_tot'] = count($ope['atr']);
    // recorro datos
    $pos_val = 0;    
    foreach( $dat as $pos => $val ){
      // titulos
      if( !empty($ope['tit'][$pos]) ) $_ .= app_lis::tab_pos('tit',$pos,$ope,$ele);
      // fila-columnas
      $pos_val++; 
      $ele_pos = $ele['ite'];
      api_ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_.="
      <tr".api_ele::atr($ele_pos).">";
        foreach( $ope['atr'] as $ide ){
          // valor
          $dat_val = $_val_obj ? $val->{$ide} : $val[$ide];
          // html
          if( $opc_htm ){
            $htm = $dat_val;
          }// elementos
          elseif( is_array( $dat_val ) ){
            $htm = isset($dat_val['htm']) ? $dat_val['htm'] : api_ele::val($dat_val);
          }// textos
          else{
            $htm = app::let($dat_val);
          }
          $ele['dat_val']['data-atr'] = $ide;
          $_.="
          <td".api_ele::atr($ele['dat_val']).">{$htm}</td>";
        }      
        $_ .= "
      </tr>";
      // detalles
      if( !empty($ope['det'][$pos]) ) $_ .= app_lis::tab_pos('det',$pos,$ope,$ele);                    
    }
    return $_;
  }// titulo + detalle
  static function tab_pos( string $tip, int $ide, array $ope = [], array $ele = [] ) : string {
    $_ = "";    
    if( isset($ope[$tip][$ide]) ){
      foreach( api_lis::ite($ope[$tip][$ide]) as $val ){ 
        $_.="
        <tr".api_ele::atr($ele["{$tip}_ite"]).">

          <td".api_ele::atr(api_ele::jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">

            ".( is_array($val) ? api_ele::val($val) : "<p class='{$tip} tex_ali-izq'>".app::let($val)."</p>" )."

          </td>
        </tr>";
      }        
    }
    return $_;
  }

  // opciones
  static function opc( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";

    $_ite = function ( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
      $_ = "";
      
      $val_ite = !empty($val);
      $val_arr = $val_ite && is_array($val);
      $opc_ide = in_array('ide',$opc);
  
      $obj_tip = FALSE;
      foreach( $dat as $i => $v){ 
        $obj_tip = api_obj::tip($v);
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
          $atr = api_ele::atr($e);
        }
        // elemento
        elseif( $obj_tip == 'nom' ){
          $e = api_ele::jun($e,$v);
          if( !isset($e['value']) ) $e['value'] = $i;
          $htm = isset($e['htm']) ? $e['htm'] : $i;
          $atr = api_ele::atr($e);
        }
        // objeto ( ide + nom + des + tit )
        elseif( $obj_tip == 'atr' ){
          $_ide = isset($v->ide) ? $v->ide : FALSE ;
          $_htm = isset($v->nom) ? $v->nom : FALSE ;
          // valor
          if( isset($e['value']) ){ 
            $e['value'] = api_obj::val($v,$e['value']); 
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
            $htm = api_obj::val($v,$e['htm']);
          }else{
            if( !!$opc_ide && $_ide && $_htm ){
              $htm = "{$_ide}: {$_htm}";
            }elseif( $_htm ){
              $htm = $_htm;
            }else{
              $htm = $_ide; 
            }
          }
          $atr = api_ele::atr($e,$v);            
        }// por posiciones
        else{
          $htm = "( \"".implode( '", "', $v )."\" )" ;
          $atr = api_ele::atr($e);
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

    $ope_eti = !empty($ope['eti']) ? api_obj::dec($ope['eti'],[],'nom') : [];
    // etiqueta del contenedor
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
    <{$eti}".api_ele::atr($ope_eti).">";

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

  // items : dl, ul, ol
  static function ite( array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
    $_ = "";
    $_eje = self::$EJE."ite";
    // operador
    if( isset($ope['opc']) ) $_ .= app_lis::ope('ite', $ope['opc'] = api_lis::ite($ope['opc']), $ope);
    // por punteo o numerado
    if( api_obj::pos($dat) ){
      $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul'; 
      $_ .= "
      <{$eti}".api_ele::atr($ope['lis']).">";
        foreach( $dat as $pos => $val ){
          $_ .= app_lis::ite_pos( 1, $pos, $val, $ope, $eti );
        }$_.="
      </{$eti}>";
    }
    // por términos
    else{
      // agrego toggle del item
      api_ele::eje($ope['ite'],'cli',"{$_eje}_val(this);",'ini');
      $_ .= "
      <dl".api_ele::atr($ope['lis']).">";
        foreach( $dat as $nom => $val ){ 

          $ope_ite = $ope['ite'];

          if( empty($ope_ite['id']) ) $ope_ite['id'] = "_doc-tex ".str_replace(' ','_',mb_strtolower($nom));
          $_ .= "
          <dt".api_ele::atr($ope_ite).">
            ".app::let($nom)."
          </dt>";
          foreach( api_lis::ite($val) as $ite ){ $_ .= "
            <dd".api_ele::atr($ope['val']).">
              ".app::let($ite)."
            </dd>";
          }
        }$_.="
      </dl>";
    }
    return $_;
  }
  static function ite_pos( int $niv, int | string $ide, mixed $val, array $ope, string $eti = "ul" ) : string {
    $_ = "
    <li".api_ele::atr($ope['ite']).">";
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
          $_ .= "<li>".app_lis::ope('ite',$opc,$ope)."</li>";
        }
        foreach( $val as $ide => $val ){
          $_ .= app_lis::ite_pos( $niv, $ide, $val, $ope, $eti );
        }$_.="
        </$eti>";
      }
      $_ .= "
    </li>";
    return $_;
  }

  // contenedores : ul > ...li > .val(.ico + tex-tit) + lis/htm
  static function val( array $dat, array $ope = [] ) : string {
    foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
    $_ = "";
    // elementos        
    api_ele::cla($ope['lis'],"lis",'ini');
    api_ele::cla($ope['dep'],"lis",'ini');
    api_ele::cla($ope['ope'],"ite",'ini');      
    // operadores
    if( isset($ope['opc']) ) $_ .= app_lis::ope('val', api_lis::ite($ope['opc']), $ope);
    // listado
    $_ .= "
    <ul".api_ele::atr($ope['lis']).">";
    $ide = 0;
    foreach( $dat as $val ){
      $ide++;
      $_ .= app_lis::val_pos( 1, $ide, $val, $ope );
    }$_ .= "
    </ul>";
    return $_;
  }    
  static function val_pos( int $niv, int | string $ide, string | array $val, array $ope ) : string {
  
    $ope_ite = $ope['ite'];      
    api_ele::cla($ope_ite,"pos ide-$ide",'ini');
    // con dependencia : evalúo rotacion de icono
    if( $val_lis = is_array($val) ){
      $ope_ico = $ope['ico'];
      $ele_dep = isset($ope["lis-$niv"]) ? api_ele::jun($ope['dep'],$ope["lis-$niv"]) : $ope['dep'];
      if( isset($ele_dep['class']) && preg_match("/".DIS_OCU."/",$ele_dep['class']) ) api_ele::cla($ope_ico,"ocu");
      if( !isset($val['ite_ope']) ) $val['ite_ope'] = [];
      $val['ite_ope']['ico'] = $ope_ico;
    }// sin dependencias : separo item por icono vacío
    else{
      api_ele::cla($ope_ite,"sep");
    }
    $_ = "
    <li".api_ele::atr( isset($ope["ite-$ide"]) ? api_ele::jun($ope["ite-$ide"],$ope_ite) : $ope_ite  ).">

      ".( $val_lis ? app::val( isset($val['ite']) ? $val['ite'] : $ide, $val['ite_ope'] ) : $val );
      
      if( $val_lis ){
        // sublista
        if( isset($val['lis']) ){
          $ope['dep']['data-niv'] = $niv;
          $_ .= "
          <ul".api_ele::atr($ele_dep).">";

          if( is_array($val['lis'])  ){
            // operador por dependencias : 1° item de la lista
            if( isset($ope['opc'])){
              $opc = [];
              foreach( $val['lis'] as $i => $v ){ $lis_dep = is_array($v); break; }                
              if( in_array('tog_dep',$ope['opc']) && $lis_dep ) $opc []= "tog";
              if( !empty($opc) ) $_ .= "
              <li>".app_lis::ope('val',$opc,$ope)."</li>";
            }
            foreach( $val['lis'] as $i => $v ){

              $_ .= app_lis::val_pos( $niv+1, $i, $v, $ope );
            }
          }
          // listado textual
          elseif( is_string($val['lis']) ){

            $_ .= $val['lis'];
          }$_ .= "
          </ul>";
        }// contenido html directo ( asegurar elemento único )
        elseif( isset($val['htm']) ){

          $_ .= is_string($val['htm']) ? $val['htm'] : api_ele::dec($val['htm']);
        }
      }$_ .= "          
    </li>";        
    return $_;
  }
}