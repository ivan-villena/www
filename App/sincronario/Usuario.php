<?php

if( !class_exists("Sincronario") ){

  require_once("./App/Sincronario.php");
}

class Usuario extends Usu {

  public int $edad;

  public object $Fec;

  public object $Kin;

  public object $Psi;

  public array $Sin;

  public function __construct(){

    parent::__construct( $_SESSION['usu'] );

    // calculo edad actual
    $this->edad = Fec::año_cue( $this->fecha );    

    // cargo valores del sincronario por fecha de nacimiento
    $_hol = Sincronario::val($this->fecha);

    $this->Fec = Fec::dat( $this->fecha );

    $this->Kin = Dat::_('hol.kin', $_hol['kin'] );

    $this->Psi = Dat::_('hol.psi', $_hol['psi'] );

    $this->Sin = explode('.',$_hol['sin']);

  }

  /* Sesion */
  public function ver_sesion() : string {
    
    $_ = "";

    // personales
    $Fec = $this->Fec;
    $Kin = $this->Kin;
    $Psi = $this->Psi;

    // transitos
    $Diario = Sincronario::val( Fec::dia() );

    // cargo transitos para el dia
    $Transito = $this->buscar_transito( $_SESSION['Fec']->val );    

    // nombre + fecha : kin + psi
    $_ = "
    <section class='ficha'>

      <div>

        <p class='tex-tit tex-3 mar_aba-1'>".Doc_Val::let("{$this->nombre} {$this->apellido}")."</p>

        <p>".Doc_Val::let($Fec->val." ( {$this->edad} años )")."</p>

      </div>
      
      <table class='transitos'>

        <thead>
          <tr>
            <th></th>
            <th>Personal</th>
            <th>Diario</th>
            <th>Combinado</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <th>Kin Planetario</th>
            <td>".Doc_Val::ima('hol',"kin",$Kin)."</td>
            <td>".Doc_Val::ima('hol',"kin",$Diario['kin'])."</td>
            <td>".Doc_Val::ima('hol',"kin",Num::ran($kin_sum = $Diario['kin']+$Kin->ide,260))."</td>
          </tr>
          <tr>
            <th>Psi-Cronos</th>
            <td>".Doc_Val::ima('hol',"psi",$Psi)."</td>
            <td>".Doc_Val::ima('hol',"psi",$Diario['psi'])."</td>
            <td>".Doc_Val::ima('hol',"kin",Num::ran($psi_sum = $Diario['psi']+$Psi->ide,260))."</td>
          </tr>
          <tr>
            <th>Combinado</th>
            <td>".Doc_Val::ima('hol',"kin", Num::ran($Kin->ide + $Psi->kin, 260))."</td>
            <td>".Doc_Val::ima('hol',"kin", Num::ran($Diario['kin'] + $Diario['psi'], 260))."</td>
            <td>".Doc_Val::ima('hol',"kin", Num::ran($kin_sum + $psi_sum, 260))."</td>
          </tr>
        </tbody>        

      </table>

    </section>";

    return $_;
  }

