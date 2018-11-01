<?php
	require_once "bootstrap.php";
	
	$city = $entityManager->find('City', 8);
	
	$user = new User();
	
	$user->setUsername('root');
	$user->setPassword('123456');
	$user->setActived(true);
	$user->setDepartment("天鹅湖中队");
	$user->setSessionId('');
	$dt = new DateTime('NOW');
	
	$t = $dt->format('Y-m-d H:i:s');
	$user->setCreatedTime(new DateTime($t));
	$user->setLoginTime(new DateTime($t));
	$user->setIsOnline(false);
	$user->setPrivilege('200'); // 支队接警员权限：100，中队接警员权限：200, 普通用户：300
	$user->setCity($city);
	$user->setDepartmentId(7);
	$entityManager->persist($user);
	
	$entityManager->flush();
	
	echo $user->getId() . "\n";
