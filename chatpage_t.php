<?php 
	session_start();
	if(isset($_SESSION['name']))
	{
		include "layouts/header2.php"; 
		include "config.php"; 
		
		$sql="SELECT * FROM `chat`";

		$query = mysqli_query($conn,$sql);
		
	//encrypt process
	function encrypt_decrypt($action,$string){
		$output = false;
		
		$encrypt_method = "AES-256-CBC";
		$secret_key = "12345";
		$secret_iv = "This is my secret_iv";
		
		$key = hash('sha256',$secret_key);
		
		$iv = substr(hash('sha256',$secret_iv),0,16);
		
		if($action == 'encrypt'){
			$output = openssl_encrypt($string,$encrypt_method,$key,0,$iv);
			$output = base64_encode($output);
		}else if($action == 'decrypt'){
			$output = openssl_decrypt(base64_decode($string),$encrypt_method,$key,0,$iv);
		}
		return $output;
	}
	
	
	
	
?>
<style>
  h2{
color:white;
  }
  label{
color:white;
  }
  span{
	  color:#673ab7;
	  font-weight:bold;
  }
  .container {
    margin-top: 3%;
    width: 60%;
    background-color: #26262b9e;
    padding-right:10%;
    padding-left:10%;
  }
  .btn-primary {
    background-color: #673AB7;
	}
	.display-chat{
		height:300px;
		background-color:#d69de0;
		margin-bottom:4%;
		overflow:auto;
		padding:15px;
	}
	.message{
		background-color: #c616e469;
		color: white;
		border-radius: 5px;
		padding: 5px;
		margin-bottom: 3%;
	}
  </style>

<div class="container">
  <center><h2>Welcome <span style="color:#dd7ff3;"><?php echo $_SESSION['name']; ?> !</span></h2>
	<label>Join the chat</label>
  </center></br>
  <div class="display-chat">
<?php
	
	if(mysqli_num_rows($query)>0)
	{
		while($row= mysqli_fetch_assoc($query))	
		{
?>
		<div class="message">
			<p>
				<span><?php echo $row['name']; ?> :</span>
				
				<?php
					if($row['name'] == $_SESSION['name'])
						echo $row['message'];
					else
					{
						$id = $row["id"];
						$encrypted_txt = encrypt_decrypt('encrypt',$row['message']);
						echo $encrypted_txt;
						$decrypted_txt = encrypt_decrypt('decrypt',$encrypted_txt);
						echo "<form method='post' id='form' action='chatpage_t.php'>";
						echo "<p id='p'></p>";
						
						echo "<input type='hidden' name='text' id='$id' style='background-color:red;' value=' $decrypted_txt'><br>";
						echo "<input type='text' id='text' placeholder='Enter decrypt key'>&nbsp;&nbsp;";
						echo "<input type='button' id='btn' style='background-color:green;'  value='Decrypt'>";
						echo "</form>";
						//echo "<input type='text' id='hidden' style='background-color:red;' value=' $encrypted_txt'><br>";
						//echo "<input type='text' id='text' placeholder='Enter decrypt key'>&nbsp;&nbsp;";
						//echo "<input type='button' style='background-color:green;'  value='Decrypt' onclick='dec()'>";
						
						//$decrypted_txt = encrypt_decrypt('decrypt',$encrypted_txt);
						//echo "<br>Decrypted Text = ".$decrypted_txt."<br>";
					}
	
				?>
			</p>
		</div>
<?php
		}
	}
	else
	{
?>
<div class="message">
			<p>
				No previous chat available.
			</p>
</div>
<?php
	} 
?>

  </div>
  <form class="form-horizontal" method="post" action="sendMessage.php">
    <div class="form-group">
      <div class="col-sm-10">          
        <textarea name="msg" class="form-control" placeholder="Type your message here..."></textarea>
      </div>
	         
      <div class="col-sm-2">
        <button type="submit" class="btn btn-primary">Send</button><br>
      </div>

    </div>
  </form>
	<!--div class="form-group">
      <div class="col-sm-10">          
        <input type="text" id="text" placeholder="Type your key">
      </div>
	         
      <div class="col-sm-2">
		<button class="btn btn-info" onclick="dec()">Decrypt</button>
      </div-->

    </div>
</div>

<!--script>
function dec(){
var id = document.getElementById("text").value;
						if(id == "12345"){
							//document.getElementById("p").innerHTML = document.getElementById("hidden").value;
							var e = document.getElementById("form");
							for(var i = 0;i < e.length;i++){
								document.getElementById("p").innerHTML = document.getAttribute("id");
							}
						}else{
							alert("Your key is wrong!");
						}
}
</script -->	
<script src="jquery.js"></script>
 <script>
         $(document).ready(function(){
             $(document).on('click','#btn', function(e){
				 
					if($(e.target).closest("form").find("input[id='text']").val() == "12345"){
					//alert($("#text").val());
					var v = $(this).closest("form").find("input[name='text']").val();
					//alert(v);
					$(this).closest("form").find("p[id='p']").html("Decrypt Text :" + v);
					
				}else{
					alert("Your key is wrong!");
				}
				
             });
         });
      </script>
</body>
</html>
<?php
	}
	else
	{
		header('location:index.php');
	}
?>