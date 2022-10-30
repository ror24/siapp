<?php

date_default_timezone_set("Asia/Jakarta");

$wkt=date("Y-m-d H:i:s");
require_once"konmysqli.php";

$temp=$wkt;
echo $temp;

$sql="select `temp` from `tb_temp`";
$ada=getJum($conn,$sql);
if($ada>0){
	$d=getField($conn,$sql);
	$temp=$d["temp"];
	


	 $sql2="select `sig_class_id`,`signature`,`sig_name`,`timestamp`,`layer4_dport`,`layer4_sport`,inet_ntoa(`ip_src`) as `ip_src`,inet_ntoa(`ip_dst`) as `ip_dst`,`sig_priority` from `acid_event` where `timestamp` > '$temp' order by cid desc";// where `temp`>'$temp' order by ID desc";
	$d2=getField($conn,$sql2);
	$sig_class_id=$d2["sig_class_id"];
	$signature=$d2["signature"];
	$sig_name=$d2["sig_name"];
	$timestamp=$d2["timestamp"];
	$layer4_dport=$d2["layer4_dport"];
	$layer4_sport=$d2["layer4_sport"];
	
	$ip_src=$d2["ip_src"];
	$ip_dst=$d2["ip_dst"];
	
	if($timestamp=="0000-00-00 00:00:00" || strlen($timestamp)<5){}
	else{
	echo ">>Ada penyusup masuk $ip_src to $ip_dst ($timestamp)/$sig_name";
	
	
	$sqlb="select `sig_class_name` from `sig_class` where sig_class_id='$sig_class_id'";
	$db=getField($conn,$sqlb);
	$sig_class_name=$db["sig_class_name"];
	

			$name = "Admin LAN";
			$message = "$name:Ada percobaan serangan server $ip_src to $ip_dst ($timestamp)/$sig_name: $sig_class_name";
	
	echo"<hr>";
	echo $message;
		
		$sql3="DELETE from `tb_temp`";
		process($conn,$sql3);
		
		 $sql3="INSERT INTO `tb_temp` (`temp`) VALUES ('$timestamp');";
		process($conn,$sql3);
		
		///////////////////
		define ('url',"https://api.telegram.org/bot890094260:AAHVjVM_ALzgl-_JK0-N_VYBH8nMFuk8VDw/");
			$update = json_decode(file_get_contents("php://input") ,true);
			$chat_id ="624225377";
		file_get_contents(url."sendmessage?text=".$message."&chat_id=".$chat_id."");
//======================
	}//else
	}
		

 //where `temp`>'$temp'
 //inet_ntoa
//token: 763220421:AAHt3f2V5qnJ1sjSg2kRva1uxL4ytt5df6Q
//chat_id: 647876166
function process($conn,$sql){
$s=false;
$conn->autocommit(FALSE);
try {
  $rs = $conn->query($sql);
  if($rs){
	    $conn->commit();
	    $last_inserted_id = $conn->insert_id;
 		$affected_rows = $conn->affected_rows;
  		$s=true;
  }
} 
catch (Exception $e) {
	echo 'fail: ' . $e->getMessage();
  	$conn->rollback();
}
$conn->autocommit(TRUE);
return $s;
}
function getField($conn,$sql){
	$rs=$conn->query($sql);
	$rs->data_seek(0);
	$d= $rs->fetch_assoc();
	$rs->free();
	return $d;
}
function getJum($conn,$sql){
  $rs=$conn->query($sql);
  $jum= $rs->num_rows;
	$rs->free();
	return $jum;
}
?>




