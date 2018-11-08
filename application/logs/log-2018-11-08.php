<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

DEBUG - 2018-11-08 21:50:52 --> UTF-8 Support Enabled
DEBUG - 2018-11-08 21:50:52 --> No URI present. Default controller set.
DEBUG - 2018-11-08 21:50:52 --> Global POST, GET and COOKIE data sanitized
ERROR - 2018-11-08 21:50:52 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'root'@'localhost' (using password: NO) /Users/capucivar/Documents/GraduationDesign/FAMS/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-11-08 21:50:52 --> Unable to connect to the database
ERROR - 2018-11-08 21:50:52 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/capucivar/Documents/GraduationDesign/FAMS/system/core/Exceptions.php:272) /Users/capucivar/Documents/GraduationDesign/FAMS/system/core/Common.php 573
DEBUG - 2018-11-08 22:04:48 --> UTF-8 Support Enabled
DEBUG - 2018-11-08 22:04:48 --> No URI present. Default controller set.
DEBUG - 2018-11-08 22:04:48 --> Global POST, GET and COOKIE data sanitized
ERROR - 2018-11-08 22:04:48 --> Query error: Table 'fams.user_token' doesn't exist - Invalid query: 
                SELECT
                        *
                FROM
                        `user`
                INNER JOIN user_token AS ut ON `user`.userid = ut.userid
                WHERE
                        ut.token = NULL
                AND `user`.isvalid = 1
                AND ut.isvalid = 1
DEBUG - 2018-11-08 22:08:15 --> UTF-8 Support Enabled
DEBUG - 2018-11-08 22:08:15 --> No URI present. Default controller set.
DEBUG - 2018-11-08 22:08:15 --> Global POST, GET and COOKIE data sanitized
ERROR - 2018-11-08 22:08:15 --> Severity: Notice --> Undefined property: Home::$NoticeModel /Users/capucivar/Documents/GraduationDesign/FAMS/application/controllers/Home.php 20
ERROR - 2018-11-08 22:08:15 --> Severity: error --> Exception: Call to a member function getNoticeNew() on null /Users/capucivar/Documents/GraduationDesign/FAMS/application/controllers/Home.php 20
ERROR - 2018-11-08 22:08:15 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/capucivar/Documents/GraduationDesign/FAMS/application/controllers/BaseC.php:30) /Users/capucivar/Documents/GraduationDesign/FAMS/system/core/Common.php 573
DEBUG - 2018-11-08 22:08:16 --> UTF-8 Support Enabled
DEBUG - 2018-11-08 22:08:16 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2018-11-08 22:08:16 --> Total execution time: 0.0379
DEBUG - 2018-11-08 22:08:17 --> UTF-8 Support Enabled
DEBUG - 2018-11-08 22:08:17 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2018-11-08 22:08:17 --> Total execution time: 0.0129
