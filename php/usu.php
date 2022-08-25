<?php

  // propiedades 
  class _usu {

    public int $ide;

    private string $pas;

    public string $nom;

    public string $ape;

    public string $fec;

    public string $ses_mai;

    public string $ses_tel;

    public string $ses_ubi;    

    public function __construct( string $ide ){

      if( !empty( $_dat = _dat::var("_api.usu", [ 'ver'=>"`usu`='{$ide}'", 'opc'=>['uni'] ]) ) ){      

        // cargo atributos de la base
        foreach( $_dat as $atr => $val ){

          $this->$atr = $val;
        }

        // cargo holon
        $this->hol = _hol::ver($this->fec);
        $this->hol['kin'] = _hol::_('kin',$this->hol['kin']);
        $this->hol['psi'] = _hol::_('psi',$this->hol['psi']);
      }
    }

    public function ses_ini(){
    }

    public function ses_fin(){
    }
  }

  // datos y preferencias de la cuenta
  class _usu_dat {

    // formularios
    static function val( string $tip = NULL, mixed $ope = NULL ) : string {
      global $_usu;
      $_ = "";     
      $esq = 'api'; 
      $est = 'usu';
      if( !isset($tip) ){

        if( !empty($_usu->ide) ){
          
          $_kin = $_usu->hol['kin'];
          $_psi = $_usu->hol['psi'];
          
          $win = [
            'ico' => "ses_usu",
            'nom' => "Cuenta de Usuario",
            'htm' => "
            
            <form class='api_dat' data-ope='{$tip}' data-esq='{$esq}' data-est='{$est}'>

              <fieldset class='ren'>
    
                "._doc::var('atr', [$esq,$est,$atr='nom'], [ 'val'=>$_usu->$atr  ], 'eti')."
    
                "._doc::var('atr', [$esq,$est,$atr='ape'], [ 'val'=>$_usu->$atr  ], 'eti')."                        
              
              </fieldset>
    
              <fieldset class='ren'>
    
                "._doc::var('atr', [$esq,$est,$atr='usu'], [ 'val'=>$_usu->$atr  ],'eti')."
    
                "._doc::var('atr', [$esq,$est,$atr='fec'], [ 'val'=>$_usu->$atr, 'ite'=>[ 'class'=>"tam-ini" ]  ], 'eti')."            
    
                <div class='ite'>
    
                  "._doc::ima('hol','kin',$_kin,['class'=>"mar_hor-1"])."

                  <c class='sep'>+</c>
    
                  "._doc::ima('hol','psi',$_psi,['class'=>"mar_hor-1"])."
    
                  <c class='sep'>=</c>
    
                  "._doc::ima('hol','kin',$_kin->ide + $_psi->tzo,['class'=>"mar_hor-1"])."          
    
                </div>
    
              </fieldset>
    
              <fieldset class='ren ".DIS_OCU."'>
    
                "._doc::var('atr', [$esq,$est,$atr='ses_mai'], [ 'val'=>$_usu->$atr  ], 'eti')."
    
                "._doc::var('atr', [$esq,$est,$atr='ses_tel'], [ 'val'=>$_usu->$atr  ], 'eti')."
    
                "._doc::var('atr', [$esq,$est,$atr='ses_ubi'], [ 'val'=>$_usu->$atr  ], 'eti')."
              
              </fieldset>
    
            </form>
            "
          ];
    
          echo _doc_art::win('usu_dat',$win);
        }
      }
      else{
        switch( $tip ){
        case '': break;
        }
      }
      return $_;      
    }
  }

  // agenda
  class _usu_age {

    // datos de la base
    static function dat( string $est, string $tip, object $dat ){    
      global $_usu; $esq = 'usu';
      $_ = [];
      $_est = explode('_',$est);
      $dat->usu = $this->ide;
      // elimino relaciones
      if( $tip == 'eli' ){

        if( in_array($_est[1],['per','gru']) ){
          $_["_{$esq}.age_gru_per"] = [ 'ver'=>"`usu`='{$dat->usu}' AND `{$_est[1]}`='{$dat->ide}'" ];
        }
      }
      // actualizo kines
      else{
        foreach( ['fec','ini','fin'] as $atr ){

          if( !empty($dat->$atr) ){
            
            $dat->{"{$atr}_kin"} = $this->_dat->hol['kin'];
            $dat->{"{$atr}_sin"} = $this->_dat->hol['sin'];
          }
        }
      }
      // agregar o modificiar
      $ver = "`usu`='{$dat->usu}'".( !empty($dat->ide) ? " AND `ide`='{$dat->ide}'" : "" );
      
      // elimino identificadores 
      if( isset($dat->ide) ) unset($dat->ide);
      
      // preparo consulta principal
      $_["_{$esq}.{$est}"] = [ 
        'val'=>$dat, 
        'ver'=>$ver 
      ];

      return $_;
    }
    
    // navegador
    static function nav( string $est, array $ele=[], ...$opc ){
      global $_usu; $esq = 'usu';

      $_nom = explode('_',$est);
      $_atr = _dat::atr($esq,$est);
      $_est = _dat::est($esq,$est);
      
      $_url = "{$esq}/".str_replace('_','/',$est);
      $_rec = SYS_NAV.$_url."/";    

      $_ = "
      <!-- listado -->
      <nav ide='{$esq}.{$est}'>
            
        <h2>{$_est->nom}</h2>

        <!-- abm -->

        "._doc_val::dat('nav',[ 'url'=>$_url ])."

        <!-- items -->
        <table class='lis mar-1' style='border-collapse: separate; border-spacing: 0 5px;'>

          <tbody>";
          // datos      
          $ver = "`usu`='{$_usu->usu}'";
          // agenda      
          $val_age = ( $_nom[0]=='age' );
          $val_fec = FALSE;
          // orden
          if( $val_age ){
            $val_fec = in_array($_nom[1],['per','gru','not']);
            if( $val_fec ){
              if( in_array($_nom[1],['gru']) ){
                $ord = ["`fec` ASC"];
              }else{
                $ord = ["`nom` ASC"]; if( $_nom[1] == 'per' ) $ord []= "`ape` ASC";
              }        
            }else{
              $ord = ["`ini` ASC"];
            }
          }
          // valores
          $_val = _dat::var("_{$esq}.{$est}", [ 'ver'=>$ver, 'ord'=>!empty($ord) ? $ord : NULL ]);    
                    
          $atr_ide = '';
          if( $val_age ){ 
            $atr_ide = !!$val_fec ? 'fec_kin' : 'ini_kin'; 
          }
          foreach( $_val as $pos => $_dat ){
            $val_ide = isset($_dat->ide) ? $_dat->ide : $pos;
            $dat_ide = "_dat-nav _{$esq}-{$est}-{$val_ide}-";
            $dat_atr = '';// valores de filtro
            $kin_fic = !empty($_dat->$atr_ide) ? _doc::ima('hol','kin',$_dat->$atr_ide,['eti'=>"label",'for'=>$dat_ide]) : '';
            $_ .= "
            <tr data-ide='{$val_ide}'>
              <td ope='opc'>
                <input id='{$dat_ide}' type='checkbox' title='Seleccionar...'>
              </td>
              <td ope='kin'>
                {$kin_fic}
              </td>
              <td ope='url' style='width:100%'>";
                if( $val_age ){
                  if( $val_fec ){ 
                    $htm = "{$_dat->nom}".( !empty($_dat->ape) ? " $_dat->ape" : "" );
                  }else{
                    $htm = ( !empty($_dat->ini) ? _doc::let( _fec::val($_dat->ini).( !empty($_dat->ini_hor) ? " a las {$_dat->ini_hor}" : '' ) )."<br>" : '' ).( !empty($_dat->nom) ? _doc::let($_dat->nom) : '' );
                  }
                }$_ .= "
                <a href='{$_rec}{$_dat->ide}'".( !empty($_dat->des) ? " title='{$_dat->des}'" : '' ).">{$htm}</a>
              </td>
            </tr>";
          }
          $_ .= "
          </tbody>

        </table>

      </nav>";
      return $_;
    }
    
    // formularios
    static function val( string $esq, string $est, string $tip, $ide=0, ...$opc ){
      global $_usu;

      $_atr = _dat::atr($esq,$est);    

      $_ = "
      <form class='api_dat' data-ope='{$tip}' data-esq='{$esq}' data-est='{$est}' >
      
        "._doc_val::dat('val',[ 'tip'=>$tip ])."

        "._doc::var('atr', [$esq,$est,'ide'], [ 'val'=>!empty($ide) ? $ide : NULL, 'ite'=>['class'=>'dis-ocu'] ]);            
        $_dat = !empty($ide) ? _("_{$esq}.{$est}",[ 'ver'=>"`usu`='{$_usu->usu}' AND `ide` = {$ide}", 'opc'=>['uni'] ]) : new stdClass();
        $_dat->usu = $_usu->usu;
        $_est = explode('_',$est);
        switch( $_est[0] ){
        case 'age':
          switch( $_est[1] ){
          // Diario
          case 'dia':
            $rel_lis = [ "Familiar", "Laboral", "Estudio", "Social", "Hobbies" ];
            $_rep = [ ''=>"-", 'dia'=>"Diariamente", 'sem'=>"Semanalmente", 'mes'=>"Mensualmente", 'año'=>"Anualmente" ];            
            $_ .= "

            <fieldset class='ite'>

              "._doc::var('atr', [$esq,$est,'ini'], [ 'val'=>isset($_dat->ini) ? $_dat->ini : NULL ] )."

              "._doc::var('atr', [$esq,$est,'ini_hor'], [ 'val'=>isset($_dat->ini_hor) ? $_dat->ini_hor : NULL  ])."

            </fieldset>          

            <fieldset class='ite'>

              "._doc::var('atr', [$esq,$est,'fin'], [ 'val'=>isset($_dat->fin) ? $_dat->fin : NULL  ])."

              "._doc::var('atr', [$esq,$est,'fin_hor'], [ 'val'=>isset($_dat->fin_hor) ? $_dat->fin_hor : NULL  ])."
            
            </fieldset>

            "._doc::var('atr', [$esq,$est,'nom'], [ 'val'=>isset($_dat->nom) ? $_dat->nom : NULL  ])."

            "._doc::var('atr', [$esq,$est,'tip'], [ 'val'=>isset($_dat->tip) ? $_dat->tip : NULL, ['val'=>[ 'lis'=>$rel_lis ] ]  ])."

            "._doc::var('atr', [$esq,$est,'des'], [ 'val'=>isset($_dat->des) ? $_dat->des : NULL  ])."
            ";
            break;
          // Notas
          case 'not':
            $rel_lis = [ "Cocina", "Jardin", "Estudio" ];
            $_ .= "

            <fieldset class='ite'>
              "._doc::var('atr', [$esq,$est,'fec'], [ 'val'=>isset($_dat->fec) ? $_dat->fec : NULL  ])."
              ".(!empty($_dat->fec_kin) ? _doc::ima('hol','kin',$_dat->fec_kin,['class'=>'mar_hor-2']) : '')."
            </fieldset>

            "._doc::var('atr', [$esq,$est,'tip'], [ 'val'=>isset($_dat->tip) ? $_dat->tip : NULL, ['val'=>[ 'lis'=>$rel_lis ] ]  ])."

            "._doc::var('atr', [$esq,$est,'nom'], [ 'val'=>isset($_dat->nom) ? $_dat->nom : NULL  ])."

            "._doc::var('atr', [$esq,$est,'des'], [ 'val'=>isset($_dat->des) ? $_dat->des : NULL  ])."

            ";
            if( !empty($ide) ){
              // busco dependencias
              $not_des = _dat::var("_usu.age_not_det",['ver'=>"`not`='{$ide}'"]);
              $_.="

              <h2>Detalles</h2>

              "._doc_val::dat('est')."
              
              <table>
                <tbody>";
                foreach( $not_des as $_dat ){ $_ .= "
                  <tr data-ide='{$_dat->ide}'>
                    <td atr='ide'><input type='checkbox'></td>
                    <td atr='fec'>{$_dat->fec}</td>
                    <td atr='tex'>{$_dat->tex}</td>
                  </tr>";                
                }$_.="
                </tbody>
              </table>";
            }
            break;
          // contactos
          case 'per':
            $rel_lis = [ 'Familia', 'Trabajo', 'Estudio', 'Salidas', 'Hobbies' ];
            $_ .= "

            <fieldset class='ite'>

              "._doc::var('atr', [$esq,$est,'nom'], [ 'val'=>isset($_dat->nom) ? $_dat->nom : NULL  ])."
              
              "._doc::var('atr', [$esq,$est,'ape'], [ 'val'=>isset($_dat->ape) ? $_dat->ape : NULL  ])."
              
            </fieldset>

            <fieldset class='ite'>
              "._doc::var('atr', [$esq,$est,'fec'], [ 'val'=>isset($_dat->fec) ? $_dat->fec : NULL  ])."
              ".(!empty($_dat->fec_kin) ? _doc::ima('hol','kin',$_dat->fec_kin,['class'=>'mar_hor-2']) : '')."
            </fieldset>

            "._doc::var('atr', [$esq,$est,'rel'], [ 'val'=>isset($_dat->rel) ? $_dat->rel : NULL, ['val'=>[ 'lis'=>$rel_lis ] ]  ])."

            <fieldset class='ite'>

              <div class='ite'>            
                "._doc::var('atr', [$esq,$est,'ini'], [ 'val'=>isset($_dat->ini) ? $_dat->ini : NULL  ])."
                ".(!empty($_dat->ini_kin) ? _doc::ima('hol','kin',$_dat->ini_kin,['class'=>'mar_hor-2']) : '')."
              </div>

              <div class='ite'>            
                "._doc::var('atr', [$esq,$est,'fin'], [ 'val'=>isset($_dat->fin) ? $_dat->fin : NULL  ])."
                ".(!empty($_dat->fin_kin) ? _doc::ima('hol','kin',$_dat->fin_kin,['class'=>'mar_hor-2']) : '')."
              </div>

            </fieldset>

            "._doc::var('atr', [$esq,$est,'des'], [ 'val'=>isset($_dat->des) ? $_dat->des : NULL  ])."
            
            ";
            if( !empty($ide) ){

            }
            break;

          // grupos
          case 'gru':
            $_lis = [ 'Familiar', 'Laboral', 'Estudio', 'Social', 'Hobbies' ];
            $_ .= "          
            <fieldset class='ite'>

              "._doc::var('atr', [$esq,$est,'fec'], [ 'val'=>isset($_dat->fec) ? $_dat->fec : NULL  ])."
              
              "._doc::var('atr', [$esq,$est,'fin'], [ 'val'=>isset($_dat->fin) ? $_dat->fin : NULL  ])."

            </fieldset>

            "._doc::var('atr', [$esq,$est,'nom'], [ 'val'=>isset($_dat->nom) ? $_dat->nom : NULL  ])."

            "._doc::var('atr', [$esq,$est,'tip'], [ 'val'=>isset($_dat->tip) ? $_dat->tip : NULL, ['val'=>[ 'lis'=>$_lis ] ]  ])."

            "._doc::var('atr', [$esq,$est,'des'], [ 'val'=>isset($_dat->des) ? $_dat->des : NULL  ])."

            ";
            // listado de integrantes

            break;
          }
          break;
        }
        $_ .= "                
      </form>";
      return $_;
    }  
  }