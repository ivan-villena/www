<?php

class api_opc {

  static string $IDE = "api_opc-";
  static string $EJE = "api_opc.";

  function __construct(){
  }
  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_opc;
    $est = "_$ide";
    if( !isset($api_opc->$est) ) $api_opc->$est = api_dat::est_ini(DAT_ESQ,"opc{$est}");
    $_dat = $api_opc->$est;
    
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
    }// toda la lista
    elseif( isset($_dat) ){
      $_ = $_dat;
    }
    return $_;
  }

  // controladores
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."var";
    $_eje = self::$EJE."var";
    
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
      if( !empty($dat) ){ 
        $ope['checked']='checked'; 
      }
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
        $_ .= "
        <div var='opc_mul'>";
        $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
        foreach( $_dat as $ide => $val ){ $_ .= "
          <div class='doc_val'>
            <label for='{$ope_ide}-{$ide}'>{$val}<c>:</c></label>
            <input id='{$ope_ide}-{$ide}' type='checkbox' name='{$ide}' value='{$ide}'>
          </div>";
        }$_ .= "
        </div>";
      }
      break;          
    }
    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".api_ele::atr($ope).">";            
    }
    return $_;
  }    

  // opciones
  static function lis( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";

    $_ite = function ( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
      $_ = "";
      
      $val_ite = !empty($val);
      $val_arr = $val_ite && is_array($val);
      $opc_ide = in_array('ide',$opc);
  
      $obj_tip = FALSE;
      foreach( $dat as $i => $v){ 
        $obj_tip = api_obj::val_tip($v);
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
          $e = api_ele::val_jun($e,$v);
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

    // etiqueta del contenedor
    $ope_eti = !empty($ope['eti']) ? api_obj::val_dec($ope['eti'],[],'nom') : [];
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

}