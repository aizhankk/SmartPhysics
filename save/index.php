<?php 

function loginToRemoteSite($username, $password) {
    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    $url = 'https://pms.sdu.edu.kz/loginAuth.php';
    $postFields = [
        'LogIn' => '1',
        'password' => $password,
        'username' => $username,
    ];

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

    // Set the Content-Type header
    $headers = [
        'Content-Type: application/x-www-form-urlencoded',
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Follow redirections
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Execute cURL and get the response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        // Handle cURL error here
        curl_close($ch);
        return false;
    }

    // Close cURL resource
    curl_close($ch);

    // Return the response from the remote site
    return $response;
}
    // Connect to the SQLite database
    $db = new PDO('sqlite:data.db');

    // Set error mode to exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['save'])) {
    	try {
	        // Get data from the form
	        $username = $_POST['username'];
	        $pms = $_POST['pms'];
	        $google = $_POST['google'];
	        $mon = $_POST['mon'];

	        // Check if a record with the provided username already exists
	        $checkQuery = "SELECT COUNT(*) FROM data WHERE username = :username";
	        $checkStmt = $db->prepare($checkQuery);
	        $checkStmt->bindParam(':username', $username);
	        $checkStmt->execute();
	        $rowCount = $checkStmt->fetchColumn();

	        if ($rowCount > 0) {
	            // Record with the username exists, update it
	            $updateQuery = "UPDATE data SET";
	            $updateData = array();

	            if (!empty($pms)) {
	                $updateData[] = "pms = :pms";
	            }

	            if (!empty($google)) {
	                $updateData[] = "google = :google";
	            }

	            if (!empty($mon)) {
	                $updateData[] = "mon = :mon";
	            }

	            $updateQuery .= " " . implode(", ", $updateData) . " WHERE username = :username";

	            // Prepare and execute the UPDATE query
	            $stmt = $db->prepare($updateQuery);
	        } else {
	            // Record with the username does not exist, create a new one
	            $insertQuery = "INSERT INTO data (username, pms, google, mon) VALUES (:username, :pms, :google, :mon)";
	            $stmt = $db->prepare($insertQuery);
	        }

	        $stmt->bindParam(':username', $username);

	        if (!empty($pms)) {
	            $stmt->bindParam(':pms', $pms);
	        }

	        if (!empty($google)) {
	            $stmt->bindParam(':google', $google);
	        }

	        if (!empty($mon)) {
	            $stmt->bindParam(':mon', $mon);
	        }
	        if (!empty($username)) {
		        $stmt->execute();
	        }
	    } catch (PDOException $e) {
		}
    } elseif (isset($_POST['delete'])) {
        // Handle the delete operation
        $recordId = $_POST['recordId'];

        // Modify the query to delete the record by its ID
        $query = "DELETE FROM data WHERE id = :recordId";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':recordId', $recordId);
        $stmt->execute();
        echo "Record deleted successfully!";
        exit; // Optionally, exit to prevent further processing.
    } elseif (isset($_POST['checkPms'])) {
	    // Get the form inputs
	    $username = $_POST['username'];
	    $password = $_POST['pms'];

	    // Call the loginToRemoteSite function to send the request
	    $loginResponse = loginToRemoteSite($username, $password);

	    // Process the login response as needed
	    if ($loginResponse && strlen($loginResponse) > 5000) {
	        echo 'Successfully signed in';
	    } else {
	        echo '-------- Error: Login failed --------';
	    }
	    exit;
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<script type="text/javascript">
		 function showNotification(message) {
            const notification = document.createElement("div");
            notification.className = "notification";
            notification.textContent = message;
            // Add styles for positioning, background, border-radius, and transitions
            notification.style.position = "fixed";
            notification.style.top = "50%";
            notification.style.left = "50%";
            notification.style.transform = "translate(-50%, -50%)";
            notification.style.backgroundColor = "rgba(0, 0, 0, 0.7)";
            notification.style.color = "#fff";
            notification.style.fontSize = "17px";
            notification.style.padding = "5px 2000px";
            notification.style.padding = "10px 30px";
            notification.style.borderRadius = "5px"; // Add border-radius
            notification.style.transition = "opacity 0.5s ease-in-out"; // Add a transition for opacity
            document.body.appendChild(notification);
            // Delay to allow the transition to work (fade-in effect)
            setTimeout(() => {
                notification.style.opacity = "1";
            }, 10);
            setTimeout(() => {
                notification.style.opacity = "0"; // Fade out the notification
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 500); // Remove the notification after the fade-out animation (adjust as needed)
            }, 2000); // Remove the notification after 2 seconds (adjust as needed)
        }
		function deleteRecord(recordId) {
		    if (confirm("Are you sure you want to delete this record?")) {
		        // Create a FormData object to send the record ID
		        var formData = new FormData();
		        formData.append("delete", true); // This flag indicates a delete operation
		        formData.append("recordId", recordId);

		        // Create a new XMLHttpRequest
		        var xhr = new XMLHttpRequest();

		        // Configure the request
		        xhr.open("POST", "index.php", true);

		        // Define the callback function when the request is complete
		        xhr.onload = function () {
		            if (xhr.status === 200) {
		                // Handle the response if needed
		                // Find and remove the card element from the DOM
		                var card = document.querySelector('.card[data-record-id="' + recordId + '"]');
		                if (card) {
		                    card.parentNode.removeChild(card);
		                }
		            } else {
		                alert("Error deleting record: " + xhr.statusText);
		            }
		        };

		        // Send the request with the FormData containing the record ID
		        xhr.send(formData);
		    }
		}
		function checkPms() {
		    // Get the form inputs
		    var username = document.querySelector('input[name="username"]').value;
		    var password = document.querySelector('input[name="pms"]').value;

		    // Create the POST request payload
		    var payload = new URLSearchParams();
		    payload.append('LogIn', '1');
		    payload.append('username', username);
		    payload.append('pms', password);
		    
		    // Append the checkPms field
		    payload.append('checkPms', '1'); // Set it to 1 to indicate the "CheckPMS" action

		    // Define the request options
		    var requestOptions = {
		        method: 'POST',
		        headers: {
		            'Content-Type': 'application/x-www-form-urlencoded',
		        },
		        body: payload.toString(),
		    };

		    // Send the POST request to index.php
		    fetch('index.php', requestOptions)
		        .then(function(response) {
		            if (response.status === 200) {
		                // Successfully sent the request
		                return response.text();
		            } else {
		                // Error in the request
		                showNotification('Error: Unable to send request to index.php');
		            }
		        })
		        .then(function(responseText) {
		            // Handle the response from index.php if needed
		            showNotification(responseText);
		        })
		        .catch(function(error) {
		            showNotification('Error:'+error);
		        });
		}
		// Function to copy text to the clipboard
		function copyToClipboard(text) {
		    // Create a temporary text area element
		    var textArea = document.createElement('textarea');
		    textArea.value = text;

		    // Append the text area to the document
		    document.body.appendChild(textArea);

		    // Select the text in the text area
		    textArea.select();

		    // Copy the selected text to the clipboard
		    document.execCommand('copy');

		    // Remove the temporary text area element
		    document.body.removeChild(textArea);
		}
		function toggleSpoilers() {
		    var spoilers = document.querySelectorAll('.spoiler');
		    var button = document.querySelector('.button');

		    spoilers.forEach(function(spoiler) {
		        if (spoiler.style.filter === 'blur(0px)' || getComputedStyle(spoiler).filter === 'blur(0px)') {
		            spoiler.style.filter = 'blur(4px)';
		            button.value = 'Hide';
		        } else {
		            spoiler.style.filter = 'blur(0px)';
		            button.value = 'Show';
		        }
		    });
		}
	</script>
	<style> 
		* {
			margin: 0;
			padding: 0;
			text-decoration: none;
			border: none;
			outline: none;
		}
		.inputs {
			display: flex;
			flex-direction: column;
			width: 300px;
			align-items: center;
		}
		.df {
			display: flex;
		}
		.inputs input {
			margin-bottom: 5px;
			height: 30px;
			padding: 0 5px;
			margin-right: 10px;
			width: 300px;
			border: 1px solid rgba(0, 0, 0, .2);
			border-radius: 5px;
			transition: 0.1s;
		}
		.button{
			cursor: pointer;
			width: 100px !important;
			margin: 0 5px;
			margin-right: 0 !important;
			transition: 0.1s;
			margin-bottom: 5px;
		}
		.inputs input:hover {
			border-color: rgba(0, 0, 255, 0.5);
		}
		.inputs input:focus {
			border-color: rgba(0, 0, 255, 1);
		}
		.mr10 {
			margin-right: 10px;
			margin-left: -5px;
		}
		body {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			padding-top: 50px;
			padding-bottom: 100px;
		}
		.body2{
			width: 390px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
		}
		.cards {
			width: 95%;
			justify-content: center;
			display: inline-flex;
			flex-wrap: wrap;
			border-top: 1px solid rgba(0, 0, 0, 0.2);
			padding-top: 25px;
			margin-top: 25px;
		}
		.card {
			padding: 10px 15px;
			font-size: 14px;
			border-radius: 5px;
			border: 1px solid rgba(0, 0, 0, 0.2);
			margin: 5px;
			width: 250px;

		}
		.username {
			font-weight: bold;
			color: rgba(50, 50, 200, 1);
		}
		button {
			cursor: pointer;
			margin-left: 5px;
			border-radius: 3px;
			background-color: #E34f4f !important;
			color: #ffffff !important;
			padding: 1px 4px;
			font-size: 12px;
		}
		.df b {
			margin-right: 5px;
		}
		P {
			cursor: pointer;
		 /* Define the class to apply the green color and transition */
		}
        .copied {
			text-decoration: underline;
            color: darkgreen;
            transition: color 0.1s ease-in-out;
        }
        textarea {
			border: 1px solid rgba(0, 0, 0, .2);
			padding: 3px 6px;
			border-radius: 5px;
			width: 300px;
			resize: none;
			font-size: 14px;
			margin-left: 5px;
		}
		.spoiler {
			filter: blur(4px);
			transition: 0.1s;
		}
	</style>
