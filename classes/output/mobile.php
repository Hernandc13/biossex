<?php

namespace theme_biossex\output;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../lib.php');

class mobile {

    public static function show_capacitacion($args) {
        global $USER, $DB, $OUTPUT, $CFG;

        $args = (object) $args;
        $nameuser = $USER->firstname . " " . $USER->lastname;
        //$acceso = $USER->lastaccess;
        //Obtenemos el ultimo acceso del usuario a moodle.
        $acceso = $USER->lastlogin;
        $fechaIngreso = gmdate("d/m/Y", $acceso);
        //Obtenemos la fecha actual del sistema.
        $fechaActual = date('d/m/Y');
        //Mandamos el saludo correspondiente.
        $saludo = theme_biossex_compare_dates($fechaActual, $fechaIngreso);
        // get picture of user logged
        $srcpixuser = theme_biossex_get_pix_user();
        //Obtenemos el puesto personalizado de la tabla mdl_user_info_data a su vez hace referencia a la tabla
        $field_puesto = get_config('theme_biossex', 'field_puesto');
        $resultado ="";
        if(isset($field_puesto)){
            $data = $DB->get_record_sql("SELECT data FROM {user_info_data} WHERE fieldid = {$field_puesto} AND userid = {$USER->id}");
            $resultado = $data->data;
        } 
        //Obtenemos el plan de capacitación correspondiente al tipo de puesto del usuario
        //Consulta a base de datos CAPACITACIÓN
        if($resultado!=""){
            $sqlCapacitacion = "SELECT * FROM mdl_capacitacion C where C.tipo_puesto like '%$resultado'";
            $ConsultaCapacitacion = $DB->get_records_sql($sqlCapacitacion);
        }
        foreach($ConsultaCapacitacion as $semana){
            
            $semana->cursos=array_values($DB->get_records_sql("SELECT id,fullname FROM mdl_course WHERE id IN ($semana->cursoid)"));
            
        }
        $wwwroot = $CFG->wwwroot;
        $data = [
            'PlanCapacitacion' => array_values($ConsultaCapacitacion),
            'wwwroot' => $wwwroot,
            'nameuser' => $nameuser,
            'saludo' => $saludo,
            'puesto' => $resultado,
            'pixuser' => $srcpixuser,
        ];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    //'html' => '<h1 class="text-center">{{ "plugin.local_hello.hello" | translate }}</h1>',
                    'html' => $OUTPUT->render_from_template('theme_biossex/mobile_capacitacion', $data),
                ],
            ],
        ];
    }

}