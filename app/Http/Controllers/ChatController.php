<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function signup(){
        return view('signup');
    }
   public function login(){
		extract($_POST);
		$qry = $this->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	
    
    public function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	
    public function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	
    
    public function create_account(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->query("SELECT * FROM users where username = '$username' ")->num_rows;
		if($chk > 0){
			return json_encode(array("status"=>2,"msg"=>"Username already exist."));
			exit;
		}
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", avatar = '$fname' ";

		}
			$save = $this->query("INSERT INTO users set ".$data);
		if($save){
			$login = $this->login();
			if($login==1)
			return json_encode(array("status"=>1));
		}
	}
	
    
    public function get_users(){
		extract($_POST);
		$data = " name Like '%$filter%' ";
		$data .= " or username Like '%$filter%' ";
		$rdata = array();
		$get= $this->query("SELECT * FROM users where $data");
		while($row = $get->fetch_assoc()){
			$rdata[] = $row;
		}
		return json_encode($rdata);

	}

//Função para envio de mensagem 
    public function send_chat(){
		extract($_POST);
		$data = " message = '$message' ";
		$data .= ", user_id = '{$_SESSION['login_id']}' ";
		if(empty($convo_id)){
			$cdata = " user_ids = '$user_id,{$_SESSION['login_id']}' ";
			$cdata2 = " user_ids = '{$_SESSION['login_id']},$user_id' ";
			$user_ids = $_SESSION['login_id'].",".$user_id;
			$chk = $this->query("SELECT * from threads where $cdata or $cdata2 ");
			if($chk->num_rows> 0){
				$convo_id = $chk->fetch_array()['id'];
			}else{
				$thread = $this->query("INSERT INTO threads set $cdata ");
				$convo_id = $this->insert_id;
			}
		}else{
			$qry =$this->query("SELECT * from threads where md5(id) ='$convo_id' ")->fetch_array();
			$convo_id = $qry['id'];
			$user_ids = $qry['user_ids'];

		}
		$data .= ", convo_id = '$convo_id' ";
		$save = $this->query("INSERT INTO chats set $data");
		if($save)
			return json_encode(array('status'=>1,'convo_id'=>md5($convo_id),'convo_users'=>$user_ids));
	}
	
    
    public function load_convo(){
		extract($_POST);
		$data = array();
		$get = $this->query("SELECT m.message,u.id,u.name,u.avatar FROM chats m inner join users u on u.id = m.user_id where md5(m.convo_id) = '$convo_id' ");
		while($row= $get->fetch_assoc()){
			$data[] = $row;
		}
		return json_encode($data);
	}
	
    
    public function read_msg(){
		extract($_POST);
		if(isset($user_id) && $user_id > 0){
			$update = $this->query("UPDATE messages set status = 1 where md5(convo_id) = '$convo_id' and user_id=$user_id ");
			if($update){
				return 1;
			}
		}
	}


    public function phpSocket(){
        define('HOST_NAME',"localhost"); 
        define('PORT',"2306");
        $null = NULL;
        $mysocket = new SocketClass();

        $socketResource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socketResource, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($socketResource, 0, PORT);
        socket_listen($socketResource);
        $keys = array();
        session_start();
        $clientSocketArray = array($socketResource);
        while (true) {
            $newSocketArray = $clientSocketArray;
            socket_select($newSocketArray, $null, $null, 0, 10);
            
            if (in_array($socketResource, $newSocketArray)) {
                $newSocket = socket_accept($socketResource);
                $clientSocketArray[] = $newSocket;
                
                $header = socket_read($newSocket, 1024);
                $mysocket->doHandshake($header, $newSocket, HOST_NAME, PORT);
                
                socket_getpeername($newSocket, $client_ip_address);
                $id = isset($_GET['id'])? $_GET['id'] :0;
                $connectionACK = $mysocket->newConnectionACK($client_ip_address,$id);
                
                $mysocket->send($connectionACK);
                
                $newSocketIndex = array_search($socketResource, $newSocketArray);
                unset($newSocketArray[$newSocketIndex]);
            }
            
            foreach ($newSocketArray as $newSocketArrayResource) {	
                while(socket_recv($newSocketArrayResource, $socketData, 1024, 0) >= 1){
                    $socketMessage = $mysocket->unseal($socketData);
                    $messageObj = json_decode($socketMessage);
                    
                    $chat_box_message = $mysocket->createChatBoxMessage($messageObj);
                    $mysocket->send($chat_box_message);
                    break 2;
                }
                
                $socketData = @socket_read($newSocketArrayResource, 1024, PHP_NORMAL_READ);
                if ($socketData === false) { 
                    socket_getpeername($newSocketArrayResource, $client_ip_address);
                    $connectionACK = $mysocket->connectionDisconnectACK($client_ip_address);
                    $mysocket->send($connectionACK);
                    $newSocketIndex = array_search($newSocketArrayResource, $clientSocketArray);
                    unset($clientSocketArray[$newSocketIndex]);			
                }

            }
        }
        socket_close($socketResource);
    }
    


}
ob_start();
$action = $_GET['action'];
$crud = new ChatController();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'create_account'){
	$save = $crud->create_account();
	if($save)
		echo $save;
}
if($action == 'get_users'){
	$get = $crud->get_users();
	if($get)
		echo $get;
}
if($action == 'send_chat'){
	$save = $crud->send_chat();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'load_convo'){
	$get = $crud->load_convo();
	if($get)
		echo $get;
}
if($action == 'read_msg'){
	$save = $crud->read_msg();
	if($save)
		echo $save;
}