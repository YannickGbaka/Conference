<?php 
//Globals variables
//////Boolean
$formSubmit = null;
$evaluator = null;
// Field 
$evaluatorName = null;
$evaluation = null;
$authorsComment = null;
$chairmanMessage = null;
//Boolean pour un article évalué
$isEvaluate = null;

// Trying to connect to the Database
try{
	$query = $db->query('SELECT * FROM articles');
	$articles = $query->fetchAll(PDO::FETCH_OBJ);
}// catch errors in exception cases
catch(PDOException $e){
	$error = "[Erreur] ".$e->getMessage();
}
// verify if we a convenable page in order to print a form
if(isset($_GET['page']) && $_GET['page'] == 'evaluation')
{	if(isset($_GET['app']) && $_GET['app'] == 'evl')
	{
		if(isset($_GET['id'])&& $_GET['id']>0){
			$evaluator = true;
			$request = $db->prepare('SELECT * FROM evaluation where numArticle = :id');
			$request->execute([
				'id' => $_GET['id']
			]);
			$evaluations = $request->fetchAll(PDO::FETCH_OBJ); //Retour de l'évaluation
			if(!empty($evaluations)){
				$isEvaluate = true;
			}
			if(isset($_POST['subEval']))
			{
				//we are making sure there if these fields are not empty

				if(!empty($_POST['evaluatorName']) && !empty($_POST['evaluation']) && !empty($_POST['authorsComment']) && !empty($_POST['chairmanMessage']))
				{
					
					$_POST['evaluation'] = (int) $_POST['evaluation'];
					$evaluatorName = $_POST['evaluatorName'];
					$evaluation = $_POST['evaluation'];
					$authorsComment = $_POST['authorsComment'];
					$chairmanMessage = $_POST['chairmanMessage'];
					if($evaluation<0){
						$formSubmit = false;
					}elseif ($evaluation>0 && $evaluation <= 5) 
					{
						$request = $db->prepare('INSERT INTO evaluation (numArticle, evaluatorName, 	evaluationNote, authorsComment, chairmanMessage) VALUES (:num, :name, :note, :comment, :message )');

						$request->execute([
							'num' => str_secur($_GET['id']),
							'name' =>str_secur($evaluatorName),
							'note' => str_secur($evaluation),
							'comment' => nl2br(str_secur($authorsComment)),
							'message' => nl2br(str_secur($chairmanMessage)),
						]);
						$formSubmit = true;
					}
				}
				else{
					$formSubmit = false;
				}
			}
		}
		
	}
}