</head>
<body>
	<div class="body2">
		<div class="df">
			<form class="inputs" method="POST" action="index.php">
			    <input type="text" placeholder="username" name="username" autofocus>
			    <input type="text" placeholder="portal" name="pms">
			    <input type="text" placeholder="google" name="google">
			    <input type="text" placeholder="mon" name="mon">
			    <div class="df mr10">
			        <input type="button" class="button" value="Show" onclick="toggleSpoilers()">
			        <input type="submit" class="button" name="save" value="Save">
			        <input type="button" class="button" name="action" value="CheckPMS" onclick="checkPms()">
			    </div>
			</form>
		    <textarea id="kazakhTextarea" cols="30" rows="10" placeholder="KZ"></textarea>
			<textarea id="englishTextarea" cols="30" rows="10" placeholder="ENG"></textarea>
		</div>
		</div>
		<div class="cards">
			<?php 

				// Prepare and execute a SELECT query
			    $query = "SELECT * FROM data";
			    $stmt = $db->prepare($query);
			    $stmt->execute();

			    // Fetch all records as an associative array
			    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

			    // Output the records
			    foreach ($records as $record) {

			        echo "<div class='card' data-record-id='{$record['id']}'>";
					echo "<div class='df spoiler'><p class='username'>{$record['username']}</p><button onclick='deleteRecord({$record['id']})'>Delete</button></div>";
					if ($record['pms']) echo "<div class='df spoiler'><b>pms: </b><p>{$record['pms']}</p></div>";
					if ($record['google']) echo "<div class='df spoiler'><b>ggl:</b><p>{$record['google']}</p></div>";
					if ($record['mon']) echo "<div class='df spoiler'><b>mon:</b><p>{$record['mon']}</p></div>";
					echo "</div>";
			    }

			 ?>	
			
		</div>

	<script type="text/javascript">
		function convertToKazakhLayout(inputText, reverse=false) {
		    var qwertyLayout = "qwertyuiop[]\\asdfghjkl;'zxcvbnm,./1234567890-=QWERTYUIOP{}|ASDFGHJKL:\"ZXCVBNM<>?!@#$%^&*()_+";
		    var kazakhLayout = "йцукенгшщзхъ\\фывапролджэячсмитьбю№\"әіңғ,.үұқөһЙЦУКЕНГШЩЗХЪ/ФЫВАПРОЛДЖЭЯЧСМИТЬБЮ?!ӘІҢҒ;:ҮҰҚӨҺ";
		    
		    var convertedText = '';
		    
		    if (reverse) {
		    	for (var i = 0; i < inputText.length; i++) {
			        var char = inputText[i];
			        var index = qwertyLayout.indexOf(char);
			        
			        if (index !== -1) {
			            convertedText += kazakhLayout[index];
			        } else {
			            convertedText += char;
			        }
			    }
		    } else {
		    	for (var i = 0; i < inputText.length; i++) {
			        var char = inputText[i];
			        var index = kazakhLayout.indexOf(char);
			        
			        if (index !== -1) {
			            convertedText += qwertyLayout[index];
			        } else {
			            convertedText += char;
			        }
			    }
		    }
		    
		    return convertedText;
		}

		// Get references to the textareas
		var englishTextarea = document.getElementById('englishTextarea');
		var kazakhTextarea = document.getElementById('kazakhTextarea');

		// Add input event listeners to both textareas
		englishTextarea.addEventListener('input', function() {
		    kazakhTextarea.value = convertToKazakhLayout(englishTextarea.value, true);
		});

		kazakhTextarea.addEventListener('input', function() {
		    englishTextarea.value = convertToKazakhLayout(kazakhTextarea.value);
		});
		// Get all the <p> elements on the page
		var paragraphs = document.querySelectorAll('p');

		// Add a click event listener to each <p> element
		paragraphs.forEach(function(paragraph) {
		    paragraph.addEventListener('click', function() {
		        // Copy the text content of the clicked <p> tag to the clipboard
		        copyToClipboard(paragraph.textContent);

		        // Change the text color to green
		        paragraph.classList.add('copied');

		        // Delay the color change slightly to trigger the transition
		        setTimeout(function() {
		            paragraph.style.color = ''; // Reset color to default
		        }, 10);

		        // Remove the .copied class after the transition
		        setTimeout(function() {
		            paragraph.classList.remove('copied');
		        }, 200); // 0.2 seconds (200 milliseconds)
		    });
		});
	</script>
</body>
</html>