  /* Transitos */
  public function ver_transito( string $fec = "", array $ele = [] ) : string {

    // cargo transitos para el dia
    $Transito = $this->buscar_transito( !empty($fec) ? $fec : $_SESSION['Fec']->val );

    $_ = "
    <section>

      <table class='transitos'>

        <thead>
          <tr>
            <th></th>
            <th>Anual</th>
            <th>Lunar</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <th>Kin Planetario</th>
            <td>".Doc_Val::ima('hol',"kin",$Transito->Anual->Kin)."</td>
            <td>".Doc_Val::ima('hol',"kin",$Transito->Lunar->Ond_kin->ide)."</td>
          </tr>
          <tr>
            <th>Psi-Cronos</th>
            <td>".Doc_Val::ima('hol',"psi",$Transito->Anual->Psi)."</td>
            <td>".Doc_Val::ima('hol',"psi",$Transito->Lunar->psi)."</td>
          </tr>
          <tr>
            <th>Combinado</th>
            <td>".Doc_Val::ima('hol',"kin", Num::ran($Transito->Anual->Kin->ide + $Transito->Anual->Psi->ide, 260))."</td>
            <td>".Doc_Val::ima('hol',"kin", Num::ran($Transito->Lunar->Ond_kin->ide + $Transito->Lunar->psi, 260))."</td>
          </tr>
        </tbody>        

      </table>

    </section>";

    return $_;
  }  
  public function ver_transito_anual( string | object $fec, array $dat = [], array $ele = [] ) : string {
    
    $_ani = $dat['ani'];
    $_cas_arm = Dat::_('hol.cas_arm',$dat['ani']->arm);
    $_ani_arm = Dat::get("hol-hum_cas_arm",[ 'ver'=>"`ide`=$_ani->arm", 'opc'=>"uni"] );
    $_ani_fec = Fec::dat($_ani->fecha);      
    $_ani_ton = Dat::_('hol.ton',$dat['ani']->ton);
    $_kin = Dat::_('hol.kin',$_ani->kin);
    $_ = "
    <h3>Tránsito Anual</h3>

    <p>".Doc_Val::let("#$_ani->eda de 51: desde el $_ani_fec->val")."</p>

    ".Doc_Var::num('ran',$_ani->eda,[ 'min'=>0, 'max'=>51, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='-ite mar_ver-1'>
      ".Doc_Val::ima('hol',"cas_arm",$_cas_arm,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='tex-3'>
        <p class='tex-tit'>".Doc_Val::let($_ani_arm->nom)."</p>
        <p>$_cas_arm->nom<c>:</c> $_cas_arm->col<c>,</c> $_cas_arm->pod<c>.</c></p>
        <p>$_ani_ton->ond_nom<c>:</c> $_ani_ton->ond_pod</p>
        <p>".Doc_Var::num('ran',$_ani->ton,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."</p>
      </div>
    </div>

    ".Doc_Dat::inf('hol.kin',$_kin,['cit'=>"des",'ima'=>[]])."

    ";
    return $_;
  }
  public function ver_transito_lunar( string | object $fec, array $dat = [], array $ele = [] ) : string {
    $_lun = $dat['lun'];
    $_lun_fec = Fec::dat($_lun->fecha);
    $_lun_ton = Dat::_('hol.ton',$_lun->ide);
    $_kin = Dat::_('hol.kin',$_lun->kin);
    $_ = "
    <h3>Tránsito Lunar</h3>

    <p>".Doc_Val::let("#$_lun->ide de 13: desde el $_lun_fec->val")."</p>

    ".Doc_Var::num('ran',$_lun->ide,[ 'min'=>1, 'max'=>13, 'class'=>"anc-100", 'disabled'=>1 ],'ver')."

    <div class='-ite mar_ver-1'>
      ".Doc_Val::ima('hol',"ton",$_lun_ton,[ 'class'=>"tam-7 mar_der-2" ])."
      <div class='tex-3'>
        <p>$_lun_ton->ond_nom<c>:</c> $_lun_ton->ond_pod</p>          
      </div>
    </div>


    ".Doc_Dat::inf('hol.kin',$_kin,['cit'=>"des"])."

    ";
    return $_;
  }
  public function ver_transito_diario( string | object $fec, object $dat = NULL ) : string {

    $_ = "
    <h3>Tránsito Diario</h3>

    ";
    return $_;
  }

  public function crear_transito( string $fec = '' ) : object {
    $_ = new stdClass;

    

    return $_;
  }
  public function crear_transito_anual( string $cas, int $año, string $fec, array $Hol ) : object {

    $Transito_anual = new stdClass;

    $Transito_anual->ide = $cas;
    $Transito_anual->año = $año;
    $Transito_anual->fec = $Hol['fec'];
    $Transito_anual->sin = $Hol['sin'];    
    $Transito_anual->Kin = Dat::_('hol.kin',$Hol['kin']);
    $Transito_anual->Psi = Dat::_('hol.psi',$Hol['psi']);
    $Transito_anual->Cas = Dat::_('hol.cas',$cas);    
    $Transito_anual->Ond = Dat::_('hol.kin_nav_ond',$Transito_anual->Kin->nav_ond);    

    return $Transito_anual;
  }
  public function crear_transito_lunar( object $Ton, array $Hol, string $ond ) : object {

    $Transito_lunar = new stdClass;

    $Transito_lunar->ide = $Ton->ide; 
    $Transito_lunar->fec = $Hol['fec'];
    $Transito_lunar->sin = $Hol['sin'];
    $Transito_lunar->kin = $Hol['kin'];
    $Transito_lunar->psi = $Hol['psi'];
    $Transito_lunar->Ton = $Ton;
    $Transito_lunar->Ond_kin = Dat::_('hol.kin',$ond);

    return $Transito_lunar;
  }
  public function crear_transito_diario( string $fec = '' ) : object {
    $_ = new stdClass;

    

    return $_;
  }      

