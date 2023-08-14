<?php

require_once '../../../config.php';
require_once($CFG->libdir.'/filelib.php');
require_once(__DIR__ . '/../lib.php');

global $USER, $DB, $CFG; //Variables globales para la obtención de datos.
// get fullname of user logged

//Imagenes locales para el plan de capacitación
$url5= $CFG->wwwroot . "/theme/biossex/pix/white-flag.svg";
$circle= $CFG->wwwroot . "/theme/biossex/pix/check-circled-outline.svg";
$circleright= $CFG->wwwroot . "/theme/biossex/pix/arrow-right.svg";
$circleleft=$CFG->wwwroot . "/theme/biossex/pix/arrow-left.svg";


//Declaramos las variables para recibir la información del post
$PUESTO = "";
$CURSO1 = "";
$CURSO2 = "";
$CURSO3 = "";
$CURSO4 = "";
$CURSO5 = "";
$CURSO6 = "";
$CURSO7 = "";
$CURSO8 = "";
$CURSO9 = "";
$CURSO10 = "";
$CURSO11 = "";
$CURSO12 = "";
$CURSO13= "";
$CURSO14 = "";
$CURSO15 = "";
$$CURSOSemana = "";
$PUESTOSemana = "";
$ID = "";
$sqlExist = "";
$ConsultaExist = "";
$GENERAL = "";
$SEMANA = "";
$count = "";
$valor = "";
$idU = "";
$puestoU = "";
$semanaU = "";
$cursoU = "";
$everyone = "";
$sql3 = "";
$Consulta3 = "";
$buscar = "";
$sqlBusqueda = "";
$ConsultaBusqueda = "";
$sql = "";
$Consulta = "";
$sql2 = "";
$Consulta2 = "";
$sql3 = "";
$Consulta3 = "";

//Consulta a base de datos PUESTOS
$sql = "SELECT id,data FROM mdl_user_info_data";
$Consulta = $DB->get_records_sql($sql);

//Consulta a base de datos CURSOS
$sql2 = "SELECT id,shortname FROM mdl_course  WHERE id != 1";
$Consulta2 = $DB->get_records_sql($sql2);

//Consulta a base de datos CAPACITACIÓN
$sql3 = "SELECT * FROM mdl_capacitacion";
$Consulta3 = $DB->get_records_sql($sql3);


//Obtenemos la información del método post
$PUESTO=$_REQUEST['puestoselect'];
$CURSO1=$_REQUEST['CURSO1'];
$CURSO2= $_REQUEST['CURSO2'];
$CURSO3= $_REQUEST['CURSO3'];
$CURSO4= $_REQUEST['CURSO4'];
$CURSO5= $_REQUEST['CURSO5'];
$CURSO6=$_REQUEST['CURSO6'];
$CURSO7=$_REQUEST['CURSO7'];
$CURSO8=$_REQUEST['CURSO8'];
$CURSO9=$_REQUEST['CURSO9'];
$CURSO10=$_REQUEST['CURSO10'];
$CURSO11=$_REQUEST['CURSO11'];
$CURSO12=$_REQUEST['CURSO12'];
$CURSO13=$_REQUEST['CURSO13'];
$CURSO14=$_REQUEST['CURSO14'];
$CURSO15=$_REQUEST['CURSO15'];



$CURSOSemana=$_REQUEST['CURSOSemana'];
$PUESTOSemana=$_REQUEST['PUESTOSemana'];
$ID = $_REQUEST['id'];
//Inicializamos arreglo stdClass para insertar a la tabla base de datos.
$planlog = new stdClass();

//Verificamos la variable post puesto y se verifica que no se encuentre,
//información ya registrada de ese puesto.

if($PUESTO!=""){
  $sqlExist = "SELECT * FROM mdl_capacitacion where tipo_puesto like '%$PUESTO'";
  $ConsultaExist = $DB->get_records_sql($sqlExist);
  if(count($ConsultaExist)>0){
    //Si se encuentra algun registro se manda un mensaje de alerta.
    echo '<script language="javascript">';
    echo 'alert("Ya esta registrado un plan de capacitación,selecione un puesto diferente.");';
    echo '</script>';
  }else{
    //En caso de no encontrar información con ese puesto se registra la información.
    if($CURSO1!="" or $CURSO2!="" or $CURSO3!="" or $CURSO4!="" or $CURSO5!="" or $CURSO6!="" or $CURSO7!="" or $CURSO8!="" or
    $CURSO9!="" or $CURSO10!="" or $CURSO11!="" or $CURSO12!="" or $CURSO13!="" or $CURSO14!="" or $CURSO15!=""){
    $i = 1;
      while ($i <= 15) {
        $GENERAL ='CURSO'.$i;
        if ($$GENERAL!= "") {
          $SEMANA = "Semana " . $i;
          theme_biossex_insert_data_capacitacion($$GENERAL, $PUESTO, $SEMANA);
        }
      $i++;
      }
     echo '<script language="javascript">';
     echo 'alert("Se registro el plan de capacitación.");';
     echo '</script>';
    }else{
         
     echo '<script language="javascript">';
     echo 'alert("No se pudo registrar, ingrese toda la información solicitada.");';
     echo '</script>';
    }
  }
}

