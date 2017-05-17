<?php 

require_once 'classes/Product.php';
require_once 'classes/Pagination.php';

//Сессия запоминает текущий вид сортировки
session_start();

//Заполнение таблицы products
if (Product::isTableEmpty())
	Product::fillTable(1000);

//Создание объекта класса Pagination
if ($_GET['page'] == NULL) {
	$page = 1;
}
else {
	$page = $_GET['page'];
}
$pagination = new Pagination($page);

//Сортировка
if (isset($_GET['sort'])) {
	$_SESSION['sort'] = $_GET['sort'];
}

if (isset($_POST['submit'])) {
	$_SESSION['sort'] = $_POST['sort'];
	$_SESSION['priceMin'] = $_POST['priceMin'];
	$_SESSION['priceMax'] = $_POST['priceMax'];
	$_SESSION['dateMin'] = $_POST['yearMin'] . '-' 
		. $_POST['monthMin'] . '-' . $_POST['dayMin'];
	$_SESSION['dateMax'] = $_POST['yearMax'] . '-' 
		. $_POST['monthMax'] . '-' . $_POST['dayMax'];
}

$productsList = Product::getProductsList($page);

//Массивы для отображения месяцев в форме
$months = array(1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
	5 => 'Май',  6 => 'Июнь', 7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь',
	10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь');

$keys = array_reverse(array_keys($months));
$monthsReverse = array_combine($keys, array_reverse($months));

 ?>

 <html>
 <head>
 	<meta charset="utf-8">
 	<title>Тестовое задание</title>
 	<link rel="stylesheet" href="css/style.css" type="text/css">
 </head>
 <body>
 	
 	<div align="center">

 	<form action="" method="POST" class="form-block">

 		<div class="form-elem">
	 		Сортировать по 
		 	<select name="sort" class="form">
		 		<option value=""></option>
		 		<option value="name">наименованию</option>
		 		<option value="brand">бренду</option>
		 		<option value="type">типу</option>
		 	</select>
		</div>

		<div class="form-elem">
 			Цена от 
 			<input type="text" name="priceMin" 
 				value="<?php echo $priceMin; ?>" size="7" class="form">
 			до
 			<input type="text" name="priceMax" 
 				value="<?php echo $priceMax; ?>" size="7" class="form">
 		</div>

 		<div class="form-elem">
	 		Дата с
	 		<select name="dayMin" class="form">
	 			<?php for ($i=1; $i <= 31; $i++): ?>
	 				<option value="<?php echo $i; ?>">
	 					<?php echo $i; ?>
	 				</option>
	 			<?php endfor; ?>
	 		</select>
	 		<select name="monthMin" class="form">
	 			<?php foreach ($months as $key => $month): ?>
	 				<option value="<?php echo $key; ?>">
	 					<?php echo $month; ?>
	 				</option>
	 			<?php endforeach; ?>
	 		</select>
	 		<select name="yearMin" class="form">
	 			<?php for ($i=2017; $i >= 2000; $i--): ?>
	 				<option value="<?php echo $i; ?>">
	 					<?php echo $i; ?>
	 				</option>
	 			<?php endfor; ?>
	 		</select>
	 		по
			<select name="dayMax" class="form">
	 			<?php for ($i=31; $i >= 1; $i--): ?>
	 				<option value="<?php echo $i; ?>">
	 					<?php echo $i; ?>
	 				</option>
	 			<?php endfor; ?>
	 		</select>
	 		<select name="monthMax" class="form">
	 			<?php foreach ($monthsReverse as $key => $month): ?>
	 				<option value="<?php echo $key; ?>">
	 					<?php echo $month; ?>
	 				</option>
	 			<?php endforeach; ?>
	 		</select>
	 		<select name="yearMax" class="form">
	 			<?php for ($i=2017; $i >= 2000; $i--): ?>
	 				<option value="<?php echo $i; ?>">
	 					<?php echo $i; ?>
	 				</option>
	 			<?php endfor; ?>
	 		</select>
	 	</div>

	 	<div class="form-elem">
	 		<input type="submit" name="submit" value="Искать" class="button">
	 	</div>

 	</form>

 	<ul class="head">
 		<li class="head-elem">
 			<a href="?page=<?php echo $page,'&'; ?>sort=code"><b>Артикул</b></a>
 		</li>
 		<li class="head-elem">
 			<a href="?page=<?php echo $page,'&'; ?>sort=name"><b>Наименование</b></a>
 		</li>
 		<li class="head-elem">
 			<a href="?page=<?php echo $page,'&'; ?>sort=brand"><b>Бренд</b></a>
 		</li>
 		<li class="head-elem">
 			<a href="?page=<?php echo $page,'&'; ?>sort=type"><b>Тип</b></a>
 		</li>
 		<li class="head-elem">
 			<a href="?page=<?php echo $page,'&'; ?>sort=color"><b>Цвет</b></a>
 		</li>
 		<li class="head-elem">
 			<a href="?page=<?php echo $page,'&'; ?>sort=price"><b>Цена, руб.</b></a>
 		</li>
 		<li class="head-elem">
 			<a href="?page=<?php echo $page,'&'; ?>sort=discount"><b>Скидка</b></a>
 		</li>
 		<li class="head-elem">
 			<a href="?page=<?php echo $page,'&'; ?>sort=date"><b>Дата</b></a>
 		</li>
 	</ul>

 	<?php foreach ($productsList as $product): ?>
 		<ul class="list">
 			<li class="list-elem"><?php echo $product['code']; ?></li>
 			<li class="list-elem"><?php echo $product['name']; ?></li>
 			<li class="list-elem"><?php echo $product['brand']; ?></li>
 			<li class="list-elem"><?php echo $product['type']; ?></li>
 			<li class="list-elem"><?php echo $product['color']; ?></li>
 			<li class="list-elem"><?php echo $product['price']; ?></li>
 			<li class="list-elem"><?php echo $product['discount']; ?></li>
 			<li class="list-elem"><?php echo $product['date']; ?></li>
 		</ul>
 	<?php endforeach; ?>

 	<?php echo $pagination->getPages(); ?>

 	</div>

 </body>
 </html>