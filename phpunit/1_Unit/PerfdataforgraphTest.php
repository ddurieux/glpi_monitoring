<?php

class PerfdataforgraphTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function testTcp() {
      global $DB;

      $pmComponent = new PluginMonitoringComponent();
      $pmComponent->add(array(
          'name' => 'check',
          'plugin_monitoring_checks_id' => '2'
      ));

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => 'time=0.019037s;;;0.000000;3.000000',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_tcp'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'response_time'  => array('19.0'),
          'other'          => array('0'),
          'timeout'        => array('3000'),
          'warning'        => array('0'),
          'critical'        => array('0')
      );

      $this->assertEquals($a_reference, $ret[0], "Data of check_tcp");
   }



   /**
    * @test
    */
   public function testLoad() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => 'load1=0.104;1.500;3.000;0; load5=0.120;1.000;2.000;0; load15=0.093;2.000;4.000;0;',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_load'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'load1min_current'     => array('0.1'),
          'load1min_warning'     => array('1.5'),
          'load1min_critical'    => array('3.0'),
          'load1min_other'       => array('0'),
          'load5min_current'     => array('0.12'),
          'load5min_warning'     => array('1.0'),
          'load5min_critical'    => array('2.0'),
          'load5min_other'       => array('0'),
          'load15min_current'    => array('0.09'),
          'load15min_warning'    => array('2.0'),
          'load15min_critical'   => array('4.0'),
          'load15min_other'      => array('0')
      );

      $this->assertEquals($a_reference, $ret[0], "data of check_load");
   }



   /**   
    * @test   
    */
   public function testPf() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => 'current=444;8000;9000; percent=4.4%; limit=10000;',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_pf'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'states_current'    => array('444'),
          'states_warning'    => array('8000'),
          'states_critical'   => array('9000'),
          'percent'           => array('4.4'),
          'limit'             => array('10000')
      );

      $this->assertEquals($a_reference, $ret[0], "data of check_pf");
   }



   /**   
    * @test   
    */
   public function testDisk() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => '/tmp=5MB;1157;1302;0;1447',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_disk'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'used'           => array('5000000'),
          'used_warning'   => array('1157000000'),
          'used_critical'  => array('1302000000'),
          'used_other'     => array('0'),
          'totalcapacity'  => array('1447000000')
      );

      $this->assertEquals($a_reference, $ret[0], "data of check_disk");
   }



   /**   
    * @test   
    */
   public function testMySQLTmpdisktable() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => 'pct_tmp_table_on_disk=38.24%;25;50 pct_tmp_table_on_disk_now=4.25%',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_mysql_health tmp_disk_tables'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'tmp_table_on_disk_current'        => array('38.24'),
          'tmp_table_on_disk_warning'        => array('25'),
          'tmp_table_on_disk_critical'       => array('50'),
          'tmp_table_on_disk_now_current'    => array('4.25')
      );

      $this->assertEquals($a_reference, $ret[0], "data of check_mysql_health__tmp_disk_tables");
   }



   /**   
    * @test   
    */
   public function testCpuusage() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => 'cpu_usage=4%;80;95; cpu_user=0%; cpu_system=4%;',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_cpu_usage'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'usage'          => array('4'),
          'usage_warning'  => array('80'),
          'usage_critical' => array('95'),
          'user'           => array('0'),
          'system'     => array('4')
      );

      $this->assertEquals($a_reference, $ret[0], "data of check_cpu_usage");
   }



   /**   
    * @test   
    */
   public function testHTTP() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => 'time=0.220672s;1.000000;2.000000;0.000000 size=7386B;;;0',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_http'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'time_current'  => array('221'),
          'time_warning'  => array('1000'),
          'time_critical' => array('2000'),
          'time_other'    => array('0'),
          'size_current'  => array('7386'),
          'size_other'    => array('0'),
          'size_warning'    => array('0'),
          'size_critical'    => array('0')
      );

      $this->assertEquals($a_reference, $ret[0], "data of check_http");
   }



   /**   
    * @test   
    */
   public function testIostatBSD() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => 'tps=7.325;;; tpsr=3.175;;; tpsw=4.15;;; reads=55.95KB;;; writes=78.7KB;;; svc_t=0.85;;;',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_iostat_bsd'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'IOTPS_read_write'  => array('7.33'),
          'IOTPS_read'        => array('3.18'),
          'IOTPS_write'       => array('4.15'),
          'Kbps_read'         => array('55950.0'),
          'Kbps_write'        => array('78700.0'),
          'transactiontime'   => array('0.85'),
          'value1.2'          => Array('0'),
          'value1.3'          => Array('0'),
          'value2.2'          => Array('0'),
          'value2.3'          => Array('0'),
          'value3.2'          => Array('0'),
          'value3.3'          => Array('0'),
          'value4.2'          => Array('0'),
          'value4.3'          => Array('0'),
          'value5.2'          => Array('0'),
          'value5.3'          => Array('0'),
          'value6.2'          => Array('0'),
          'value6.3'          => Array('0')
      );

      $this->assertEquals($a_reference, $ret[0], "data of check_iostat_bsd");
   }



   /**   
    * @test   
    */
   public function testNginxstatus() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => 'Writing=1;;;; Reading=0;;;; Waiting=9;;;; Active=10;;;; ReqPerSec=1.964401;;;; ConnPerSec=0.190939;;;; ReqPerConn=8.167504;;;;',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_nginx_status'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'Writing'     => array('1'),
          'Reading'     => array('0'),
          'Waiting'     => array('9'),
          'Active'      => array('10'),
          'ReqPerSec'   => array('1.96'),
          'ConnPerSec'  => array('0.19'),
          'ReqPerConn'  => array('8.17'),
          'value1.2'    => array('0'),
          'value1.3'    => array('0'),
          'value1.4'    => array('0'),
          'value2.2'    => array('0'),
          'value2.3'    => array('0'),
          'value2.4'    => array('0'),
          'value3.2'    => array('0'),
          'value3.3'    => array('0'),
          'value3.4'    => array('0'),
          'value4.2'    => array('0'),
          'value4.3'    => array('0'),
          'value4.4'    => array('0'),
          'value5.2'    => array('0'),
          'value5.3'    => array('0'),
          'value5.4'    => array('0'),
          'value6.2'    => array('0'),
          'value6.3'    => array('0'),
          'value6.4'    => array('0'),
          'value7.2'    => array('0'),
          'value7.3'    => array('0'),
          'value7.4'    => array('0')
      );

      $this->assertEquals($a_reference, $ret[0], "data of check_nginx_status");
   }



   /**   
    * @test   
    */
   public function testIftraffic41() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+2 minutes"));
      $input = array(
          'perf_data' => 'inUsage=0.06%;85;98 outUsage=0.50%;85;98 inBandwidth=580585.00bps outBandwidth=5010017.19bps inAbsolut=58697810111 outAbsolut=125801495656',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_iftraffic41'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);
      $a_reference = array(
          'inpercentcurr'     => array('0.06'),
          'inpercentwarn'     => array('85'),
          'inpercentcrit'     => array('98'),
          'outpercentcurr'   => array('0.5'),
          'outpercentwarn'    => array('85'),
          'outpercentcrit'    => array('98'),
          'inbps'             => array('580585'),
          'outbps'            => array('5010017'),
          'inbound'           => array('58697810111'),
          'outbound'          => array('125801495656')
      );

      $this->assertEquals($a_reference, $ret[0], "data of check_iftraffic41");
   }



   /**   
    * @test   
    */
   public function testNoData() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+22 minutes"));

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_tcp'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'response_time'  => array('0', '0', '0', '0'),
          'other'          => array('0', '0', '0', '0'),
          'timeout'        => array('0', '0', '0', '0'),
          'warning'        => array('0', '0', '0', '0'),
          'critical'        => array('0', '0', '0', '0')
      );

      $this->assertEquals($a_reference, $ret[0], "Data of check_tcp");
   }



   /**   
    * @test   
    */
   public function testMissNewData() {
      global $DB;

      $DB->query('TRUNCATE TABLE `glpi_plugin_monitoring_serviceevents`');

      $pmServiceevent = new PluginMonitoringServiceevent();

      $begin_date = date('Y-m-d H:i:s');
      $end_date = Date('Y-m-d H:i:s', strtotime("+22 minutes"));
      $input = array(
          'perf_data' => 'time=0.019037s;;;0.000000;3.000000',
          'date'      => $begin_date
      );
      $pmServiceevent->add($input);

      $query = 'SELECT * FROM `glpi_plugin_monitoring_serviceevents`';
      $result = $DB->query($query);

      $a_perfdatas = getAllDatasFromTable("glpi_plugin_monitoring_perfdatas",
                                          "`name`='check_tcp'");
      $a_perfdata = current($a_perfdatas);
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks(1);

      $ret = $pmServiceevent->getData($result, $a_perfdata['id'], $begin_date, $end_date);

      $a_reference = array(
          'response_time'  => array('19.0', '0', '0', '0', '0'),
          'other'          => array('0', '0', '0', '0', '0'),
          'timeout'        => array('3000.0', '0', '0', '0', '0'),
          'warning'        => array('0', '0', '0', '0', '0'),
          'critical'        => array('0', '0', '0', '0', '0')
      );

      $this->assertEquals($a_reference, $ret[0], "Data of check_tcp");
   }

