<?php

require_once '../../../config.php';
require_once($CFG->libdir.'/filelib.php');

global $USER, $DB, $CFG; //Variables globales para la obtención de datos.
// get fullname of user logged

//Imagenes locales para el plan de capacitación
$url5= $CFG->wwwroot . "/theme/biossex/pix/webinar.jpg";
$circle= $CFG->wwwroot . "/theme/biossex/pix/check-circled-outline.svg";
$circleright= $CFG->wwwroot . "/theme/biossex/pix/arrow-right.svg";
$circleleft=$CFG->wwwroot . "/theme/biossex/pix/arrow-left.svg";


  $sql3 = "";
  $Consulta3 = "";
  $PRESENTADOR="";
  $TITULO = "";
  $LINK = "";
  $FECHA="";
  $ID = ""; 
  $everyone="";
  $buscar = "";
  $sqlBusqueda = "";
  $ConsultaBusqueda = "";
  $sqlBusqueda = "";
  $idW = "";
  $tituloW = "";
  $presentadorW = "";
  $linkW ="";
  $fechaW = "";
  $fechaNormal="";




  //Actualizar registro de webinar

$idW = $_REQUEST['idUpdateW'];
$tituloW = $_REQUEST['tituloUpdateW'];
$presentadorW = $_REQUEST['presentadorUpdateW'];
$linkW = $_REQUEST['linkUpdateW'];
$fechaW = $_REQUEST['txtfecha'];
$fechaNormal=$_REQUEST['fechaUpdateW'];

if ($idW != "" && $tituloW!="" && $presentadorW!="" && $linkW!="" && $fechaNormal!="") {
  $recordW = new stdClass();
  if($fechaW!=""){
    $recordW->id =$idW;
    $recordW->titulo = $tituloW;
    $recordW->presentador = $presentadorW;
    $recordW->link = $linkW;
    $recordW->fecha = $fechaW;
    $DB->update_record('webinar', $recordW);
    echo '<script language="javascript">';
    echo 'alert("Se actualizo el registro webinar.");';
    echo '</script>';
  }else{
    $recordW->id =$idW;
    $recordW->titulo = $tituloW;
    $recordW->presentador = $presentadorW;
    $recordW->link = $linkW;
    $recordW->fecha = $fechaNormal;
    $DB->update_record('webinar', $recordW);
    echo '<script language="javascript">';
    echo 'alert("Se actualizo el registro webinar.");';
    echo '</script>';
  }
       
}
  $ID = $_REQUEST['id'];
//Consulta a base de datos CAPACITACIÓN
$sql3 = "SELECT * FROM mdl_webinar";
$Consulta3 = $DB->get_records_sql($sql3);

if($_POST['txtTitulo']){
  $TITULO=$_POST['txtTitulo'];
  $PRESENTADOR=$_POST['txtPresentador'];
  $LINK=$_POST['txtlink'];
  $FECHA=$_POST['txtfecha'];
}
 if($_POST['txtbusqueda']){
  $everyone = $_POST['txtbusqueda'];
  $buscar = $_POST['txtbusqueda'];
  if (empty($everyone)) {
  }else{
    // everyone
  if(empty($everyone)){
  }else{
    $sql3 = "SELECT * FROM mdl_capacitacion";
  $everyone = $DB->get_records_sql($sql3);
    $Consulta3 = $everyone;
  }
  }
  
  
  if (empty($buscar)) {
  }else{
   //Filtrar registros en la tabla capacitación
  if(empty($buscar)){
  $sql3 = "SELECT * FROM mdl_webinar";
  $Consulta3 = $DB->get_records_sql($sql3);
  }else{
    $sqlBusqueda = "SELECT * FROM mdl_webinar where titulo like '%$buscar' or presentador like '%$buscar'";
    $ConsultaBusqueda = $DB->get_records_sql($sqlBusqueda);
    $Consulta3 = $ConsultaBusqueda;
  }
  }
  
 }

//Eliminar Registro
if(empty($ID)){
  // echo "No llego la información.";
}else{
   $table = "webinar";
   $DB->delete_records($table, array('id'=>$ID));
   $sql3 = "SELECT * FROM mdl_webinar";
   $Consulta3 = $DB->get_records_sql($sql3);
 echo '<script language="javascript">';
 echo 'alert("Se elimino el registro.");';
 echo '</script>';
}

if($TITULO!=""){
    $webinarlog = new stdClass();
    $webinarlog->id = "";
    $webinarlog->titulo = $TITULO;
    $webinarlog->presentador = $PRESENTADOR;
    $webinarlog->link = $LINK;
    $webinarlog->fecha = $FECHA;
    $DB->insert_record('webinar', $webinarlog);
    $sql3 = "SELECT * FROM mdl_webinar";
    $Consulta3 = $DB->get_records_sql($sql3);
    echo '<script language="javascript">';
    echo 'alert("Se registro el webinar.");';
    echo '</script>';
}
$wwwroot = $CFG->wwwroot;



$PAGE->set_url('/pages/webinar.php');
$PAGE->set_context(context_system::instance());

require_login();

$PAGE->set_title('Webinar');
$PAGE->navbar->add('Registro de Webinar', 'webinar.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading($SITE->shortname);


$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'hasdrawertoggle' => true,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'urlpuesto'=>$url5,
    'circle'=>$circle,
    'circleright'=>$circleright,
    'circleleft'=> $circleleft,
    'webinar'=>array_values($Consulta3),
    'wwwroot'=>$wwwroot
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('theme_biossex/webinar',$templatecontext);

echo $OUTPUT->footer();