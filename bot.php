<?php
error_reporting(0);
function curl($url, $post = 0, $httpheader = 0, $proxy = 0){ // url, postdata, http headers, proxy, uagent
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        if($post){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if($httpheader){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        }
        if($proxy){
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
            // curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch);
        if(!$httpcode) return "Curl Error : ".curl_error($ch); else{
            $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            curl_close($ch);
            return array($header, $body);
        }
    }
function head(){
  $h[]="Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
  $h[]="User-Agent: Dalvik/2.1.0 (Linux; U; Android 7.0; Redmi Note 4 MIUI/V11.0.2.0.NCFMIXM)";
  $h[]="Host: quizearner.quizearner.com";
  return $h;
}
function news($mail,$name,$pass){
  $url="http://quizearner.quizearner.com/api/players/new";
  $data="email=$mail&name=$name&password=$pass&device_id=".substr(md5(time()),0,16)."&";
  return curl($url,$data,head());
}
function add($email,$ref){
  $url="http://quizearner.quizearner.com/api/players/referral/add";
  $data="email=$email&referral=$ref&";
  return curl($url,$data,head());
}
function getdata($mail){
  $url="http://quizearner.quizearner.com/api/players/getplayerdata";
  $data="email=$mail&";
  return curl($url,$data,head());
}
function update($id,$poin){
  $url="http://quizearner.quizearner.com/api/players/$id/update";
  $data="points=$poin";
  return curl($url,$data,head());
}
function wd($poin,$email,$id){
  $url="http://quizearner.quizearner.com/api/withdrawals/request/new";
  $on=$poin/1000;
  $data="amount=$on&account=$email&method=PayPal&player_id=$id&points=$poin&";
  return curl($url,$data,head());
}
function tmp(){
  echo "\e[1;37m~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\e[0m\n";
}
function ban(){
  $ban="\e[1;36m
_  _ ____ _  _ ____ ___ ____  _ _ 
|_/  |__| |_/  |__|  |  |  |  | | 
| \_ |  | | \_ |  |  |  |__| _| | 
  \n";
  echo $ban;
}
function save($data,$data_post){
  if(!file_get_contents($data)){
    file_put_contents($data,"[]");}
    $ad=json_decode(file_get_contents($data),1);
    $arr=array_merge($ad,$data_post);
    file_put_contents($data,json_encode($arr,JSON_PRETTY_PRINT));
}
function ruser(){
  $url='https://randomuser.me/api/?format=json';
  return json_decode(file_get_contents($url),1);
}
//warna
$cl=shell_exec('clear');$r="\e[1;31m";$g="\e[1;32m";
$y="\e[1;33m";$p="\e[1;35m";$c="\e[1;36m";$w="\e[1;37m";$dl="\e[0m";
while(1):
echo $cl;
ban();
tmp();
echo "{$y}ALL PASSWORD AKUN REFERALL {$c}123456\n";
echo "{$r}[1]{$g}create referall\n";
echo "{$r}[2]{$g}Cek all user & add refferal\n";
echo "{$r}[3]{$g}Akun utama up poin\n";
echo "{$r}[4]{$g}Akun reff up poin\n";
echo "{$r}[5]{$g}Witdhraw\n";
tmp();
$menu=readline("{$r}[>]{$p}input menu{$c}:");
tmp();
switch ($menu) {
  case 1:
while(1){
foreach(ruser() as $head => $body){
 $name=$body[0]['name']['first'];
 $mail=$body[0]['login']['username']."@gmail.com";
 $pass="123456";
 $data="reff.json";
 $arr=[$name=>[
   "nama"  => $name,
   "email" => $mail,
   "pass"  => $pass
   ]];
   save($data,$arr);
 $i=1;
 $getdata=json_decode(news($mail,$name,$pass)[1],1);
 echo "{$w}[{$r}".$i++."{$w}]{$g}".$getdata["message"]." {$r}|{$r} email: {$c}".$mail."{$dl}\n";
 }
}
break;
  case 2:
    $data="code.json";
    if(!file_exists($data)){
      $rr=readline("{$r}[>]{$g}input code reff{$c}: ");
      $sk=["codeReff"=>$rr];
      save($data,$sk);
    }
    $drf=json_decode(file_get_contents("reff.json"),1);
    $ref=json_decode(file_get_contents("code.json"),1);
    foreach($drf as $head => $body){
      $mail=$body['email'];
      $prof=json_decode(add($mail,$ref['codeReff'])[1],1);
      echo "{$r}[{$w}+{$r}]{$c} ".$mail." {$r}|| {$g}".$prof["message"]." add your refferal {$p}".$ref['codeReff']."{$dl}\n";
    }
break;
 case 3:
   $data="my.json";
   if(!file_exists($data)){
     $my=readline("{$r}[>]{$g}Your email{$c}:");
     echo "\n";
     $pass=readline("{$r}[>]{$g}Your password{$c}:");
     $myi=["email"=>$my,"pass"=>$pass];
     save($data,$myi);
   }
   
   $data=json_decode(file_get_contents($data),1);
   while(1){
   $get=json_decode(getdata($data['email'])[1],1);
   $poin=5 * 5;
   $up=json_decode(update($get[0]['id'],$poin)[1],1);
   foreach($get as $head => $body){
        echo "\t{$p}ID    {$c}".$body['id']."\n";
        echo "\t{$p}NAME  {$c}".$body['name']."\n";
        echo "\t{$p}EMAIL {$c}".$body['email']."\n";
        echo "\t{$p}SCORE {$y}".$body['score']."\n";
   }
   echo $g.$up['message']."\n";
   tmp();
}
break;
 case 4:
   $i=1;
   while(1):
   $ref=json_decode(file_get_contents("reff.json"),1);
   foreach($ref as $head => $body){
     $data=json_decode(getdata($body['email'])[1],1);
     $poin=5*5;
     $update=json_decode(update($data[0]['id'],$poin)[1],1);
     
     foreach($data as $im => $us){
       echo "{$p} Total akun reff ({$y}".count($ref)."{$p})\n";
       echo  $g."[{$y}".$i++."{$g}]{$p}\tID    {$c} ".$us['id']."\n";
       echo "{$p}\tNAME  {$c} ".$us['name']."\n";
       echo "{$p}\tEMAIL  {$c}".$us['email']."\n";
       echo "{$p}\tSCORE  {$c}".$us['score']."\n";
     }
     echo $g.$update['message']."\n";
     tmp();
   }
   endwhile;
break;
 case 5:
   $wd="wd.json";
   if(!file_exists($wd)){
     $m=readline("{$r}[>]{$p}Akun paypal{$c}:");
     $data=["paypal"=>$m];
     save($w,$data);
   }
   $pay=json_decode(file_get_contents($wd),1);
   echo "{$r}[1]{$g}Wd akun utama\n";
   echo "{$r}[2]{$g}Wd akun reff\n";
   $main=readline("{$r}[>]{$g}Input{$w}:");
   if($main == 1){
     $my=json_decode(file_get_contents("my.json"),1);
     $get=json_decode(getdata($my["email"])[1],1);
     foreach($get as $head => $body){
     $cek=json_decode(wd($body["score"],$pay['paypal'],$body['id'])[1],1);
     echo "{$r}[+]{$g}status Witdhraw {$c}".$cek['message']." {$y}TO {$c}".$pay["paypal"]."\n";
     tmp();
     }
     
   }else if($main == 2){
     $rf=json_decode(file_get_contents("reff.json"),1);
     foreach($rf as $head => $body){
     $get=json_decode(getdata($body['email'])[1],1);
     foreach($get as $as => $bs){
       $ck=json_decode(wd($bs['score'],$pay["paypal"],$bs["id"])[1],1);
       echo "{$r}[+]{$g} status Witdhraw {$c}".$ck['message']."{$y} to {$c}".$pay['paypal']."\n";
       tmp();
      }
     }
   }
break;
}
readline("{$p}enter ");
endwhile;




