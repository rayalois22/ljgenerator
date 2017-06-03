<?php 
## Illustration of how to get and format the timestamp in php
## 'Y' -> Year, 'm' -> Month, 'l' -> Day of the week eg Monday, 'd' -> Day of the month eg. 31
## 'h' -> Hour 01-12, 'i' -> Minutes 00-59, 's' -> Seconds 00-59
	$datetime = new DateTime('now');
	#echo $datetime;
	$time = date("Y.m.l.d.h.i.s");
	echo $time;
?>