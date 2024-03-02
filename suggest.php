<?php 
	
	include 'libs.php';
	if (!$_COOKIE['user']) {
		header("Location: login.php");
	}
	if (isset($_POST['problem_text'])) {
	    $problem_photo = md5(time().$_FILES['problem_photo']['name']).'.'.end(explode('.', $_FILES['problem_photo']['name']));
	    move_uploaded_file($_FILES['problem_photo']['tmp_name'], 'uploads/'.$problem_photo);

 		$answer_photo = md5(time().$_FILES['answer_photo']['name']).'.'.end(explode('.', $_FILES['answer_photo']['name']));
	    move_uploaded_file($_FILES['answer_photo']['tmp_name'], 'uploads/'.$answer_photo);


 		$solution_photo = md5(time().$_FILES['solution_photo']['name']).'.'.end(explode('.', $_FILES['solution_photo']['name']));
	    move_uploaded_file($_FILES['solution_photo']['tmp_name'], 'uploads/'.$solution_photo);



		$problem_text = $_POST['problem_text'];
		$answer_text = $_POST['answer_text'];
		$solution_text = $_POST['solution_text'];
		$author = $_POST['author'];
		$lang = $_POST['lang'];
		$complexity = $_POST['complexity'];
		$topic_id = $_POST['topic_id'];
		$class = $_POST['class'];

		if (newProblem($problem_text, $problem_photo, $answer_text, $answer_photo, $solution_text, $solution_photo, $author, $lang, $complexity, $topic_id, $class)) {
			echo "success";
		} else {
			echo "error";
		}
		exit();
	}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Suggest Question - Smark Physics</title>
	<link rel="stylesheet" href="css/style.css?das=<?=rand(1, 100000)?>">
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
		<!-- suggest problem page with 2 input for answer and problem and 2 inputs for uploading images for answer and problem -->
		<div class="login">
			<p class="title">Suggest problem</p>
			<div class="suggest_input">
				<div class="filter"><p>Problem:</p></div>
				<textarea placeholder="Write problem statement here..." id=problem></textarea>
				<label class="input-file">
				   	<input type="file" name="file" id="problem_photo">		
				   	<span>Problem Photo</span>
			 	</label>
			</div>
			<hr>
			<div class="suggest_input">
				<div class="filter"><p>Answer:</p></div>
				<textarea placeholder="Write answer here..." id=answer></textarea>
				<label class="input-file">
				   	<input type="file" name="file" id="answer_photo">		
				   	<span>Answer Photo</span>
			 	</label>
			 	<div class="filter"><p>Solution:</p></div>
				<textarea placeholder="Write solution here..." id=solution></textarea>
				<label class="input-file">
				   	<input type="file" name="file" id="solution_photo">		
				   	<span>Solution Photo</span>
			 	</label>
			 	<br>
			 	<br>
			 	<br>
				<div class="filter">
					<p>Complexity:</p>
					<div class="toggle primary">
						<input type="radio" name="complexity" value="1" id="topic" checked/>
						<label for="topic">Easy</label>
						<input type="radio" name="complexity" value="2" id="topic1"/>
						<label for="topic1">Medium</label>
						<input type="radio" name="complexity" value="3" id="topic2"/>
						<label for="topic2">Hard</label>
					</div>
				</div>
				<div class="filter">
					<p>Language:</p>
					<div class="toggle primary">
						<input type="radio" name="lang" value="KZ" id="lang1" checked="checked"/>
						<label for="lang1">KZ</label>
						<input type="radio" name="lang" value="RU" id="lang2"/>
						<label for="lang2">RU</label>
						<input type="radio" name="lang" value="ENG" id="lang3"/>
						<label for="lang3">ENG</label>
					</div>
				</div>
				<div class="filter">
					<p>Topic:</p>
					<select class="topics list" name="ddProducts"> 
					   	<?php 
					   		$topics = getTopics();
					   		foreach ($topics as $topic) {
					   			echo "<option value=\"".$topic['id']."\">".$topic['name']."</option>";
					   		}

					   	 ?>
					</select>
				</div>
				<div class="filter">
					<p>Classes:</p>
					<select class="class list" name="ddProducts"> 
					   	<option value="7">7</option>
					   	<option value="8">8</option>
					   	<option value="9">9</option>
					   	<option value="10">10</option>
					   	<option value="11">11</option>
					   	<option value="12">12</option>
					</select>
				</div>
			</div>

			<button class="suggest_button" onclick="suggest()">Suggest</button>
		</div>
		<div class="error" id="alert" style="display: none;">
			<p>Wrong password</p>
		</div>
	</div>
	<div class="footer">
		<p>© 2023 Smark Physics</p>
	</div>
	<script src="js/script.js?das=<?=rand(1, 100000)?>"></script>
	<script>
		function suggest() {
			// get values from inputs
			problem_text = $('#problem').val()
			problem_photo = $(`#problem_photo`).prop("files")[0];
			answer_text = $(`#answer`).val()
			answer_photo = $(`#answer_photo`).prop("files")[0];
			solution_text = $(`#solution`).val()
			solution_photo = $(`#solution_photo`).prop("files")[0];
			author = <?=getUser($_COOKIE['user'])['id']?>;
			lang = $(`input[name=lang]:checked`).val()
			complexity = $(`input[name=complexity]:checked`).val()
			topic_id = $(`.topics`).val()
			class_ = $(`.class`).val()

			showALert("Uploading problem wait a bit...", 0)
			var formData = new FormData();
			formData.append("problem_text", problem_text);
			formData.append("problem_photo", problem_photo);
			formData.append("answer_text", answer_text);
			formData.append("answer_photo", answer_photo);
			formData.append("solution_text", solution_text);
			formData.append("solution_photo", solution_photo);
			formData.append("author", author);
			formData.append("lang", lang);
			formData.append("complexity", complexity);
			formData.append("topic_id", topic_id);
			formData.append("class", class_);
			$.ajax({
			    url : "suggest.php",
			    type: "POST",
			    data : formData,
			    processData: false,
			    contentType: false,
			    success:function(data, textStatus, jqXHR){
			    	if (data == `success`) {
						showALert(data, 0)
						$('#problem').val('')
						$('#problem_photo').val('')
						$('#answer').val('')
						$('#answer_photo').val('')
						setTimeout(function () {
							location.reload()
						}, 500)
					} else {
						showALert("Success!", 1)
					}
			    },
			    error: function(jqXHR, textStatus, errorThrown){
			        //if fails
			    }
			});


		}
	</script>
</body>
</html>

