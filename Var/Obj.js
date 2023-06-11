// WINDOW
'use strict';

// Objeto : [ ...val ], [ ...nom => val ], { ...atr : val }
class Obj {

  /* Contenido : valores por atributo = ()($)atr() */
  static val( $dat, $val='' ){
    let $_={},$={};
    $_ = [];
    $val.split(' ').forEach( $pal =>{ 
      $.let=[];
      $pal.split('()').forEach( $cad =>{ 
        $.val = $cad;
        if( $cad.substring(0,3)=='($)' ){ 
          $.val = !!($dat[ $.atr = $cad.substring(3) ]) ? $dat[$.atr] : ''; 
        }
        $.let.push($.val );
      });
      $_.push($.let.join(''));
    });
    $_ = $_.join(' ');
    return $_;
  }// Codifico a string : {}-[] => "..."
  static val_cod( $dat ){
  
    let $_ = $dat;
  
    if( typeof($dat) == 'object' ){ 
  
      $_ = JSON.stringify($dat); 
    }
  
    return $_;
  }// Decodifico desde string : "..." => {}-[]
  static val_dec( $dat, $ope ){
  
    let $_ = $dat;
  
    if( typeof($dat) == 'string' ){
  
      if( !!$ope && /\(\)\(\$\).+\(\)/.test($dat) ){
        $dat = Obj.val($ope,$dat);
      }
      // json : {} + []
      if( /^({|\[).*(}|\])$/.test($dat) ){ 
  
        $_ = JSON.parse($dat);
      }
      // valores textuales : ('v_1','v_2','v_3')
      else if( /^\('*.*'*\)$/.test($dat) ){ 
  
        $_ = /','/.test($dat) ? $dat.substr(1,$dat.length-1).split("','") : [ $dat.trim().substr(1,$dat.length-1) ] ;      
      }
      // elemento del documento : "a_1(=)v_1(,,)a_2(=)v_2"
      else if( /\(,,\)/.test($dat) && /\(=\)/.test($dat) ){
        $dat.split('(,,)').forEach( $v => { 
          $eti = $v.split('(=)'); 
          $_[$eti[0]] = $eti[1]; 
        });
      }
    }
    return $_;
  }// cuento items
  static val_cue( $dat ){

    let $_ = 0;

    if( Obj.tip($dat) ){

      for( const $nom in $dat ){
        $_ ++;
      }
    }

    return $_;
  }

  // Tipos : pos | nom | atr
  static tip( $dat ){
  
    let $_ = false;
  
    if( Array.isArray($dat) ){
      $_ = "pos";
    }
    else if( !!$dat && typeof($dat)=='object' ){
  
      $_ = ( $dat.constructor.name == 'Object' ) ? "nom" : "atr";
    }
    return $_;
  }

  // listado
  static pos( $dat ){
    
    let $_ = $dat;

    if( typeof($dat) == 'object' ){

      // elemento : armo listado o convierto a iterable
      if( /(NodeList|^HTML[a-zA-Z]*(Element|Collection)$)/.test($dat.constructor.name) ){

        $_ = ( /^HTML[a-zA-Z]*Element/.test($dat.constructor.name) ) ? Obj.pos_ite($dat) : Array.from( $dat ) ;
      }
      // convierto : {} => []
      else{
        $_ = []; 

        for( const $i in $dat ){ 
          
          $_.push( $dat[$i] ); 
        }
      }
    }
    else if( !Obj.pos_val($dat) ){
      
      $_ = [ $dat ];
    }

    return $_;
  }// Valido tipo : []
  static pos_val( $dat ){

    return Array.isArray($dat);

  }// Aseguro iteraciones : []
  static pos_ite( $dat = [] ){

    return Obj.pos_val($dat) ? $dat : [ $dat ] ;

  }

  // nombres
  static nom( $dat ){
  }

  // atributos
  
  // proceso estructura
  static est( $dat, $ope ){
    let $_ = $dat;

    // nivelacion de clave : num + ide + lis
    if( !!($ope['niv']) ){ 
      $.key = $ope['niv'];
      $.tip = typeof($ope['niv']);
      // key-pos = numérica : []
      if( $.tip == 'number' ){ 
        $_ = [];
        $.k = parseInt($.key);
        for( const $i in $dat ){ 
          $_[$.k++]=$dat[$i]; 
        }
      }
      // key-ide = Literal : {}
      else if( $.tip=='string' ){ 
        $_ = {}; 
        $.k = $.key.split('(.)');
        for( const $i in $dat ){
          $.ide=[];
          for( let $ide of $.k ){ 
            $.ide.push($dat[$i][$ide]); 
          }
          $_[$.ide.join('(.)')] = $dat[$i];
        }
      }
      // keys-[1-7] => [ [ [ [],[ {-_-} ],[], ] ] ]
      else if( Array.isArray($.key) ){ 
        $_ = {};
        $.k = $.key;
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
    }

    // devuelvo valor unico : objeto
    if( !!($ope['opc']) ){
      $ope['opc'] = Obj.pos_ite($ope['opc']);
      if( $ope['opc'].includes('uni') ){
        for( const $pos in $_ ){ 
          $_ = $_[$pos]; 
          break;
        }
      }
    }

    return $_;
  }
}