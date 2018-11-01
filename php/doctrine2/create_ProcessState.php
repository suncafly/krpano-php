<?php
	require_once "bootstrap.php";
	
	$params = array(
		array(0,'指挥中心推送警情'),
		array(1,'中队接警'),
		array(2,'中队呈报警力'),
		array(3,'中队抵达现场'),
		array(4,'中队处置完毕'),
		array(5,'中队返回途中'),
		array(6,'中队已归队'),
	);
	
	for ($i = 0, $len = count($params); $i < $len; ++$i) {
		$ps = new ProcessState();
		$ps->setType($params[$i][0]);
		$ps->setContent($params[$i][1]);
		$entityManager->persist($ps);
	}
	
	$entityManager->flush();

	