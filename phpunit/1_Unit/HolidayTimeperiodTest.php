<?php

class HolidayTimeperiodTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function get_holiday() {
      global $DB;

      $calendar         = new Calendar();
      $calendarSegment  = new CalendarSegment();
      $holiday          = new Holiday();
      $calendar_Holiday = new Calendar_Holiday();
      $pmShinken        = new PluginMonitoringShinken();

      $input = array(
         'name' => 'work week',
         'entities_id' => 0
      );
      $calendars_id = $calendar->add($input);

      $input = array(
         'calendars_id' => $calendars_id,
         'entities_id'  => 0,
         'is_recursive' => 0,
         'day'          => 1,
         'begin'        => '08:00:00',
         'end'          => '20:00:00'
      );
      $calendarSegment->add($input);

      $input['day'] = 2;
      $calendarSegment->add($input);

      $input['day'] = 3;
      $calendarSegment->add($input);

      $input['day'] = 4;
      $calendarSegment->add($input);

      $input['day'] = 5;
      $calendarSegment->add($input);

      $input = array(
         'name'         => 'december 25',
         'entities_id'  => 0,
         'begin_date'   => '2015-12-25',
         'end_date'     => '2015-12-25',
         'is_perpetual' => 1
      );
      $holidays_id = $holiday->add($input);

      $input = array(
         'calendars_id' => $calendars_id,
         'holidays_id'  => $holidays_id
      );
      $calendar_Holiday->add($input);

      $hols = $pmShinken->_addHoliday(0, $holidays_id);

      $this->assertEquals('december25', $hols, "Holiday not right");
   }



   /**
    * @test
    */
   public function get_timeperiods() {

      $pmShinken = new PluginMonitoringShinken();

      $pmShinken->_addTimeperiod(0, 4);

      $tp = $pmShinken->generateTimeperiodsCfg();

      $a_references = array(
         0 => array(
            'timeperiod_name' => 'workweek',
            'alias'           => 'work week',
            'monday'          => '08:00-20:00',
            'tuesday'         => '08:00-20:00',
            'wednesday'       => '08:00-20:00',
            'thursday'        => '08:00-20:00',
            'friday'          => '08:00-20:00',
            'exclude'         => 'december25'
         ),
         1 => array(
            'timeperiod_name' => 'december25',
            'alias'           => 'december 25',
            'december 25'     => '00:00-24:00'
         )
      );

      $this->assertEquals($a_references, $tp, "timeperiods");

   }

}

?>
