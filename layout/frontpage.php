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
 * Frontpage layout for the biossex theme.
 *
 * @package   theme_biossex
 * @copyright 2017 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('sidepre-open', PARAM_ALPHA);

require_once($CFG->libdir . '/behat/lib.php');

require_once($CFG->libdir . '/filelib.php');

require_once(__DIR__ . '/../lib.php');

//Imagenes locales para el plan de capacitación
$logoFooter= $CFG->wwwroot . "/theme/biossex/pix/LogoBiossmann.png";

//Actualizamos foto de perfil.
if (!empty($_FILES)) {
    theme_biossex_update_image_profile($_FILES);
}
//obtenemos el nombre del usuario actual
global $USER, $DB, $CFG;
// get fullname of user logged
$nameuser = $USER->firstname . " " . $USER->lastname;
//$acceso = $USER->lastaccess;
//Obtenemos el ultimo acceso del usuario a moodle.
$acceso = $USER->lastlogin;
$fechaIngreso = gmdate("d/m/Y", $acceso);
//Obtenemos la fecha actual del sistema.
$fechaActual = date('d/m/Y');
//Mandamos el saludo correspondiente.
$saludo = "";
if (theme_biossex_compare_dates($fechaActual, $fechaIngreso) == 0 or theme_biossex_compare_dates($fechaActual, $fechaIngreso) <= 6){
    $saludo = get_config('theme_biossex', 'unaSemana');
    echo $saludo;
}else if (theme_biossex_compare_dates($fechaActual, $fechaIngreso) == 7 or theme_biossex_compare_dates($fechaActual, $fechaIngreso) <= 13) {
    $saludo = get_config('theme_biossex', 'unaSemana');
} else if (theme_biossex_compare_dates($fechaActual, $fechaIngreso) == 14 or theme_biossex_compare_dates($fechaActual, $fechaIngreso) <= 20) {
    $saludo = get_config('theme_biossex', 'dosSemanas');
} else if (theme_biossex_compare_dates($fechaActual, $fechaIngreso) == 21 or theme_biossex_compare_dates($fechaActual, $fechaIngreso) <= 27) {
    $saludo = get_config('theme_biossex', 'tresSemanas');
} else if (theme_biossex_compare_dates($fechaActual, $fechaIngreso) == 28 or theme_biossex_compare_dates($fechaActual, $fechaIngreso) <= 34) {
    $saludo = get_config('theme_biossex', 'cuatroSemanas');
} else if (theme_biossex_compare_dates($fechaActual, $fechaIngreso) == 35) {
    $saludo = get_config('theme_biossex', 'cincoSemanas');
}


//Obtenemos si el podcast esta visible
$ViewPodcast = get_config('theme_biossex', 'ViewPodcast');

$wwwroot = $CFG->wwwroot;
// get picture of user logged
$srcpixuser = theme_biossex_get_pix_user();


$progressuser = array_values(theme_biossex_get_progress_user($USER->id));


$extraclasses = [];

$themesettings = new \theme_biossex\util\theme_settings();

