<!DOCTYPE html>
<html>
<?php include ('header.php');  ?>

  <body class="bg-white" id="top">
  
<?php include ('nav.php');  ?>

 
  <section class="section section-shaped section-lg">
    <div class="shape shape-style-1 shape-primary">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
<!-- ======================================================================================================================================== -->

<div class="container ">
    
    	 <div class="row">
          <div class="col-md-8 mx-auto text-center">
            <span class="badge badge-danger badge-pill mb-3">Prediction</span>
          </div>
        </div>
		
          <div class="row row-content">
            <div class="col-md-12 mb-3">

				<div class="card text-white bg-gradient-success mb-3">
				  <div class="card-header">
				  <span class="text-success display-4">Crop Prediction</span>					
				  </div>

				  <div class="card-body">
				     <form role="form" action="#" method="post" class="prediction-form">     
					 
				        <div class="prediction-table">
                            <table class="table table-hover table-bordered bg-gradient-white text-center display" id="myTable">
                                <thead>
                                    <tr class="font-weight-bold text-default">
                                        <th><center>State</center></th>
                                        <th><center>District</center></th>
                                        <th><center>Season</center></th>
                                        <th><center>Prediction</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td>
                                            <div class="form-group">
                                                <select onchange="print_city('state', this.selectedIndex);" id="sts" name="stt" class="form-control" required>
                                                </select>
                                                <script language="javascript">print_state("sts");</script>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select id="state" name="district" class="form-control" required>
                                                    <option value="">Select District</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select name="Season" class="form-control">
                                                    <option value="">Select Season ...</option>
                                                    <option value="Kharif">Kharif</option>
                                                    <option value="Whole Year">Whole Year</option>
                                                    <option value="Autumn">Autumn</option>
                                                    <option value="Rabi">Rabi</option>
                                                    <option value="Summer">Summer</option>
                                                    <option value="Winter">Winter</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <center>
                                                <div class="form-group">
                                                    <button type="submit" name="Crop_Predict" class="btn btn-predict">
                                                        <i class="fas fa-seedling mr-2"></i>Predict
                                                    </button>
                                                </div>
                                            </center>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <?php
                    if(isset($_POST['Crop_Predict'])) {
                        $state = trim($_POST['stt']);
                        $district = trim($_POST['district']);
                        $season = trim($_POST['Season']);
                        
                        // Remove quotes from JSON encoded values
                        $state = trim($state, '"');
                        $district = trim($district, '"');
                        $season = trim($season, '"');
                        
                        $command = escapeshellcmd("python ML/crop_prediction/ZDecision_Tree_Model_Call.py \"$state\" \"$district\" \"$season\"");
                        $output = shell_exec($command);
                        
                        // Debug output
                        error_log("Debug - Command: " . $command);
                        error_log("Debug - Raw output: " . $output);
                        
                        // Clean and process the output
                        $lines = explode("\n", $output);
                        $final_output = "";
                        foreach($lines as $line) {
                            if(strpos($line, "Debug:") === false) {
                                $final_output .= trim($line);
                            }
                        }
                        
                        $crops = explode(",", $final_output);
                    ?>
                        
                    <div class="prediction-result">
                        <div class="result-header">
                            <h3>Prediction Results</h3>
                        </div>
                        <div class="result-content">
                            <div class="result-item">
                                <span class="result-label">State:</span>
                                <span class="result-value"><?php echo htmlspecialchars($state); ?></span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">District:</span>
                                <span class="result-value"><?php echo htmlspecialchars($district); ?></span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Season:</span>
                                <span class="result-value"><?php echo htmlspecialchars($season); ?></span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Recommended Crop:</span>
                                <span class="result-value prediction-badge">
                                    <i class="fas fa-leaf mr-2"></i>
                                    <?php 
                                    if(!empty($crops)) {
                                        foreach($crops as $crop) {
                                            if(trim($crop) != "") {
                                                echo htmlspecialchars(trim($crop)) . ", ";
                                            }
                                        }
                                    } else {
                                        echo "No crops found for the selected criteria";
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
				  </div>
				</div>
            </div>
          </div>  
       </div>
		 
</section>

    <?php require("footer.php");?>

</body>
</html>
