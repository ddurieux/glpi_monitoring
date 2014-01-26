DELIMITER $$

-- USE `glpidb`$$

DROP TRIGGER /*!50032 IF EXISTS */ `ai_glpi_plugin_monitoring_hostcounters`$$

CREATE
    /*!50017 DEFINER = 'root'@'%' */
    TRIGGER `ai_glpi_plugin_monitoring_hostcounters` AFTER INSERT ON `glpi_plugin_monitoring_hostcounters` 
    FOR EACH ROW BEGIN
	DECLARE _dailyCountersExist TINYINT;
	DECLARE _yesterdayCountersExist TINYINT;
	DECLARE _day DATE;
	DECLARE _dayBefore DATE;
	DECLARE _changedToday TINYINT;
	DECLARE _previousCounter INT(11);
	
	-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Host : ', NEW.hostname, ', date : ', NEW.date, ', new counter : ', NEW.counter, ', value : ', NEW.value));

	SELECT DATE(new.date) INTO _day;
	SELECT DATE(DATE_SUB(new.date, INTERVAL 1 DAY)) INTO _dayBefore;
	-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Day: ', _day, ', day before: ', _dayBefore));
	
	SELECT COUNT(cPagesTotal) INTO _dailyCountersExist FROM `glpi_plugin_monitoring_hostdailycounters` WHERE `hostname` = new.hostname AND `day` = _day LIMIT 1;
	-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Day counters exist: ', _dailyCountersExist));
	SELECT COUNT(cPagesTotal) INTO _yesterdayCountersExist FROM `glpi_plugin_monitoring_hostdailycounters` WHERE `hostname` = new.hostname AND `day` = _dayBefore LIMIT 1;
	-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Day before counters exist: ', _yesterdayCountersExist));
	-- 	Create daily counters row for concerned host/day ...
	IF _dailyCountersExist = 0 THEN
		-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Creating new daily row ...'));
		
		INSERT INTO `glpi_plugin_monitoring_hostdailycounters` (`hostname`, `day`) VALUES (new.hostname, _day); 
		IF _yesterdayCountersExist = 1 THEN
			-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Copying day before row ...'));
			UPDATE `glpi_plugin_monitoring_hostdailycounters` AS t
			JOIN `glpi_plugin_monitoring_hostdailycounters` AS tb
				ON tb.hostname=new.hostname AND tb.day=_dayBefore
			SET
				t.cPagesTotal = tb.cPagesTotal
				, t.cPagesToday = 0
				, t.cPagesRemaining = tb.cPagesRemaining
				, t.cRetractedTotal = tb.cRetractedTotal
				, t.cRetractedToday = 0
				, t.cPrinterChanged = tb.cPrinterChanged
				, t.cPaperChanged = tb.cPaperChanged
				, t.cBinEmptied = tb.cBinEmptied
				, t.cPaperLoad = tb.cPaperLoad
			WHERE
				t.hostname=new.hostname AND t.day=_day;
		ELSE
			-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Setting new row ...'));
			UPDATE `glpi_plugin_monitoring_hostdailycounters` 
			SET
				cPagesTotal = 0
				, cPagesToday = 0
				, cPagesRemaining = 2000
				, cRetractedTotal = 0
				, cRetractedToday = 0
				, cRetractedRemaining = 0
				, cPaperChanged = 0
				, cPrinterChanged = 0
				, cBinEmptied = 0
				, cPaperLoad = 2000
			WHERE
				`hostname`=new.hostname AND `day`=_day
			LIMIT 1;
		END IF;
	END IF;
	
	-- Update daily paper load counters row for concerned host/day ...
	IF NEW.counter = 'cPaperChanged' THEN
		UPDATE `glpi_plugin_monitoring_hostdailycounters` 
		SET
			cPaperChanged = new.value
			, cPaperLoad = (new.value + 1) * 2000
			, cPagesRemaining = (new.value + 1) * 2000 - cPagesTotal
		WHERE
			`hostname`=new.hostname AND `day`=_day
		LIMIT 1;
	END IF;
	
	-- Update daily bin emptied counters row for concerned host/day ...
	IF NEW.counter = 'cBinEmptied' THEN
		IF _yesterdayCountersExist = 1 THEN
			UPDATE `glpi_plugin_monitoring_hostdailycounters` AS t
			JOIN `glpi_plugin_monitoring_hostdailycounters` AS tb
				ON t.hostname=new.hostname AND t.day=_dayBefore
			SET
				t.cRetractedRemaining = IF (t.cBinEmptied > tb.cBinEmptied, 0, t.cRetractedRemaining)
			WHERE
				t.hostname=new.hostname AND t.day=_day;
		END IF;
		
		UPDATE `glpi_plugin_monitoring_hostdailycounters` 
		SET
			cBinEmptied = new.value
		WHERE
			`hostname`=new.hostname AND `day`=_day
		LIMIT 1;
	END IF;
	
	-- Update daily printer changed counters row for concerned host/day ...
	IF NEW.counter = 'cPrinterChanged' THEN
		IF _yesterdayCountersExist = 1 THEN
			-- Did the counter changed today ?
			SELECT cPrinterChanged INTO _previousCounter FROM `glpi_plugin_monitoring_hostdailycounters` WHERE hostname=new.hostname AND DAY=_dayBefore;
			-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Previous value : ', _previousCounter));
			
			IF new.value > _previousCounter THEN
				-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Updating cPrinterChanged with previous day, value : ', new.value));
				SELECT cPagesRemaining INTO _previousCounter FROM `glpi_plugin_monitoring_hostdailycounters` WHERE hostname=new.hostname AND DAY=_dayBefore;
				-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Previous remaining, value : ', _previousCounter));
				-- If printer changed today, pages printed today are zero an pages remaining do not change
				UPDATE `glpi_plugin_monitoring_hostdailycounters` 
				SET
					cPagesToday = 0
					, cRetractedToday = 0
					, cPagesRemaining = _previousCounter
				WHERE
					`hostname`=new.hostname AND `day`=_day
				LIMIT 1;
			END IF;
		END IF;
		
		UPDATE `glpi_plugin_monitoring_hostdailycounters` 
		SET
			cPrinterChanged = new.value
		WHERE
			`hostname`=new.hostname AND `day`=_day
		LIMIT 1;
		
	END IF;
	
	-- Update daily printed pages counters row for concerned host/day ...
	IF NEW.counter = 'cPagesTotal' THEN
		IF _yesterdayCountersExist = 1 THEN
			-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Updating cPagesTotal with previous day ...'));
			
			UPDATE `glpi_plugin_monitoring_hostdailycounters` AS t
			JOIN `glpi_plugin_monitoring_hostdailycounters` AS tb
				ON tb.hostname=new.hostname AND tb.day=_dayBefore
			SET
				t.cPagesTotal = new.value
				, t.cPagesToday = IF (t.cPrinterChanged > tb.cPrinterChanged, 0, GREATEST(new.value - tb.cPagesTotal, 0))
				, t.cPagesRemaining = IF (t.cPrinterChanged > tb.cPrinterChanged, tb.cPagesRemaining, t.cPaperLoad - new.value)
			WHERE
				t.hostname=new.hostname AND t.day=_day;
		ELSE
			-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Updating cPagesTotal with current day ...'));
			
			UPDATE `glpi_plugin_monitoring_hostdailycounters` 
			SET
				cPagesTotal = new.value
				, cPagesToday = new.value
				, cPagesRemaining = cPaperLoad - new.value
			WHERE
				`hostname`=new.hostname AND `day`=_day
			LIMIT 1;
		END IF;
	END IF;
	
	-- Update daily retracted pages counters row for concerned host/day ...
	IF NEW.counter = 'cRetractedTotal' THEN
		IF _yesterdayCountersExist = 1 THEN
			-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Updating cRetractedTotal with previous day ...'));
			
			UPDATE `glpi_plugin_monitoring_hostdailycounters` AS t
			JOIN `glpi_plugin_monitoring_hostdailycounters` AS tb
				ON tb.hostname=new.hostname AND tb.day=_dayBefore
			SET
				t.cRetractedTotal = new.value
				, t.cRetractedToday = IF (t.cPrinterChanged > tb.cPrinterChanged, 0, GREATEST(new.value - tb.cRetractedTotal, 0))
				, t.cRetractedRemaining = IF (t.cPrinterChanged > tb.cPrinterChanged, tb.cRetractedRemaining, new.value)
			WHERE
				t.hostname=new.hostname AND t.day=_day;
		ELSE
			-- INSERT INTO `glpi_plugin_monitoring_import_logs` (`log`) VALUES (CONCAT('Updating cRetractedTotal with current day ...'));
			
			UPDATE `glpi_plugin_monitoring_hostdailycounters` 
			SET
				cRetractedTotal = new.value
				, cRetractedToday = new.value
				, cRetractedRemaining = new.value
			WHERE
				`hostname`=new.hostname AND `day`=_day
			LIMIT 1;
		END IF;
	END IF;
    END;
$$

DELIMITER ;