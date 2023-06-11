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
      $eti = 'span';      
      if( isset($ele['eti']) ){
        $eti = $ele['eti'];
        unset($ele['eti']);
      }
      if( $eti == 'button' && empty($ele['type']) ) $ele['type'] = "button"; 
      
      $_ = "
      <{$eti}".Ele::atr(Ele::cla($ele,"val_ico ide-$ide",'ini')).">
        {$ico[$ide]->val}
      </{$eti}>";
    }
    return $_;
  }  

  // imagen : .val_ima.$ide
  static function ima( ...$dat ) : string {
    $_ = "";
    // por aplicacion
    if( isset($dat[2]) ){

      $ele = isset($dat[3]) ? $dat[3] : [];

      $_ = Doc_Dat::val('ima', "{$dat[0]}.{$dat[1]}", $dat[2], $ele );
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

      // etiqueta
      $eti = 'span';
      if( isset($ele['eti']) ){
        $eti = $ele['eti'];
        unset($ele['eti']);
      }
      // codifico boton
      if( $eti == 'button' && empty($ele['type']) ) $ele['type'] = "button";
      
      // ide de imagen
      Ele::cla($ele,"val_ima",'ini');
      
      // contenido
      $htm = "";
      if( !empty($ele['htm']) ){
        Ele::cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $htm = $ele['htm'];
        unset($ele['htm']);
      }
      $_ = "<{$eti}".Ele::atr($ele).">{$htm}</{$eti}>";
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

    Ele::cla($ele,"num",'ini');

    $_ = Ele::eti($ele);

    return $_;
  }

  // parrafo
  static function tex( mixed $dat, array $ele = [] ) : string {
    $_ = "";

    $tex = [];
      
    foreach( explode("/n",$dat) as $pal ){
      $tex []= Doc_Val::let($pal);
    }

    $ele['htm'] = implode("<br>",$tex);

    if( empty($ele['eti']) ){
      $ele['eti'] = "p";      
    }

    Ele::cla($ele,"tex",'ini');

    $_ = Ele::eti($ele);

    return $_;
  }
  
  // cita textual
  static function cit( mixed $dat, array $ele = [] ) : string {

    $ele['eti'] = "q";

    Ele::cla($ele,"tex_cit",'ini');

    return Doc_Val::tex($dat, $ele);
  }

  // fecha
  static function fec( mixed $dat, array $ele = [] ) : string {

    $ele['eti'] = "time";    

    Ele::cla($ele,"val_fec",'ini');

    $ele['value'] = Doc_Val::let( Fec::val_var($dat) );

    $ele['htm'] = $dat;

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
}