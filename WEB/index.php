<?php

//require 'vendor/autoload.php';
require 'Slim-2.6.2/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

if (!ini_get('date.timezone')) {
	date_default_timezone_set('GMT');
}

$app -> get('/products', 'getProducts');
$app -> get('/products/:id', 'getProduct');
$app -> post('/products/:id', 'updateProduct');
$app -> post('/products', 'addProduct');
$app -> post('/products/delete/:id', 'deleteProduct');

$app -> get('/materials/:productid', 'getMaterials');
$app -> post('/materials/:id', 'updateMaterial');
$app -> get('/material/:id', 'getMaterial');
$app -> post('/materials', 'addMaterial');
$app -> post('/materials/delete/:id', 'deleteMaterial');

$app -> get('/process/:productId', 'getProcess');
$app -> post('/process', 'addProcess');
$app -> post('/process/update/:processId', 'updateProcess');
$app -> post('/process/delete/:id', 'deleteProcess');
$app -> post('/process/delete/', 'deleteProcesses');
$app -> get('/process/edit/:id', 'getProcessForEdit');

$app -> post('/process/group', 'addProcessGroup');
$app -> get('/process/groups/:id', 'getProcessGroups');
$app -> post('/process/group/update/:id', 'updateProcessGroups');
$app -> get('/process/group/edit/:id', 'getProcessGrpForEdit');
$app -> post('/process/group/delete/:id', 'deleteProcessGroup');

$app -> get('/users/:type', 'getUsers');
$app -> get('/user/:userId', 'getUser');
$app -> post('/users/password/:id', 'updatePassword');
$app -> post('/users/:userId', 'updateUser');
$app -> post('/users', 'addUser');
$app -> post('/users/delete/:id', 'deleteUser');

$app -> post('/authenticate', 'authenticate');

$app -> run();

function updateProcessGroups($processGrpId) {
	global $app;
	$req = $app -> request();
	$processGrp = json_decode($req -> getBody());
	if (isProductUnique($processGrp -> name, $processGrp -> productId, $processGrpId) == 0) {
		$sql = "UPDATE processgroups SET name=:name, description=:description, updatedBy=:updatedBy, updatedAt=NOW() WHERE id=$processGrpId";
		try {
			$db = getConnection();
			$stmt = $db -> prepare($sql);
			$stmt -> bindParam("name", $processGrp -> name);
			$stmt -> bindParam("description", $processGrp -> description);
			$stmt -> bindParam("updatedBy", $processGrp -> updatedBy);
			$stmt -> execute();
			$db = null;
			echo json_encode(array("processgroup" => $processGrp));
		} catch(PDOException $e) {
			error_log($e -> getMessage(), 3, '/var/tmp/php.log');
			echo '{"error":{"text":' . $e -> getMessage() . '}}';
		}
	} else {
		echo json_encode(array('error' => "Boat name already exists"));
	}
}

