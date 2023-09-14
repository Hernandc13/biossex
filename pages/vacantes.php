<?php

require_once '../../../config.php';
require_once($CFG->libdir.'/filelib.php');

global $USER, $DB, $CFG; //Variables globales para la obtención de datos.
// get fullname of user logged

$nameuser = $USER->firstname." ".$USER->lastname;

// get picture of user logged
$srcpixuser = false;
if (isloggedin() && !isguestuser() && $USER->picture > 0) {
    $usercontext = context_user::instance($USER->id, IGNORE_MISSING);
    $url = moodle_url::make_pluginfile_url($usercontext->id, 'user', 'icon', null, '/', "f$1")
            . '?rev=' . $USER->picture;
} else {
    // If the user does not have a profile picture, use the default faceless picture.
    global $PAGE, $CFG;
    $renderer = $PAGE->get_renderer('core');
    if ($CFG->branch >= 33) {
        $url = $renderer->image_url('u/f$1');
    } else {
        $url = $renderer->image_url ('u/f$1'); // Deprecated as of Moodle 3.3.
    }
}
$srcpixuser = str_replace('/f%24', '/f$', $url);



$url5= $CFG->wwwroot . "/theme/biossex/pix/plancapacitacion.png";

$PAGE->set_url('/pages/vacantes.php');
$PAGE->set_context(context_system::instance());

$array=[];
require_login();

$PAGE->set_title('Vacantes');
$PAGE->navbar->add('Vacantes', 'vacantes.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading($SITE->shortname);

echo $OUTPUT->header();
 //Recorrer los registros de la base de datos
 $e=1;
 $bdimagenesVacantes=array();
 $bdVac=array();

 //Obtenemos la cantidad de vacantes que se van a registrar(countvacant)
 $sqlcountvacant = "SELECT value FROM mdl_config_plugins where name='countvacant'";
 $Consultacountvacant = $DB->get_record_sql($sqlcountvacant);
 $valuecountvacant= $Consultacountvacant->{'value'};
 while ($e <= $valuecountvacant) {
     //Se recorren los registros y se almacenan en el arreglo $bdimagenesVacantes
      $sqlImgV = "SELECT value FROM mdl_config_plugins where name='imagevacante$e'";
      $ConsultaImgV = $DB->get_record_sql($sqlImgV);
       $valueImgV= $ConsultaImgV->{'value'};
    
    //Se recorren los registros de los textos de cada imagen

    $sqlImgTextoV = "SELECT value FROM mdl_config_plugins where name='imagedescripcion$e'";
    $ConsultaImgTextoV = $DB->get_record_sql($sqlImgTextoV);
     $valueImgTextoV= $ConsultaImgTextoV->{'value'};

     $sqlImgTitle = "SELECT value FROM mdl_config_plugins where name='imagetitle$e'";
     $ConsultaTitle = $DB->get_record_sql($sqlImgTitle);
      $valueTitle= $ConsultaTitle->{'value'};

      $sqlRefiere = "SELECT value FROM mdl_config_plugins where name='refiere$e'";
     $ConsultaRefiere = $DB->get_record_sql($sqlRefiere);
      $valueRefiere= $ConsultaRefiere->{'value'};

      $sqlInterna = "SELECT value FROM mdl_config_plugins where name='interna$e'";
      $ConsultaInterna = $DB->get_record_sql($sqlInterna);

      $valueInterna= $ConsultaInterna->{'value'};
      if($valueInterna!=''){
        $visible="block";
      }else{
        $visible="none";
      }
      if($valueRefiere!=''){
        $visibleR="block";
        }else{
            $visibleR="none";
        }
      $bdimagenesVacantes=[];
      $bdimagenesVacantes[]=['url'=>"$CFG->wwwroot/pluginfile.php/1/theme_biossex/imagevacante$e/0$valueImgV",'texto'=>$valueImgTextoV,
      'title'=>$valueTitle,'refiere'=>$valueRefiere,'interna'=>$valueInterna,'visibleInterna'=>$visible,'visibleRefiere'=>$visibleR];
      $bdVac[]=$bdimagenesVacantes;
       $e++;
    }
 
 $wwwroot = $CFG->wwwroot;

 
 //Obtenemos el link del boton refiere
 $sqlRefiere = "SELECT value FROM mdl_config_plugins where name='refiere'";
 $ConsultaRef = $DB->get_record_sql($sqlRefiere);
 $Refiere= $ConsultaRef->{'value'};

  //Obtenemos el link del boton interno
  $sqlInterno = "SELECT value FROM mdl_config_plugins where name='interna'";
  $ConsultaInt = $DB->get_record_sql($sqlInterno);
  $Interno= $ConsultaInt->{'value'};

 $templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'hasdrawertoggle' => true,
    'vacantes'=>$bdVac,
    'pixuser' =>$srcpixuser,
    'wwwroot'=>$wwwroot,
    'Refiere'=>$Refiere,
    'Interna'=>$Interno
];

echo $OUTPUT->render_from_template('theme_biossex/vacantes',$templatecontext);

echo $OUTPUT->footer();