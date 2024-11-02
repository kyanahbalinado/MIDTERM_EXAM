<?php  

function insertWebDev($pdo, $first_name, $last_name, 
	$specialization, $rating) {
	$sql = "INSERT INTO tattoo_artists (first_name, last_name, 
		specialization, rating) VALUES(?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$first_name, $last_name, 
		$specialization, $rating]);
	if ($executeQuery) {
		return true;
	}
}

function updateWebDev($pdo, $first_name, $last_name, 
	$specialization, $rating, $artist_id) {
	$sql = "UPDATE tattoo_artists
				SET first_name = ?,
					last_name = ?,
					specialization = ?, 
					rating = ?
				WHERE artist_id = ?;";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$first_name, $last_name, 
		$specialization, $rating, $artist_id]);
	
	if ($executeQuery) {
		return true;
	}
}

function deleteWebDev($pdo, $artist_id) {
	$deleteWebDevProj = "DELETE FROM tattoos WHERE artist_id = ?";
	$deleteStmt = $pdo->prepare($deleteWebDevProj);
	$executeDeleteQuery = $deleteStmt->execute([$artist_id]);

	if ($executeDeleteQuery) {
		$sql = "DELETE FROM tattoo_artists WHERE artist_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$artist_id]);

		if ($executeQuery) {
			return true;
		}
	}
}

function getAllWebDevs($pdo) {
	$sql = "SELECT * FROM tattoo_artists";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getTattooByID($pdo, $artist_id) {
	$sql = "SELECT * FROM tattoos WHERE artist_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$artist_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function getTattoosByArtist($pdo, $artist_id) {
	$sql = "SELECT * FROM tattoos WHERE artist_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$artist_id]);
	return $stmt->fetchAll();
}

function getTattooArtistByID($pdo, $artist_id) {
    $sql = "SELECT * FROM tattoo_artists WHERE artist_id = ?";
    $stmt = $pdo->prepare($sql); 
	$executeQuery = $stmt->execute([$artist_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function insertProject($pdo, $tattoo_name, $tattoo_style, $date_done, $cost, $artist_id) {
	$sql = "INSERT INTO tattoos (tattoo_name, tattoo_style, date_done, cost, artist_id) VALUES (?,?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$tattoo_name, $tattoo_style, $date_done, $cost, $artist_id]);
	if ($executeQuery) {
		return true;
	}
}

function getProjectByID($pdo, $tattoo_id) {
	$sql = "SELECT 
				tattoos.tattoo_id AS tattoo_id,
				tattoos.tattoo_name AS tattoo_name,
				tattoos.tattoo_style AS tattoo_style,
				tattoos.date_done AS date_done,
				tattoos.cost AS cost,
				CONCAT(tattoo_artists.first_name,' ',tattoo_artists.last_name) AS tattoo_owner
			FROM tattoos
			JOIN tattoo_artists ON tattoos.artist_id = tattoo_artists.artist_id
			WHERE tattoos.tattoo_id = ? 
			GROUP BY tattoos.tattoo_name;";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$tattoo_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function updateProject($pdo, $tattoo_name, $tattoo_style, $date_done, $cost, $tattoo_id) {
	$sql = "UPDATE tattoos
			SET tattoo_name = ?,
				tattoo_style = ?,
				date_done  = ?,
				cost  = ?
			WHERE tattoo_id = ?;";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$tattoo_name, $tattoo_style, $date_done, $cost, $tattoo_id]);

	if ($executeQuery) {
		return true;
	}
}

function deleteProject($pdo, $tattoo_id) {
	$sql = "DELETE FROM tattoos WHERE tattoo_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$tattoo_id]);
	if ($executeQuery) {
		return true;
	}
}


require_once 'dbConfig.php';

function insertNewUser($pdo, $username, $password) {

	$checkUserSql = "SELECT * FROM user_passwords WHERE username = ?";
	$checkUserSqlStmt = $pdo->prepare($checkUserSql);
	$checkUserSqlStmt->execute([$username]);

	if ($checkUserSqlStmt->rowCount() == 0) {

		$sql = "INSERT INTO user_passwords (username,password) VALUES(?,?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$username, $password]);

		if ($executeQuery) {
			$_SESSION['message'] = "User successfully inserted";
			return true;
		}

		else {
			$_SESSION['message'] = "An error occured from the query";
		}

	}
	else {
		$_SESSION['message'] = "User already exists";
	}

	
}



function loginUser($pdo, $username, $password) {
	$sql = "SELECT * FROM user_passwords WHERE username=?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username]); 

	if ($stmt->rowCount() == 1) {
		$userInfoRow = $stmt->fetch();
		$usernameFromDB = $userInfoRow['username']; 
		$passwordFromDB = $userInfoRow['password'];

		if ($password == $passwordFromDB) {
			$_SESSION['username'] = $usernameFromDB;
			$_SESSION['message'] = "Login successful!";
			return true;
		}

		else {
			$_SESSION['message'] = "Password is invalid, but user exists";
		}
	}

	
	if ($stmt->rowCount() == 0) {
		$_SESSION['message'] = "Username doesn't exist from the database. You may consider registration first";
	}

}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_passwords";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}

}

function getUserByID($pdo, $user_id) {
	$sql = "SELECT * FROM user_passwords WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}


?>