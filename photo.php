<?

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('upload_max_filesize', '64M');

include 'config.php';



$host = '127.0.0.1';
$db   = DB_NAME;
$user = DB_USER;
$pass = DB_PASS;
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// SELECT * FROM table1 LEFT JOIN table2 ON table1.taskid = table2.id

// $data = $pdo->query('SELECT * FROM cities_data')->fetchAll(PDO::FETCH_ASSOC);
//
// $city = $data[0]['city_name'];
// $addr = $data[0]['city_addr'];
// $tel = $data[0]['city_tel'];
//
// foreach($data as $cities){
//     if($_SERVER['HTTP_HOST'] == $cities['route']){
//         //echo $cities['city_name'];
//         $city = $cities['city_name'];
//         $addr = $cities['city_addr'];
//         $tel = $cities['city_tel'];
//     }
// }


class Photo {
    function adduser($userdata = array()){
        global $pdo;
        //return 'INSERT INTO `user_id` (`user_mail`, `user_birthd`) VALUES ("'.$userdata["mail"].'", "'.$userdata["date"].'");';
        $pdo->query('INSERT INTO `user_id` (`user_mail`, `user_birthd`, `user_tel`) VALUES ("'.$userdata["mail"].'", "'.$userdata["date"].'","'.$userdata["tel"].'");');
        $id = $pdo->lastInsertId();
        return $id;
    }
    function addPhoto($data = array()){
        global $pdo;
        $uploaddir  = 'img';
        $files      = $_FILES; // полученные файл
        $photoerr = array();
        // echo ini_get('upload_max_filesize');
        foreach( $files as $file ){

            $cond = true;
            $limitsize = 1048576;

            if($file['size'] > $limitsize){
                //var_dump($photoerr);
                array_push($photoerr, 'Файл '.$file['name'].' не был загружен (максимальный размер файла 1 Мбайт)' );
            }else{
                $file_name    = $file['name'];
                $fileinfo     = pathinfo($file['name']) ;
                $realname     = $fileinfo['filename'];
                $i = 0;
                if(file_exists("$uploaddir/$file_name")){
                    $condition = true;
                    do{
                        $i++;
                        $file_name = $realname . '_'.$i. '.' . $fileinfo["extension"];
                        //echo $file_name;
                        if(!file_exists("$uploaddir/$file_name")){
                            $condition = false;
                            //var_dump("$uploaddir/$file_name");
                            move_uploaded_file( $file['tmp_name'], "$uploaddir/$file_name" );
                            $path = "/$uploaddir/$file_name";
                            $pdo->query('INSERT INTO `user_photo` (`user_id`,`photo_path`) VALUES ("'.$data["id"].'", "'.$path.'")');
                        }
                        //$file_name = $realname . '_'.$i. '.' . $fileinfo["extension"];
                    }while($condition);
                } else {
                    move_uploaded_file( $file['tmp_name'], "$uploaddir/$file_name" );
                    $path = "/$uploaddir/$file_name";
                    // echo $path;
                    $pdo->query('INSERT INTO `user_photo` (`user_id`,`photo_path`) VALUES ("'.$data["id"].'", "'.$path.'")');
                }
            }

    	}

        // var_dump($photoerr);
        return $photoerr;


    }
    function getUserId($data = array()){
        global $pdo;
        $id = 0;
        $res1 = $pdo->query('SELECT * FROM `user_id` WHERE `user_tel` = "'.$data["tel"].'"')->fetchAll(PDO::FETCH_ASSOC);
        $res2 = $pdo->query('SELECT * FROM `user_id` WHERE `user_mail` = "'.$data["mail"].'"')->fetchAll(PDO::FETCH_ASSOC);
        if($res1){$id = $res1[0]['id'];}
        if($res2){$id = $res2[0]['id'];}
        return $id;
    }

    function getUserData($id){
        global $pdo;
        return $pdo->query('SELECT * FROM `user_id` WHERE `id` = "'.$id.'"')->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllUsersId($i = 0){
        global $pdo;
        $lim = 2;
        $s = $i * 2;
        return $pdo->query('SELECT `id` FROM `user_id` LIMIT '.$s.', '.$lim.'')->fetchAll(PDO::FETCH_ASSOC);
    }

    function getPhotos($id){
        global $pdo;
        $arr = '';
        $data = $pdo->query('SELECT `photo_path` FROM `user_photo` WHERE `user_id` = "'.$id.'" ')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $path) {
            $arr .= '<div class="col-md-3 col-sm-6 img-container"><img src="'.$path['photo_path'].'" /></div>';
        }
        return $arr;
    }
}


// 1601510400 cегодня
// 568080000 18 лет

if($_POST){
    $error = array();
    $resultarray = array();
    $photo = new Photo();

    if(isset($_POST['add'])){

        if($_POST['email'] == ''){
            $error = array_merge($error,array('emailerror' => array('Поле "Почта" обязательно для заполнения')));
        }
        if($_POST['tel'] == ''){
            $error = array_merge($error,array('telerror' => array('Поле "Телефон" обязательно для заполнения')));
        }
        if($_POST['date'] == ''){
            $error = array_merge($error,array('dateerror' => array('Поле "Дата" обязательно для заполнения')));
        } else {

            $now = new DateTime();
            $date = DateTime::createFromFormat("Y-m-d", $_POST['date']);
            $interval = $now->diff($date);

            if($interval->days < 6575){
                $error = array_merge($error,array('dateerror' => array('Вам меньше 18 лет')));
            }
            //array_push($error['dateerror'],$date->format('m.d.Y'));
        }
        if(!$_FILES){
            $error = array_merge($error,array('photoerror' => array('Выберите фото для загрузки')));
        }

        //echo count($error);
        if(count($error) == 0){
            $userdata = array(
                'mail'  => $_POST['email'],
                'date'  => $_POST['date'],
                'tel'   => $_POST['tel'],
            );
            $id = $photo->getUserId($userdata);
            if(!$id){
                $id = $photo->adduser($userdata);
                // echo 'Пользователь добавлен';
            } else {
                // echo 'Такой пользователь существует';
            }
            if($_FILES){
                $userdata = array_merge($userdata, array('id' => $id));
                $photoerror = $photo->addPhoto($userdata);
                if($photoerror){
                    $error = array_merge($error,array('photoerror' => $photoerror));
                }
                //var_dump($photoerror);
            }
        }

        $resultarray = array_merge($resultarray, array('error' => $error));
        echo json_encode($resultarray);
    }
    if(isset($_POST['get'])){
        $page = $_POST['page'];
        // echo $page;
        $idarray = $photo->getAllUsersId($page);
        //var_dump($idarray);
        foreach ($idarray as $id) {
            $userdata = $photo->getUserData($id['id']);
            $tel = '+7 (ХХХ) ХХХ-'.substr($userdata[0]['user_tel'], 13, 17);
            //var_dump($tel);
            $photodata = $photo->getPhotos($id['id']);
            if($photodata){
                $data = '<div class="row photoblock">';
                $data .= $photo->getPhotos($id['id']);
                $data .= '<div class="col-12 text-center">Номер телефона: '.$tel.'</div>';
                $data .= '</div>';
            } else {
                $data = '<div class="row photoblock">';
                $data .= '<h2 class="text-center">В блоке фотографии отсутствуют</h2>';
                $data .= '<div class="col-12 text-center">Номер телефона: '.$tel.'</div>';
                $data .= '</div>';
            }
            echo $data;
        }
    }
}
