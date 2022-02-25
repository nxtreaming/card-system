<?php
namespace App\Library; class UrlShorten { public static function shorten($sp8e97b1, $sp289ab3 = false) { if ($sp289ab3 === false) { $sp289ab3 = \App\System::_get('domain_shorten'); } if ($sp289ab3 === '1' || $sp289ab3 === 'url.cn') { $spf2a468 = UrlShorten::url_cn($sp8e97b1); } elseif ($sp289ab3 === '2' || $sp289ab3 === 't.cn') { $spf2a468 = UrlShorten::t_cn($sp8e97b1); } elseif ($sp289ab3 === 'w.url.cn') { $spf2a468 = UrlShorten::w_url_cn($sp8e97b1); } elseif ($sp289ab3 === 'custom') { $spf2a468 = UrlShorten::custom($sp8e97b1); } else { return $sp8e97b1; } } public static function t_cn_official($sp8e97b1) { $sp8e97b1 = urlencode($sp8e97b1); $sp714e68 = '2590114856'; $sp937a35 = 'http://api.t.sina.com.cn/short_url/shorten.json?source=' . $sp714e68 . '&url_long=' . $sp8e97b1; $spb14efa = curl_init(); curl_setopt($spb14efa, CURLOPT_URL, $sp937a35); curl_setopt($spb14efa, CURLOPT_RETURNTRANSFER, 1); curl_setopt($spb14efa, CURLOPT_SSL_VERIFYPEER, 0); curl_setopt($spb14efa, CURLOPT_HEADER, 0); curl_setopt($spb14efa, CURLOPT_HTTPHEADER, array('Content-type: application/json')); $sp501b73 = curl_exec($spb14efa); curl_close($spb14efa); $sp37532d = json_decode($sp501b73, true); return isset($sp37532d['url_short']) && strstr($sp37532d['url_short'], 'http://') ? $sp37532d['url_short'] : null; } public static function t_cn($sp8e97b1) { $sp8e97b1 = urlencode($sp8e97b1); $sp937a35 = 'https://i.alapi.cn/url/?url=' . $sp8e97b1; $spb14efa = curl_init(); curl_setopt($spb14efa, CURLOPT_URL, $sp937a35); curl_setopt($spb14efa, CURLOPT_RETURNTRANSFER, 1); curl_setopt($spb14efa, CURLOPT_SSL_VERIFYPEER, 0); curl_setopt($spb14efa, CURLOPT_HEADER, 0); curl_setopt($spb14efa, CURLOPT_HTTPHEADER, array('Content-type: application/json')); $sp501b73 = curl_exec($spb14efa); curl_close($spb14efa); $sp37532d = json_decode($sp501b73, true); return isset($sp37532d['shortUrl']) && strstr($sp37532d['shortUrl'], 'http') ? $sp37532d['shortUrl'] : null; } public static function url_cn($sp8e97b1) { $sp8e97b1 = urlencode($sp8e97b1); $sp937a35 = 'https://api.uomg.com/api/long2dwz?dwzapi=urlcn&url=' . $sp8e97b1; $spb14efa = curl_init(); curl_setopt($spb14efa, CURLOPT_URL, $sp937a35); curl_setopt($spb14efa, CURLOPT_RETURNTRANSFER, 1); curl_setopt($spb14efa, CURLOPT_SSL_VERIFYPEER, 0); curl_setopt($spb14efa, CURLOPT_HEADER, 0); curl_setopt($spb14efa, CURLOPT_HTTPHEADER, array('Content-type: application/json')); curl_setopt($spb14efa, CURLOPT_TIMEOUT, 5); curl_setopt($spb14efa, CURLOPT_CONNECTTIMEOUT, 5); $sp501b73 = curl_exec($spb14efa); curl_close($spb14efa); $sp37532d = json_decode($sp501b73, true); return isset($sp37532d['ae_url']) && strstr($sp37532d['ae_url'], 'http') ? $sp37532d['ae_url'] : null; } public static function w_url_cn($sp8e97b1) { return null; } public static function custom($sp8e97b1) { $sp194b55 = ''; $sp9494dc = ''; $sp8e97b1 = urlencode($sp8e97b1); $sp937a35 = 'http://api.his.cat/api/url/shorten.json?id=' . $sp194b55 . '&key=' . $sp9494dc . '&url=' . $sp8e97b1; $spb14efa = curl_init(); curl_setopt($spb14efa, CURLOPT_URL, $sp937a35); curl_setopt($spb14efa, CURLOPT_RETURNTRANSFER, 1); curl_setopt($spb14efa, CURLOPT_SSL_VERIFYPEER, 0); curl_setopt($spb14efa, CURLOPT_HEADER, 0); curl_setopt($spb14efa, CURLOPT_HTTPHEADER, array('Content-type: application/json')); curl_setopt($spb14efa, CURLOPT_TIMEOUT, 5); curl_setopt($spb14efa, CURLOPT_CONNECTTIMEOUT, 5); $sp501b73 = curl_exec($spb14efa); curl_close($spb14efa); $sp37532d = json_decode($sp501b73, true); return isset($sp37532d['data']) && isset($sp37532d['data']['short_url']) && strstr($sp37532d['data']['short_url'], 'http') ? $sp37532d['data']['short_url'] : null; } }