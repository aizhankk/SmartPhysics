<?php 

	include 'libs.php';


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign up - Smark Physics</title>
	<link rel="stylesheet" href="css/style.css?das=<?=rand(1, 100000)?>">
    <script src="js/script.js?das=<?=rand(1, 100000)?>"></script>
</head>
<body>
	<div class="header">
		<p class="small" onclick="openMenu('menu_left', 'menu_right')"><?php echo $_GET['topic_id'] && $_GET['topic_id'] != -1 ? getTopic($_GET['topic_id'])['name'] : "Topics"; ?></p>
		<p><a href="index.php">Smark Physics</a></p>
		<p class="small" onclick="openMenu('menu_right', 'menu_left')"><?=$_COOKIE['user']?></p>
	</div>
	<div class="menu left" id="menu_left">
		<?php 

			$topics = getTopics();
			echo '<a href="index.php?topic_id=-1">All</a>';
			foreach ($topics as $topic) {
				echo '<a href="index.php?topic_id='.$topic['id'].'">'.$topic['name'].'</a>';
			}


		 ?>
	</div>
	<div class="menu right" id="menu_right">
		<a href="">Profile - <?=$_COOKIE['user']?></a>
		<?php if (getUser($_COOKIE['user'])['position'] == '1' || getUser($_COOKIE['user'])['admin'] == '1')
			echo '<a href="suggest.php">Suggest problem (Teacher)</a>'
		 ?>
		<a href="" onclick="logOut()">Log out</a>
	</div>
	<div class="back" id="back" onclick="closeAllMenu()"></div>
	<div class="content">
		<div class="problem">
			<?php 

				$problem_id = $_GET['id'];
				$problem = getProblem($problem_id);
				$complexity = $problem['complexity'] == 1 ? "Easy" : ($problem['complexity'] == 2 ? "Medium" : "Hard");

				echo '<a href="index.php?topic_id='.$problem['topic_id'].'" class="back_button"><- Back to <b>Questions</b></a>
				<div class="tags">
					<p>'.getTopic($problem['topic_id'])['name'].'</p>
					<p>'.$complexity.'</p>
					<p>'.$problem['lang'].'</p>
				</div>

				<p class="title">'.$problem['problem_text'].'</p>
				<p class="author">Author: '.getUserById($problem['author'])['username'].'</p>
				<img src="uploads/'.$problem['problem_photo'].'" alt="">
				<button class="seeanswer_button" onclick="toggleAnswer(this)">Show Answer</button>
				<div class="answer" id="answer" style="display: none;">
					<p>'.$problem['answer_text'].'</p>
					<img src="uploads/'.$problem['answer_photo'].'" alt="">
				</div>
				<button class="seeanswer_button" onclick="toggleSolution(this)">Show Solution</button>
				<div class="answer" id="solution" style="display: none;">
					<p>'.$problem['solution_text'].'</p>
					<img src="uploads/'.$problem['solution_photo'].'" alt="">
				</div>';

			 ?>
		</div>	
		
		
	</div>
	<div class="footer">
		<p>© 2023 Smark Physics</p>
	</div>
</body>
</html>

