<?php
/* File shim: giữ link cũ (chitiet.php?id=...) hoạt động.
   Chỉ gán action rồi đưa vào router Module 3 & 6 ở public/index.php */
$_REQUEST['action'] = 'chitiet';
require __DIR__ . '/index.php';
