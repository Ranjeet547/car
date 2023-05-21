<?php
class Category{

    public static function getallCategories(){
    require_once("connect.php");
    $sql = "SELECT Categ_id, Categ_name, SubCateg_name FROM `categories` WHERE 1 ORDER BY Categ_name;";
    $result = $db->query($sql);
    $myarray = array();
    if($result){
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $myArray[] = $row;
        }
        $result->close();
        header('Content-Type: application/json');
        echo json_encode($myArray);
    }
}

    public static function addnew(){
        $mssg = [];

        if (empty($_POST['Categname'])){
            $mssg['name'] = 'Category Name is missing';
            $mssg['success'] = 'FAILED';
        }
        if (empty($_POST['Subcategname'])){
            $mssg['location'] = 'Sub Category name is missing';
            $mssg['success'] = 'FAILED';
        }
        if (!isset($_FILES['imageUpload'])){
          $mssg['file'] = 'Image for Category is missing';
          $mssg['success'] = 'FAILED';
        }
        if(!empty($mssg)){
            return $mssg;
        }
        require_once("connect.php");
        $mycateg = mysqli_real_escape_string($db,$_POST['Categname']);
        $mysubcateg = mysqli_real_escape_string($db,$_POST['Subcategname']);
        $target_dir = "products/";
        $target_file = $target_dir.basename($_FILES["imageUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        if (move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $target_file)) {
        } else {
            $mssg['file'] = "Sorry, there was an error uploading your file.";
            $mssg['success'] = 'FAILED';
        }

        $imglink=basename( $_FILES["imageUpload"]["name"],".jpg");
        $imglink="$target_dir/$imglink.$imageFileType";
        $stmt = $db->prepare("INSERT INTO `categories`(`Categ_id`, `Categ_name`, `SubCateg_name`, `Item_count`, `img_link`) VALUES (NULL,?,?,'0',?);");
        $stmt->bind_param('sss',$mycateg,$mysubcateg, $imglink);
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