<?php
namespace App\Library\Geetest; class Lib { const GT_SDK_VERSION = 'php_3.2.0'; public static $connectTimeout = 1; public static $socketTimeout = 1; private $response; public $captcha_id; public $private_key; public function __construct($sp628536, $sp669250) { $this->captcha_id = $sp628536; $this->private_key = $sp669250; } public function pre_process($sp6738b1 = null) { $sp783cd0 = 'http://api.geetest.com/register.php?gt=' . $this->captcha_id; if ($sp6738b1 != null and is_string($sp6738b1)) { $sp783cd0 = $sp783cd0 . '&user_id=' . $sp6738b1; } $sp684049 = $this->send_request($sp783cd0); if (strlen($sp684049) != 32) { $this->failback_process(); return 0; } $this->success_process($sp684049); return 1; } private function success_process($sp684049) { $sp684049 = md5($sp684049 . $this->private_key); $sp75fce3 = array('success' => 1, 'gt' => $this->captcha_id, 'challenge' => $sp684049); $this->response = $sp75fce3; } private function failback_process() { $sp1a7db7 = md5(rand(0, 100)); $spe7929e = md5(rand(0, 100)); $sp684049 = $sp1a7db7 . substr($spe7929e, 0, 2); $sp75fce3 = array('success' => 0, 'gt' => $this->captcha_id, 'challenge' => $sp684049); $this->response = $sp75fce3; } public function get_response_str() { return json_encode($this->response); } public function get_response() { return $this->response; } public function success_validate($sp684049, $sp0d8b50, $sp628a4d, $sp6738b1 = null) { if (!$this->check_validate($sp684049, $sp0d8b50)) { return 0; } $spb97786 = array('seccode' => $sp628a4d, 'sdk' => self::GT_SDK_VERSION); if ($sp6738b1 != null and is_string($sp6738b1)) { $spb97786['user_id'] = $sp6738b1; } $sp783cd0 = 'http://api.geetest.com/validate.php'; $spff1a0b = $this->post_request($sp783cd0, $spb97786); if ($spff1a0b == md5($sp628a4d)) { return 1; } else { if ($spff1a0b == 'false') { return 0; } else { return 0; } } } public function fail_validate($sp684049, $sp0d8b50, $sp628a4d) { if ($sp0d8b50) { $spf43b52 = explode('_', $sp0d8b50); try { $spa9bdfd = $this->decode_response($sp684049, $spf43b52['0']); $spca4e10 = $this->decode_response($sp684049, $spf43b52['1']); $sp69bbf9 = $this->decode_response($sp684049, $spf43b52['2']); $sp90e63b = $this->get_failback_pic_ans($spca4e10, $sp69bbf9); $sp31252f = abs($spa9bdfd - $sp90e63b); } catch (\Exception $spf745ad) { return 1; } if ($sp31252f < 4) { return 1; } else { return 0; } } else { return 0; } } private function check_validate($sp684049, $sp0d8b50) { if (strlen($sp0d8b50) != 32) { return false; } if (md5($this->private_key . 'geetest' . $sp684049) != $sp0d8b50) { return false; } return true; } private function send_request($sp783cd0) { if (function_exists('curl_exec')) { $sp7732b4 = curl_init(); curl_setopt($sp7732b4, CURLOPT_URL, $sp783cd0); curl_setopt($sp7732b4, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout); curl_setopt($sp7732b4, CURLOPT_TIMEOUT, self::$socketTimeout); curl_setopt($sp7732b4, CURLOPT_RETURNTRANSFER, 1); $spb97786 = curl_exec($sp7732b4); if (curl_errno($sp7732b4)) { $sp63e473 = sprintf('curl[%s] error[%s]', $sp783cd0, curl_errno($sp7732b4) . ':' . curl_error($sp7732b4)); $this->triggerError($sp63e473); } curl_close($sp7732b4); } else { $sp77ba63 = array('http' => array('method' => 'GET', 'timeout' => self::$connectTimeout + self::$socketTimeout)); $sp518297 = stream_context_create($sp77ba63); $spb97786 = file_get_contents($sp783cd0, false, $sp518297); } return $spb97786; } private function post_request($sp783cd0, $sp55e091 = '') { if (!$sp55e091) { return false; } $spb97786 = http_build_query($sp55e091); if (function_exists('curl_exec')) { $sp7732b4 = curl_init(); curl_setopt($sp7732b4, CURLOPT_URL, $sp783cd0); curl_setopt($sp7732b4, CURLOPT_RETURNTRANSFER, 1); curl_setopt($sp7732b4, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout); curl_setopt($sp7732b4, CURLOPT_TIMEOUT, self::$socketTimeout); if (!$sp55e091) { curl_setopt($sp7732b4, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); } else { curl_setopt($sp7732b4, CURLOPT_POST, 1); curl_setopt($sp7732b4, CURLOPT_POSTFIELDS, $spb97786); } $spb97786 = curl_exec($sp7732b4); if (curl_errno($sp7732b4)) { $sp63e473 = sprintf('curl[%s] error[%s]', $sp783cd0, curl_errno($sp7732b4) . ':' . curl_error($sp7732b4)); $this->triggerError($sp63e473); } curl_close($sp7732b4); } else { if ($sp55e091) { $sp77ba63 = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded
' . 'Content-Length: ' . strlen($spb97786) . '
', 'content' => $spb97786, 'timeout' => self::$connectTimeout + self::$socketTimeout)); $sp518297 = stream_context_create($sp77ba63); $spb97786 = file_get_contents($sp783cd0, false, $sp518297); } } return $spb97786; } private function decode_response($sp684049, $sp4efd50) { if (strlen($sp4efd50) > 100) { return 0; } $spb39cc2 = array(); $spdbd67b = array(); $spac949e = array('0' => 1, '1' => 2, '2' => 5, '3' => 10, '4' => 50); $sp88dd02 = 0; $sp89a09f = 0; $sp516614 = str_split($sp684049); $spab3751 = str_split($sp4efd50); for ($sp02b863 = 0; $sp02b863 < strlen($sp684049); $sp02b863++) { $sp5f5b2b = $sp516614[$sp02b863]; if (in_array($sp5f5b2b, $spdbd67b)) { continue; } else { $spf43b52 = $spac949e[$sp88dd02 % 5]; array_push($spdbd67b, $sp5f5b2b); $sp88dd02++; $spb39cc2[$sp5f5b2b] = $spf43b52; } } for ($spd0792c = 0; $spd0792c < strlen($sp4efd50); $spd0792c++) { $sp89a09f += $spb39cc2[$spab3751[$spd0792c]]; } $sp89a09f = $sp89a09f - $this->decodeRandBase($sp684049); return $sp89a09f; } private function get_x_pos_from_str($spc886de) { if (strlen($spc886de) != 5) { return 0; } $sp96d9c3 = 0; $spa6663e = 200; $sp96d9c3 = base_convert($spc886de, 16, 10); $sp75fce3 = $sp96d9c3 % $spa6663e; $sp75fce3 = $sp75fce3 < 40 ? 40 : $sp75fce3; return $sp75fce3; } private function get_failback_pic_ans($sp71a696, $sp350c43) { $spb29a9b = substr(md5($sp71a696), 0, 9); $spa29c66 = substr(md5($sp350c43), 10, 9); $sp15d69b = ''; for ($sp02b863 = 0; $sp02b863 < 9; $sp02b863++) { if ($sp02b863 % 2 == 0) { $sp15d69b = $sp15d69b . $spb29a9b[$sp02b863]; } elseif ($sp02b863 % 2 == 1) { $sp15d69b = $sp15d69b . $spa29c66[$sp02b863]; } } $sp1a7664 = substr($sp15d69b, 4, 5); $sp90e63b = $this->get_x_pos_from_str($sp1a7664); return $sp90e63b; } private function decodeRandBase($sp684049) { $spbc84bc = substr($sp684049, 32, 2); $sp2169b0 = array(); for ($sp02b863 = 0; $sp02b863 < strlen($spbc84bc); $sp02b863++) { $sp8f6f5d = ord($spbc84bc[$sp02b863]); $sp75fce3 = $sp8f6f5d > 57 ? $sp8f6f5d - 87 : $sp8f6f5d - 48; array_push($sp2169b0, $sp75fce3); } $sp7bff46 = $sp2169b0['0'] * 36 + $sp2169b0['1']; return $sp7bff46; } private function triggerError($sp63e473) { } }