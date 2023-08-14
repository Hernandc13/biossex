<?php

require_once '../../../config.php';
require_once($CFG->libdir.'/filelib.php');

global $USER, $DB, $CFG; //Variables globales para la obtención de datos.
// get fullname of user logged

//Declaramos las variables
$sql = "";
$Consulta="";
$sql2="";
$Consulta2="";
$sql3 ="";
$Consulta3="";
$ID="";
$sql4="";
$Consulta4="";

//Consulta a base de datos PUESTOS
$sql = "SELECT id,data FROM mdl_user_info_data";
$Consulta = $DB->get_records_sql($sql);

//Consulta a base de datos CURSOS
$sql2 = "SELECT id,shortname FROM mdl_course  WHERE id != 1";
$Consulta2 = $DB->get_records_sql($sql2);

$sql3 = "SELECT * FROM mdl_capacitacion";
$Consulta3 = $DB->get_records_sql($sql3);

$ID = $_REQUEST['id'];

if(empty($ID)){
    echo "No llego la información.";
}else{
    $sql4 = "SELECT * FROM mdl_capacitacion where id=$ID";
    $Consulta4 = $DB->get_records_sql($sql4);
}


$PAGE->set_url('/pages/PlanCapacitacion.php');
$PAGE->set_context(context_system::instance());

require_login();

$PAGE->set_title('Plan de Capacitación');
$PAGE->navbar->add('Actualizar Registro', 'updateCapacitacion.php');
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
    'updateRecord' => array_values($Consulta4),
    'wwwroot'=>$wwwroot
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('theme_biossex/updateCapacitacion',$templatecontext);

echo $OUTPUT->footer();