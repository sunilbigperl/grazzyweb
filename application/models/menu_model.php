<?php
Class Menu_model extends CI_Model
{
	function GetMenus($id){
		$sql = "SELECT * FROM `restaurant_menu` where restaurant_id=$id and `delete` = 0 order by menu_id DESC";
		
		$query = $this->db->query($sql);
		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
		} 
		
		return $result;
	}
	
	function GetMenu($id){
		$result	= $this->db->get_where('restaurant_menu', array('menu_id'=>$id,'delete'=>0))->row();
		//echo $this->db->last_query(); exit;
		if(!$result)
		{
			return false;
		}
		$result->categories			= $this->get_menu_categories($result->menu_id);

		return $result;
	}
	
	
	
	function InsertMenus($menus,$id){
		foreach($menus as $men){
			foreach($men as $menu){
				$categories = explode(":", $menu['category_id']);
				
				$sql =$this->db->query("INSERT INTO `restaurant_menu`(`restaurant_id`, `code`, `menu`, `description`, `price`, `type`, `size`, `itemPreparation_time`, `enabled`
				) VALUES ('".$id."','".$menu['code']."','".$menu['menu']."','".$menu['description']."','".$menu['price']."',
				'".$menu['type']."','".$menu['size']."','".$menu['itemPreparation_time']."','".$menu['enabled']."')");
				$menu_category = $this->db->insert_id();
				if(count($categories) > 0){
					foreach($categories as $cat){
						$sql =$this->db->query("INSERT INTO `menu_categories`(category_id,menu_category) VALUES ('".$cat."','".$menu_category."')");
					}
				}
			}
		}
	}

	function InsertCustomisation($menus,$id){
       
		foreach($menus as $men){
			//foreach($men as $menu){
				//$categories = explode(":", $menu['category_id']);
//print_r($men);exit;
				//print_r($men);exit;
			// $sql = "select * from `restaurant_menu` where menu_id ='".$id."'";
			// print_r($sql);exit;
			// $sql =$this->db->query("INSERT INTO `restaurant_menu`(`restaurant_id`, `code`, `menu`, `description`, `price`, `type`, `size`, `itemPreparation_time`, `enabled`
			// 	) VALUES ('".$id."','".$menu['code']."','".$menu['menu']."','".$menu['description']."','".$menu['price']."',
			// 	'".$menu['type']."','".$menu['size']."','".$menu['itemPreparation_time']."','".$menu['enabled']."')");
				$menu_category = $this->db->insert_id();
				//print_r($menu_category);exit;
				$values=$men;
				//print_r($values);exit;
                $array1=array();
				

				for($i=0;$i<count($men);$i++)
				{
					$data1=array();
					$data1[0]['name']=$men[$i+1]['name1'];
				$data1[0]['weight']=$men[$i+1]['weight'];
				$data1[0]['price']=$men[$i+1]['price'];


				//$array1=array();
				$array1[$i]['type']=$men[$i+1]['type'];
				$array1[$i]['name']=$men[$i+1]['name'];
				$array1[$i]['values']=$data1;

				// $array2=array($array1);
//print_r($array2);exit;

				}
				$array2=array($array1);
				// $data1=array();
				// $data1[0]=$data1['name1'];
				// $data1['weight']='small';
				// $data1['price']='120';
//print_r($array2);exit;
				// $data1=array();
				// $data1['name']='vegpizza';
				// $data1['weight']='small';
				// $data1['price']='120';
				
				// $data[1]['type']='checklist';
				// $data[1]['name']='size';
    //             $data[1]['values']=array($data1);
    //             print_r($data);exit;
				//$data=$menu['customisation'];
				//print_r($menu);exit;
				// $sql =$this->db->query("INSERT INTO `restaurant_menu`(`restaurant_id`, `code`, `menu`, `description`, `price`, `type`, `size`, `itemPreparation_time`, `enabled`
				// ) VALUES ('".$id."','".$menu['code']."','".$menu['menu']."','".$menu['description']."','".$menu['price']."',
				// '".$menu['type']."','".$menu['size']."','".$menu['itemPreparation_time']."','".$menu['enabled']."')");

				// $sql =$this->db->query("INSERT INTO `restaurant_menu`(`customisation`,`restaurant_id`, `code`, `menu`, `description`, `price`, `type`, `size`, `itemPreparation_time`, `enabled`
				//  ) VALUES ('".serialize($menu['customisation'])."','".$id."','".$menu['code']."','".$menu['menu']."','".$menu['description']."','".$menu['price']."',
				//  '".$menu['type']."','".$menu['size']."','".$menu['itemPreparation_time']."','".$menu['enabled']."')");
                 
      $sql=$this->db->query("update `restaurant_menu` set `customisation`= '".serialize($array2[0])."'  where `menu_id`='".$id."' ");

      //print_r($sql);exit;


				// $sql =$this->db->query("INSERT INTO `restaurant_menu`(`customisation`
				//  ) VALUES ('".serialize($array2[0])."','".$id."')");
				 // print_r("INSERT INTO `restaurant_menu`(`customisation`
				 //  ) VALUES ('".serialize($array2[0])."')");exit;
				$menu_category = $this->db->insert_id();
				if(count($categories) > 0){
					foreach($categories as $cat){
						$sql =$this->db->query("INSERT INTO `menu_categories`(category_id,menu_category) VALUES ('".$cat."','".$menu_category."')");
					}
				}
			//}
		}
	}

	function save($menu, $categories=false)
	{
		
		if($menu['menu_id'] == ""){$menu['menu_id'] = false;}
		if ($menu['menu_id'])
		{
			$this->db->where('menu_id', $menu['menu_id']);
			$this->db->update('restaurant_menu', $menu);
			
			$id	= $menu['menu_id'];
		}
		else
		{
			$this->db->insert('restaurant_menu', $menu);
			$id	= $this->db->insert_id();
		}
		
		if($categories !== false)
		{
			
			if($menu['menu_id'])
			{
				//get all the categories that the product is in
				$cats	= $this->get_menu_categories($menu['menu_id']);
				
				//generate cat_id array
				$ids	= array();
				foreach($cats as $c)
				{
					$ids[]	= $c->id;
				}

				//eliminate categories that products are no longer in
				foreach($ids as $c)
				{
					if(!in_array($c, $categories))
					{
						$this->db->delete('menu_categories', array('menu_category'=>$id,'category_id'=>$c));
					}
				}
				
				//add products to new categories
				foreach($categories as $c)
				{
					if(!in_array($c, $ids))
					{
						$this->db->insert('menu_categories', array('menu_category'=>$id,'category_id'=>$c));
					}
				}
			}
			else
			{
				//new product add them all
				foreach($categories as $c)
				{
					$this->db->insert('menu_categories', array('menu_category'=>$id,'category_id'=>$c));
				}
			}
		}
		
		
		//return the product id
		return $id;
	}
	
	function get_menu_categories($id)
	{
		return $this->db->where('menu_category', $id)->join('categories', 'category_id = categories.id')->get('menu_categories')->result();
	}
	
	  function delete($id,$res_id)
    {
		$data['delete'] = 1;
        $this->db->where('menu_id', $id);
		$this->db->where('restaurant_id', $res_id);
        //$this->db->delete('restaurant_menu');
		 $this->db->update('restaurant_menu',$data);
       
    }
	
	function MenuStatusChange($data){
		
		 if ($data['restaurant_id'] && $data['menu_id'])
        {
			$this->db->where('menu_id', $data['menu_id']);
            $this->db->where('restaurant_id', $data['restaurant_id']);
            $this->db->update('restaurant_menu', $data);
        }
	}
}