<?php
// This file is part of Ranking block for Moodle - http://moodle.org/
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
 * Theme biossex block settings file
 *
 * @package    theme_biossex
 * @copyright  2017 Willian Mano http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();
global $USER, $DB, $CFG;

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingbiossex', get_string('configtitle', 'theme_biossex'));

    /*
    * ----------------------
    * General settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_biossex_general', get_string('generalsettings', 'theme_biossex'));

    // Logo file setting.
    $name = 'theme_biossex/logo';
    $title = get_string('logo', 'theme_biossex');
    $description = get_string('logodesc', 'theme_biossex');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

 
    // Favicon setting.
    $name = 'theme_biossex/favicon';
    $title = get_string('favicon', 'theme_biossex');
    $description = get_string('favicondesc', 'theme_biossex');
    $opts = array('accepted_types' => array('.ico'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset.
    $name = 'theme_biossex/preset';
    $title = get_string('preset', 'theme_biossex');
    $description = get_string('preset_desc', 'theme_biossex');
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_biossex', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets.
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset files setting.
    $name = 'theme_biossex/presetfiles';
    $title = get_string('presetfiles', 'theme_biossex');
    $description = get_string('presetfiles_desc', 'theme_biossex');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        array('maxfiles' => 20, 'accepted_types' => array('.scss')));
    $page->add($setting);

    // Login page background image.
    $name = 'theme_biossex/loginbgimg';
    $title = get_string('loginbgimg', 'theme_biossex');
    $description = get_string('loginbgimg_desc', 'theme_biossex');
    $opts = array('accepted_types' => array('.png', '.jpg', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbgimg', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $brand-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_biossex/brandcolor';
    $title = get_string('brandcolor', 'theme_biossex');
    $description = get_string('brandcolor_desc', 'theme_biossex');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $navbar-header-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_biossex/navbarheadercolor';
    $title = get_string('navbarheadercolor', 'theme_biossex');
    $description = get_string('navbarheadercolor_desc', 'theme_biossex');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $navbar-bg.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_biossex/navbarbg';
    $title = get_string('navbarbg', 'theme_biossex');
    $description = get_string('navbarbg_desc', 'theme_biossex');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $navbar-bg-hover.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_biossex/navbarbghover';
    $title = get_string('navbarbghover', 'theme_biossex');
    $description = get_string('navbarbghover_desc', 'theme_biossex');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Course format option.
    $name = 'theme_biossex/coursepresentation';
    $title = get_string('coursepresentation', 'theme_biossex');
    $description = get_string('coursepresentationdesc', 'theme_biossex');
    $options = [];
    $options[1] = get_string('coursedefault', 'theme_biossex');
    $options[2] = get_string('coursecover', 'theme_biossex');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_biossex/courselistview';
    $title = get_string('courselistview', 'theme_biossex');
    $description = get_string('courselistviewdesc', 'theme_biossex');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $page->add($setting);


    // Must add the page after definiting all the settings!
    $settings->add($page);

    /*
    * ----------------------
    * Advanced settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_biossex_advanced', get_string('advancedsettings', 'theme_biossex'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_biossex/scsspre',
        get_string('rawscsspre', 'theme_biossex'), get_string('rawscsspre_desc', 'theme_biossex'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_biossex/scss', 
    get_string('rawscss', 'theme_biossex'),
        get_string('rawscss_desc', 'theme_biossex'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Google analytics block.
    $name = 'theme_biossex/googleanalytics';
    $title = get_string('googleanalytics', 'theme_biossex');
    $description = get_string('googleanalyticsdesc', 'theme_biossex');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    /*
    * -----------------------
    * Frontpage settings tab
    * -----------------------
    */
    $page = new admin_settingpage('theme_biossex_frontpage', get_string('frontpagesettings', 'theme_biossex'));
    
    $custom_fields = $DB->get_records_menu($table = 'user_info_field', $conditions_array = array(), $sort = '', $fields = 'id, name');
    if(!empty($custom_fields)){
        
        $name = 'theme_biossex/field_puesto';
        $title = get_string('PuestoDesc', 'theme_biossex');
        $description = get_string('PuestoText', 'theme_biossex');
        $default = ' ';
        $setting = new admin_setting_configselect($name, $title, $description, $default, $custom_fields );
        $page->add($setting);  
        
    }

     // Texto Welcome setting.
     $name = 'theme_biossex/TextoWelcome';
     $title = get_string('WelcomeDesc', 'theme_biossex');
     $description = get_string('TextoWelcome', 'theme_biossex');
     $default = 'Bienvenidos';
     $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
     $page->add($setting);


          // Video Welcome setting.
     $name = 'theme_biossex/VideoWelcome';
     $title = get_string('VideoWelcomeDesc', 'theme_biossex');
     $description = get_string('VideoWelcome', 'theme_biossex');
     $default = '<iframe width="475" height="280" src="https://www.youtube.com/embed/pA49eZe3L6k" title="Quirófano Inteligente biossmann" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
     $setting = new admin_setting_configtextarea($name, $title, $description, $default);
     $setting->set_updatedcallback('theme_reset_all_caches');
     $page->add($setting);

       // Url Podcast.
       $name = 'theme_biossex/urlpodcast';
       $title = get_string('urlpodcastdesc', 'theme_biossex');
       $description = get_string('urlpodcast', 'theme_biossex');
       $default = '37i9dQZF1DX5trt9i14X7j';
       $setting = new admin_setting_configtext($name, $title, $description, $default);
      $setting->set_updatedcallback('theme_reset_all_caches');
      $page->add($setting);
        //ocultar Podcast
      $name = 'theme_biossex/ViewPodcast';
    $title = get_string('ViewPodcast', 'theme_biossex');
    $description = get_string('ViewPodcastDesc', 'theme_biossex');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $page->add($setting);

       // Saludos.
       //Una semana
       $name = 'theme_biossex/unaSemana';
       $title = get_string('unaSemanadesc', 'theme_biossex');
       $description = get_string('unaSemana', 'theme_biossex');
       $default = '¡Bienvenido!';
       $setting = new admin_setting_configtext($name, $title, $description, $default);
      $setting->set_updatedcallback('theme_reset_all_caches');
      $page->add($setting);

        //Dos semanas
        $name = 'theme_biossex/dosSemanas';
        $title = get_string('dosSemanasdesc', 'theme_biossex');
        $description = get_string('dosSemanas', 'theme_biossex');
        $default = '¡Hola de nuevo!';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
       $setting->set_updatedcallback('theme_reset_all_caches');
       $page->add($setting);
 
         //Tres semanas
         $name = 'theme_biossex/tresSemanas';
         $title = get_string('tresSemanasdesc', 'theme_biossex');
         $description = get_string('tresSemanas', 'theme_biossex');
         $default = 'Continuemos con tu capacitación';
         $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

         //Cuatro semanas
         $name = 'theme_biossex/cuatroSemanas';
         $title = get_string('cuatroSemanasdesc', 'theme_biossex');
         $description = get_string('cuatroSemanas', 'theme_biossex');
         $default = '¡Te hemos extrañado!';
         $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        
         //Cinco semanas
         $name = 'theme_biossex/cincoSemanas';
         $title = get_string('cincoSemanasdesc', 'theme_biossex');
         $description = get_string('cincoSemanas', 'theme_biossex');
         $default = '¡Que alegría verte de nuevo!';
         $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

 // Texto Portada para slider setting.
 $name = 'theme_biossex/TextoPortadaSlider';
 $title = get_string('TextoPortadaSliderDesc', 'theme_biossex');
 $description = get_string('TextoPortadaSlider', 'theme_biossex');
 $default = '<center>
    <h5 style="color: #F38020;"><b>WEBINAR</b></h5>
