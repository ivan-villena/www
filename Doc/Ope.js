// WINDOW
'use strict';

class Doc_Ope {  

  // Sección Principal
  static sec( $ide ){
    $Doc.Ope.sec.querySelectorAll(`article[class*="ide-"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
    $Doc.Ope.sec.querySelectorAll(`article.ide-${$ide}.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
    $Doc.Ope.sec.scroll(0, 0);
  }

  // Panel
  static pan( $ide ){

    let $ = { 'ide':"", 'cla': ['bor-sel','fon-sel'] };
    
    // oculto todo
    if( !$ide ){

      Doc.act('cla_agr',$Doc.Ope.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]`),DIS_OCU);
    }
    // muestro uno articulo/navegador
    else{
      // Enlace
      Doc.act('cla_eli',$Doc.Ope.bot.querySelectorAll(`a.bor-sel.fon-sel`),$.cla);
      if( typeof($ide) == 'string' ) $ide = $Doc.Ope.bot.querySelector(`a[data-ide="${$ide}"]`);
      Doc.act('cla_agr',$ide,$.cla);
      
      // Contenedor
      $.ide = $ide.dataset.ide;
      Doc.act('cla_agr',$Doc.Ope.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]:not( .ide-${$.ide}, .${DIS_OCU} )`),DIS_OCU);
      Doc.act('cla_tog',$Doc.Ope.pan.querySelectorAll(`:is(nav,article).ide-${$.ide}`),DIS_OCU);
    }