if (isloggedin()) {
    $blockshtml = $OUTPUT->blocks('side-pre');
    $hasblocks = strpos($blockshtml, 'data-block=') !== false;

    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
    $draweropenright = (get_user_preferences('sidepre-open', 'true') == 'true');

    if ($navdraweropen) {
        $extraclasses[] = 'drawer-open-left';
    }

    if ($draweropenright && $hasblocks) {
        $extraclasses[] = 'drawer-open-right';
    }


    $alertmsg = theme_biossex_get_setting('alertmsg');
    $alertcontent = (empty($alertmsg)) ? false : format_text($alertmsg, FORMAT_HTML, ['noclean' => true]);



    $bodyattributes = $OUTPUT->body_attributes($extraclasses);
    $regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();

    $ConsultaCapacitacion="";
    //Configuración  para la obtención de las imagenes slider
    $component = 'theme_biossex';
    $itemid = 0;
    $filepath = get_config('theme_biossex', 'vacantimg');
    $filearea = 'vacantimg';
    $syscontext = context_system::instance();
    $url = moodle_url::make_pluginfile_url($syscontext->id, $component, $filearea, $itemid, null, $filepath);
    //Consulta para acceder al texto registrado en la base de datos.
    $value = get_config('theme_biossex', 'TextoWelcome');
    $valueVideo = get_config('theme_biossex', 'VideoWelcome');
    $valueVideo2 = get_config('theme_biossex', 'VideoTutorial');
    //Obtenemos la cantidad de imagenes que se van a registrar(slidercount)
    $valueSlidercount = get_config('theme_biossex', 'slidercount');
    //Recorrer los registros de la base de datos
    $bdCarrucel = theme_biossex_get_image_slider($syscontext, $component, $itemid);
    //Se obtiene el texto para la portada del slider desde la base de datos(TextoPortadaSlider).
    $valueTextoPortada = get_config('theme_biossex', 'TextoPortadaSlider');

    //Se obtiene la imagen de portada para slider
    $component = 'theme_biossex';
    $itemid = 0;
    $filepath = get_config('theme_biossex', 'ImagenPortadaSlider');
    $filearea = 'imagenportadaslider';
    $syscontext = context_system::instance();
    $urlPortada = moodle_url::make_pluginfile_url($syscontext->id, $component, $filearea, $itemid, null, $filepath);

    //Se verifica que se tenga una imagen para el carrucel webinar
    $sqlExist = "SELECT * from mdl_config_plugins where plugin='theme_biossex' and name='imagenwebinar' and value=''";
    $ConsultaExist = $DB->get_records_sql($sqlExist);
    if(count($ConsultaExist)>0){
        $urlimgWebinar ="https://www.biossmann.com/statics/images/proposito.jpg";
         
    }else{
        //Se obtiene la imagen del carrucel webinars
    $component = 'theme_biossex';
    $itemid = 0;
    $filepath = get_config('theme_biossex', 'ImagenWebinar');
    $filearea = 'imagenwebinar';
    $syscontext = context_system::instance();
    $urlimgWebinar = moodle_url::make_pluginfile_url($syscontext->id, $component, $filearea, $itemid, null, $filepath);
    }
  
   
    //Se obtienen de la base de datos la información de las redes solciales biossex
    //FACEBOOK

    $valueFacebook = get_config('theme_biossex', 'facebook');
    //INSTAGRAM

    $valueInstagram = get_config('theme_biossex', 'instagram');
    //YOUTUBE

    $valueYoutube = get_config('theme_biossex', 'youtube');
    //IN

    $valueIn = get_config('theme_biossex', 'linkedin');
    //obtenemos url de spotify
    $urlSpotify = get_config('theme_biossex', 'urlpodcast');
    $field_puesto = get_config('theme_biossex', 'field_puesto');
    $resultado ="";
    if(isset($field_puesto)){
        $data = $DB->get_record_sql("SELECT data FROM {user_info_data} WHERE fieldid = {$field_puesto} AND userid = {$USER->id}");
        $resultado = $data->data;
        if($resultado==""){
            $resultado="Sin Puesto";
        }
    } 
 
 
  
   $dbman = $DB->get_manager();
    
   $table = new xmldb_table('webinar');
    //verificamos si la tabla existe 
    if (!$dbman->table_exists($table)) {

        
        $table->add_field('id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('titulo', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('presentador', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('link', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('fecha', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, '0');
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        //si no existe la tabla se hace la creación de la misma con  los datos colocados anteriormente.
        $dbman->create_table($table);
    } else {
         //si la tabla ya existe hacemos la consulta a base de datos y mandar la información.
        $ConsultaWebinar="";
        $sqlWebinar = "SELECT *, DATE_FORMAT(fecha, '%d %M %Y') AS 'fecha' FROM mdl_webinar order by fecha DESC";
        $ConsultaWebinar = $DB->get_records_sql($sqlWebinar);
        //$resconsulta = array_values($ConsultaWebinar);
        
    }

    //Consulta Webinar
    $ConsultaWebinar="";
        $sqlWebinar = "SELECT *, DATE_FORMAT(fecha, '%d %M %Y') AS 'fecha' FROM mdl_webinar order by fecha DESC";
        $ConsultaWebinar = $DB->get_records_sql($sqlWebinar);
        $Webinar = array_values($ConsultaWebinar);


        $curses="";
        //consultamos los cursos
        $curses= "SELECT * FROM {course} WHERE id != 1";
       $ConsultaCurses = $DB->get_records_sql($curses);
       
         
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
        'nameuser' => $nameuser,
        'progressuser' => $progressuser,
        'progressuserA' => array_splice($progressuser, 0, 6),
        'TextoWelcome' => $value,
        'VideoWelcome' => $valueVideo,
        'VideoTutorial'=>$valueVideo2,
        'Cantidad' => $valueSlidercount,
        'valores' => $bdCarrucel,
        'TextoPortada' => $valueTextoPortada,
        'ImgPortada' => $urlPortada,
        'pixuser' => $srcpixuser,
        'facebook' => $valueFacebook,
        'instagram' => $valueInstagram,
        'youtube' => $valueYoutube,
        'linkedin' => $valueIn,
        'urlSpotify' => $urlSpotify,
        'saludo' => $saludo,
        'viewPodcast' => $ViewPodcast,
        'wwwroot' => $wwwroot,
        'fielpuesto' => $resultado,
        'PlanCapacitacionMax' => $concapacitacion,
        'PlanCapacitacion' => $concapacitacionmax,
        'Webinar'=>$Webinar,
        'ImgWebinar' =>$urlimgWebinar,
        'Result'=>$resvacio,
        'logoFooter'=>$logoFooter,
        'curses'=>array_values($ConsultaCurses),
    ];


    // Improve boost navigation.
    theme_biossex_extend_flat_navigation($PAGE->flatnav);

    $templatecontext['flatnavigation'] = $PAGE->flatnav;

    $templatecontext = array_merge($templatecontext, $themesettings->footer_items(), $themesettings->slideshow());

    echo $OUTPUT->render_from_template('theme_biossex/frontpage', $templatecontext);
} else {
    $sliderfrontpage = false;
    if ((theme_biossex_get_setting('sliderenabled', true) == true) && (theme_biossex_get_setting('sliderfrontpage', true) == true)) {
        $sliderfrontpage = true;
        $extraclasses[] = 'slideshow';
    }

    $numbersfrontpage = false;
    if (theme_biossex_get_setting('numbersfrontpage', true) == true) {
        $numbersfrontpage = true;
    }

    $sponsorsfrontpage = false;
    if (theme_biossex_get_setting('sponsorsfrontpage', true) == true) {
        $sponsorsfrontpage = true;
    }

    $clientsfrontpage = false;
    if (theme_biossex_get_setting('clientsfrontpage', true) == true) {
        $clientsfrontpage = true;
    }

    $bannerheading = '';
    if (!empty($PAGE->theme->settings->bannerheading)) {
        $bannerheading = theme_biossex_get_setting('bannerheading', true);
    }

    $bannercontent = '';
    if (!empty($PAGE->theme->settings->bannercontent)) {
        $bannercontent = theme_biossex_get_setting('bannercontent', true);
    }

    $shoulddisplaymarketing = false;
    if (theme_biossex_get_setting('displaymarketingbox', true) == true) {
        $shoulddisplaymarketing = true;
    }

    $disablefrontpageloginbox = false;
    if (theme_biossex_get_setting('disablefrontpageloginbox', true) == true) {
        $disablefrontpageloginbox = true;
        $extraclasses[] = 'disablefrontpageloginbox';
    }

    $bodyattributes = $OUTPUT->body_attributes($extraclasses);
    $regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();

    $templatecontext = [
        'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
        'output' => $OUTPUT,
        'bodyattributes' => $bodyattributes,
        'hasdrawertoggle' => false,
        'canloginasguest' => $CFG->guestloginbutton and !isguestuser(),
        'cansignup' => $CFG->registerauth == 'email' || !empty($CFG->registerauth),
        'bannerheading' => $bannerheading,
        'bannercontent' => $bannercontent,
        'shoulddisplaymarketing' => $shoulddisplaymarketing,
        'sliderfrontpage' => $sliderfrontpage,
        'numbersfrontpage' => $numbersfrontpage,
        'sponsorsfrontpage' => $sponsorsfrontpage,
        'clientsfrontpage' => $clientsfrontpage,
        'disablefrontpageloginbox' => $disablefrontpageloginbox,
        'logintoken' => \core\session\manager::get_login_token()
    ];

    $templatecontext = array_merge($templatecontext, $themesettings->footer_items(), $themesettings->marketing_items());

    if ($sliderfrontpage) {
        $templatecontext = array_merge($templatecontext, $themesettings->slideshow());
    }

    if ($numbersfrontpage) {
        $templatecontext = array_merge($templatecontext, $themesettings->numbers());
    }

    if ($sponsorsfrontpage) {
        $templatecontext = array_merge($templatecontext, $themesettings->sponsors());
    }

    if ($clientsfrontpage) {
        $templatecontext = array_merge($templatecontext, $themesettings->clients());
    }

    echo $OUTPUT->render_from_template('theme_biossex/frontpage_guest', $templatecontext);
}