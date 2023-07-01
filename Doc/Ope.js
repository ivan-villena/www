// WINDOW
'use strict';

class Doc_Ope {  

  /* Sección Principal */
  static sec( $ide ){
    $Doc.Ope.sec.querySelectorAll(`article[class*="ide-"]:not(.dis-ocu)`).forEach( $e => $e.classList.add("dis-ocu") );
    $Doc.Ope.sec.querySelectorAll(`article.ide-${$ide}.dis-ocu`).forEach( $e => $e.classList.remove("dis-ocu") );
    $Doc.Ope.sec.scroll(0, 0);
  }

  /* Panel */
  static pan( $ide ){

    let $ = { 'ide':"", 'cla': ['bor-sel','fon-sel'] };
    
    // oculto todo
    if( !$ide ){

      Doc.act('cla_agr',$Doc.Ope.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]`),"dis-ocu");
    }
    // muestro uno articulo/navegador
    else{
      // Enlace
      Doc.act('cla_eli',$Doc.Ope.bot.querySelectorAll(`a.bor-sel.fon-sel`),$.cla);
      if( typeof($ide) == 'string' ) $ide = $Doc.Ope.bot.querySelector(`a[data-ide="${$ide}"]`);
      Doc.act('cla_agr',$ide,$.cla);
      
      // Contenedor
      $.ide = $ide.dataset.ide;
      Doc.act('cla_agr',$Doc.Ope.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]:not( .ide-${$.ide}, .dis-ocu )`),"dis-ocu");
      Doc.act('cla_tog',$Doc.Ope.pan.querySelectorAll(`:is(nav,article).ide-${$.ide}`),"dis-ocu");
    }

    // Panel
    if( $Doc.Ope.pan.querySelector(`:is(nav,article)[class*="ide-"]:not(.dis-ocu)`) ){      
      Doc.act('cla_eli',$Doc.Ope.pan,"dis-ocu");
    }
    else{
      Doc.act('cla_eli',$Doc.Ope.bot.querySelectorAll(`a.bor-sel.fon-sel`),$.cla);
      Doc.act('cla_agr',$Doc.Ope.pan,"dis-ocu");
    }    
  }

  /* Modal */
  static win( $ide, $ope ){

    let $ = {};    
    
    // botones de cierre
    if( $ide.nodeName ){
      
      // cierro pantalla
      $.art = Doc.ver($ide,{'eti':"article"});
      
      // oculto el articulo
      $.art.classList.add("dis-ocu");      
      
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
      $.art.classList.contains("dis-ocu") && $.art.classList.remove("dis-ocu");
      
      // scroll
      $.htm.scroll(0,0);
    }

    // oculto/muestro pantalla de fondo
    if( $Doc.Ope.win.querySelector(`article[class*="ide-"]:not(.dis-ocu)`) ){

      $Doc.Ope.win.classList.contains("dis-ocu") && $Doc.Ope.win.classList.remove("dis-ocu");
    }
    else if( !$Doc.Ope.win.classList.contains("dis-ocu") ){

      $Doc.Ope.win.classList.add("dis-ocu");
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

  /* Articulo */
  static art(){    
  }// Índice
  static art_nav( $tip, $ele, $ope ){

    let $ = {};

    switch( $tip ){
    // - Toggles por item
    case 'tog':
      
      if( $ope ){

        return Doc_Ope.val($ele,$ope);

      }
      else if( $.nav = $ele ? Doc_Ope.art_nav('mar',$ele) : false ){
        
        // hago toogles ascendentes
        while(
          ( $.lis = Doc.ver($.nav,{'eti':'ul'}) ) 
          && 
          ( $.val = $.lis.previousElementSibling ) &&  $.val.classList.contains('ope_val')
          && 
          ( $.nav = $.val.querySelector('a[href^="#"]') )
        ){
          if( $.lis.classList.contains("dis-ocu") && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('val_ico') ){
            
            Doc_Ope.val($.ico);
          }
        }
      }

      break;
    // - Filtros de lista
    case 'ver':
      
      if( !$ope ) $ope = 'a[href]';

      // ejecuto filtros
      Doc_Val.lis_dep('ver', $ele, $ope);

      // volver a marcar el fondo del elemento seleccionado
      Doc_Ope.art_nav('tog',$Doc.Ope.var.nextElementSibling);

      break;
    // - seleccion
    case 'val': 

      if( !$ope ) $ope = "fon-sel";

      $.lis = Doc.ver($ele,{'eti':'nav'});

      if( $.lis ){
        // elimino marcas previas
        $.lis.querySelectorAll(`ul.lis.nav :is( li.pos.sep, li.pos:not(.sep) > .ope_val ).${$ope}`).forEach( 
          $e => $e.classList.remove($ope) 
        );

        // controlo el toggle automatico por dependencias
        if( 
          ( $.dep = $ele.parentElement.parentElement.querySelector('ul.lis') ) 
          &&
          ( $ele.classList.contains('val_ico') || $.dep.classList.contains("dis-ocu") ) 
        ){
          Doc_Ope.val($ele);
        }

        // pinto fondo
        if( !( $.bot = $ele.parentElement.querySelector('.val_ico') ) || !$.bot.classList.contains('ocu') ){

          $ele.parentElement.classList.add($ope);
        }
      }      
      break;
    // - Marcas
    case 'mar': 
      $.val = location.href.split('#')[1];

      // hago toogle por item
      if( $.val && ( $.nav = $ele.querySelector(`a[href="#${$.val}"]`) ) ){
          
        Doc_Ope.art_nav('val',$.nav);
      }

      return $.nav;
      break;
    }
  }

  /* Navegacion de Contenido : pestaña, barra, iconos, botones */
  static nav( $ele, $ope, ...$opc ){
    let $ = {};

    // capturo contenedor
    $.nav = $ele.parentElement;
    $.tip = $.nav.classList[1];

    if( $.tip == 'bot' ){
      // creo o elimino la seccion adicional
      $.her = $ele.nextElementSibling;
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
      if( $.sel_ant = $.nav.querySelector(`a.fon-sel`) ){
  
        if( !$.val_tog || $.sel_ant != $ele ) $.sel_ant.classList.remove("fon-sel");
      }
      // contenido
      if( $ope ){
        // recorro items
        Obj.pos( $.lis.children ).forEach( $e => {
          // coincide con el seleccionado
          if( $e.classList.contains(`ide-${$ope}`) ){          
            // hago toogles
            if( $.val_tog ){
              $e.classList.toggle("dis-ocu");
              $ele.classList.toggle("fon-sel");
            }
            // muestro y selecciono
            else if( $e.classList.contains("dis-ocu") ){
              $e.classList.remove("dis-ocu");
              $ele.classList.add("fon-sel");
            }
            $.tip_ver = !$e.classList.contains("dis-ocu");
  
          }// oculto los no coincidentes
          else if( !$e.classList.contains("dis-ocu") ){ 
            $e.classList.add("dis-ocu"); 
          }
        });
      }
      // oculto o muestro contenedor
      if( $.tip != 'pes' ){
        if( $.tip_ver ){
          $.lis.classList.contains("dis-ocu") && $.lis.classList.remove("dis-ocu");
        }else{
          !$.lis.classList.contains("dis-ocu") && $.lis.classList.add("dis-ocu");
        }
      }  
    }  
  }// Contenedor por botones
  static nav_bot( $ele, $ope, $val ){

    if( $ele.nodeName && typeof($ope) == 'string' ){

      // cargo cotenido por seccion visible
      if( $ele.nextElementSibling && $ele.nextElementSibling.nodeName == 'SECTION' ){
        
        // pido contenido
        Eje.val($ope, $val ? $val : [], $htm => {
          
          // cargo html
          if( $htm ) Doc.htm( $ele.nextElementSibling, $htm );
        });
      }
    }
  }

  /* Variable : form > .ope_var > label + (select,input,textarea,button)[name] */
  static var( $tip, $ele, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      
      // cargo formulario
      $Doc.Ope.var = Doc.ver($tip,{'eti':'form'});
      
      // devuelvo identificador
      if( $ele && typeof($ele) == 'object' ) $ = $ele;
      $.var_ide = $tip.getAttribute('name');
    }
    // operaciones
    else if( $tip && typeof($tip) == 'string' ){

      switch( $tip ){
      case 'mar':
        if( $ope ){
          $ele.parentElement.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e.classList.remove($ope) );
          $ele.classList.add($ope);
        }
        break;
      case 'tog':
        if( $ope ){
          $ele.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e != $ele && $e.classList.remove($ope) );
          $ele.classList.toggle($ope);
        }
        break;
      }
    }
    return $;
  }

  /* Contenedor : bloque + visible/oculto */
  static val( $ele, $ope ){
    let $ = {};
    // elementos del documento
    if( !$ope ){
      $.ite = $ele.parentElement;
      if( 
        ( $.bot = $.ite.querySelector('.val_ico.ide-ope_tog') ) 
        && ( $.sec = $.ite.nextElementSibling )
      ){        
      
        if( $.bot.classList.contains('ocu') ){
          $.bot.classList.remove('ocu');
          $.sec.classList.remove("dis-ocu");
        }
        else{
          $.bot.classList.add('ocu');
          $.sec.classList.add("dis-ocu");
        }
      }
    }
    // por opciones
    else if( ['tod','nad'].includes($ope) ){

      if( $Doc.Ope.var = Doc.ver($ele,{'eti':"form"}) ){

        $.lis = !!$Doc.Ope.var.nextElementSibling ? $Doc.Ope.var.nextElementSibling : $Doc.Ope.var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.ope_val > .val_ico.ide-ope_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }
}