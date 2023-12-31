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
 * Theme biossex certificates page.
 *
 * @package    theme_biossex
 * @copyright  2020 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

$courseid = optional_param('id', 0, PARAM_INT);

require_login();

$title = get_string('certificatestitle', 'theme_biossex');
if (!$courseid) {
    $PAGE->set_context(context_system::instance());
    $PAGE->set_pagelayout('standard');
    $PAGE->set_heading($SITE->fullname);
    $PAGE->set_url('/theme/biossex/certificates.php');
} else {
    $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

    require_login($course);

    $coursename = format_string($course->fullname, true, ['context' => context_course::instance($course->id)]);
    $title = $coursename . ': ' . get_string('certificatestitle', 'theme_biossex');

    $PAGE->set_context(context_course::instance($course->id));
    $PAGE->set_pagelayout('incourse');
    $PAGE->set_heading($coursename);
    $PAGE->set_url('/theme/biossex/certificates.php', ['id' => $course->id]);
}

$PAGE->set_title($title);

$PAGE->navbar->add(get_string('certificates', 'theme_biossex'));

$viewrenderable = new theme_biossex\output\certificates($courseid);

$output = $PAGE->get_renderer('theme_biossex');

$output->heading($title);

echo $output->header();

echo $output->render($viewrenderable);

echo $output->footer();
