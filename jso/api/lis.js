// WINDOW
'use strict';

// listado - tabla : []
class api_lis {

  // aseguro iteracion : []
  static ite( $dat = [] ){

    return api_obj.tip($dat) == 'pos' ? $dat : [ $dat ] ;
  }
  // convierto a listado : []
  static val( $dat ){
  
    let $_ = $dat;
  
    // elemento : armo listado o convierto a iterable
    if( $dat.constructor && /(NodeList|^HTML[a-zA-Z]*(Element|Collection)$)/.test($dat.constructor.name) ){

      $_ = ( /^HTML[a-zA-Z]*Element/.test($dat.constructor.name) ) ? api_lis.ite($dat) : Array.from( $dat ) ;
    }
    // convierto : {} => []
    else if( typeof($dat) == 'object' ){

      $_=[]; 

      for( const $i in $dat ){ 
        
        $_.push( $dat[$i] ); 
      }
    }
    return $_;
  }
  // operador de estructura
  static ope( $dat, $ope ){
    let $_ = $dat;
    // nivelacion por identificador
    if( !!($ope['niv']) ) $_ = api_lis.niv($dat,$ope);

    // valor unico : objeto
    if( !!($ope['opc']) && $ope['opc'].includes('uni')) $_ = api_lis.uni($dat);

    return $_;
  }
  // nivelacion : num + ide + lis
  static niv( $dat, $ope ){

    let $_ = $dat, $={ tip : typeof($ope['niv']) };

    // key-pos = numérica : []
    if( $.tip == 'number' ){ 
      $_=[];
      $.k = parseInt($.key);
      for( const $i in $dat ){ 
        $_[$.k++]=$dat[$i]; 
      }
    }
    // key-ide = Literal : {}
    else if( $.tip=='string' ){ 
      $_={}; 
      $.k = $.key.split('(.)');
      for( const $i in $dat ){
        $.ide=[];
        for( let $ide of $.k ){ $.ide.push($dat[$i][$ide]); }
        $_[$.ide.join('(.)')] = $dat[$i];
      }
    }
    // keys-[1-7] => [ [ [ [],[ {-_-} ],[], ] ] ]
    else if( Array.isArray($ope['niv']) ){ 
      $_ = {};
      $.k = $ope['niv'];
      switch( $k.length ){
      case 0: $_ = $dat; break;
      case 1: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]]=$d; } break;
      case 2: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]]=$d; } break;
      case 3: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]]=$d; } break;
      case 4: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]]=$d; } break;
      case 5: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]][$d[$.k[4]]]=$d; } break;
      case 6: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]][$d[$.k[4]]][$d[$.k[5]]]=$d; } break;
      case 7: for( const $i in $dat ){ const $d=$dat[$i]; $_[$d[$.k[0]]][$d[$.k[1]]][$d[$.k[2]]][$d[$.k[3]]][$d[$.k[4]]][$d[$.k[5]]][$d[$.k[6]]]=$d; } break;
      }
    }
    return $_;
  }
  // valor unico : objeto
  static uni( $dat ){

    let $_ = {};

    for( const $pos in $dat ){ 
      $_ = $dat[$pos]; 
      break;
    }

    return $_;
  }
}