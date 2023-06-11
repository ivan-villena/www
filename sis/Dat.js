// WINDOW
'use strict';

// Dato
class Dat {

  Est = {};

  constructor( $dat = {} ){

    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }    

  }

  // getter 
  static _( $ide, $key ){

    let $_, $ = Dat.ide($ide);

    $_ = $.dat = Dat.est($.esq,$.est,'dat');

    if( !!$key && $.dat.length ){

      $_ = $key;

      if( Number($key) ){

        $key = parseInt($key)-1;

        $_ = !!$.dat[$key] ? $.dat[$key] : $key;
      }
      else if( typeof($key) == 'string' ){

        $_ = !!$.dat[$key] ? $.dat[$key] : $key;

      }
      else if( Obj.tip($key) == 'pos' ){

        switch( $key.length ){ 
          case 1: $_ = !!$.dat[$key[0]] ? $.dat[$key[0]] : $key; break;
          case 2: $_ = !!$.dat[$key[0]][$key[1]] ? $.dat[$key[0]][$key[1]] : $key; break;
          case 3: $_ = !!$.dat[$key[0]][$key[1]][$key[2]] ? $.dat[$key[0]][$key[1]][$key[2]] : $key; break;
          case 4: $_ = !!$.dat[$key[0]][$key[1]][$key[2]][$key[3]] ? $.dat[$key[0]][$key[1]][$key[2]][$key[3]] : $key; break;
          case 5: $_ = !!$.dat[$key[0]][$key[1]][$key[2]][$key[3]][$key[4]] ? $.dat[$key[0]][$key[1]][$key[2]][$key[3]][$key[4]] : $key; break;
        }
      }

    }
    return $_;
  }  
  /* cargo por estructura | objeto */
  static get( $dat, $ope, $val='' ){

    let $_=[], $={};

    // por objeto[->propiedad]
    if( $ope && typeof($ope) == 'string' ){

      $_ = $val;

      if( !$val || !Obj.tip($val) ){

        $_ = Dat._(`${$dat}.${$ope}`, $val ? $val : null);
      }
    }  
    // de la documento 
    else if( typeof($dat) == 'string' ){
      
      $_ = ( $.ver = Doc.ope($dat) ) ? $.ver : [];
    }
    // por estructura : [ {}, [] ]
    else{

      $_ = Obj.est('ver',$dat,$ope);
    }
    return $_;
  }  
  
  // identificador: esq.est[...atr]
  static ide( $dat = '', $val = {} ){
    
    $val.ide = $dat.split('.');
    $val.esq = $val.ide[0];
    $val.est = $val.ide[1] ? $val.ide[1] : false;
    $val.atr = $val.ide[2] ? $val.ide[2] : false;

    return $val;
  }

  /* Tipo */
  static tip( $val ){

    let $tam = 0, $ide = typeof($val), $dat_tip;
    
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
    }

    // busco
    $dat_tip = Dat._("var.tip");
    return !!$dat_tip[$ide] ? $dat_tip[$ide] : false;
  }
  
  /* Comparaciones */
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
    case '^^':  $_ = Tex.val_dec(`^${Tex.val_cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!^':  $_ = Tex.val_dec(`^[^${Tex.val_cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    case '$$':  $_ = Tex.val_dec( `${Tex.val_cod($val)}$`, $opc.join('') ).test( $dat.toString() ); break;
    case '!$':  $_ = Tex.val_dec( `[^${Tex.val_cod($val)}]$`, $opc.join('') ).test( $dat.toString() ); break;
    case '**':  $_ = Tex.val_dec( `${Tex.val_cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!*':  $_ = Tex.val_dec( `[^${Tex.val_cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    }
    return $_;
  }  
  
  // Cargo estructura desde app.dat
  static est( $esq, $est, $ope, $dat ) {

    // Necesita de un objeto instanciado
    let $={}, $_ = $Dat.Est[$esq][$est];

    // Estructura de la base
    if( !$_ ){
      // ...
    }

    // Propiedades
    if( $ope ){
      $.ope_atr = typeof($ope) == 'string' ? $ope.split('.') : $ope;
      $.ope_atr.forEach( $ide => {
        $_ = ( typeof($_) == 'object' && !!($_[$ide]) ) ? $_[$ide] : false;
      });

      if( $_ && $dat ){
        switch( $.ope_atr[0] ){
        case 'val':
          $_ = Obj.val( Dat.get($esq,$est,$dat), $_ );
          break;
        }
      }
    }

    return $_;
  }// identificador por seleccion : imagen, color...
  static est_ide( $tip, $esq, $est, $atr, $dat ){
      
    let $={}, 
    // armo identificadores
    $_ = { 'esq': $esq, 'est': $est, 'atr':$atr, 'ide':"", 'val':null };

    if( !!($atr) ) $_['est'] = ( $atr == 'ide' ) ? $est : `${$est}_${$atr}`;

    $_['ide'] = `${$_['esq']}.${$_['est']}`;

    // busco estructura relacionada por atributo
    $.esq = $_.esq;
    $.est = $_.est;
    if( $.dat_atr = Dat.est_rel($esq,$est,$atr) ){
      $.esq = $.dat_atr.esq;
      $.est = $.dat_atr.est;
    }
    
    if( !!( $.dat_val = Dat.est($.esq,$.est,`val.${$tip}`,$dat) ) ){
      $_['val'] = $.dat_val;
    }

    return $_;    
  }// busco dato relacional por atributo
  static est_rel( $esq, $est, $atr ){

    let $ = {}, $_ = false;
    
    if( $Dat.Est[$esq][$est]?.atr[$atr] && ( $.ide = $Dat.Est[$esq][$est]?.atr[$atr].var.dat ) ){

      $.ide = $.ide.split('-');

      $_ = {
        'esq': $.ide[0], 
        'est': $.ide[1],
        'dat': `${$.ide[0]}-${$.ide[1]}`
      };
    }
    else{

    }
    
    return $_;
  }
}