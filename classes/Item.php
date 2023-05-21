<?php
class Item{
//properties
    public $name,$category,$location,$ownerId,$desc,$rprice,$sprice;
    private $id;
//methods
public static function connect(){
$servername = "localhost";
$username = "id17823253_root";
$password = "Ishaan@website18102000";
$database = "id17823253_localhost";


// Create connection
  $db = new mysqli($servername, $username, $password, $database);
  if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}else{
  return $db;
}
  

// Check connection

}

public function set_details(){
    $db = Item::connect();
    $sql = "SELECT * FROM `items` WHERE ItemId ='$this->id'";
    $result = $db->query($sql);
    $myarray = array();
    if(mysqli_num_rows($result) == 1){
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $this->name = $row['ItemName'];
        $this->category = $row['ItemCateg'];
        $this->location = $row['ItemLocation'];
        $this->desc = $row['Itemdesc'];
        $this->status = $row['ItemStatus'];
        $this->rprice = $row['RentPrice'];
        $this->sprice =  $row['SellPrice'];
        $this->ownerId =  $row['Owner'];
        
    }
    $db->close();
  }

public function __construct($id) {
    $this->id = $id;
  }

  

  public function getid(){
    return $this->id;
  }

  public function getowner(){
    return $this->ownerId;
  }

  public function addRequest($ureqid, $action){
    $db = Item::connect();
    $stmt = $db->prepare("INSERT INTO `requestlog` (`ReqId`, `ReqBy`, `ReqItem`, `ItemOwner`, `ReqAction`, `ReqStatus`) VALUES (NULL, ?, ?, ?, ?, ?);");
    $reqstatus='PENDING';
	  $stmt->bind_param("sssss",$ureqid,$this->id,$this->ownerId,$action,$reqstatus);
	  $result = $stmt->execute();
    $db->close();
    if($result == true){
      return 'Request send';
    }else{
      return 'Request failed';
    }
    
    
  }
  public static function addnewItem(){
    $mssg = [];

    if (empty($_POST['itemname'])){
        $mssg['name'] = 'Item Name is missing';
        $mssg['success'] = 'FAILED';
    }
    if (empty($_POST['itemlocation'])){
        $mssg['location'] = 'Item Location is missing';
        $mssg['success'] = 'FAILED';
    }
    if (empty($_POST['itemCategory'])){
      $mssg['success'] = 'FAILED';
      $mssg['location'] = 'Item Category is missing';
    }
    if (empty($_POST['itemdesc'])){
        $mssg['description'] = 'Item Description token is missing';
        $mssg['success'] = 'FAILED';
    }
    if (empty($_POST['rentprice'])){
      $errors['rprice'] = 'Rent price is missing';
      $mssg['success'] = 'FAILED';
    }
    if (empty($_POST['sellprice'])){
      $mssg['sprice'] = 'Sell price is missing';
      $mssg['success'] = 'FAILED';
    }
    if (!isset($_FILES['imageUpload'])){
      $mssg['file'] = 'Image is missing';
      $mssg['success'] = 'FAILED';
    }
    if(!empty($mssg)){
        return $mssg;
    }

    $db = Item::connect();
    $myitemname = mysqli_real_escape_string($db,$_POST['itemname']);
    $myitemloc = mysqli_real_escape_string($db,$_POST['itemlocation']); 
    $myitemdesc = mysqli_real_escape_string($db,$_POST['itemdesc']);
    $myitemrprice = mysqli_real_escape_string($db,$_POST['rentprice']); 
    $myitemsprice = mysqli_real_escape_string($db,$_POST['sellprice']);
    $myitemcateg = mysqli_real_escape_string($db,$_POST['itemCategory']);
    $myitemowner= $_SESSION['identity'];
    $itemstatus='AVAILABLE';
    $target_dir = "uploads/";
    $target_file = $target_dir.basename($_FILES["imageUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    if (move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $target_file)) {
    } else {
        $mssg['file'] = "Sorry, there was an error uploading your file.";
        $mssg['success'] = 'FAILED';
    }

    $image=basename( $_FILES["imageUpload"]["name"],".jpg");

    $stmt = $db->prepare("INSERT INTO `items` (`ItemId`, `ItemLocation`, `ItemStatus`, `RentPrice`, `SellPrice`, `ItemName`, `Owner`, `ItemCateg`, `Itemdesc`,`itemImage`) VALUES (NULL, ?, ?,?,?, ?, ?, ?, ?,?);");
    $stmt->bind_param('sssssssss',$myitemloc,$itemstatus,$myitemrprice, $myitemsprice, $myitemname, $myitemowner, $myitemcateg, $myitemdesc, $image);
	  $result = $stmt->execute();
    $db->close();
    if($result == true){
      $mssg['success'] = 'SUCCESS';
      return $mssg;
    }else{
      $mssg['success'] = 'FAILED';
      $mssg['dberror'] = 'Cannot add this Item.';
      return $mssg;
      
    }
      
   
    
  }
}
?>