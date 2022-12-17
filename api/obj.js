// WINDOW
'use strict';

// Objeto : [ ...val ], [ ...nom => val ], { ...atr : val }
class api_obj {

  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    
  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    // aseguro datos
    if( !$api_obj || $api_obj[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_obj[$est];

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        default:        
          if( Number($val) ) $val = parseInt($val)-1;
          if( $_dat && !!$_dat[$val] ) $_ = $_dat[$val];        
          break;
        }        
      }
    }
    else{
      $_ = $_dat ? $_dat : [];
    }
    return $_;
  }

  // valores por atributo : ()($)atr()
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
  }// {}-[] => "..."
  static val_cod( $dat ){
  
    let $_ = $dat;
  
    if( typeof($dat) == 'object' ){ 
  
      $_ = JSON.stringify($dat); 
    }
  
    return $_;
  }// "..." => {}-[]
  static val_dec( $dat, $ope ){
  
    let $_ = $dat;
  
    if( typeof($dat) == 'string' ){
  
      if( !!$ope && /\(\)\(\$\).+\(\)/.test($dat) ){
        $dat = api_obj.val($ope,$dat);
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
  }// valido tipos : pos | nom | atr
  static val_tip( $dat ){
  
    let $_ = false;
  
    if( Array.isArray($dat) ){
      $_ = "pos";
    }
    else if( !!$dat && typeof($dat)=='object' ){
  
      $_ = ( $dat.constructor.name == 'Object' ) ? "nom" : "atr";
    }
    return $_;
  }

  // controlador
  static var( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    $_ = document.createElement('ul');

    $_.classList.add('lis');

    for( const $i in $dat ){ const $v = $dat[$i];

      $.tip = api_dat.tip($v);

      $.ite = document.createElement('li');
      $.ite.classList.add('mar_ver-1');
      $.ite.innerHTML = `
        <q class='ide'>${$i}</q> <c class='sep'>:</c>
      `;
      if( ![undefined,NaN,null,true,false].includes($v) ){

        $.ite.innerHTML += api_tex.let( ( $.tip.dat=='obj' ) ? JSON.stringify($v) : $v.toString() ) ;          
      }
      else{
        $.ite.innerHTML += `<c>${$v}</c>`;
      }

      $_.appendChild($.ite);
    }

    return $_;      
  }
}