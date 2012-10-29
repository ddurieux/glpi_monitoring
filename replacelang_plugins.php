<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$DB_file = 'locales/en_GB_back.php';
$found = array();
$sql_query = file_get_contents($DB_file);
$folders = array('inc', 'front', 'ajax', 'install');

foreach ($folders as $folder) {
   foreach (glob($folder.'/*.php') as $file) {
      $php_line_content = file_get_contents($file);
      preg_match_all("/LANG\[\'([\w]+)\'\]\[([\d]+)\]/",$php_line_content,$out);      
	if (isset($out[0][0])) {
	   foreach ($out[0] as $val) {
               if (isset($found[$val])) {
                   $found[$val]++;
               } else {
                  $found[$val] = 1;
	       }
           }
	}
   }
}
print_r($found);


?>
