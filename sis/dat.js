'use strict';

class sis_dat {
  
  _tip = {};

  _ope = {};
  
  _est = {};

  constructor( $dat = {} ){

    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }

  }
  
  /* Dato : estructura | objeto | valor */
  static get( $dat, $ope, $val='' ){

    let $_=[], $={};

    // por objeto[->propiedad]
    if( $ope && typeof($ope) == 'string' ){

      $.esq = $dat;
      $.est = $ope;
      $_ = $val;

      if( !$val || !api_obj.val_tip($val) ){
        
        // por clase : metodo estático
        if( $.esq && ( $.cla = eval(`api_${$.esq}`) ) ){

          if( !!$.cla._ ) $_ = $.cla._($.est,$val);
        }
      }
    }  
    // de la documento 
    else if( typeof($dat) == 'string' ){
      
      $_ = ( $.ver = $dom.ope($dat) ) ? $.ver : [];
    }
    // por estructura : [ {}, [] ]
    else{

      $_ = api_lis.val_est('ver',$dat,$ope);
    }
    return $_;
  }

  /* Tipo */
  static tip( $val ){
    let $tam = 0, $ide = typeof($val);
    
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
    return !!$sis_dat._tip[$ide] ? $sis_dat._tip[$ide] : false;
  }
  
  /* Operaciones */
  static val( $dat, $ide, $val, $opc=['g','i'] ){
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
    case '^^':  $_ = api_tex.val_dec(`^${api_tex.val_cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!^':  $_ = api_tex.val_dec(`^[^${api_tex.val_cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    case '$$':  $_ = api_tex.val_dec( `${api_tex.val_cod($val)}$`, $opc.join('') ).test( $dat.toString() ); break;
    case '!$':  $_ = api_tex.val_dec( `[^${api_tex.val_cod($val)}]$`, $opc.join('') ).test( $dat.toString() ); break;
    case '**':  $_ = api_tex.val_dec( `${api_tex.val_cod($val)}`, $opc.join('') ).test( $dat.toString() ); break;
    case '!*':  $_ = api_tex.val_dec( `[^${api_tex.val_cod($val)}]`, $opc.join('') ).test( $dat.toString() ); break;
    }
    return $_;
  }

  /* -- Estructura -- */
  static est( $esq, $est, $ope, $dat ) {

    let $={}, $_ = $sis_dat._est[$esq][$est];

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
          $_ = api_obj.val( sis_dat.get($esq,$est,$dat), $_ );
          break;
        }
      }
    }

    return $_;
  }// identificador: esq.est[...atr]
  static est_ide( $dat = '', $val = {} ){
    
    $val.ide = $dat.split('.');
    $val.esq = $val.ide[0];
    $val.est = $val.ide[1] ? $val.ide[1] : false;
    $val.atr = $val.ide[2] ? $val.ide[2] : false;

    return $val;
  }// busco dato relacional por atributo
  static est_atr_dat( $esq, $est, $atr ){

    let $={}, $_ = false;
    
    if( $sis_dat._est[$esq][$est]?.atr[$atr] && ( $.dat_atr = $sis_dat._est[$esq][$est]?.atr[$atr].var.dat ) ){

      $.dat_atr = $.dat_atr.split('_');

      $_ = { 'esq': $.dat_atr.shift(), 'est': $.dat_atr.join('_') };
    }
    return $_;
  }


}