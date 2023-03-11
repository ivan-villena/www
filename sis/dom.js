'use strict';

class sis_dom {

  /* Elementos por operaciones */

  // Pagina
  doc = {
    bot: 'body > .doc_bot',
    win: 'body > .doc_win',
    pan: 'body > .doc_pan',
    sec: 'body > .doc_sec',
    bar: 'body > .doc_bar',
    pie: 'body > .doc_pie'
  };

  // Datos de Estructura: Operador + Listado + tablero
  dat = {
    var: null
    ,
    ope: {
      // acumulados
      acu : [ "pos", "mar", "ver", "opc" ],
      // filtros
      ver : {
        dat : `form.ide-dat select[name="val"]`,
        fec : `form.ide-fec input[name="ini"]`,
        pos : `form.ide-pos input[name="ini"]`
      }    
    },
    lis: {
      // Valores
      val : `.ide-lis .dat.lis`,
      // Operador
      val_acu : `.ide-lis .ide-ver .ide-acu`,      
      val_sum : `.ide-lis .ide-ver .ide-sum`,
      val_cue : `.ide-lis .ide-ver .ide-cue`,    
      // filtros
      ver : `.ide-lis .ide-ver`,
      // Descripciones
      des : `.ide-lis .ide-des`    
    },
    tab: {
      ide : null,
      dep : null,
      cla : null,
      // Valores
      val : `main > article > .dat.tab`,
      // operador
      val_acu : `.doc_pan > .ide-val .ide-acu`,
      val_sum : `.doc_pan > .ide-val .ide-sum`,
      val_cue : `.doc_pan > .ide-val .ide-cue`,
      // Seleccion
      ver : `.doc_pan > .ide-ver`,
      // seccion + posicion
      sec : `.doc_pan > .ide-opc .ide-sec`,    
      pos : `.doc_pan > .ide-opc .ide-pos`,
      // ...opciones
      opc : `.doc_pan > .ide-opc .ide-opc`
    }
  };

  constructor(){

    // Cargo Elementos del Documento
    for( const $atr in this.doc ){
      this.doc[$atr] = document.querySelector(this.doc[$atr]);
    }
    
    // Cargo Elementos de Datos
    ['lis','tab'].forEach( $ope => {

      for( const $ide in this.dat[$ope] ){ 

        if( typeof(this.dat[$ope][$ide]) == 'string' ){

          this.dat[$ope][$ide] = document.querySelector(this.dat[$ope][$ide]); 
        }
      }
    });
    // - Actualizo clase principal
    this.dat.tab.cla = ".pos.ope";
  }

}