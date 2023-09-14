<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme functions.
 *
 * @package    theme_biossex
 * @copyright 2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


use core_completion\progress;


/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_biossex_get_extra_scss($theme) {
    $scss = $theme->settings->scss;

    $scss .= theme_biossex_set_headerimg($theme);

    $scss .= theme_biossex_set_topfooterimg($theme);

    $scss .= theme_biossex_set_loginbgimg($theme);

    return $scss;
}

/**
 * Adds the cover to CSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_biossex_set_headerimg($theme) {
    global $OUTPUT;

    $headerimg = $theme->setting_file_url('headerimg', 'headerimg');

    if (is_null($headerimg)) {
        $headerimg = $OUTPUT->image_url('maqueta', 'theme');
    }

    $headercss = "#page-site-index.notloggedin #page-header {background-image: url('$headerimg');}";

    return $headercss;
}

/**
 * Adds the footer image to CSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_biossex_set_topfooterimg($theme) {
    global $OUTPUT;

    $topfooterimg = $theme->setting_file_url('topfooterimg', 'topfooterimg');

    if (is_null($topfooterimg)) {
        $topfooterimg = $OUTPUT->image_url('footer-bg', 'theme');
    }

    $headercss = "#top-footer {background-image: url('$topfooterimg');}";

    return $headercss;
}

/**
 * Adds the login page background image to CSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_biossex_set_loginbgimg($theme) {
    global $OUTPUT;

    $loginbgimg = $theme->setting_file_url('loginbgimg', 'loginbgimg');

    if (is_null($loginbgimg)) {
        $loginbgimg = $OUTPUT->image_url('maqueta', 'theme');
    }

    $headercss = "#page-login-index.biossex-login #page-wrapper #page {background-image: url('$loginbgimg');}";

    return $headercss;
}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_biossex_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_biossex', 'preset', 0, '/', $filename))) {
        // This preset file was fetched from the file area for theme_biossex and not theme_boost (see the line above).
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    // biossex scss.
    $biossexvariables = file_get_contents($CFG->dirroot . '/theme/biossex/scss/biossex/_variables.scss');
    $biossex = file_get_contents($CFG->dirroot . '/theme/biossex/scss/biossex.scss');

    // Combine them together.
    $allscss = $biossexvariables . "\n" . $scss . "\n" . $biossex;

    return $allscss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_biossex_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['brand-primary'],
        'navbarheadercolor' => 'navbar-header-color',
        'navbarbg' => 'navbar-bg',
        'navbarbghover' => 'navbar-bg-hover'
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return mixed
 */
