<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao {

    public function __construct(){
        parent::__construct();
    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function investor(){
        $first_name=$_REQUEST['first_name'];
        $last_name=$_REQUEST['last_name'];
        $e_mail=$_REQUEST['e-mail'];
        $company=$_REQUEST['company'];
        $stmt = $this->conn->prepare("INSERT INTO investors (first_name, last_name, email, company) VALUES ('$first_name','$last_name','$e_mail','$company')");
        $result=$stmt->execute();
        print_r($result);
    }

    /** TODO
    * Implement DAO method to validate email format and check if email exists
    */
    public function investor_email($email){
        if(strpos($email, "@") == false){
            return "invalid email format";
        }
        $stmt = $this->conn->prepare("SELECT first_name,last_name FROM investors WHERE email=$email");
        $result=$stmt->execute();
        $count = $stmt->rowCount();
        if($count==0){
            return "no users were found for that email";
        }
        return $result;
    }

    /** TODO
    * Implement DAO method to return list of investors according to instruction in MidtermRoutes.php
    */
    public function investors($id){
        $stmt=$this->conn->prepare("SELECT   share_classes.description, share_classes.equity_main_currency, share_classes.price, 
        share_classes.authorized_assets, investors.first_name, investors.last_name, investors.company,SUM(cap_table.diluted_shares) 
        FROM share_classes
         JOIN cap_table ON share_classes.id=cap_table.share_class_id
         JOIN investors ON cap_table.investor_id=investors.id
         WHERE cap_table.share_class_id=$id
         GROUP BY cap_table.share_class_id
         
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    }

}
?>
