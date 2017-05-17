<?php 

require_once 'Db.php';

class Product
{
	//Метод заполнения таблицы products
	public function fillTable($limit)
	{
		$db = Db::getConnection();
		$colors = array('White', 'Black', 'Red', 'Green', 'Blue');

		for ($i=0; $i < $limit; $i++) {
			$code = rand();
			$name = 'Товар' . $i;
			$brand = 'Бренд' . $i;
			$type = 'Тип' . $i;
			$color_index = array_rand($colors, 1);
			$color = $colors[$color_index];
			$price = rand();
			$date = date("Y-m-d");

			$query = "INSERT INTO products (id, code, name,"
				. " brand, type, color, price, discount, date)"
				. " VALUES (NULL, '$code', '$name', '$brand',"
				. " '$type', '$color', '$price', '$discount', '$date')";
			$result = $db->prepare($query);
			$result->execute();
		}
	}

	//Метод выдачи массива, элементами которого являются строки products
	public function getProductsList($page, $priceMin=0, $priceMax=32767,
		$dateMin="2000-01-1", $dateMax="2017-12-31")
	{
		$db = Db::getConnection();

		$offset = ($page - 1) * 10;
		session_start();
		$sort = $_SESSION['sort'];
		if (isset($_SESSION['priceMin']))
			$priceMin = intval($_SESSION['priceMin']);
		if (isset($_SESSION['priceMax']))
			$priceMax = $_SESSION['priceMax'] == NULL ? 
				32767 : intval($_SESSION['priceMax']);
		if (isset($_SESSION['dateMin']))
			$dateMin = $_SESSION['dateMin'];
		if (isset($_SESSION['dateMax']))
			$dateMax = $_SESSION['dateMax'];

		if ($sort == NULL) {
			$query = "SELECT * FROM products WHERE price>='$priceMin'"
				. " AND price<='$priceMax' AND date>='$dateMin'"
				. " AND date<='$dateMax' ORDER BY id DESC LIMIT 10 OFFSET $offset";
		}
		elseif ($sort == 'name') {
			$query = "SELECT * FROM products WHERE price>='$priceMin'"
				. " AND price<='$priceMax' AND date>='$dateMin'"
				. " AND date<='$dateMax' ORDER BY name ASC LIMIT 10 OFFSET $offset";
		}
		elseif ($sort == 'brand') {
			$query = "SELECT * FROM products WHERE price>='$priceMin'"
				. " AND price<='$priceMax' AND date>='$dateMin'"
				. " AND date<='$dateMax' ORDER BY brand ASC LIMIT 10 OFFSET $offset";
		}
		elseif ($sort == 'type') {
			$query = "SELECT * FROM products WHERE price>='$priceMin'"
				. " AND price<='$priceMax' AND date>='$dateMin'"
				. " AND date<='$dateMax' ORDER BY type ASC LIMIT 10 OFFSET $offset";
		}
		elseif ($sort == 'code') {
			$query = "SELECT * FROM products ORDER BY code ASC LIMIT 10 OFFSET $offset";
		}
		elseif ($sort == 'color') {
			$query = "SELECT * FROM products ORDER BY color ASC LIMIT 10 OFFSET $offset";
		}
		elseif ($sort == 'price') {
			$query = "SELECT * FROM products ORDER BY price ASC LIMIT 10 OFFSET $offset";
		}
		elseif ($sort == 'discount') {
			$query = "SELECT * FROM products ORDER BY discount ASC LIMIT 10 OFFSET $offset";
		}
		elseif ($sort == 'date') {
			$query = "SELECT * FROM products ORDER BY date ASC LIMIT 10 OFFSET $offset";
		}

		$result = $db->prepare($query);
		$result->execute();

		$i = 0;
		$productsList = array();
		while ($row = $result->fetch()) {
			$productsList[$i]['id'] = $row['id'];
			$productsList[$i]['code'] = $row['code'];
			$productsList[$i]['name'] = $row['name'];
			$productsList[$i]['brand'] = $row['brand'];
			$productsList[$i]['type'] = $row['type'];
			$productsList[$i]['color'] = $row['color'];
			$productsList[$i]['price'] = $row['price'];
			$productsList[$i]['discount'] = $row['discount'];
			$productsList[$i]['date'] = $row['date'];
			$i++;
		}

		return $productsList;
	}

	//Метод проверяет, пустая ли таблица products
	public function isTableEmpty()
	{
		$db = Db::getConnection();

		$query = "SELECT count(id) as count FROM products";
		$result = $db->prepare($query);
		$result->execute();
		$row = $result->fetch();

		if ($row['count'] == 0)
			return true;
		else
			return false;
	}

	public function getProductsCount() 
	{
		$db = Db::getConnection();

		$query = "SELECT count(id) as count FROM products";
		$result = $db->prepare($query);
		$result->execute();
		$row = $result->fetch();

		return $row['count'];
	}
}

 ?>