  public function buscar_transito( string $fec = '' ) : object {

    $Transito = new stdClass;

    $Transito->Anual = $this->buscar_transito_anual($fec, FALSE);

    $Transito->Lunar = $this->buscar_transito_lunar($fec, $Transito->Anual);

    $Transito->Diario = $this->buscar_transito_diario($fec, $Transito->Anual, $Transito->Lunar);
    
    return $Transito;
  }
  public function buscar_transito_anual( string $fec = '', $lun = FALSE ) : object {
    
    $Transito_anual = new stdClass;

    // fecha buscada
    $Fec = Fec::dat($fec);

    // cantidad de años entre la fecha de nacimiento y la fecha buscada ( los años del usuario )
    $año_cue = Fec::año_cue( $this->Fec, $Fec );    

    // ultimo cumpleaños para esa fecha buscada
    $fec_val = "{$this->Fec->dia}/{$this->Fec->mes}/".( $this->Fec->año + $año_cue );

    // cargo el holon para ese dia de cumpleaños $año_cue
    $Hol = Sincronario::val($fec_val);

    // posicion del castillo para ese periodo
    $cas_pos = $año_cue + 1;

    $Transito_anual->ide = $cas_pos;
    $Transito_anual->val = $fec;
    $Transito_anual->año = $año_cue;
    $Transito_anual->fec = $fec_val;
    $Transito_anual->sin = $Hol['sin'];
    $Transito_anual->Cas = Dat::_('hol.cas',$cas_pos);
    $Transito_anual->Kin = Dat::_('hol.kin',$Hol['kin']);
    $Transito_anual->Psi = Dat::_('hol.psi',$Hol['psi']);
    $Transito_anual->Ond = Dat::_('hol.kin_nav_ond',$Transito_anual->Kin->nav_ond);
    
    // cargo los 13 ciclos lunares
    if( $lun ){

      // fecha inicial: misma que el ciclo anual
      $sin_lun = $fec_val;

      // Kines de la onda encantada
      $kin_ond = $Transito_anual->Ond->kin;

      $Transito_anual->Luna = [];

      foreach( Dat::_('hol.ton') as $Ton ){

        $Hol_lun = Sincronario::val($sin_lun);

        $Transito_lunar = new stdClass;
        $Transito_lunar->ide = intval($Ton->ide); 
        $Transito_lunar->fec = $Hol_lun['fec'];
        $Transito_lunar->sin = $Hol_lun['sin'];
        $Transito_lunar->kin = $Hol_lun['kin'];
        $Transito_lunar->psi = $Hol_lun['psi'];
        $Transito_lunar->Ton = $Ton;
        $Transito_lunar->Ond_kin = Dat::_('hol.kin',$kin_ond);
                
        $Transito_anual->Luna[$Transito_lunar->ide] = $Transito_lunar;
        
        // incremento 28 dias
        $sin_lun = Fec::ope($sin_lun,28,'+','dia');

        // incremento 1 posicion de la onda encantada anual
        $kin_ond++;
      }
    }
    
    return $Transito_anual;
  }
  public function buscar_transito_lunar( string $fec = '', object $Transito_anual = NULL ) : object {
    
    $Transito_lunar = new stdClass;

    if( !isset($Transito_anual) ) $Transito_anual = $this->buscar_transito_anual($fec, FALSE);

    // Kines de la onda encantada
    $kin_ond = $Transito_anual->Ond->kin;

    // fecha inicial: misma que el ciclo anual
    $sin_lun = $Transito_anual->fec;    

    // cantidad de dias entre el inicio del ciclo y la fecha buscada
    $dia_cue = Fec::dia_cue($sin_lun, $fec);

    // ciclo lunar: cantidad de lunas desde el inicio de ciclo y la fecha buscada
    $lun_cue = intval($dia_cue / 28);

    // fecha inicial del ciclo lunar
    $lun_fec = Fec::ope($sin_lun, $lun_cue * 28, '+', 'dia');

    // Datos del dia
    $Hol_lun = Sincronario::val($lun_fec);

    $Transito_lunar->ide = $lun_cue;
    $Transito_lunar->fec = $Hol_lun['fec'];
    $Transito_lunar->sin = $Hol_lun['sin'];
    $Transito_lunar->kin = $Hol_lun['kin'];
    $Transito_lunar->psi = $Hol_lun['psi'];
    $Transito_lunar->Ton = Dat::_('hol.ton',$lun_cue);
    $Transito_lunar->Ond_kin = Dat::_('hol.kin',$kin_ond+$lun_cue-1);
    
    return $Transito_lunar;
  }  
  public function buscar_transito_diario( string $fec = '', object $Transito_anual = NULL, object $Transito_lunar = NULL ) : object {
    
    $Transito_diario = new stdClass;

    // combinacion del kin personal con el kin diario
    $Transito_diario->kin     = "";

    // cantidad de dias que pasaron desde el inicio del ciclo anual
    if( !isset($Transito_anual) ) $Transito_anual = $this->buscar_transito_anual($fec, FALSE);
    $Transito_diario->Anual   = $Transito_anual;
    $Transito_diario->ani_dia = 0;

    // cantidad de dias que pasaron desde el inicio del ciclo lunar
    if( !isset($Transito_lunar) ) $Transito_lunar = $this->buscar_transito_lunar($fec, $Transito_anual);
    $Transito_diario->Lunar   = $Transito_lunar;
    $Transito_diario->lun_dia = 0;

    return $Transito_diario;
  }

