// WINDOW
'use strict';

// Elemento : <eti ...atr="val"> ...htm + ...tex </eti>
class Ele {

  // paso objeto a html : {} / {dom} => ""
  static val( ...$ele ){

    let $_ = "", $={};

    $ele.forEach( $ele => {

      if( typeof($ele)=='string' ){

        $_ += $ele;
      }
      else{

        if( $ele['let'] ){
          $_ += Doc_Val.let($ele['let']);
        }
        // por ícono
        else if( $ele['ico'] ){
          $.ico_ide = $ele['ico'];
          delete($ele['ico']);
          $_ += Doc_Val.ico($.ico_ide,$ele);
        }
        // por ficha
        else if( $ele['ima'] ){
          $.est = $ele['ima'].split('.');
          delete($ele['ima']);
          $.est.push( !!($ele['ide']) ? $ele['ide'] : 0, $ele);
          $_ += Doc_Val.ima(...$.est);
        }
        // por variable
        else if( $ele['tip'] ){

          $.htm = "";

          $.tip = $ele['tip']; 
          delete($ele['tip']);
          
          $.val = ''; 
          if( $ele['val'] !== undefined ){ 
            $.val = $ele['val'];
            delete($ele['val']);
          }
          
          $.tip = $.tip.split('_');          
          $.eje = $.tip.shift();

          if( !$.tip.length ){

            if( Doc_Val[$.eje] ){
            
              $.htm += Doc_Val[$.eje]( $.val, $ele );
            }
          }
          else{

            if( Doc_Var[$.eje] ){
            
              $.htm += Doc_Var[$.eje]( $.tip.join('_'), $.val, $ele );
            }
          }

          if( $.htm ){
            $_ += $.htm;
          }
          else{
            $_ += `<div class='err' title='No existe el operador ${$.eje}-${$.tip.join('_')}'></div>`;
          }

        }// por etiqueta
        else{

          $_ += Ele.eti($ele);
        }
      }
    });

    return $_;
  }// conversiones por tipo : "<>" / {dom} <=> {}
  static val_dec( $ele, $dat ){

    let $_ = $ele, $={ tip : typeof($ele) };
    
    // "" => {}
    if( $.tip == 'string' ){

      $_ = Obj.val_dec($ele,$dat);
    }
    // {html} => {}
    else if( false ){


    }

    return $_;
  }// combino
  static val_jun( $ele, $mod, $ope = {} ){
    let $_ = $ele, $={};
    $.dat = ( $ope['dat'] !== undefined ) ? $ope['dat'] : null;
    // aseguo elemento
    $ele = Ele.val_dec($ele,$.dat);
    // recorro 2°dos elemento...
    Obj.pos_ite($mod).forEach( $mod => {
      for( const $atr in ( $.lis_atr = Ele.val_dec($mod,$.dat) ) ){ const $v = $.lis_atr[$atr];
        // si no tiene el atributo, lo agrego
        if( !($_[$atr]) ){ 
          $_[$atr] = $v;
        }// combino, actualizo
        else{
          switch($atr){          
          case 'onclick':   Ele.eje($_,'cli',$v); break;// separador: "(;;)"
          case 'onchange':  Ele.eje($_,'cam',$v); break;// separador: "(;;)"
          case 'oninput':   Ele.eje($_,'inp',$v); break;// separador: "(;;)"
          case 'class':     Ele.cla($_,$v); break;// separador: " "
          case 'style':     Ele.css($_,$v); break;// separador: ";"
          default:          $_[$atr] = $v;   break;
          }
        }
      }
    });
    return $_;
  }

  // etiqueta : <eti atr="">...htm</eti>
  static eti( $ele ){

    let $_="",$={};

    $.eti = 'span'; 
    if( $ele['eti'] !== undefined ){

      if( $ele['eti'] == 'button' && !($ele['type']) ) $ele['type'] = 'button';
      
      $.eti = $ele['eti'];
      delete($ele['eti']);
    }

    if( $ele['val'] !== undefined ){

      if( $ele['value'] === undefined ) $ele['value'] = $ele['val'];
      delete($ele['val']);
    }    

    $.htm = ''; 
    if( $ele['htm'] !== undefined ){
      
      $.htm = $ele['htm'];
      delete($ele['htm']);

      if( Obj.tip($.htm) ){
        
        $._htm_val = '';
        Obj.pos_ite($.htm).forEach( $e => 
          $._htm_val =+ ( typeof($e) == 'string' ) ? $e : Ele.val($e) 
        );
        $.htm = $._htm_val;
      }
    }

    $_ = `
    <${$.eti}${Ele.atr($ele)}>
      ${!['input','img','br','hr'].includes($.eti) ? `${$.htm}
    </${$.eti}>` : ''}`;

    return $_;
  }
  
