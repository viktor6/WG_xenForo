<?php
define('API_URL', 'http://api.worldoftanks.ru/');
define('API_VERSION', 'wot');
define('CLANINFO_METHOD', API_URL.API_VERSION.'/clan/info/');
define('APPLICATION_ID', 'demo');
define('CLAN_ID', '1');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, CLANINFO_METHOD . "?application_id=" . APPLICATION_ID . "&clan_id=" . CLAN_ID);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
$result = json_decode(curl_exec($ch), true);

if (curl_errno($ch))
{
    echo "CURL returned error: ".curl_error($ch)."\n";
    die();
}
curl_close($ch);
?>
<html>
<head>
<meta charset="utf-8" />
    <title>Example</title>
</head>
<body>
<?
if(empty($result['status']) || $result['status'] == 'error'){
    echo '<div style="background-color: #EDEDE8;">';
    foreach($result['error'] as $key => $value){
        echo "  <b>{$key}:</b> {$value}<br/>";
    }
    echo '</div>';
}else{

/*ЭТО НАЧАЛО*/  

    $myhost = 'localhost';
    $myuser = 'test';
    $mypassw = '123456789';
    $mybd = 'xenforo';

    $link = mysqli_connect($myhost,$myuser,$mypassw,$mybd)
            or die("Ошибка  : " . mysqli_connect_error()); 

  $sql = "SELECT b.user_id, b.field_value FROM xf_user_field_value AS b WHERE b.field_value<>'';";
    $q = mysqli_query($link, $sql);
     while($line = mysqli_fetch_assoc($q))
     {
         // $db_users[] = $line["field_value"];
          $db_users[$line["field_value"]] = $line["user_id"];
       // var_dump($db_users); 
     }

//$clan_users = array_keys ($member_data['account_name']);
  // var_dump($clan_users);





    	foreach($result['data'] as $clan) {

        echo '<div style="background-color: #EDEDE8;">';
	    echo "  <b>Clan:</b> " . $clan['abbreviation'] . "<br/>";
	
		foreach($clan['members'] as $member_id => $member_data){
            echo "  <b>Nickname_id:</b> " . $member_data['account_id'] . "<br/>";
			echo "  <b>Nickname:</b> " . $member_data['account_name'] . "<br/>";
//var_dump($member_data['account_name'], in_array($db_users[$line['field_value']], $member_data['account_name']));
   //           $api_users = json_decode(curl_exec($result['data']), true);
//var_dump($db_users);
       // if (in_array($member_data['account_name'], $db_users))
          if (isset($db_users[$member_data['account_name']]))  
         {
     $sql = "UPDATE xf_user SET `secondary_group_ids`='4' where `user_id` ='".$db_users[$member_data['account_name']]."';";
      $q = mysqli_query($link, $sql);
     } else {
     //$sql = "UPDATE xf_user SET `secondary_group_ids`='';";
      $q = mysqli_query($link, $sql);
 //var_dump($sql);
    }
     

			echo "  <b>Role:</b> " . $member_data['role_i18n'] . "<br/>";
			echo "  <b>В клане:</b> " . date('d.m.Y H:i', $member_data['created_at']) . "<br/>";
			echo "  <b>role2:</b> " . $member_data['role'] . "<br/>";
			echo "  ======================== <br/>"	 ;
        }
        echo '</div>';
    }
}
?>
</body>
</html>