//Agregar semana extra al plan de capacitación ya existente.
if($CURSOSemana!=""){
  $sqlExist = "SELECT * FROM mdl_capacitacion where tipo_puesto like '%$PUESTOSemana'";
  $ConsultaExist = $DB->get_records_sql($sqlExist);
if(count($ConsultaExist)>0){
  for ($i=1; $i <= count($ConsultaExist); $i++) { 
    if($i==count($ConsultaExist)){
        $count = count($ConsultaExist);
        $count=$count+1;
      foreach ($CURSOSemana as $val) {
        $valor .= $val . ","; //para almacenarla
       }
      $valor=substr($valor,0,-1);//para eliminar la ultima coma.
      $planlog->id = "";
       $planlog->tipo_puesto = $PUESTOSemana;
       $planlog->semanas = "Semana ".$count;
       $planlog->cursoid = $valor;
       if(count($ConsultaExist)<=14){
        $DB->insert_record('capacitacion', $planlog);
        echo '<script language="javascript">';
        echo 'alert("Se agrego una nueva semana al plan de capacitación del puesto,'.$PUESTOSemana.'.");';
        echo '</script>';
        $valor = "";
       }else{
        echo '<script language="javascript">';
        echo 'alert("Excedió la cantidad máxima de semanas.");';
        echo '</script>';
       }
    }
  }
}else{
  echo '<script language="javascript">';
  echo 'alert("No se encontro información, inicie un plan de capacitación para ese puesto.");';
  echo '</script>';
}
}
//Eliminar Registro
if(empty($ID)){
   // echo "No llego la información.";
}else{
    $table = "capacitacion";
    $DB->delete_records($table, array('id'=>$ID));
  echo '<script language="javascript">';
  echo 'alert("Se elimino el registro.");';
  echo '</script>';
}

//Actualizar registro

$idU = $_REQUEST['idUpdate'];
$puestoU = $_REQUEST['puestoUpdate'];
$semanaU = $_REQUEST['semanaUpdate'];
$cursoU = $_REQUEST['cursoUpdate'];

if ($cursoU != "") {
    foreach ($cursoU as $val) {
        $valor .= $val . ","; //para almacenarla
       }
       $valor=substr($valor,0,-1);//para eliminar la ultima coma.
       $recordUpdate = new stdClass();
      // echo "update:".$valor;
        // Update the path.
        $recordUpdate->id =$idU;
    $recordUpdate->tipo_puesto = $puestoU;
    $recordUpdate->semanas = $semanaU;
    $recordUpdate->cursoid = $valor;
        $DB->update_record('capacitacion', $recordUpdate);
        echo '<script language="javascript">';
        echo 'alert("Se actualizo el registro.");';
        echo '</script>';
       $valor = "";
}



// everyone
$everyone = $_POST['txtbusqueda'];
if(empty($everyone)){
}else{
  $sql3 = "SELECT * FROM mdl_capacitacion";
$everyone = $DB->get_records_sql($sql3);
  $Consulta3 = $everyone;
}

//Filtrar registros en la tabla capacitación
$buscar = $_POST['txtbusqueda'];
if(empty($buscar)){
$sql3 = "SELECT * FROM mdl_capacitacion";
$Consulta3 = $DB->get_records_sql($sql3);
}else{
  $sqlBusqueda = "SELECT * FROM mdl_capacitacion where tipo_puesto like '%$buscar' or id like '%$buscar'";
  $ConsultaBusqueda = $DB->get_records_sql($sqlBusqueda);
  $Consulta3 = $ConsultaBusqueda;
}


$PAGE->set_url('/pages/PlanCapacitacion.php');
$PAGE->set_context(context_system::instance());

require_login();

$PAGE->set_title('Plan de Capacitación');
$PAGE->navbar->add('Plan de Capacitación', 'PlanCapacitacion.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading($SITE->shortname);

$wwwroot = $CFG->wwwroot;
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'hasdrawertoggle' => true,
    'navdraweropen' => $navdraweropen,
    'draweropenright' => $draweropenright,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'alertcontent' => $alertcontent,
    'puestos'=>array_values($Consulta),
    'cursos'=> array_values($Consulta2),
    'capacitacion'=> array_values($Consulta3),
    'urlpuesto'=>$url5,
    'circle'=>$circle,
    'circleright'=>$circleright,
    'circleleft'=> $circleleft,
    'wwwroot'=>$wwwroot
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('theme_biossex/capacitacion',$templatecontext);

echo $OUTPUT->footer();