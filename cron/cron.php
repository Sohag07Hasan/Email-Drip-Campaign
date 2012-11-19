<?php
/*
 * handle the cron jobs
 * */

set_time_limit(0);

include '../../../../wp-load.php';

Scheduler_cron_job::handle_scheduler();
