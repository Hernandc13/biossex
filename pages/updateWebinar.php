<?php

require_once '../../../config.php';
require_once($CFG->libdir.'/filelib.php');

global $USER, $DB, $CFG; //Variables globales para la obtención de datos.
// get fullname of user logged

//Declaramos las variables
$ID="";
$sql4="";
$Consulta4="";




$ID = $_REQUEST['id'];

if(empty($ID)){
    echo "No llego la información.";
}else{
    $sql4 = "SELECT * FROM mdl_webinar where id=$ID";
    $Consulta4 = $DB->get_records_sql($sql4);
}




$PAGE->set_url('/pages/updateWebinar.php');
$PAGE->set_context(context_system::instance());

require_login();

$PAGE->set_title('Webinar');
$PAGE->navbar->add('Actualizar Registro', 'updateWebinar.php');
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
    'updateRecord' => array_values($Consulta4),
    'wwwroot'=>$wwwroot
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('theme_biossex/updateWebinar',$templatecontext);

echo $OUTPUT->footer();