<?php
/* File shim cho trang thanh toán (thanhtoan.php).
   Chỉ gán action rồi đưa vào router Module 3 & 6 ở public/index.php */
$_REQUEST['action'] = 'thanhtoan';
require __DIR__ . '/index.php';