</center>
<p style="font-size: 12px; text-align: justify;">El Colegio de Anestesiólogos del Estado de Puebla, A.C. te invitan a la sesión académica:</p>
<p style="font-size: 14px; text-align: center;"><b>"IMPLICACIONES MÉDICO-LEGALES DEL RECIDENTE DE LAS ESPECIALIDADES MÉDICAS"</b></p>
<p style="font-size: 12px;">Impartido por:</p>
<p style="font-size: 14px;"><b>Lic.Guillerno Rodríguez Olivarez</b></p>';
 $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
 $page->add($setting);

    // Imagen para la portada de slider
    $name = 'theme_biossex/ImagenPortadaSlider';
    $title = get_string('ImagenPortadaSliderDesc', 'theme_biossex');
    $description = get_string('ImagenPortadaSlider', 'theme_biossex');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'imagenportadaslider', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_biossex/slidercount';
    $title = get_string('slidercount', 'theme_biossex');
    $description = get_string('slidercountdesc', 'theme_biossex');
    $default = 1;
    $options = array();
    for ($i = 0; $i < 13; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // If we don't have an slide yet, default to the preset.
    $slidercount = get_config('theme_biossex', 'slidercount');

    if (!$slidercount) {
        $slidercount = 1;
    }

    for ($sliderindex = 1; $sliderindex <= $slidercount; $sliderindex++) {
        $fileid = 'sliderimage' . $sliderindex;
        $name = 'theme_biossex/sliderimage' . $sliderindex;
        $title = get_string('sliderimage', 'theme_biossex');
        $description = get_string('sliderimagedesc', 'theme_biossex');
        $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
        $setting = new admin_setting_configstoredfile($name, $title, $description, $fileid, 0, $opts);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_biossex/slidercap' . $sliderindex;
        $title = get_string('slidercaption', 'theme_biossex');
        $description = get_string('slidercaptiondesc', 'theme_biossex');
        $default = '<center>
    <h5 style="color: #F38020;"><b>WEBINAR</b></h5>
</center>
<p style="font-size: 12px; text-align: justify;">El Colegio de Anestesiólogos del Estado de Puebla, A.C. te invitan a la sesión académica:</p>
<p style="font-size: 14px; text-align: center;"><b>"IMPLICACIONES MÉDICO-LEGALES DEL RECIDENTE DE LAS ESPECIALIDADES MÉDICAS"</b></p>
<p style="font-size: 12px;">Impartido por:</p>
<p style="font-size: 14px;"><b>Lic.Guillerno Rodríguez Olivarez</b></p>';
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);

        $name = 'theme_biossex/UrlImage'. $sliderindex;
        $title = get_string('UrlImageDesc', 'theme_biossex');
        $description = get_string('UrlImage', 'theme_biossex');
        $default = 'https://www.biossex.com/';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
       $setting->set_updatedcallback('theme_reset_all_caches');
       $page->add($setting);

    }

     // Imagen para carrucel de Webinars
     $name = 'theme_biossex/ImagenWebinar';
     $title = get_string('ImagenWebinarDesc', 'theme_biossex');
     $description = get_string('ImagenWebinar', 'theme_biossex');
     $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
     $setting = new admin_setting_configstoredfile($name, $title, $description, 'imagenwebinar', 0, $opts);
     $setting->set_updatedcallback('theme_reset_all_caches');
     $page->add($setting);
  
  
    $settings->add($page);

    /*
    * --------------------
    * Footer settings tab
    * --------------------
    */
    $page = new admin_settingpage('theme_biossex_footer', get_string('footersettings', 'theme_biossex'));
    // Facebook url setting.
    $name = 'theme_biossex/facebook';
    $title = get_string('facebook', 'theme_biossex');
    $description = get_string('facebookdesc', 'theme_biossex');
    $default = 'https://www.facebook.com/biossex-160626161282799/?epa=SEARCH_BOX';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    // Linkdin url setting.
    $name = 'theme_biossex/linkedin';
    $title = get_string('linkedin', 'theme_biossex');
    $description = get_string('linkedindesc', 'theme_biossex');
    $default = 'https://www.linkedin.com/company/biossex';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Youtube url setting.
    $name = 'theme_biossex/youtube';
    $title = get_string('youtube', 'theme_biossex');
    $description = get_string('youtubedesc', 'theme_biossex');
    $default = 'https://www.youtube.com/channel/UCxVXM9dTO_qG4vP3-AcpPuw/featured';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Instagram url setting.
    $name = 'theme_biossex/instagram';
    $title = get_string('instagram', 'theme_biossex');
    $description = get_string('instagramdesc', 'theme_biossex');
    $default = 'https://instagram.com/biossex?igshid=1tya6orxib35k';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    $settings->add($page);

  
 /*
    * --------------------
    * Forum settings tab
    * --------------------
    */
    // Forum page.
    $settingpage = new admin_settingpage('theme_biossex_forum', get_string('forumsettings', 'theme_biossex'));

    $settingpage->add(new admin_setting_heading('theme_biossex_forumheading', null,
            format_text(get_string('forumsettingsdesc', 'theme_biossex'), FORMAT_MARKDOWN)));

    // Enable custom template.
    $name = 'theme_biossex/forumcustomtemplate';
    $title = get_string('forumcustomtemplate', 'theme_biossex');
    $description = get_string('forumcustomtemplatedesc', 'theme_biossex');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $settingpage->add($setting);

    // Header setting.
    $name = 'theme_biossex/forumhtmlemailheader';
    $title = get_string('forumhtmlemailheader', 'theme_biossex');
    $description = get_string('forumhtmlemailheaderdesc', 'theme_biossex');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $settingpage->add($setting);

    // Footer setting.
    $name = 'theme_biossex/forumhtmlemailfooter';
    $title = get_string('forumhtmlemailfooter', 'theme_biossex');
    $description = get_string('forumhtmlemailfooterdesc', 'theme_biossex');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $settingpage->add($setting);

    $settings->add($settingpage);


 
}



 $settings->add($page);