<?php 

	include 'libs.php';
	if (!$_COOKIE['user']) {
		header("Location: login.php");
	}
	if (isset($_POST['topic_id'])) {
		$topic_id = $_POST['topic_id'];
		$lang = $_POST['lang'];
		$complexity = $_POST['complexity'];
		$block = $_POST['block'];
		$class = $_POST['class'];

		$problems = getProblems($lang, $complexity, $topic_id, $block, $class);
		foreach ($problems as $problem) {
			$complexity = $problem['complexity'] == 1 ? "Easy" : ($problem['complexity'] == 2 ? "Medium" : "Hard");
			echo '<div class="card">
					<p href="#" class="card_header"><a href="problem.php?id='.$problem['id'].'">'.$problem['problem_text'].'</a></p>
					<div class="tags">
						<p>'.getTopic($problem['topic_id'])['name'].'</p>
						<p>'.$complexity.'</p>
						<p>'.$problem['lang'].'</p>
						<p>'.$problem['class'].' class</p>
					</div>';
			if (getUser($_COOKIE['user'])['admin'] == '1') {
				echo '<div class=tags>';
				if ($problem['block'] == '1') {
					echo '<p class="inline inline-succ" onclick="publishProblem(\''.$problem['id'].'\')">Publish</p>';
				}
				echo '<p class="inline inline-err" onclick="deleteProblem(\''.$problem['id'].'\')">Delete</p></div>';
			}
			echo '</div>';
		}
		exit();
	}
	if (isset($_POST['publish'])) {
		$problem_id = $_POST['publish'];
		toggleProblemBlock($problem_id);
		exit();
	}
	if (isset($_POST['delete'])) {
		$problem_id = $_POST['delete'];
		deleteProblem($problem_id);
		exit();
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign up - Smark Physics</title>
	<link rel="stylesheet" href="css/style.css?das=<?=rand(1, 100000)?>">
    <script src="js/script.js?das=<?=rand(1, 100000)?>"></script>
    <script src="js/jquery-3.4.1.min.js"></script>
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
			echo '<a href="index.php?topic_id=-1"><b>All</b></a>';
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
		<div class="filters">
			<div class="filter">
				<p>Complexity:</p>
				<div class="toggle">
					<input type="radio" name="complexity" value="-1" onclick="getProblems()" id="topic0" checked="checked" />
					<label for="topic0">All</label>
					<input type="radio" name="complexity" value="1" onclick="getProblems()" id="topic"/>
					<label for="topic">Easy</label>
					<input type="radio" name="complexity" value="2" onclick="getProblems()" id="topic1"/>
					<label for="topic1">Medium</label>
					<input type="radio" name="complexity" value="3" onclick="getProblems()" id="topic2"/>
					<label for="topic2">Hard</label>
				</div>
			</div>
			<div class="filter">
				<p>Language:</p>
				<div class="toggle">
					<input type="radio" name="lang" value="-1" onclick="getProblems()" id="lang0" checked="checked" />
					<label for="lang0">All</label>
					<input type="radio" name="lang" value="KZ" onclick="getProblems()" id="lang1"/>
					<label for="lang1">KZ</label>
					<input type="radio" name="lang" value="RU" onclick="getProblems()" id="lang2"/>
					<label for="lang2">RU</label>
					<input type="radio" name="lang" value="ENG" onclick="getProblems()" id="lang3"/>
					<label for="lang3">ENG</label>
				</div>
			</div>
		</div>
		<div class="filters" style="display: <?php echo getUser($_COOKIE['user'])['admin'] == '1' ? 'flex' : 'none'; ?>" >
			<div class="filter">
				<p>Filter:</p>
				<div class="toggle">
					<input type="radio" name="filter" value="0" onclick="getProblems()" id="allproblems" checked="checked"/>
					<label for="allproblems">Questions</label>
					<input type="radio" name="filter" value="1" onclick="getProblems()" id="suggestions"/>
					<label for="suggestions">Suggestions</label>
				</div>
			</div>
			<div class="filter">
					<p>Topic:</p>
					<select class="class list_main" name="ddProducts" onchange="getProblems()"> 
					   	<option value="-1">Any class</option>
					   	<option value="7">7</option>
					   	<option value="8">8</option>
					   	<option value="9">9</option>
					   	<option value="10">10</option>
					   	<option value="11">11</option>
					   	<option value="12">12</option>
					</select>
				</div>
		</div>
		<input type="" name="" placeholder="Search problems..." class="search">
		<p class="problems_title">Problems found: <b id="problem_count">24</b></p>
		<div class="problems">
			
		</div>
	</div>
	<div class="footer">
		<p>© 2023 Smark Physics</p>
	</div>
	<script type="text/javascript">
		
		function logOut() {
			setCookie("user", "", 0);
			window.location.href = "login.php"
		}
		getProblems()
		function getProblems() {
			topic_id = <?=$_GET['topic_id']??-1?>;
			lang = $(`input[name=lang]:checked`).val()
			complexity = $(`input[name=complexity]:checked`).val()
			block = $(`input[name=filter]:checked`).val()
			class_ = $(`.class`).val()
			$.post(`index.php`, {topic_id: topic_id, lang: lang, complexity: complexity, block: block, class: class_}, function(data) {
				$(`.problems`).html(data)
				$(`#problem_count`).html($(`.card`).length)
			})
		}
		function publishProblem(problem_id) {
			$.post(`index.php`, {publish: problem_id}, function() {
				getProblems()
			})
		}
		function deleteProblem(problem_id) {
			// need to ask are you sure
			if (!confirm("Are you sure?")) return;
			$.post(`index.php`, {delete: problem_id}, function() {
				getProblems()
			})
		}
	</script>
</body>
</html>




