<?php
include_once("./../librares/connect.php");

class ApplictionControler
{
    private $connect = new connectDB();
    // public function __construct()
    // {
    //     $this->connect = new connectDB()
    // }

    private function updateProducts(array $array)
    {
        var_dump($array);
    }
    private function checkItem($value)
    {
        if (
            $value == "" ||
            $value == "undefened" ||
            $value == "0000-00-00" ||
            $value == "00:00" ||
            $value == 0
        )
            return "NULL";
        else
            return $value;
    }
    private function createQuery(array $data, string $action)
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->checkItem($value);
        }
        if ($data['action']=) {
            $query =
                "INSERT INTO `orders`(
                `nick`,
                `full_name`,
                `id_connection`,
                `id_city`,
                `street`,
                `house`,
                `corpus`,
                `entrance`,
                `flat`,
                `number`,
                `id_status`,
                `note`,
                `sum`,
                `weight`,
                `square`,
                `purchase`,
                `payment_method`,
                `measuring_date`,
                `measuring_time`,
                `delivery_date`,
                `delivery_time`,
                `expectation`,
                `no_order_1c`
            ) 
        VALUES 
            (   
                " . $data['nick'] . ",
                " . $data['full_name'] . ",
                " . $data['id_connection'] . ",
                " . $data['id_city'] . ",
                " . $data['street'] . ",
                " . $data['house'] . ",
                " . $data['corpus'] . ",
                " . $data['entrance'] . ",
                " . $data['flat'] . ",
                " . $data['number'] . ",
                " . $data['id_status'] . ",
                " . $data['note'] . ",
                " . $data['sum'] . ",
                " . $data['weight'] . ",
                " . $data['square'] . ",
                " . $data['purchase'] . ",
                " . $data['payment_method'] . ",
                " . $data['measuring_date'] . ",
                " . $data['measuring_time'] . ",
                " . $data['delivery_date'] . ",
                " . $data['delivery_time'] . ",
                " . $data['expectation'] . ",
                " . $data['no_order_1c'] . "
            );
        ";
        } else {
            $query = "UPDATE `orders` SET ";
            foreach ($data as $key => $value) {
                if("NULL" == $value){
                    
                }
            }
        }
        return $query;            м                                
        
    }
    public function create(array $data)
    {
        $query = $this->createQuery($data,"create");
        $productList = [1, 2];
        $this->updateProducts($productList);
    }
    public function update(array $data)
    {
        $productList = [1, 2];
        $this->UpdateProducts($productList);
    }
}
var_dump(new ApplictionControler);