  public function listar_transito( array $ope = [], ...$opc ) : string {

    foreach(['nav','lis','dep','opc'] as $eti ){ if( !isset($ope["$eti"]) ) $ope["$eti"] = []; }

    // listado
    $_lis = [];
    foreach( Dat::get("hol-hum_cas_arm") as $_arm ){
      
      $_lis_cic = [];
      
      foreach( Dat::get('sis-usu_cic_ani',[ 'ver'=>"`usu` = '{$this->key}' AND `arm`=$_arm->ide", 'ord'=>"`ide` ASC" ]) as $_cic ){

        // ciclos lunares
        $_lis_lun = [];
        foreach( Dat::get('sis-usu_cic_lun',[ 'ver'=>"`usu` = '{$this->key}' AND `ani`=$_cic->ide", 'ord'=>"`ide` ASC" ]) as $_lun ){                            
          $_fec = Fec::dat($_lun->fecha);
          $_lun_ton = Dat::_('hol.ton',$_lun->ide);
          $_kin = Dat::_('hol.kin',$_lun->kin);
          $_nav = "<a href='http://localhost/sincronario/tablero/tzolkin/sin=$_lun->sin' target='_blank' title='Ver en Tableros...'>".Doc_Val::let($_lun->sin)."</a>";
          $_lis_lun []= 
          "<div class='-ite'>
            ".Doc_Val::ima('hol',"kin",$_kin,['class'=>"tam-6 mar_der-1"])."
            <p>
              ".Doc_Val::let(intval($_lun_ton->ide)."° ciclo, ").$_nav.Doc_Val::let(" ( $_fec->val ).")."
              <br>".Doc_Val::let("$_lun_ton->ond_nom: $_lun_ton->ond_man")."
            </p>              
          </div>
          <p class='mar-1 tex_ali-cen'>".Sincronario::dat_des('kin',$_kin)."</p>";
        }

        // ciclo anual
        $_fec = Fec::dat($_cic->fecha);
        $_cas = Dat::_('hol.cas',$_cic->ide);
        $_cas_ton = Dat::_('hol.ton',$_cic->ton);
        $_cas_arm = Dat::_('hol.cas_arm',$_cic->arm);            
        $_kin = Dat::_('hol.kin',$_cic->kin);            
        $_lis_cic []= [
          'ite'=>[ 'eti'=>"div", 'class'=>"lis", 'htm'=> 
            "<div class='-ite'>
              ".Doc_Val::ima('hol',"kin",$_kin,['class'=>"tam-6 mar_der-1"])."
              <p title = '$_cas->des'>
                ".Doc_Val::let("$_cic->eda año".( $_cic->eda != 1 ? 's' : '' ).", $_cic->sin ( $_fec->val ):")."
                <br>".Doc_Val::let("Cuadrante $_cas_arm->col d{$_cas_arm->dir}: $_cas_arm->pod")."
                <br>".Doc_Val::let("$_cas_ton->ond_nom: $_cas_ton->ond_man")."                
              </p>
            </div>
            <p class='mar-1 tex_ali-cen'>".Sincronario::dat_des('kin',$_kin)."</p>"
          ],
          'lis'=>$_lis_lun
        ];
      }
      $_lis []= [ 'ite'=>$_arm->nom, 'lis'=>$_lis_cic ];
    }

    // configuro listado
    Ele::cla($ope['dep'],DIS_OCU);

    $ope['opc'] = [ 'tog', 'ver', 'cue', 'tog-dep' ];

    return Doc_Ope::lis('dep',$_lis,$ope);
  }
  public function listar_transito_anual() : string {
    $_ = "";

    return $_;
  }
  public function listar_transito_lunar( $transito_anual ) : string {
    $_ = "";

    return $_;
  }

}