DELIMITER $$
DROP TRIGGER IF EXISTS `OBATOUT_AFINSERT`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `OBATOUT_AFINSERT` AFTER INSERT ON `tobatout` FOR EACH ROW BEGIN
SET @KELUAR=NEW.KDTRANS;
SET @KDOBAT=NEW.KDOBAT;
SET @JKELUAR=NEW.QTY;
SET @ID=(SELECT id FROM tobatin WHERE kdobat=@KDOBAT AND sisa > 0 ORDER BY kdtrans  LIMIT 1);
SET @SISA=(SELECT SISA FROM TOBATIN WHERE ID=@ID);
REPEAT
IF(@SISA>@JKELUAR) THEN
UPDATE TOBATIN SET SISA=@SISA-@JKELUAR, KELUAR=KELUAR+@JKELUAR WHERE ID=@ID;
SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @JKELUAR);
SET @JKELUAR=0;
END IF;
IF (@SISA<@JKELUAR) THEN
UPDATE TOBATIN SET SISA=0, KELUAR=KELUAR+@SISA WHERE ID=@ID;
SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @SISA);
SET @JKELUAR=@JKELUAR-@SISA;
SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND SISA>0 ORDER BY KDTRANS LIMIT 1);
SET @SISA=(SELECT SISA FROM TOBATIN WHERE ID=@ID);
END IF;
IF(@SISA=@JKELUAR) THEN
UPDATE TOBATIN SET SISA=0,KELUAR=KELUAR+@JKELUAR WHERE ID=@ID;
SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @JKELUAR);
SET @JKELUAR=0;
END IF;
UNTIL @JKELUAR=0
END REPEAT;
END;

DROP TRIGGER IF EXISTS `OBATOUT_AFUPDATE`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `OBATOUT_AFUPDATE` AFTER UPDATE ON `tobatout` FOR EACH ROW BEGIN
SET @NQTY = NEW.QTY;
SET @KDOBAT = NEW.KDOBAT;
SET @KELUAR = NEW.KDTRANS;
SET @OQTY = OLD.QTY;

IF (@NQTY < @OQTY) THEN
	SET @QTY = @OQTY - @NQTY;
	SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND KELUAR > 0 ORDER BY KDTRANS  DESC LIMIT 1);
	SET @KELUAR=(SELECT KELUAR FROM TOBATIN WHERE ID=@ID);
	REPEAT
		IF(@KELUAR > @QTY) THEN
			UPDATE TOBATIN SET SISA=SISA+@QTY, KELUAR=KELUAR-@QTY WHERE ID=@ID;
			SET @QTY=0;
		END IF;
		IF (@KELUAR < @QTY) THEN
			UPDATE TOBATIN SET KELUAR=0, SISA=SISA+@KELUAR WHERE ID=@ID;
			SET @QTY=@QTY-@KELUAR;
			SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND KELUAR > 0 ORDER BY KDTRANS  DESC LIMIT 1);
			SET @KELUAR=(SELECT KELUAR FROM TOBATIN WHERE ID=@ID);
		END IF;
		IF(@KELUAR = @QTY) THEN
			UPDATE TOBATIN SET KELUAR=0, SISA=SISA+@KELUAR WHERE ID=@ID;
			SET @QTY=0;
		END IF;
	UNTIL @QTY=0
	END REPEAT;
END IF;

IF (@NQTY > @OQTY) THEN
	SET @JKELUAR=@NQTY-@OQTY;
	SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND SISA>0 ORDER BY KDTRANS  LIMIT 1);
	SET @SISA=(SELECT SISA FROM TOBATIN WHERE ID=@ID);
	REPEAT
		IF(@SISA>@JKELUAR) THEN
			UPDATE TOBATIN SET SISA=@SISA-@JKELUAR, KELUAR=KELUAR+@JKELUAR WHERE ID=@ID;
            SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID);
            INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @JKELUAR);
			SET @JKELUAR=0;
		END IF;
		IF (@SISA<@JKELUAR) THEN
			UPDATE TOBATIN SET SISA=0, KELUAR=KELUAR+@SISA WHERE ID=@ID;
			SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
			INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @SISA);
			SET @JKELUAR=@JKELUAR-@SISA;
			SET @ID=(SELECT ID FROM TOBATIN WHERE KDOBAT=@KDOBAT AND SISA>0 ORDER BY KDTRANS LIMIT 1);
			SET @SISA=(SELECT SISA FROM TOBATIN WHERE ID=@ID);
		END IF;
		IF(@SISA=@JKELUAR) THEN
			UPDATE TOBATIN SET SISA=0,KELUAR=KELUAR+@JKELUAR WHERE ID=@ID;
			SET @MASUK=(SELECT kdtrans FROM tobatin WHERE id=@ID); 
			INSERT INTO tfifo (MASUK, KELUAR, KDOBAT, QTY) VALUES (@MASUK, @KELUAR, @KDOBAT, @JKELUAR);
			SET @JKELUAR=0;
		END IF;
	UNTIL @JKELUAR=0
	END REPEAT;
END IF;
END;
$$

CREATE TABLE `dbfarmastok`.`tfifo` ( 
`id` INT NOT NULL AUTO_INCREMENT , 
`masuk` VARCHAR(15) NOT NULL , 
`keluar` VARCHAR(15) NOT NULL , 
`kdobat` VARCHAR(15) NOT NULL , 
PRIMARY KEY (`id`)) 
ENGINE = MyISAM;