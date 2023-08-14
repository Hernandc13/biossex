<?php
function xmldb_theme_biossex_upgrade($oldversion){
    global $DB;

    $dbman = $DB->get_manager();
    if ($oldversion < 2023012301) {

       // Define table historic_monitoring to be created.
       $table = new xmldb_table('capacitacion');
       $table3 = new xmldb_table('webinar');
       // Adding fields to table historic_monitoring.
       $table->add_field('id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
       $table->add_field('tipo_puesto', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, '0');       
       $table->add_field('semanas', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, '0');
       $table->add_field('curso_id', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, '0');       

       // Adding keys to table historic_monitoring.
       $table->add_key('primary', XMLDB_KEY_primary, ['id']);

       $table3->add_field('id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
       $table3->add_field('titulo', XMLDB_TYPE_TEXT, 'medium',null, XMLDB_NOTNULL, null, '0');       
       $table3->add_field('presentador', XMLDB_TYPE_TEXT,'medium', null, XMLDB_NOTNULL, null, '0');
       $table3->add_field('link', XMLDB_TYPE_TEXT, 'medium',null, XMLDB_NOTNULL, null, '0');   
       $table3->add_field('fecha', XMLDB_TYPE_TEXT, 'medium',null, XMLDB_NOTNULL, null, '0');   
       $table3->add_key('primary', XMLDB_KEY_primary, ['id']);

       if (!$dbman->table_exists($table3)) {
        $dbman->create_table($table3);
    }
       // Conditionally launch create table for historic_monitoring.
       if (!$dbman->table_exists($table)) {
           $dbman->create_table($table);
       }
       
     

       // Shcp_dashboard savepoint reached.
       upgrade_plugin_savepoint(true, 2023012301, 'theme', 'theme_biossex');
  }
 

  return true;
}
