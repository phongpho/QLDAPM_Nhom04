<?php
/* File shim: giữ link cũ trong navbar/footer (sanpham.php) hoạt động.
   Chỉ gán action rồi đưa vào router Module 3 & 6 ở public/index.php */
$_REQUEST['action'] = 'sanpham';
require __DIR__ . '/index.php';
