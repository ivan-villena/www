// WINDOW
'use strict';

// Fecha : aaaa-mm-dia hh:mm:ss utc
class api_fec {

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = api_app.est('fec',$ide,'dat');

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        case 'dat':
          $_ = api_fec.dat($val);
          break;
        default:
          if( Number($val) ) $val = parseInt($val)-1;
          $_ = $_dat && !!($_dat[$val]) ? $_dat[$val] : {};
          break;
        }
      }
    }
    return $_;
  }

  // Valido una fecha y devuelvo : "año/mes/dia" o "dia/mes/año"
  static val( $dat, ...$opc ){
    let $_=$dat, $={};
  
    if( typeof($dat) == 'string' ){
      $dat = $dat.split(' ')[0];
      $dat = /-/.test($dat) ? $dat.split('-') : $dat.split('/');
    }
  
    if( $.tip_obj = api_obj.val_tip($dat) ){
  
      if( $.tip_obj == 'pos' ){
        $.mes = $dat[1];
        if( $dat[0].length == 4 ){ 
          $.año = $dat[0]; 
          $.dia = $dat[2];
        }else{ 
          $.año = $dat[2]; 
          $.dia = $dat[0];
        }
      }else{
        $.año = !!($dat.año) ? $dat.año : 1900;
        $.mes = !!($dat.mes) ? $dat.mes : 1;
        $.dia = !!($dat.dia) ? $dat.dia : 1;
      }
  
      if( api_fec.val_tip($.mes, $.dia, $.año) ){
        $_ = !$opc.includes('año') ? api_num.val($.dia,2)+'/'+num.val($.mes,2)+'/'+num.val($.año,4) : api_num.val($.año,4)+'/'+num.val($.mes,2)+'/'+num.val($.dia,2);
      }else{
        $_ = false;
      } 
    }
    return $_;
  }// Decodifico : objeto Date por actual, numero, string u objeto propio
  static val_dec( $dat ){ 
    let $={},$_;
    $.tip = typeof($dat);
    if( !$dat ){
      $_ = new Date();
    }// por timestamp
    else if( $.tip == 'number' || ( $.tip == 'string' && /^\d+$/g.test($dat) ) ){ 
      $_ = new Date( parseInt($dat) );
    }// formateo e instancio
    else if( $.tip == 'string' ){ 
      $.fec = api_fec.dat($dat);
      if( $.fec.año && $.fec.mes && $.fec.dia ){ 
        $_ = new Date( $.fec.año, $.fec.mes, $.fec.dia ); 
      }else{
        $_ = $.fec;
      }
    }// {}: objeto vacío
    else if( $.tip=='object' ){
      if( $dat.año && $dat.mes && $dat.dia ){
        $_ = new Date( $dat.año, $dat.mes, $dat.dia );
      }else{
        $_ = $dat;
      }
    }
    return $_;
  }// Codifico : "año/-mes/-dia" => [ año, mes, dia ]
  static val_cod( $val, $sep ){
    let $_ = [], $ = {};
    $.val = $val;
    if( typeof($val) == 'string' ){
      if( !$sep ){
        $sep = /-/.test($val) ? '-' : ( /\//.test($val) ? '/' : '.' );
      }
      $.val = $val.split($sep).map( ite => parseInt(ite) );
    }
    if( $.val[0].length > 2 ){
      $_ = [ $.val[0], $.val[1], $.val[2] ];
    }else{
      $_ = [ $.val[2], $.val[1], $.val[0] ];
    }
    return $_;
  }// Cantidades :  de dias en el mes, año...
  static val_cue( $tip, $dat ){
    let $={},$_ = api_fec.dat($dat)
    switch( $tip ){
      case 'mes':
        $_ = new Date( $_['año'] || new Date().getFullYear(), $_['mes'], 0 ).getDate()
        break;
      case 'año':
        $.tab=[]; $.tot=0; $.num=0; $.mes=0;
        for( let $i = 1; $i <= 12 ; $i++ ){ 
          $.tab[$i] = api_fec.val_cue('mes',`${$_['año']}/${$i}/1`); 
          $.tot += $.tab[$i]; 
        }
        if( $_['mes'] == 1 ){ 
          $.num = $_['dia']; 
        }else{ 
          $.mes++;
          while( $.mes < $_['mes'] ){ $.num += $.tab[$mes_con]; $.mes++; } 
          $.num += $_['dia'];
        }
        $_={ 
          'tex':`${$.num} de ${$.tot}`, 
          'val':$.num, 
          'cue':$.tot 
        };
        break;
    }
    return $_;
  }// Busco fechas : desde - hasta
  static val_ver( $val, $ini, $fin ){
    let $_ = true, $ = {};
    if( !!$val ){
      $.val = Array.isArray($val) ? $val : api_fec.val_cod($val);
      // fecha desde
      if( !!$ini ){
        $.ini = Array.isArray($ini) ? $ini : api_fec.val_cod($ini);
        if( $.val[0] < $.ini[0] ){// el año es menor, oculto
          $_ = false;
        }else if( $.val[0] == $.ini[0] ){// mismo año
          if( $.val[1] < $.ini[1] ){
            $_ = false;
          }else if( $.val[1] == $.ini[1] ){// mismo mes
            if( $.val[2] < $.ini[2] ){
              $_ = false;
            }
          }
        }
      }
      // fecha hasta
      if( !!$fin && !!$_ ){
        $.fin = Array.isArray($fin) ? $fin : api_fec.val_cod($fin);
        if( $.val[0] > $.fin[0] ){// si el año es mayor, oculto
          $_ = false;
        }else if( $.val[0] == $.fin[0] ){// mismo año
          if( $.val[1] > $.fin[1] ){
            $_ = false;
          }else if( $.val[1] == $.fin[1] ){// mismo mes
            if( $.val[2] > $.fin[2] ){
              $_ = false;
            }
          }
        }      
      }
    }  
    return $_;
  }// Valido fecha
  static val_tip( $año, $mes, $dia ){

    let $_ = false;
    
    $mes = parseInt($mes);
    $dia = parseInt($dia);

    if( !!($año) && !!($mes) && !!($dia) ){

      $año = parseInt($año);

      if( ['1','3','5','7','8','10','12'].includes($mes) ){ 
        if( $dia > 0 && $dia <= 31){ 
          $_ = true; 
        }
      }else if( $mes != '2' ){ 

        if( $dia > 0 && $dia <= 30) $_ = true; 
      }
      // CANTIDAD DE DIAS DE FEBRERO PARA ESE AÑO
      else{
        let $val_dia = new Date( $año || new Date().getFullYear(),2,0 ).getDate();

        if( $dia > 0 && $dia <= $val_dia) $_ = true; 
      }
    }

    return $_;
  }// Devuelvo : dia y hora
  static val_dyh( $dat ){
    let $ = typeof($dat) == 'object' ? $dat : api_fec.val_dec($dat);
    return `${$.getFullYear()}/${$.getMonth()+1}/${$.getDate()} ${$.getHours()}:${$.getMinutes()}:${$.getSeconds()}`;
  }// Devuelvo : Dia
  static val_dia( $dat ){
    let $ = typeof($dat) == 'object' ? $dat : api_fec.val_dec($dat);
    return `${$.getFullYear()}/${$.getMonth()+1}/${$.getDate()}`;
  }// Devuelvo : Hora
  static val_hor( $dat ){
    let $ = typeof($dat) == 'object' ? $dat : api_fec.val_dec($dat);
    return `${$.getHours()}:${$.getMinutes()}:${$.getSeconds()}`;
  }// Devuelvo : Dia de la Semana
  static val_sem( $dat ){
    let $ = typeof($dat) == 'object' ? $dat : api_fec.val_dec($dat);
    return `d/l/m/x/j/v/s`;
  }

  // armo objeto : val + año + mes + sem + dia + tie + hor + min + seg + ubi
  static dat( $val, $sep='/' ){
    let $_={}, $={};
  
    if( typeof($val) != 'string' ){
      $val = api_fec.val($val);
      if( !$val ){
        return $val;
      }
    }
  
    $.tie = $val.replace('T',' ').split(' ');  
    $.fec = $.tie[0].replace($sep,'-').split('-');
    $.hor = !!($.tie[1]) ? $.tie[1] : false;
  
    $_.mes = parseInt($.fec[1]);  
    if( $.fec[0].length==4){ 
      $_.año = parseInt($.fec[0]);
      $_.dia = parseInt($.fec[2]);
    }else{
      $_.año = parseInt($.fec[2]);
      $_.dia = parseInt($.fec[1]);
    }
  
    if( $.hor ){
      $_.tie = $.hor;
      $.hor = $.hor.split(':');
      if( !!($.hor[2]) ){
        $_.seg = parseInt($.hor[2]);
      }
      if( !!($.hor[1]) ){
        $_.min = parseInt($.hor[1]);
      }
      $_.hor = parseInt($.hor[0]);
    }
    $_.val = [$_.dia,$_.mes,$_.año].join($sep);
    if( api_fec.val($_.val) ){
      $_.sem = api_fec.val_sem($_);
    }else{
      $_ = false;
    }
    
    return $_;  
  }
}