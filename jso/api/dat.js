// WINDOW
'use strict';

// Dato : esq.est[ide].atr
class _dat {

  // getter: estructuras | objetos
  static get( $dat, $ope, $val='' ){

    let $_=[], $={};

    // por objeto[->propiedad]
    if( $ope && typeof($ope) == 'string' ){

      $.esq = $dat;
      $.est = $ope;
      $_ = $val;

      if( !$val || !_obj.tip($val) ){
        
        // por clase : metodo estático
        if( $.esq && ( $.cla = eval(`_${$.esq}`) ) ){

          if( !!$.cla._ ) $_ = $.cla._($.est,$val);
        }
      }
    }  
    // de la documento 
    else if( typeof($dat) == 'string' ){
      
      $_ = ( $.ver = _ele.ope($dat) ) ? $.ver : [];
    }
    // por estructura : [ {}, [] ]
    else{

      $_ = _lis.ope('ver',$dat,$ope);
    }
    return $_;
  }  
  // tipo
  static tip( $val ){
    let $_ = false, 
      $ide = typeof($val), 
      $tam = 0
    ;  
    if( $val === null ){
      $ide='null'; 
    }// ejecuciones
    else if( $ide=='function' ){ 
      if( $val.prototype && /^class /.test( $val.prototype.constructor.toString() ) ){ $ide='class'; }
    }// listados
    else if( Array.isArray($val) ){ 
      $ide = 'array';
    }// objetos + archivo + documento
    else if( $ide=='object' ){
      if( $val.constructor.name=="Object" ){ 
        $ide='asoc';
      }else{ 
        if( $val.constructor.name=='Event' ){ 
          $ide='event';
        }else if( /(NodeList|^HTML[a-zA-Z]*Collection$)/.test($val.constructor.name) ){ 
          $ide='elementlist'; 
        }else if( /^HTML[a-zA-Z]*Element$/.test($val.constructor.name) ){ 
          $ide='element';
        }else if( $val.constructor.name=='FileList' ){ 
          $ide='fileList';
        }else if( $val.constructor.name=='File' ){ 
          if( /^image/.test($val.type) ){       $ide='image'
          }else if( /^audio/.test($val.type) ){ $ide='audio'
          }else if( /^video/.test($val.type) ){ $ide='video'
          }
        }else{ 
          $ide='object';
        }
      }  
    }// numeros
    else if( $ide=='number' ){
      if( Number.isNaN($val) ){ 
        $ide = 'nan';
      }else{
        $tam = $val.length;
        if( Number.isInteger($val) ){ 
          if( $tam <= 5 ){ $ide="smallint";
          }else if( $tam <= 7 ){ $ide="mediumint";
          }else if( $tam <= 10 ){ $ide="int";
          }else if( $tam <= 19 ){ $ide="bigint";
          }        
        }else{
          if( $tam <= 10 ){ $ide="decimal";
          }else if( $tam <= 12 ){ $ide="float";
          }else if( $tam <= 22 ){ $ide="double";
          }
        }
      }
    }// textos
    else if( $ide=='string' ){
      $ide="varchar";
      if( $val.length>50 ){        
        if( /^[0-9]\(>\)[0-9]\(>\)[0-9]$/.test($val) || /^[0-9]\(>\)[0-9]$/.test($val) ){ 
          $ide="range";            
        }else if( /^#k[0-9]{3}$/.test($val) ){ 
          $ide='kin';
        }else if( /^#[a-zA-Z0-9]{6}$/.test($val) || /^rgb\(/.test($val) ){ 
          $ide='color';
        }else if( /^(\d{4})(\/|-)(0[1-9]|1[0-2])\2([0-2][0-9]|3[0-1])(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])(:)([0-5][0-9])$/.test($val) ){ 
          $ide="datetime";
        }else if( /^\d{4}([\-/.])(0?[1-9]|1[1-2])\1(3[01]|[12][0-9]|0?[1-9])$/.test($val) ){ 
          $ide="date";
        }else if( /^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/.test($val) ){ 
          $ide="time";
        }
      }else if( $val.length<=255 && $val.length>=100 ){
        $ide="tinytext";
      }else if( $val.length<=65535 ){      
        $ide="text";
      }else if( $val.length<=16777215 ){   
        $ide="mediumtext";
      }else if( $val.length<=4294967295 ){ 
        $ide="longtext";
      }
    }// busco
    if( !!$_api.dat_tip[$ide] ){
      $_ = $_api.dat_tip[$ide];
    }
    return $_;
  }
  // Comparaciones
  static ver( $dat, $ide, $val, $opc=['g','i'] ){
    let $_ = false;
    switch($ide){
    case '===': $_ = ( $dat === $val );  break;
    case '!==': $_ = ( $dat !== $val );  break;
    case '=':   $_ = ( $dat ==  $val );  break;
    case '<>':  $_ = ( $dat !=  $val );  break;                  
    case '==':  $_ = ( $dat ==  $val );  break;
    case '!=':  $_ = ( $dat !=  $val );  break;          
    case '>>':  $_ = ( $dat  >  $val );  break;
    case '<<':  $_ = ( $dat  <  $val );  break;
    case '>=':  $_ = ( $dat >=  $val );  break;
    case '<=':  $_ = ( $dat <=  $val );  break;
    case '^^':  $_ = _tex.dec(`^${_tex.cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!^':  $_ = _tex.dec(`^[^${_tex.cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    case '$$':  $_ = _tex.dec( `${_tex.cod($val)}$`, $opc.join('') ).test( $dat.toString() ); break;
    case '!$':  $_ = _tex.dec( `[^${_tex.cod($val)}]$`, $opc.join('') ).test( $dat.toString() ); break;
    case '**':  $_ = _tex.dec( `${_tex.cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!*':  $_ = _tex.dec( `[^${_tex.cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    }
    return $_;
  }
  // identificador: esq.est[...atr]
  static ide( $dat='', $val={} ){
    
    $val.ide = $dat.split('.');
    $val.esq = $val.ide[0];
    $val.est = $val.ide[1] ? $val.ide[1] : false;
    $val.atr = $val.ide[2] ? $val.ide[2] : false;

    return $val;
  }
  // por seleccion : imagen, color...
  static opc( $tip, $esq, $est, $atr, $dat ){
    
    // dato
    let $={}, $_ = { 'esq': $esq, 'est': $est };

    // armo identificador
    if( !!($atr) ) $_['est'] = $atr == 'ide' ? $est : `${$est}_${$atr}`;
    
    // valido dato
    if( !!( $.dat_Val = _app.dat($_['esq'],$_['est'],`val.${$tip}`,$dat) ) ){
      $_['ide'] = `${$_['esq']}.${$_['est']}`;
      $_['val'] = $.dat_Val;
    }
    else{
      $_ = [];
    }
    return $_;    
  }
  // imagen por identificadores
  static ima( $dat, $ope ){

    let $_ = "";

    if( $dat.dataset && $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.dataset[`${$ope.esq}_${$ope.est}`] ) ){

      if( !$ope.fic ) $ope.fic = _dat.opc('ima', $ope.esq, $ope.est );

      $_ = _app.ima($ope.fic.esq, $ope.fic.est, _dat.get($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }
    return $_;
  }
}