function isProcessGrpUnique($name, $productId, $processGrpId) {
	$sql = "select count(1) FROM processgroups WHERE name='$name' and productId='$productId'";
	if ($processGrpId != null) {
		$sql .= " AND id !='$processGrpId'";
	}

	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$stmt -> execute();
		$array = $stmt -> fetch(PDO::FETCH_NUM);
		$count = $array[0];
		$db = null;
		return $count;
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getProcessGrpForEdit($proccessGrpId) {
	$sql = "SELECT * FROM processgroups WHERE id = $proccessGrpId";
	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$process = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode(array("processgroup" => $process));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function deleteProcessGroup($processGrpId) {
	global $app;
	$req = $app -> request();
	$processGrp = json_decode($req -> getBody());
	$sql = "UPDATE processgroups SET deleted = 1, deletedBy=:deletedBy, deletedAt=NOW() WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("id", $processGrpId);
		$stmt -> bindParam("deletedBy", $processGrp -> deletedBy);
		$stmt -> execute();
		$sql = "UPDATE process SET processGroupId = null WHERE processGroupId=:id";
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("id", $processGrpId);
		$stmt -> execute();
		$db = null;
		echo json_encode(array('processGrpId' => $processGrpId));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function deleteProcess($processId) {
	global $app;
	$req = $app -> request();
	$process = json_decode($req -> getBody());
	$sql = "UPDATE process SET deleted = 1, deletedBy=:deletedBy, deletedAt=NOW() WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("id", $processId);
		$stmt -> bindParam("deletedBy", $process -> deletedBy);
		$stmt -> execute();
		$db = null;
		echo json_encode(array('processId' => $processId));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function deleteProcesses() {
	global $app;
	$req = $app -> request();
	$process = json_decode($req -> getBody());
	$sql = "UPDATE process SET deleted = 1, deletedBy=:deletedBy, deletedAt=NOW() WHERE id IN ('" . implode("','", split(",", $process -> processIds)) . "')";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("deletedBy", $process -> deletedBy);
		$stmt -> execute();
		$db = null;
		echo json_encode(array('processIds' => $process -> processIds));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function deleteProduct($productId) {
	global $app;
	$req = $app -> request();
	$product = json_decode($req -> getBody());

	$sql = "UPDATE products SET deleted = 1, deletedBy=:deletedBy AND deletedAt=NOW() WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("id", $productId);
		$stmt -> bindParam("deletedBy", $product -> deletedBy);
		$stmt -> execute();
		$db = null;
		echo json_encode(array('productId' => $productId));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function deleteUser($userId) {
	global $app;
	$req = $app -> request();
	$user = json_decode($req -> getBody());

	$sql = "UPDATE users SET deleted = 1, deletedBy=:deletedBy AND deletedAt=NOW() WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("id", $userId);
		$stmt -> bindParam("deletedBy", $user -> deletedBy);
		$stmt -> execute();
		$db = null;
		echo json_encode(array('userId' => $userId));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function deleteMaterial($materialId) {
	global $app;
	$req = $app -> request();
	$material = json_decode($req -> getBody());

	$sql = "UPDATE materials SET deleted = 1, deletedBy=:deletedBy AND deletedAt=NOW() WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("id", $materialId);
		$stmt -> bindParam("deletedBy", $material -> deletedBy);
		$stmt -> execute();
		$db = null;
		echo json_encode(array('materialId' => $materialId));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function formatData($data) {
	class Employee {
		public $id;
		public $name;
	}

	$data = json_decode($data);
	$myhashmap = array();
	$employees_added = array();
	foreach ($data as $process) {
		$key = $process -> processGrpName . "-" . $process -> processName . "-" . $process -> productId;
		if (!array_key_exists($key, $myhashmap)) {
			$myhashmap[$key] = array();
			$myhashmap[$key]['processIds'] = $process -> processId;
			$myhashmap[$key]['processName'] = $process -> processName;
			$myhashmap[$key]['processGrpName'] = $process -> processGrpName;
			$myhashmap[$key]['productId'] = $process -> productId;
			$myhashmap[$key]['laborHours'] = $process -> laborHours;
			$myhashmap[$key]['totalCost'] = $process -> totalCost;
			$myhashmap[$key]['employees'] = array();
			$employeeIds = split(",", $process -> employeeIds);
			$employeeNames = split(",", $process -> employees);

			$i = 0;
			foreach ($employeeIds as $employeeId) {
				if (!in_array($employeeId, $employees_added)) {
					$e = new Employee();
					$e -> id = $employeeId;
					$e -> name = $employeeNames[$i++];
					array_push($employees_added, $employeeId);
					array_push($myhashmap[$key]['employees'], $e);
				}
			}
		} else {
			$myhashmap[$key]['laborHours'] += $process -> laborHours;
			$myhashmap[$key]['totalCost'] += $process -> totalCost;
			$myhashmap[$key]['processIds'] .= "," . $process -> processId;

			$employeeIds = split(",", $process -> employeeIds);
			$employeeNames = split(",", $process -> employees);

			$i = 0;
			foreach ($employeeIds as $employeeId) {
				if (!in_array($employeeId, $employees_added)) {
					$e = new Employee();
					$e -> id = $employeeId;
					$e -> name = $employeeNames[$i++];
					array_push($myhashmap[$key]['employees'], $e);
				}
			}
		}
	}
	echo json_encode(array("process" => array_values($myhashmap)));
}

function getProcess($productId) {
	$sql = "SELECT p.id AS processId,p.name AS processName,p.laborHours,p.productId,p.totalCost,pg.id AS processGrpId, pg.name AS processGrpName,";
	$sql .= " GROUP_CONCAT(u.id) as employeeIds, GROUP_CONCAT(CONCAT(u.firstName, ' ', u.lastName) ORDER BY u.id) as employees";
	$sql .= " FROM process p LEFT JOIN processgroups pg ON p.processGroupId = pg.id LEFT JOIN users u ON ";
	$sql .= " FIND_IN_SET(u.id, p.userId) > 0 AND u.deleted = 0 WHERE p.productId='$productId' AND p.deleted = 0 GROUP BY p.id , pg.id";

	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$process = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		//echo json_encode(array("process" => $process));
		echo formatData(json_encode($process));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getProcessForEdit($proccessIds) {
	$ids = split(",", $proccessIds);
	$sql = "SELECT name,productId, processGroupId FROM process WHERE id = '$ids[0]'";

	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$process = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode(array("process" => $process));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getProcessGroups($prodId) {
	$sql = "SELECT * FROM processgroups WHERE productId=$prodId AND deleted=0";
	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$processGrp = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode(array("processgroups" => $processGrp));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getRateByUserId($userId) {
	$sql = "select ratePerHour FROM users WHERE id='$userId'";
	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$stmt -> execute();
		$array = $stmt -> fetch(PDO::FETCH_NUM);
		$ratePerHour = $array[0];
		$db = null;
		return $ratePerHour;
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getMaterialPriceById($materialIds) {
	$sql = "SELECT SUM(`actualUnitPrice`) FROM `materials` WHERE id IN (" . $materialIds . ")";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> execute();
		$array = $stmt -> fetch(PDO::FETCH_NUM);
		$sum = $array[0];
		$db = null;
		return $sum;

	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function updateProcess($processId) {
	global $app;
	$req = $app -> request();
	$process = json_decode($req -> getBody());
	//if (!checkProcessUnique($process -> name, $process -> processGroupId, $processId)) {

	$sql = "UPDATE process SET name=:name,productId=:productId,processGroupId=:processGroupId,";
	$sql .= " updatedBy=:updatedBy,updatedAt=NOW() WHERE id IN ('" . implode("','", split(",", $processId)) . "')";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("name", $process -> name);
		$stmt -> bindParam("productId", $process -> productId);
		$stmt -> bindParam("processGroupId", $process -> processGroupId);
		$stmt -> bindParam("updatedBy", $process -> updatedBy);

		$stmt -> execute();
		$db = null;
		echo json_encode(array('processId' => $processId));
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
	// } else {
	// echo json_encode(array('error' => "Process '" . $process -> name . "' already exists."));
	// }
}

function checkProcessUnique($processName, $processGrpId, $processId) {
	try {
		$sql = "select count(1) FROM process WHERE name='$processName' AND processGroupId='$processGrpId' AND deleted=0";
		if ($processId != null) {
			$sql .= " AND id !='$processId'";
		}
		$db = getConnection();
		$stmt = $db -> query($sql);
		$stmt -> execute();
		$array = $stmt -> fetch(PDO::FETCH_NUM);
		$count = $array[0];
		return $count > 0;
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function addProcess() {
	global $app;
	$req = $app -> request();
	$process = json_decode($req -> getBody());
	//if (!checkProcessUnique($process -> name, $process -> processGroupId, null)) {
	$ratePerHour = 0;
	$materialPrice = 0;
	if ($process -> startTime != null && $process -> endTime != null) {
		$timestamp1 = strtotime($process -> startTime);
		$timestamp2 = strtotime($process -> endTime);
		$laborHours = round(abs($timestamp2 - $timestamp1) / (60 * 60), 2);
	}

	if ($process -> userId != null) {
		$ratePerHour = round(getRateByUserId($process -> userId),2);
	}

	if ($process -> materialIds != null) {
		$materialPrice = round(getMaterialPriceById($process -> materialIds), 2);
	}

	if ($process -> userId != null && $process -> materialIds != null) {
		$totalCost = round(($laborHours * $ratePerHour) + $materialPrice, 2);
	}

	$sql = "INSERT INTO process (`name`,`productId`,`processGroupId`,`materialIds`,`userId`,`startTime`,`endTime`,`laborHours`,`totalCost`,`createdBy`) VALUES (:name,:productId,:processGroupId,:materialIds,:userId,:startTime,:endTime,:laborHours,:totalCost,:createdBy)";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("name", $process -> name);
		$stmt -> bindParam("productId", $process -> productId);
		$stmt -> bindParam("processGroupId", $process -> processGroupId);
		$stmt -> bindParam("materialIds", $process -> materialIds);
		$stmt -> bindParam("userId", $process -> userId);
		$stmt -> bindParam("startTime", $process -> startTime);
		$stmt -> bindParam("endTime", $process -> endTime);
		$stmt -> bindParam("laborHours", $laborHours);
		$stmt -> bindParam("totalCost", $totalCost);
		$stmt -> bindParam("createdBy", $process -> createdBy);

		$stmt -> execute();
		$id = $db -> lastInsertId();
		$db = null;
		echo json_encode(array('processId' => $id));
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
	// } else {
	// echo json_encode(array('error' => "Process '" . $process -> name . "' already exists."));
	// }
}

function addProcessGroup() {
	global $app;
	$req = $app -> request();
	$processGrp = json_decode($req -> getBody());
	$name = $processGrp -> name;
	$productId = $processGrp -> productId;
	$sql = "select count(1) FROM processgroups WHERE name='$name' and productId='$productId' AND deleted = 0";
	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$stmt -> execute();
		$array = $stmt -> fetch(PDO::FETCH_NUM);
		$count = $array[0];
		if ($count < 1) {
			$sql = "INSERT INTO processgroups (`name`,`productId`,`description`,`createdBy`) VALUES (:name,:productId,:description,:createdBy)";
			$db = getConnection();
			$stmt = $db -> prepare($sql);
			$stmt -> bindParam("name", $name);
			$stmt -> bindParam("productId", $productId);
			$stmt -> bindParam("description", $processGrp -> description);
			$stmt -> bindParam("createdBy", $processGrp -> createdBy);
			$stmt -> execute();
			$id = $db -> lastInsertId();
			$db = null;
			echo json_encode(array('groupId' => $id));
		} else {
			echo json_encode(array('error' => "Process Group " . $name . " already exists."));
		}
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function updateUser($userId) {
	global $app;
	$req = $app -> request();
	$user = json_decode($req -> getBody());
	if (isEmailUnique($user -> email, $userId) == 0) {
		$sql = "UPDATE users SET firstName=:firstName, lastName=:lastName,email=:email,designation=:designation,phoneNumber=:phoneNumber,userType=:userType,updatedBy=:updatedBy,updatedAt=NOW()";
		if (property_exists($user, "ratePerHour")) {
			$sql .= ",ratePerHour=:ratePerHour";
		}
		$sql .= " WHERE id='$userId'";
		try {
			$db = getConnection();
			$stmt = $db -> prepare($sql);
			$stmt -> bindParam("firstName", $user -> firstName);
			$stmt -> bindParam("lastName", $user -> lastName);
			$stmt -> bindParam("email", $user -> email);
			$stmt -> bindParam("designation", $user -> designation);
			$stmt -> bindParam("phoneNumber", $user -> phoneNumber);
			$stmt -> bindParam("userType", $user -> userType);
			$stmt -> bindParam("updatedBy", $user -> updatedBy);
			if (property_exists($user, "ratePerHour")) {
				$stmt -> bindParam("ratePerHour", $user -> ratePerHour);
			}
			$stmt -> execute();
			$db = null;
			$password = null;
			if (property_exists($user, "password")) {
				$password = $user -> password;
			}
			updateLoginDetails($userId, $user -> email, $password);
			echo json_encode($user);
		} catch(PDOException $e) {
			error_log($e -> getMessage(), 3, '/var/tmp/php.log');
			echo '{"error":{"text":' . $e -> getMessage() . '}}';
		}
	} else {
		echo json_encode(array('error' => "Email Address already exists"));
	}
}

function updateLoginDetails($userId, $userName, $password) {
	$passwd_sql = "";
	if ($password != null) {
		$password = md5($password);
		$passwd_sql = ", password='$password'";
	}
	$sql = "UPDATE login SET userName=:email " . $passwd_sql . " WHERE userId='$userId'";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("email", $userName);
		$stmt -> execute();
		$db = null;
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function updatePassword($userId) {
	global $app;
	$req = $app -> request();
	$password = json_decode($req -> getBody());
	$sql = "UPDATE login SET password=:password WHERE userId='$userId'";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("password", md5($password -> password));
		$stmt -> execute();
		$db = null;
		echo json_encode(array("userId" => $userId));
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getMaterial($materialId) {
	$sql = "select * FROM materials WHERE id='$materialId'";
	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$material = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($material);
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getProduct($productId) {
	$sql = "select * FROM products WHERE id='$productId'";
	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$product = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($product);
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getUser($userId) {
	$sql = "select * FROM users WHERE id='$userId'";
	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$user = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode(array("user" => $user));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function authenticate() {
	global $app;
	$req = $app -> request();
	try {
		$sql = "SELECT userId,userType FROM login l , users u WHERE userName=:username AND password=:password AND u.id=l.userId AND u.deleted=0";
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("username", $req -> params('username'));
		$stmt -> bindParam("password", md5($req -> params('password')));
		$stmt -> execute();
		$user = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($user);
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function updateMaterial($materialId) {
	global $app;
	$req = $app -> request();
	$material = json_decode($req -> getBody());
	$actTotPrice = round($material -> actualQty * $material -> actualUnitPrice, 2);
	$stdTotPrice = round($material -> stdQty * $material -> stdUnitPrice, 2);
	$sql = "UPDATE materials SET productId=:productId, name=:name, description=:description, partNumber=:partNumber, barcode=:barcode, actualQty=:actualQty, actualUnitPrice=:actualUnitPrice,actualTotalPrice=:actualTotalPrice,stdQty=:stdQty,stdUnitPrice=:stdUnitPrice,stdTotalPrice=:stdTotalPrice,vendorName=:vendorName,vendorPartNumber=:vendorPartNum,updatedBy=:updatedBy,updatedAt=NOW() WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("productId", $material -> productId);
		$stmt -> bindParam("name", $material -> name);
		$stmt -> bindParam("description", $material -> description);
		$stmt -> bindParam("partNumber", $material -> partNumber);
		$stmt -> bindParam("barcode", $material -> barcode);
		$stmt -> bindParam("actualQty", $material -> actualQty);
		$stmt -> bindParam("actualUnitPrice", $material -> actualUnitPrice);
		$stmt -> bindParam("stdQty", $material -> stdQty);
		$stmt -> bindParam("stdUnitPrice", $material -> stdUnitPrice);
		$stmt -> bindParam("vendorName", $material -> vendorName);
		$stmt -> bindParam("vendorPartNum", $material -> vendorPartNum);
		$stmt -> bindParam("actualTotalPrice", $actTotPrice);
		$stmt -> bindParam("stdTotalPrice", $stdTotPrice);
		$stmt -> bindParam("updatedBy", $material -> updatedBy);
		$stmt -> bindParam("id", $materialId);
		$stmt -> execute();
		$db = null;
		echo json_encode($material);
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function updateProduct($productId) {
	global $app;
	$req = $app -> request();
	$product = json_decode($req -> getBody());
	if (isProductUnique($product -> name, $productId) == 0) {
		$sql = "UPDATE products SET name=:name, description=:description, updatedBy=:updatedBy, updatedAt=NOW() WHERE id=$productId";
		try {
			$db = getConnection();
			$stmt = $db -> prepare($sql);
			$stmt -> bindParam("name", $product -> name);
			$stmt -> bindParam("description", $product -> description);
			$stmt -> bindParam("updatedBy", $product -> updatedBy);
			$stmt -> execute();
			$db = null;
			echo json_encode($product);
		} catch(PDOException $e) {
			error_log($e -> getMessage(), 3, '/var/tmp/php.log');
			echo '{"error":{"text":' . $e -> getMessage() . '}}';
		}
	} else {
		echo json_encode(array('error' => "Boat name already exists"));
	}
}

function addProduct() {
	global $app;
	$req = $app -> request();
	$product = json_decode($req -> getBody());
	if (isProductUnique($product -> name, null) == 0) {
		$sql = "INSERT INTO products (`name`,`description`,`createdBy`) VALUES (:name,:description,:createdBy)";
		try {
			$db = getConnection();
			$stmt = $db -> prepare($sql);
			$stmt -> bindParam("name", $product -> name);
			$stmt -> bindParam("description", $product -> description);
			$stmt -> bindParam("createdBy", $product -> createdBy);
			$stmt -> execute();
			$product -> id = $db -> lastInsertId();
			$db = null;
			echo json_encode($product);
		} catch(PDOException $e) {
			error_log($e -> getMessage(), 3, '/var/tmp/php.log');
			echo '{"error":{"text":' . $e -> getMessage() . '}}';
		}
	} else {
		echo json_encode(array('error' => "Boat name already exists"));
	}
}

function addMaterial() {
	global $app;
	$req = $app -> request();
	$material = json_decode($req -> getBody());
	$actTotPrice = round($material -> actualQty * $material -> actualUnitPrice, 2);
	$stdTotPrice = round($material -> stdQty * $material -> stdUnitPrice, 2);

	$sql = "INSERT INTO materials (`productId`,`name`,`description`,`partNumber`,`barcode`,`actualQty`,`actualUnitPrice`,`stdQty`,`stdUnitPrice`,`actualTotalPrice`,`stdTotalPrice`,`vendorName`,`vendorPartNumber`,`createdBy`) VALUES (:productId,:name,:description,:partNumber,:barcode,:actualQty,:actualUnitPrice,:stdQty,:stdUnitPrice,:actualTotalPrice,:stdTotalPrice,:vendorName,:vendorPartNum,:createdBy)";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("productId", $material -> productId);
		$stmt -> bindParam("name", $material -> name);
		$stmt -> bindParam("description", $material -> description);
		$stmt -> bindParam("partNumber", $material -> partNumber);
		$stmt -> bindParam("barcode", $material -> barcode);
		$stmt -> bindParam("actualQty", $material -> actualQty);
		$stmt -> bindParam("actualUnitPrice", $material -> actualUnitPrice);
		$stmt -> bindParam("stdQty", $material -> stdQty);
		$stmt -> bindParam("stdUnitPrice", $material -> stdUnitPrice);
		$stmt -> bindParam("vendorName", $material -> vendorName);
		$stmt -> bindParam("vendorPartNum", $material -> vendorPartNum);
		$stmt -> bindParam("actualTotalPrice", $actTotPrice);
		$stmt -> bindParam("stdTotalPrice", $stdTotPrice);
		$stmt -> bindParam("createdBy", $material -> createdBy);
		$stmt -> execute();
		$id = $db -> lastInsertId();
		$db = null;
		echo json_encode(array('materialId' => $id));
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function isEmailUnique($email, $id) {
	$sql = "select count(1) FROM users WHERE email='$email' AND deleted=0";
	if ($id != null) {
		$sql .= " AND id !='$id'";
	}
	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$stmt -> execute();
		$array = $stmt -> fetch(PDO::FETCH_NUM);
		$count = $array[0];
		$db = null;
		return $count;
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function isProductUnique($name, $id) {
	$sql = "select count(1) FROM products WHERE name='$name'";
	if ($id != null) {
		$sql .= " AND id !='$id'";
	}

	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$stmt -> execute();
		$array = $stmt -> fetch(PDO::FETCH_NUM);
		$count = $array[0];
		$db = null;
		return $count;
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function addUser() {
	global $app;
	$req = $app -> request();
	$user = json_decode($req -> getBody());
	if (isEmailUnique($user -> email, null) == 0) {
		$sql = "INSERT INTO users (`firstName`,`lastName`,`email`,`designation`,`phoneNumber`,`userType`,`createdBy`,`ratePerHour`) VALUES (:firstName,:lastName,:email,:designation,:phoneNumber,:userType,:createdBy,:ratePerHour)";
		try {
			$db = getConnection();
			$stmt = $db -> prepare($sql);
			$stmt -> bindParam("firstName", $user -> firstName);
			$stmt -> bindParam("lastName", $user -> lastName);
			$stmt -> bindParam("email", $user -> email);
			$stmt -> bindParam("designation", $user -> designation);
			$stmt -> bindParam("phoneNumber", $user -> phoneNumber);
			$stmt -> bindParam("userType", $user -> userType);
			$stmt -> bindParam("ratePerHour", $user -> ratePerHour);
			$stmt -> bindParam("createdBy", $user -> createdBy);
			$stmt -> execute();
			$id = $db -> lastInsertId();

			$loginId = addLogin($db, $id, $user -> email, $user -> password);
			if ($loginId != null) {
				echo json_encode(array('userId' => $id));
			} else {
				$sql = "DELETE FROM users WHERE id=$id";
				$stmt = $db -> prepare($sql);
				$stmt -> execute();
			}
		} catch(PDOException $e) {
			error_log($e -> getMessage(), 3, '/var/tmp/php.log');
			echo '{"error":{"text":' . $e -> getMessage() . '}}';
		}
	} else {
		echo json_encode(array('error' => "Email Address already exists"));
	}
}

function addLogin($db, $userId, $email, $password) {
	$id = null;
	$sql = "INSERT INTO login(`userId`,`userName`,`password`) VALUES(:userId,:userName,:password)";
	try {
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("userId", $userId);
		$stmt -> bindParam("userName", $email);
		$stmt -> bindParam("password", md5($password));
		$stmt -> execute();
		$id = $db -> lastInsertId();
		$db = null;
	} catch(PDOException $e) {
		error_log($e -> getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
	return $id;
}

function getUsers($type) {
	$sql = "select * FROM users WHERE ";
	if ($type != "all") {
		$sql .= "userType='$type' AND ";
	}
	$sql .= "deleted = 0 ORDER BY firstName";

	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$users = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode(array("users" => $users));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getProducts() {
	$sql = "select * FROM products WHERE deleted = 0 ORDER BY name";
	try {
		$db = getConnection();
		$stmt = $db -> query($sql);
		$products = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode(array("products" => $products));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

function getMaterials($productId) {
	$sql = "select * FROM materials WHERE productId=:productId AND deleted = 0 ORDER BY name";
	try {
		$db = getConnection();
		$stmt = $db -> prepare($sql);
		$stmt -> bindParam("productId", $productId);
		$stmt -> execute();
		$material = $stmt -> fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode(array("materials" => $material));
	} catch(PDOException $e) {
		echo '{"error":{"text":' . $e -> getMessage() . '}}';
	}
}

/*function getConnection() {
 $dbhost = "medinaihcom.ipagemysql.com";
 $dbuser = "mysqluser";
 $dbpass = "admin";
 $dbname = "harborguardboats";
 $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
 $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 return $dbh;
 }

 function getConnection() {
 $dbhost = "nutechsolutionscom.ipagemysql.com";
 $dbuser = "mysqluser";
 $dbpass = "admin";
 $dbname = "harborguardboats";
 $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
 $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 return $dbh;
 }*/

function getConnection() {
	$dbhost = "127.0.0.1";
	$dbuser = "root";
	$dbpass = "root";
	$dbname = "harbor";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
?>