  // atributos : "< ...atr="">"
  static atr( $ele, $dat ){
    let $_='', $={};
    if( !!$dat ){
      for( const $i in $ele ){ 
        $.tex=[];
        $ele[$i].split(' ').forEach( ($pal)=>{ 
          $.let=[];
          $pal.split('()').forEach( ($cad)=>{ 
            $.sep = $cad;
            if( $cad.substring(0,3)=='($)' ){ 
              $.atr = $cad.substring(3); 
              if( $dat[$.atr] !== undefined ){ 
                $.sep = $dat[$.atr]; 
              }
            }
            $.let.push($.sep);
          });
          $.tex.push($.let.join(''));
        });// junto por espacios
        $_ += ` ${$i} = "${$.tex.join(' ').replaceAll('"',`\'`)}"`;
      }
    }// devuelvo "atr=val ..."
    else{
      for( const $i in $ele ){ 
        $_ +=` ${$i} = "${$ele[$i] && $ele[$i].toString().replaceAll('"',`\'`)}"`; 
      }
    }
    return $_;
  }

  // ejecucion
  static eje( $ele, $ide, $val, ...$opc ){

    let $_ = $ele, $ = {
      '_eve':{ 'cli':"onclick", 'cam':"onchange", 'inp':"oninput" }
    };

    if( $ide = $._eve[$ide] ){

      if( !$ide || $val === undefined ){
        
        $_ = [];
        if( $ide ){
          
          if( $ele[$ide] ) $_ = $ele[$ide].split(';');
        }// todos
        else{          
          $_ = {};
          for( const $i in $._eve ){ const $v = $._eve[$i];
            $_[$v] = [];
            if( $ele[$v] ) $_[$v] = $ele[$v].split(';');
          }
        }
        return $_;  
      }
      // operaciones
      else{
        // elimino funcion + parametros
        if( $opc.includes('eli') ){

        }// modifico funcion con parametros
        else if( $opc.includes('mod') ){

        }// agrego una funcion
        else{
          // al inicio
          if( $opc.includes('ini') ){
            $ele[$ide] = $val+( !!($ele[$ide]) ? ` ${$ele[$ide]}` : '' ); 
          }
          else if( $ele[$ide] ){
            $ele[$ide] += $val; 
          }
          else{
            $ele[$ide] = $val; 
          }
        }
        $_ = $ele;
      }
    }
    return $ele;
  }

  // clases
  static cla( $ele, $val, ...$opc ){
    let $_=$ele,$={};

    if( $val === undefined ){

      $_ = $val.className.split(' ');

    }// operaciones
    else{
      // elimino o cambio una clase
      if( ( $.eli = $opc.includes('eli') ) || ( $.tog = $opc.includes('tog') ) ){
        
        if( $opc.includes('ini') ){ 
          $.ver = '^'+$val; 
        }
        else if( $opc.includes('fin') ){ 
          $.ver = $val+'$'; 
        }
        else{
          $.ver = '^'+$val+'$';
        }
        $_ = [];
        // elimino
        if( !!$.eli ){
          $ele.classList.forEach( $cla => Tex.val_dec($.ver).test($cla) && $ele.classList.remove($cla) && ( $_.push($cla) ) );
        }// cambio : si la tiene, la saca; sino, la pone
        else if( !!$.tog ){
          $ele.classList.forEach( $cla => Tex.val_dec($.ver).test($cla) && $ele.classList.toggle($ope) && ( $_.push($cla) ) );
        }        
      }// agrego clase/s
      else{
        
        if( $opc.includes('ini') ){

          $ele['class'] = $val + ( $ele['class'] ? ` ${$ele['class']}` : '' );
        }
        else if( $ele['class'] ){

          $ele['class'] += ` ${$val}`; 
        }
        else{

          $ele['class'] = $val; 
        }

        $_ = $ele;
      }
    }
    return $_;
  }

  // estilos
  static css( $ele, $val, ...$opc ){
    let $_=$ele, $={};

    if( $val === undefined ){
      $_ = {};
      for( const $i in $ele.style ){ 
        $_[$i] = $ele.style[$i];
      }
    }// operaciones
    else{
      // eliminar una regla
      if( $opc.includes('eli') ){

      }// actualizar una regla
      else if( $opc.includes('mod') ){

      }// agregar 1 o muchas
      else{
        if( !($ele['style']) ){ $ele['style']=''; } 
        if( $opc.includes('ini') ){ 
          $ele['style'] += $val+( $ele['style'] ? `;${$ele['style']}` : '' ); 
        }
        else if( $ele['style'] ){ 
          $ele['style'] += `;${$val}`;
        }
        else{
          $ele['style'] = $val; 
        }
      }
      $_ = $ele;
    }
    return $_;
  }// - fondo : background
  static css_fon( $val, $ope={} ){
    if( !$ope['tip'] ) $ope['tip']='png';
    if( !$ope['ali'] ) $ope['ali']='center';
    if( !$ope['tam'] ) $ope['tam']='contain';
    if( !$ope['rep'] ) $ope['rep']='no-repeat';
    return `background: ${$ope['rep']} ${$ope['ali']} / ${$ope['tam']} url('${$val}.${$ope['tip']}');`;
  }
}