function theme_biossex_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    $theme = theme_config::load('biossex');

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'logo') {
        return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
    }
    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'vacantimg') {
        return $theme->setting_file_serve('vacantimg', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'imagenportadaslider') {
        return $theme->setting_file_serve('imagenportadaslider', $args, $forcedownload, $options);
    }
    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'imagenwebinar') {
        return $theme->setting_file_serve('imagenwebinar', $args, $forcedownload, $options);
    }
    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'birthday') {
        return $theme->setting_file_serve('birthday', $args, $forcedownload, $options);
    }
    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'headerimg') {
        return $theme->setting_file_serve('headerimg', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing1icon') {
        return $theme->setting_file_serve('marketing1icon', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing2icon') {
        return $theme->setting_file_serve('marketing2icon', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing3icon') {
        return $theme->setting_file_serve('marketing3icon', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'marketing4icon') {
        return $theme->setting_file_serve('marketing4icon', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'topfooterimg') {
        return $theme->setting_file_serve('topfooterimg', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'loginbgimg') {
        return $theme->setting_file_serve('loginbgimg', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'favicon') {
        return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and preg_match("/^sliderimage[1-9][0-9]?$/", $filearea) !== false) {
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and preg_match("/^sponsorsimage[1-9][0-9]?$/", $filearea) !== false) {
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM and preg_match("/^clientsimage[1-9][0-9]?$/", $filearea) !== false) {
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }
}

/**
 * Get theme setting
 *
 * @param string $setting
 * @param bool $format
 * @return string
 */
function theme_biossex_get_setting($setting, $format = false) {
    $theme = theme_config::load('biossex');

    if (empty($theme->settings->$setting)) {
        return false;
    }

    if (!$format) {
        return $theme->settings->$setting;
    }

    if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    }

    if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
    }

    return format_string($theme->settings->$setting);
}


/**
 * Extend the biossex navigation
 *
 * @param flat_navigation $flatnav
 */
function theme_biossex_extend_flat_navigation(\flat_navigation $flatnav) {
    theme_biossex_add_certificatesmenuitem($flatnav);

    theme_biossex_delete_menuitems($flatnav);

    theme_biossex_add_coursesections_to_navigation($flatnav);

    theme_biossex_rename_menuitems($flatnav);
}

/**
 * Add items to flat navigation menu
 *
 * @param flat_navigation $flatnav
 *
 */
function theme_biossex_add_certificatesmenuitem(\flat_navigation $flatnav) {
    global $COURSE;

    try {
        if (!theme_biossex_has_certificates_plugin()) {
            return;
        }

        $actionurl = new \moodle_url('/theme/biossex/certificates.php');

        // Course page.
        if ($COURSE->id > 1) {
            $parentitem = $flatnav->find('competencies', \navigation_node::TYPE_SETTING);

            if (!$parentitem || !$parentitem instanceof \flat_navigation_node) {
                $parentitem = $flatnav->find('home', \navigation_node::TYPE_SETTING);
            }

            $actionurl = new \moodle_url('/theme/biossex/certificates.php', ['id' => $COURSE->id]);
        }

        if ($COURSE->id == 1) {
            $parentitem = $flatnav->find('privatefiles', \navigation_node::TYPE_SETTING);

            if (!$parentitem || (!empty($parentitem) && empty($parentitem->id))) {
                return;
            }
        }

        if (!is_null($parentitem->parent)) {
            $certificatesitemoptions = [
                'action' => $actionurl,
                'text' => get_string('certificates', 'theme_biossex'),
                'shorttext' => get_string('certificates', 'theme_biossex'),
                'icon' => new pix_icon('i/export', ''),
                'type' => \navigation_node::TYPE_SETTING,
                'key' => 'certificates',
                'parent' => $parentitem->parent
            ];

            $certificatesitem = new \flat_navigation_node($certificatesitemoptions, 0);

            $flatnav->add($certificatesitem, $parentitem->key);
        }
    } catch (\coding_exception $e) {
        debugging($e->getMessage(), DEBUG_DEVELOPER, $e->getTrace());
    } catch (\moodle_exception $e) {
        debugging($e->getMessage(), DEBUG_NORMAL, $e->getTrace());
    }
}

/**
 * Remove items from navigation
 *
 * @param flat_navigation $flatnav
 */
function theme_biossex_delete_menuitems(\flat_navigation $flatnav) {

    $itemstodelete = [
        'coursehome'
    ];

    foreach ($flatnav as $item) {
        if (in_array($item->key, $itemstodelete)) {
            $flatnav->remove($item->key);

            continue;
        }

        if (isset($item->parent->key) && $item->parent->key == 'mycourses' &&
            isset($item->type) && $item->type == \navigation_node::TYPE_COURSE) {

            $flatnav->remove($item->key, \navigation_node::TYPE_COURSE);
        }

        if ($item->key === 'mycourses') {
            foreach ($item->children as $key => $child) {
                if (!theme_biossex_is_course_available_to_display_in_navbar($child->key)) {
                    $item->children->remove($child->key);
                }
            }
        }
    }
}

/**
 * Verify if a course can be displayed in the navbar
 *
 * @param int $courseid
 *
 * @return bool
 */
function theme_biossex_is_course_available_to_display_in_navbar($courseid) {
    global $DB, $USER;

    $course = $DB->get_record('course', ['id' => $courseid], '*');

    if (!$course) {
        return false;
    }

    if ($course->startdate != 0 && $course->startdate > time()) {
        return false;
    }

    if ($course->enddate != 0 && $course->enddate < time()) {
        return false;
    }

    $completion = new \completion_info($course);

    if (!$completion->is_enabled()) {
        return true;
    }

    $percentage = \core_completion\progress::get_course_progress_percentage($course, $USER->id);

    if (!is_null($percentage) && $percentage == 100) {
        return false;
    }

    return true;
}

/**
 * Rename navigation items text
 *
 * @param flat_navigation $flatnav
 */
function theme_biossex_rename_menuitems(\flat_navigation $flatnav) {

    $item = $flatnav->find('mycourses');

    if ($item) {
        $item->text = get_string('myactivecourses', 'theme_biossex');
    }
}

/**
 * Improve flat navigation menu
 *
 * @param flat_navigation $flatnav
 */
function theme_biossex_add_coursesections_to_navigation(\flat_navigation $flatnav) {
    global $PAGE;

    $participantsitem = $flatnav->find('participants', \navigation_node::TYPE_CONTAINER);

    if (!$participantsitem) {
        return;
    }

    if ($PAGE->course->format != 'singleactivity') {
        $coursesectionsoptions = [
            'text' => get_string('coursesections', 'theme_biossex'),
            'shorttext' => get_string('coursesections', 'theme_biossex'),
            'icon' => new pix_icon('t/viewdetails', ''),
            'type' => \navigation_node::COURSE_CURRENT,
            'key' => 'course-sections',
            'parent' => $participantsitem->parent
        ];

        $coursesections = new \flat_navigation_node($coursesectionsoptions, 0);

        foreach ($flatnav as $item) {
            if ($item->type == \navigation_node::TYPE_SECTION) {
                $coursesections->add_node(new \navigation_node([
                    'text' => $item->text,
                    'shorttext' => $item->shorttext,
                    'icon' => $item->icon,
                    'type' => $item->type,
                    'key' => $item->key,
                    'parent' => $coursesections,
                    'action' => $item->action
                ]));
            }
        }

        $flatnav->add($coursesections, $participantsitem->key);
    }
}

/**
 * Check if a certificate plugin is installed.
 *
 * @return bool
 */
function theme_biossex_has_certificates_plugin() {
    $simplecertificate = \core_plugin_manager::instance()->get_plugin_info('mod_simplecertificate');

    $customcert = \core_plugin_manager::instance()->get_plugin_info('mod_customcert');

    if ($simplecertificate || $customcert) {
        return true;
    }

    return false;
}


//Método para obtener imagenes a una pagina externa.


function theme_biossex_get_progress_user($userid) {
    global $CFG, $USER;

    require_once($CFG->libdir.'/gradelib.php');
    require_once($CFG->dirroot.'/grade/querylib.php');

   
    $coursesuser = enrol_get_all_users_courses ($userid, false, ['id','fullname'], null);
    foreach($coursesuser as $course){
        $percentageuser = progress::get_course_progress_percentage($course, $userid);
        if (!is_null($percentageuser)) {
            $course->percentage = floor($percentageuser);
            $course->progress = floor($percentageuser) . "% Completado";
        } else {
            $course->percentage = 0;
            $course->progress = "No hay avance";
        }

        $aux = grade_get_course_grade($userid, $course->id);
        if($aux->grade){
            //se agrega calificación a solo 2 decimales.
             $course->grade = number_format($aux->grade, 0);
             $certificate =  new \theme_biossmann\util\certificates($USER, $course->id);
             $cert_ob = $certificate->get_all_certificates();
             $certs_arr = [];
             foreach($cert_ob as $cert){
                 if( $course->grade >=80){
                     array_push($certs_arr, $cert["certificates"][0]->downloadurl);
                 }
             }
             
             $course->certificate = $certs_arr;
             
         }
            else {
                $course->grade = "--";
            }
       

    }
    return $coursesuser;
}
//función para la obtención de la foto de perfil del usuario actual.
function theme_biossex_update_image_profile(){
    global $USER, $DB, $CFG;
    $uploaded_file_path = $_FILES['foto']['tmp_name'];  // temp path to the actual file
    $filename = $_FILES['foto']['name'];                // the original (human readable) filename

    $filemanageroptions = array('maxbytes'       => $CFG->maxbytes,
                             'subdirs'        => 0,
                             'maxfiles'       => 1,
                             'accepted_types' => 'optimised_image');
    $usernew = new stdClass();
    $usernew->id = $USER->id;
    $usernew->deletepicture = 1;
    $usernew->imagefile = $uploaded_file_path;

    require_once( $CFG->libdir . '/gdlib.php' );

    $usericonid = process_new_icon( context_user::instance( $USER->id, MUST_EXIST ), 'user', 'icon', 0, $uploaded_file_path );
    if ( $usericonid ) {
            $DB->set_field( 'user', 'picture', $usericonid, array( 'id' => $USER->id ) );
            $USER->picture = $usericonid;
    }

    unset($_FILES,$_POST);



}
//Creamos función para comparar la fecha actual con la ultima fecha de ingreso del usuario.
function theme_biossex_compare_dates($primera, $segunda){
    $valoresPrimera = explode ("/", $primera);   
    $valoresSegunda = explode ("/", $segunda); 

    $diaPrimera    = $valoresPrimera[0];  
    $mesPrimera  = $valoresPrimera[1];  
    $anyoPrimera   = $valoresPrimera[2]; 

    $diaSegunda   = $valoresSegunda[0];  
    $mesSegunda = $valoresSegunda[1];  
    $anyoSegunda  = $valoresSegunda[2];

    $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);  
    $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);     

    if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)){
        // "La fecha ".$primera." no es v&aacute;lida";
        return 0;
    }elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
        // "La fecha ".$segunda." no es v&aacute;lida";
        return 0;
    }else{
        $diasSinAcceder = $diasPrimeraJuliano - $diasSegundaJuliano;
        $saludo = "";
        if ($diasSinAcceder == 0 || $diasSinAcceder < 0) {
            $saludo = "¡Bienvenido!";
        } else if ($diasSinAcceder == 1 || $diasSinAcceder <= 6) {
            $saludo = get_config('theme_biossex', 'unDia');
            if($saludo!=""){
              return  $saludo;
            }
        } else if ($diasSinAcceder == 7 || $diasSinAcceder <= 13) {
            $saludo = get_config('theme_biossex', 'unaSemana');
            if($saludo!=""){
                return  $saludo;
              }
        } else if ($diasSinAcceder== 14 || $diasSinAcceder <= 30) {
            $saludo = get_config('theme_biossex', 'dosSemanas');
            if($saludo!=""){
                return  $saludo;
              }
        } else if ($diasSinAcceder == 31) {
            $saludo = get_config('theme_biossex', 'unMes');
            if($saludo!=""){
                return  $saludo;
              }
        } else if ($diasSinAcceder > 31) {
            $saludo = get_config('theme_biossex', 'masdeunMes');
            if($saludo!=""){
                return  $saludo;
              }
        }
    } 
}
//función para la obtención de las imagenes del slider
function theme_biossex_get_image_slider($syscontext,$component,$itemid){
$i=1;
$bdimagenes=array();
$valueSlidercount=get_config('theme_biossex', 'slidercount');
while ($i <= $valueSlidercount) {
    //Se recorren los registros y se almacenan en el arreglo $bdimagenes
    $valueImg= get_config('theme_biossex', 'sliderimage'.$i);
    $filearea = 'sliderimage'.$i;
   //Se recorren los registros de los textos de cada imagen
    $valueImgTexto= get_config('theme_biossex', 'slidercap'.$i);
   $bdimagenes=[];
   $urlslider = moodle_url::make_pluginfile_url($syscontext->id,$component,$filearea,$itemid,null,$valueImg);
   $bdimagenes[]=['url'=>$urlslider ,'texto'=>$valueImgTexto];
   $bdCarrucel[]=$bdimagenes;
    $i++;
}
return $bdCarrucel;
}

//función para insertar datos a la tabla capacitación
function theme_biossex_insert_data_capacitacion($CURSO,$PUESTO,$SEMANA){
global $USER, $DB, $CFG; //Variables globales para la obtención de datos.
$planlog = new stdClass();
foreach ($CURSO as $val) {
    $valor .= $val . ","; //para almacenarla
  }
 $valor=substr($valor,0,-1);//para eliminar la ultima coma.
   $planlog->id = "";
    $planlog->tipo_puesto = $PUESTO;
   $planlog->semanas = $SEMANA;
$planlog->cursoid = $valor;
    $DB->insert_record('capacitacion', $planlog);
   $valor = "";
}

function theme_biossex_get_pix_user(){
    global $USER;

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
    return str_replace('/f%24', '/f$', $url);
}