// inUsage=0.06%;85;98 outUsage=0.50%;85;98 inBandwidth=580585.00bps outBandwidth=5010017.19bps inAbsolut=58697810111 outAbsolut=125801495656


   /**   
    * @test   
    */
   public function testSplitPerfdataNbSessions() {
      global $DB;

      $perfdata = "'Nb de sessions actives'=2;15;20;";

      $a_perfdata = PluginMonitoringPerfdata::splitPerfdata($perfdata);

      $a_reference = array(
          "'Nb de sessions actives'=2;15;20;"
      );

      $this->assertEquals($a_reference, $a_perfdata);
   }



   /**   
    * @test   
    */
   public function testSplitPerfdataDisk() {
      global $DB;

      $perfdata = "'C: %'=3%;25;75 'C:'=14.32G;116.416;349.247;0;465.662";

      $a_perfdata = PluginMonitoringPerfdata::splitPerfdata($perfdata);

      $a_reference = array(
          "'C: %'=3%;25;75",
          "'C:'=14.32G;116.416;349.247;0;465.662"
      );

      $this->assertEquals($a_reference, $a_perfdata);
   }



   /**   
    * @test   
    */
   public function testSplitPerfdataPrinter() {
      global $DB;

      $perfdata = "'Cut pages'=15c 'Retracted pages'=3c";

      $a_perfdata = PluginMonitoringPerfdata::splitPerfdata($perfdata);

      $a_reference = array(
          "'Cut pages'=15c",
          "'Retracted pages'=3c"
      );

      $this->assertEquals($a_reference, $a_perfdata);
   }

}

?>