    // Panel
    if( $Doc.Ope.pan.querySelector(`:is(nav,article)[class*="ide-"]:not(.${DIS_OCU})`) ){      
      Doc.act('cla_eli',$Doc.Ope.pan,DIS_OCU);
    }
    else{
      Doc.act('cla_eli',$Doc.Ope.bot.querySelectorAll(`a.bor-sel.fon-sel`),$.cla);
      Doc.act('cla_agr',$Doc.Ope.pan,DIS_OCU);
    }    
  }

  // Modal
  static win( $ide, $ope ){

    let $ = {};    
    
    // botones de cierre
    if( $ide.nodeName ){
      
      // cierro pantalla
      $.art = Doc.ver($ide,{'eti':"article"});
      
      // oculto el articulo
      $.art.classList.add(DIS_OCU);      
      
      // si es una pantalla por posicion, elimino el articulo
      if( $.art.dataset.pos ){
        
        // ir al previo: elimino el articulo
        if( $ide.dataset.ope == 'pre' ){
          
          $.art.parentElement.removeChild($.art);

        }// cierre de todos
        else{
          // elimino articulos con el mismo identificador por posicoin
          $.art.parentElement.querySelectorAll(`article.${$.art.classList[0]}[data-pos]`).forEach( $art => {

            $art.parentElement.removeChild($art);
          });
        }
      }
    }
    // genero articulos
    else{

      $.art = typeof($ide) == 'string' ?  $Doc.Ope.win.querySelector(`article.ide-${$ide}`) : $ide;

      $.htm = $.art.querySelector(`div:nth-child(2)`);
      
      // actualizo contenido
      if( !!$ope ){
        
        $.opc = $ope.opc ? Obj.pos_ite($ope.opc) : [];

        // si es el operador: creo nueva ventana con mismo identificador
        if( $ide == 'ope' ){
          
          // copio el articulo principal y busco su posicion
          $Doc.Ope.win.appendChild( $.art = $.art.cloneNode(true) );
          
          $.art.dataset.pos = $Doc.Ope.win.querySelectorAll(`article[data-pos]`).length + 1;
          
          // agrego icono : retroceder
          if( $.art.dataset.pos > 1 ){

            $.ope = $.art.querySelector('header:first-child > .ope_bot');

            $.ope.insertBefore( Doc.val( Doc_Val.ico('ope_nav-izq',{ 
              'title': "Volver a la pantalla anterior", 
              'data-ope':"pre", 
              'onclick':"Doc_Ope.win(this)" 
            })), 
              $.ope.querySelector('.val_ico.ide-dat_fin') 
            );
          }

          $.htm = $.art.querySelector(`div:nth-child(2)`);
        }

        // icono
        if( $.ico = $.art.querySelector(`header:first-child > .val_ico:first-of-type`) ){

          $.ico.innerHTML = '';

          if( $ope.ico !== undefined ){

            if( typeof($ope.ico) == 'string' ) $ope.ico = Doc_Val.ico($ope.ico,{ class:'mar_hor-1' });

            Doc.mod($ope.ico,$.ico);
          }
        }

        // titulo
        if( $.tit = $.art.querySelector(`header:first-child > h2`) ){

          $.tit.innerHTML = ( $ope.cab !== undefined ) ? Doc_Val.let($ope.cab) : '';
        }

        // Actualizo contenido
        Doc.eli($.htm);
        Doc.htm($.htm,$ope.htm);
      }

      // muestro por valor
      $.art.classList.contains(DIS_OCU) && $.art.classList.remove(DIS_OCU);
      
      // scroll
      $.htm.scroll(0,0);
    }

    // oculto/muestro pantalla de fondo
    if( $Doc.Ope.win.querySelector(`article[class*="ide-"]:not(.${DIS_OCU})`) ){

      $Doc.Ope.win.classList.contains(DIS_OCU) && $Doc.Ope.win.classList.remove(DIS_OCU);
    }
    else if( !$Doc.Ope.win.classList.contains(DIS_OCU) ){

      $Doc.Ope.win.classList.add(DIS_OCU);
    }    
  }// opciones: texto + botones( si / no )
  static win_opc( $tex, $eje, $bot, $ope = {} ){

    if( !$bot ){
      
      $bot = [
        { 'htm':"No", 'onclick':"Doc_Ope.win(this);" },
        { 'htm':"Si", 'onclick':`${ !!$eje ? `${$eje}; ` : "" }Doc_Ope.win(this);` }
      ];
    }
    else{

      $bot = Obj.pos_ite($bot);
    }

    $ope.htm = `
    <form class='mar-aut'>
    
      ${ typeof($tex) == 'string' ? `<p>${Doc_Val.let($tex)}</p>` : Ele.val($tex) }

      <fieldset class='ope_bot dir-ver'>`;

        $bot.forEach( button => {

          if( !button.eti ) button.eti = "button";
          if( !button.type ) button.type = "button";

          $ope.htm += Ele.val(button);
        });

      $ope.htm += `
      </fieldset>

    </form>`;

    Doc_Ope.win('ope', $ope);
  }

  // Navegacion de Contenido : pestaña-barra-iconos-botones-indice
  static nav( $dat, $ope, ...$opc ){
    let $ = {};

    // capturo contenedor
    $.nav = $dat.parentElement;
    $.tip = $.nav.classList[1];

    if( $.tip == 'bot' ){
      // creo o elimino la seccion adicional
      $.her = $dat.nextElementSibling;
      if( !$.her || $.her.nodeName == 'BUTTON' ){
        // creo una seccion a continuacion para el contenido a generar
        Doc.agr(document.createElement('section'),$.nav,$.her);
      }
      else{
        // elimino la seccion del contenido
        Doc.eli($.nav,$.her);
      }
    }
    else{
      $.tip_ver = false;
      // capturo contenido
      $.lis = $.nav.nextElementSibling;
      // con toggles
      $.val_tog = $opc.includes('tog');
      // elimino fondo por seleccion anterior
      if( $.sel_ant = $.nav.querySelector(`a.${FON_SEL}`) ){
  
        if( !$.val_tog || $.sel_ant != $dat ) $.sel_ant.classList.remove(FON_SEL);
      }
      // contenido
      if( $ope ){
        // recorro items
        Obj.pos( $.lis.children ).forEach( $e => {
          // coincide con el seleccionado
          if( $e.classList.contains(`ide-${$ope}`) ){          
            // hago toogles
            if( $.val_tog ){
              $e.classList.toggle(DIS_OCU);
              $dat.classList.toggle(FON_SEL);
            }
            // muestro y selecciono
            else if( $e.classList.contains(DIS_OCU) ){
              $e.classList.remove(DIS_OCU);
              $dat.classList.add(FON_SEL);
            }
            $.tip_ver = !$e.classList.contains(DIS_OCU);
  
          }// oculto los no coincidentes
          else if( !$e.classList.contains(DIS_OCU) ){ 
            $e.classList.add(DIS_OCU); 
          }
        });
      }
      // oculto o muestro contenedor
      if( $.tip != 'pes' ){
        if( $.tip_ver ){
          $.lis.classList.contains(DIS_OCU) && $.lis.classList.remove(DIS_OCU);
        }else{
          !$.lis.classList.contains(DIS_OCU) && $.lis.classList.add(DIS_OCU);
        }
      }  
    }  
  }/* Contenedor por botones */
  static nav_bot( $dat, $ope, $val ){

    if( $dat.nodeName && typeof($ope) == 'string' ){

      // cargo cotenido por seccion visible
      if( $dat.nextElementSibling && $dat.nextElementSibling.nodeName == 'SECTION' ){
        
        // pido contenido
        Eje.val($ope, $val ? $val : [], $htm => {
          
          // cargo html
          if( $htm ) Doc.htm( $dat.nextElementSibling, $htm );
        });
      }
    }
  }

  /* Variable : form > .ope_var > label + (select,input,textarea,button)[name] */
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $Doc.Ope.var = Doc.ver($dat,{'eti':'form'});
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
  }

  /* Contenedor : bloque + visible/oculto */
  static val( $dat, $ope ){
    let $ = {};
    // elementos del documento
    if( !$ope ){
      $.ite = $dat.parentElement;
      if( 
        ( $.bot = $.ite.querySelector('.val_ico.ide-ope_tog') ) 
        && ( $.sec = $.ite.nextElementSibling )
      ){        
      
        if( $.bot.classList.contains('ocu') ){
          $.bot.classList.remove('ocu');
          $.sec.classList.remove(DIS_OCU);
        }
        else{
          $.bot.classList.add('ocu');
          $.sec.classList.add(DIS_OCU);
        }
      }
    }
    // por opciones
    else if( ['tod','nad'].includes($ope) ){

      if( $Doc.Ope.var = Doc.ver($dat,{'eti':"form"}) ){

        $.lis = !!$Doc.Ope.var.nextElementSibling ? $Doc.Ope.var.nextElementSibling : $Doc.Ope.var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.ope_val > .val_ico.ide-ope_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }

  /* Listados : contenido, posicion, desplazamiento */
  static lis( $tip, $dat, $ope, $val ){
    let $ = {};

    switch( $tip ){
    // punteos
    case 'pos': 
      $ = Doc_Ope.var($dat);
      // toggles
      if( $dat.nodeName == 'DT' ){

        $.dd = $dat.nextElementSibling;

        while( $.dd && $.dd.nodeName == 'DD' ){
          $.dd.classList.toggle(DIS_OCU);
          $.dd = $.dd.nextElementSibling;
        }
      }
      break;
    // desplazamiento horizontal
    case 'bar': 
      $ = Doc_Ope.var($dat);

      if( $ope == 'val' ){

        $.lis = $Doc.Ope.var.previousElementSibling;

        $.val = $Doc.Ope.var.querySelector('[name="val"]');
        $.pos = Num.val($.val.value);

        switch( $dat.getAttribute('name') ){
        case 'ini': $.pos = Num.val($.val.min);
          break;
        case 'pre': $.pos = $.pos > Num.val($.val.min) ? $.pos-1 : $.pos;
          break;
        case 'pos': $.pos = $.pos < Num.val($.val.max) ? $.pos+1 : $.pos;
          break;
        case 'fin': $.pos = Num.val($.val.max);
          break;
        }
        // valido y muestro item
        $.val.value = $.pos;

        Doc.act('cla_agr',$.lis.querySelectorAll(`li.pos:not(.${DIS_OCU})`),DIS_OCU);

        if( $.ite = $.lis.querySelector(`li.ide-${$.pos}`) ) $.ite.classList.remove(DIS_OCU);
      }
      break;
    }

    return $;
  }// contenido
  static lis_dep( $tip, $dat, $ope, $val ){

    let $ = {};

    // - Toggles
    if( $tip == 'tog' ){

      Doc_Ope.val($dat,$ope);
    }
    // Filtros
    else if( $tip == 'ver' ){

      if( !$ope ) $ope = 'p:first-of-type';
      if( !$val ) $val = 'tex-luz';

      $ = Doc_Ope.var($dat);

      // busco listado
      if( $Doc.Ope.var ){

        $.lis = !! $Doc.Ope.var.nextElementSibling ? $Doc.Ope.var.nextElementSibling : $Doc.Ope.var.parentElement;

        if( $.lis.nodeName == 'LI' ){
          $.lis = $.lis.parentElement;
          $.val_dep = true;
        }
      }
  
      // ejecuto filtros
      if( $.lis && ( $.val = $Doc.Ope.var.querySelector(`[name="val"]`) ) ){

        // elimino marcas anteriores
        if( $val ) $.lis.querySelectorAll(`li.pos ${$ope}.${$val}`).forEach( $ite => $ite.classList.remove($val) );        

        // operador de comparacion
        $.ope = $Doc.Ope.var.querySelector(`[name="ope"]`);
        $.ope = $.ope ? $.ope.value : "**";
  
        // 1- muestro u oculto por coincidencias
        $.lis.querySelectorAll(`li.pos ${$ope}`).forEach( $ite => {
          
          // capturo item : li > [.val] (p / a)
          $.ite = Doc.ver($ite,{'eti':'li'});
          
          // ejecuto comparacion por elemento selector ( p / a )
          if( !($.tex = $.val.value.trim()) || Dat.ver($ite.innerText.trim(), $.ope, $.tex) ){
            
            // oculto/mustro item
            $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);
            
            // agrego brillo
            if( !!$val && !!$.tex ) $ite.classList.add($val);
          }
          else{
            
            $.ite.classList.add(DIS_OCU);
          }
        });
        
        // 2- por cada item mostrado, muestro ascendentes
        $.tot = 0;
        if( $.val.value ){
          
          $.lis.querySelectorAll(`li.pos:not(.${DIS_OCU}) ${$ope}`).forEach( $ite => {
            
            $.tot ++;
            
            // subo desde el listado
            $.val = $ite.parentElement.parentElement;
            
            while( ( $.ite = $.val.parentElement.parentElement ) && $.ite.nodeName == 'LI' && $.ite.classList.contains('pos') ){

              $.ite.classList.contains(DIS_OCU) && $.ite.classList.remove(DIS_OCU);

              $.val = $.ite;
            }
          });
        }
        
        // actualizo toggle: muestro todos
        if( $.val_ico = $Doc.Ope.var.querySelector(`.val_ico.ide-ope_tog-tod`) ){

          Doc_Ope.val($.val_ico,'tod');
        }
        
        // actualizo total
        if( $.tot_val = $Doc.Ope.var.querySelector(`[name="tot"]`) ) $.tot_val.innerHTML = $.tot;

      }        
    }

  }// posicion: punteos y terminos
  static lis_pos( $tip, $dat, $ope, $val ){

    let $ = {};

    // - Toggles
    if( $tip == 'tog' ){

      $ = Doc_Ope.var($dat);

      if( !$dat || !$ope ){
        Doc.act('cla_tog',$.lis.children,DIS_OCU); 
      }
      else{
        Obj.pos($.lis.children).forEach( $ite => {
  
          if( $ite.nodeName == 'DT' && !$ite.classList.contains(DIS_OCU) ){
  
            if( $ite.nextElementSibling ){
              if( 
                ( $ope == 'tod' &&  $ite.nextElementSibling.classList.contains(DIS_OCU) )
                ||
                ( $ope == 'nad' &&  !$ite.nextElementSibling.classList.contains(DIS_OCU) )
              ){
                Doc_Ope.lis('pos',$ite);
              }
            }
          }
        } );
      }
    }

    // - Filtro
    else if( $tip == 'ver' ){

      $ = Doc_Ope.var($dat);
    
      // filtro por valor textual        
      if( !$ope ){
        $.lis = $Doc.Ope.var.nextElementSibling;
        // muestro por coincidencias
        if( $.val = $Doc.Ope.var.querySelector('[name="val"]').value ){
          // oculto todos
          Doc.act('cla_agr',$.lis.children,DIS_OCU); 
  
          $.ope = $Doc.Ope.var.querySelector('[name="ope"]').value;
          
          if( $.lis.nodeName == 'DL' ){
            $.lis.querySelectorAll(`dt`).forEach( $e => {
              // valido coincidencia
              $.ope_val = Dat.ver($e.innerHTML,$.ope,$.val) ? $e.classList.remove(DIS_OCU) : $e.classList.add(DIS_OCU);
              $.dd = $e.nextElementSibling;
              while( $.dd && $.dd.nodeName == 'DD' ){
                $.ope_val ? $.dd.classList.remove(DIS_OCU) : $.dd.classList.add(DIS_OCU);
                $.dd = $.dd.nextElementSibling;
              }
            });
          }
          else{
            Obj.pos($.lis.children).forEach( $e => 
              Dat.ver($e.innerHTML,$.ope,$.val) && $e.classList.remove(DIS_OCU) 
            );
          }
        }
        else{
          Doc.act('cla_eli',$.lis.children,DIS_OCU);
        }
      }
      // operadores
      else{
        switch( $ope ){
        case 'tod': Doc.act('cla_eli',$.lis.children,DIS_OCU); break;
        case 'nad': Doc.act('cla_agr',$.lis.children,DIS_OCU); break;
        }
      }
  
      // actualizo cuenta
      if( $.tot = $Doc.Ope.var.querySelector('[name="tot"]') ){
        if( $.lis.nodeName == 'DL' ){
          $.tot.innerHTML = Obj.pos($.lis.children).filter( $ite => $ite.nodeName=='DT' && !$ite.classList.contains(DIS_OCU) ).length;
        }else{
          $.tot.innerHTML = Obj.pos($.lis.children).filter( $ite => !$ite.classList.contains(DIS_OCU) ).length;
        }
      }  
    }
  }
}