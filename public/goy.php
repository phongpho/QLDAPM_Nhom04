<?php
/* File shim: giữ link "Gợi ý theo chỉ số" (goy.php) trên navbar hoạt động.
   Chỉ gán action rồi đưa vào router Module 3 & 6 ở public/index.php */
$_REQUEST['action'] = 'goy';
require __DIR__ . '/index.php';
