// WINDOW
'use strict';

// Dato : esq.est[ide].atr
class api_dat {
 
  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = api_app.est('dat',$ide,'dat');

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

    return $_;
  }
  
  // Variable : form > .dov_var > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $dom.dat.var = api_ele.val_ver($dat,{'eti':'form'});
      $.var_ide = $dat.getAttribute('name');
    }
    else{
      switch( $tip ){
      case 'mar':
        if( $ope ){
          $dat.parentElement.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e.classList.remove($ope) );
          $dat.classList.add($ope);
        }
        break;
      case 'tog':
        if( $ope ){
          $dat.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e != $dat && $e.classList.remove($ope) );
          $dat.classList.toggle($ope);
        }
        break;
      }
    }
    return $;
  }// toggles por : form > fieldsets > ...
  static var_tog( $ide ){
    api_lis.val_cod( api_ele.val_ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }  

  // Valor: p[tit, nom, des] + ima 
  static val( $tip, $ide, $dat, ...$opc ){

    let $_ = "", $ = {};
    // proceso estructura
    $ = api_app.est_ide($ide,$);
    // cargo datos
    $._dat = api_app.dat($.esq,$.est,$dat);
    // cargo valores
    $._val = api_app.est($.esq,$.est,'val');
    
    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      $_ = api_obj.val($._dat,$._val.nom) + ( $._val.des ? "\n"+api_obj.val($._dat,$._val.des) : '' );
    }
    else if( !!($._val[$tip]) ){
      $.tip_val = typeof($._val[$tip]);
      if( $tip == 'ima' ){
        if( $.tip_val != 'string' ) $tip = 'tab';
      }
      else if( $.tip_val == 'string' ){
        $_ = api_obj.val($._dat,$._val[$tip]);  
      }
    }

    // armo ficha
    if( $tip == 'ima' ){
      
      // identificador
      $.ele = !!$opc[0] ? $opc[0] : {};      
      $.ele['data-esq'] = $.esq;
      $.ele['data-est'] = $.est;
      
      // 1 o muchos...
      $_ = "";
      $.ima_ele = $.ele;
      $.ima_lis = ( typeof($dat) == 'string' ? ( $dat.split( /, /.test($dat) ? ", " : " - " )  ) : [ $dat ] );
      $.ima_lis.forEach( $dat_val => {

        $._dat = api_app.dat($.esq,$.est,$dat_val);

        $.ima_ele['data-ide'] = $._dat.ide;
        
        // titulos
        if( $.ima_ele.title === undefined ){

          $.ima_ele.title = api_dat.val('tit',`${$.esq}.${$.est}`,$._dat);
        }
        else if( $.ima_ele.title === false ){
          delete($.ima_ele.title);
        }
        // acceso informe
        if( $.ima_ele.onclick === undefined ){
          if( api_app.est($.esq,$.est,'inf') ) $.ima_ele.onclick = `dat.inf('${$.esq}','${$.est}',${parseInt($._dat.ide)})`;
        }
        else if( $.ima_ele.onclick === false ){

          delete($.ima_ele.onclick);
        }
        // informe      
        $_ += api_fig.ima( { 'style': api_obj.val($._dat,$._val[$tip]) }, $.ima_ele );
      })
    }
    // pido tablero por identificador
    else if( $tip == 'tab' ){
      $.par = $_val['ima'];
      $.ele_ima = $.ele;
    }
    // por contenido
    else if( !!$opc[0] ){
      if( !($opc[0]['eti']) ) $opc[0]['eti'] = 'p'; 
      $opc[0]['htm'] = api_tex.let($_);
      $_ = api_ele.val($opc[0]);
    }

    return $_;
  }// opciones : esquema.estructura.atributos.valores
  static val_opc( $tip, $dat, $ope, ...$opc ){
    let $_="", $ = api_dat.var($dat);
    // vacio valores y atributos
    $.ini = ( $ide = ["val"] ) => {
      $ide.forEach( $i => { 
        if( $.ope = $dom.dat.var.querySelector(`[name="${$i}"]`) ) api_ele.val_eli( $.ope, `option:not([value=""])` ); 
      });
    };
    switch( $tip ){
    case 'esq':
      $.ini();
      break;
    case 'est':
      $.ini();
      $.val = $dat.value.split('.');
      if( $.ope = $dat.nextElementSibling.nextElementSibling ){
        $.ope.value = "";
        api_ele.act('cla_agr', $.ope.querySelectorAll(`[data-esq][data-est]:not(.${DIS_OCU})`), DIS_OCU );
        if( $.val[1] ){
          api_ele.act('cla_eli', $.ope.querySelectorAll(`[data-esq="${$.val[0]}"][data-est="${$.val[1]}"]`), DIS_OCU );
        }
      }
      break; 
    case 'atr':
      $.ini();
      // elimino selector
      if( $.opc = $dat.parentElement.querySelector('select[name="val"]') ){
        api_ele.val_eli($.opc,'option:not([value=""])');        
        $.opc.dataset.esq = '';
        $.opc.dataset.est = '';
  
        if( $dat.value ){
          $.dat = $dat.options[$dat.selectedIndex];        
          // identificadores
          $ = api_app.est_ide( $.dat.dataset.ide ? $.dat.dataset.ide : $.dat.value, $ );
          $.opc.dataset.esq = $.esq;
          $.opc.dataset.est = $.est;
          api_eje.val(['api_app::dat', [`${$.esq}_${$.est}`] ], $dat => {
            $.opc = api_opc.lis( $dat, $.opc, 'ide');
          });
        }
      }
      break;
    }    
    return $_;
  }// identificador por seleccion : imagen, color...
  static val_ide( $tip, $esq, $est, $atr, $dat ){
      
    let $={}, 
    // armo identificadores
    $_ = { 'esq': $esq, 'est': $est, 'atr':$atr, 'ide':"", 'val':null };

    if( !!($atr) ) $_['est'] = ( $atr == 'ide' ) ? $est : `${$est}_${$atr}`;

    $_['ide'] = `${$_['esq']}.${$_['est']}`;

    // busco estructura relacionada por atributo
    $.esq = $_.esq;
    $.est = $_.est;
    if( $.dat_atr = api_app.est_atr_dat($esq,$est,$atr) ){
      $.esq = $.dat_atr.esq;
      $.est = $.dat_atr.est;
    }
    
    if( !!( $.dat_val = api_app.est($.esq,$.est,`val.${$tip}`,$dat) ) ){
      $_['val'] = $.dat_val;
    }

    return $_;    
  }// imagen por identificadores
  static val_ima( $dat, $ope ){

    let $ = {}, $_ = "";

    if( $dat.dataset && $ope.esq && $ope.est && $ope.atr && ( $ope.val = $dat.dataset[`${$ope.esq}_${$ope.est}`] ) ){
      
      // por atributo
      if( $.dat_atr = api_app.est_atr_dat($ope.esq,$ope.est,$ope.atr) ){
        if( !$ope.fic ) $ope.fic = {};
        $ope.fic.esq = $.dat_atr.esq;
        $ope.fic.est = $.dat_atr.est;
      }// por seleccion
      else if( !$ope.fic ){
        $ope.fic = api_dat.opc('ima', $ope.esq, $ope.est );
      }

      $_ = api_fig.ima($ope.fic.esq, $ope.fic.est, api_app.dat($ope.esq,$ope.est,$ope.val)[$ope.atr], $ope.ele);
    }
    return $_;
  }
  
  /* Ficha : imagenes por valor ( relaciones por estructura ) 
  */
  static fic( $dat, $ope, ...$opc ){
    let $_="", $ = api_dat.var($dat);
    $.dat = {};

    // actualizo valores principales
    $dat.querySelectorAll(`.dat_var`).forEach( $ite =>{
      
      $.atr = $ite.querySelector('[name]').getAttribute('name');
      $.num = $ite.querySelector('[max]');
      $.num_max = $.num.getAttribute('max');
      $.dat[`${$.atr}`] = ( $ope > 0 ) ? api_num.val_ran($ope, $.num_max) : 0;
      $.num.innerHTML = $.dat[`${$.atr}`];
    });    

    // actualizo fichas : principal => { ...dependencias } 
    $dat.querySelectorAll(`.dat_var [data-esq][data-est][data-atr][data-ima]`).forEach( $ite => {

      $.esq = $ite.dataset.esq;
      $.est = $ite.dataset.est;
      $.atr = $ite.dataset.atr;
      $.ima = $ite.dataset.ima.split('.');
      // actualizo fichas
      api_ele.val_eli($ite,'.fig_ima');
      if( $.val = $.dat[$.est] ){
        api_ele.val_agr( api_fig.ima( $.ima[0], $.ima[1], api_app.dat($.esq,$.est,$.val)[$.atr], {'class':`tam-4`} ), $ite );
      }
    });   
    
    return $_;
  }

  /* Informe : 
  */
  static inf( $esq, $est, $val ){
    // pido ficha
    api_eje.val([ `api_dat::inf`, [ $esq, $est, $val ] ], $htm => {
      // muestro en ventana
      if( $htm ) api_doc.win('doc_ope',{ 
        ico: "", 
        cab: "", 
        htm: $htm, 
        opc: [ "pos" ] 
      });
    });
  }

  // alta, baja, modificacion por tabla-informe
  static abm( $tip, $dat, $ope, ...$opc ){
    let $ = api_dat.var($dat);
    switch( $tip ){
    // cargo valores
    case 'var':
      $._val = {};
      $dom.dat.var.querySelectorAll(`[id][name]`).forEach( $atr => {          
        $._val[ $atr.name ] = $.atr.value;
      });      
    // inicializo valores
    case 'ope':
      $dom.dat.var.querySelectorAll(`.dat_var > :is(select,input,textarea).fon-roj`).forEach( $e => $e.classList.remove('fon-roj') );
      $dom.dat.var.querySelectorAll(`.dat_var > ul.col-roj`).forEach( $e => $e.parentElement.removeChild($e) );
      break;
    // proceso errores
    case 'err':
      $._val = {};
      $._err = {};

      this.abm('ope', $dat);

      $dom.dat.var.querySelectorAll(`[id][name]`).forEach( $atr => {
        
        $.ide=$atr.name;

        $.tip = '';
        if( $atr.type ){
          if( /^date/.test($atr.type) ){
            $.tip = 'fec';
          }else if( $atr.type=='text' ){
            $.tip = 'tex';
          }else if( $atr.type=='number' ){
            $.tip = 'num';
          }
        }

        $._err[$.ide] = [];
        $._val[$.ide] = $.val = $atr.value;
        
        // obligatorio ( not null )
        if( !$.val ){          
          if( $atr.required ){
            $._err[$.ide].push(`Es Obligatorio...`);
          }// formateo nulos
          else{
            switch( $.tip ){
            case 'fec': $._val[$.ide] = null; break;
            case 'num': $._val[$.ide] = null; break;
            }
          }
        }
        // máximos ( longitud[tex] - valor[num-fec] )
        if( $.max = $atr.getAttribute('max') ){
          switch( $.tip ){
          case 'fec': 
            if( !$.val ){
              // $._err[$.ide].push(`No puede ser anterior al ${$.min}...`);
            }break;
          case 'num':
            if( $.val < parseInt($.min) ){
              $._err[$.ide].push(`No puede ser menor a ${$.min}...`);
            }break;
          case 'tex':
            if( $.val.length < parseInt($.min) ){
              $._err[$.ide].push(`No puede tener menos de ${$.min} caractéres...`);
            }break;
          }
        }
        // mínimos ( valores[num-fec] )
        if( $.max = $atr.getAttribute('max') ){
          switch( $.tip ){
          case 'fec': 
            if( !$.val ){
              // $._err[$.ide].push(`No puede ser posterior que el ${$.max}...`);
            }break;
          case 'num': 
            if( $.val > parseInt($.max) ){
              $._err[$.ide].push(`No puede ser mayor que ${$.max}...`);
            }break;
          case 'tex': 
            if( $.val.length > parseInt($.max) ){
              $._err[$.ide].push(`No puede tener más de ${$.max} caractéres...`);
            }break;
          }
        }
        // proceso errores
        if( $._err[$.ide].length ){
          // pinto fondo
          if( !$atr.classList.contains('fon-roj') ){ 
            $atr.classList.add('fon-roj'); 
          }
          // cargo lista
          $._tex = `
          <ul class='col-roj'>`; 
            $._err[$.ide].forEach( $e => $._tex += `
            <li>${_tex.let($e)}</li>`
            ); $._tex += `
          </ul>`;
          api_ele.val_agr( $._tex, $_atr );
        }

      });
      break;                        
    // reinicio formulario
    case 'fin':
      this.abm('ope', $dat );
      $dom.dat.var.reset();
      break;  
    // proceso ABM : ini - agr - mod - eli
    default:
      $.tip_eli = ( $tip == 'eli' );
      // cargo datos
      if( $.tip_eli ){
        if( !confirm('¿Confirmar Eliminación?') ){ return $; }
        $ = this.abm('var',$dom.dat.var);
      }else{
        $ = this.abm('err', $dat);
      }        
      // ejecuto proceso
      if( $.tip_eli || ( $._val && !$._tex ) ){        
        // actualizo datos
        if( ( $.esq = $dom.dat.var.dataset.esq ) && ( $.est = $dom.dat.var.dataset.est ) ){
          api_eje.val(['dat::abm', [ $.esq, $.est, $tip, $._val ] ], $e => {
            if( !$e._err ){
              // reiniciar formulario
              this.abm('fin',$dat);
              $dom.dat.var.reset();              
              // reiniciar página
              window.location.href = ( $.tip_eli ) ? window.location.href.split('/').slice(0,-1).join('/') : window.location.href;
            }
          });
        }// proceso propio
        else{

        }
      }   
      break;    
    }
    return $;
  }
}