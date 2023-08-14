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
 * A two column layout for the biossex theme.
 *
 * @package   theme_biossex
 * @copyright 2017 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*
use html_writer;
use custom_menu;
use action_menu_filler;
use action_menu_link_secondary;
use stdClass;
use moodle_url;
use action_menu;
use pix_icon;
use theme_config;
use core_text;
use help_icon;
use context_system;
use core_course_list_element;
*/

defined('MOODLE_INTERNAL') || die;

global $DB, $CFG;
$results = new stdClass();
user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('sidepre-open', PARAM_ALPHA);

require_once($CFG->libdir . '/behat/lib.php');

$logoFooter= $CFG->wwwroot . "/theme/biossex/pix/Logobiossmann.png";

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
    $draweropenright = (get_user_preferences('sidepre-open', 'true') == 'true');
} else {
    $navdraweropen = false;
    $draweropenright = false;
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

$extraclasses = [];

if ($draweropenright && $hasblocks) {
    $extraclasses[] = 'drawer-open-right';
}

$alertmsg = theme_biossex_get_setting('alertmsg');
$alertcontent = (empty($alertmsg)) ? false : format_text($alertmsg, FORMAT_HTML, ['noclean' => true]);

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();



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
    'canviewadmininfos' => false,
    'alertcontent' => $alertcontent,
    'logoFooter'=>$logoFooter
];


$themesettings = new \theme_biossex\util\theme_settings();

$templatecontext = array_merge($templatecontext, $themesettings->footer_items());

if (is_siteadmin() && $PAGE->pagetype == 'my-index') {
    $adminifos = new \theme_biossex\util\admininfos();
    $templatecontext['totalusage'] = $adminifos->get_totaldiskusage();
    $templatecontext['totalactiveusers'] = $adminifos->get_totalactiveusers();
    $templatecontext['totalsuspendedusers'] = $adminifos->get_suspendedusers();
    $templatecontext['totalcourses'] = $adminifos->get_totalcourses();
    $templatecontext['onlineusers'] = $adminifos->get_totalonlineusers();
    $templatecontext['canviewadmininfos'] = true;
}

// Improve boost navigation.
theme_biossex_extend_flat_navigation($PAGE->flatnav);

$templatecontext['flatnavigation'] = $PAGE->flatnav;

echo $OUTPUT->render_from_template('theme_biossex/mydashboard', $templatecontext);
