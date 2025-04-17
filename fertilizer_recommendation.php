<!DOCTYPE html>
<html>
<?php include ('header.php'); ?>

<body class="bg-white" id="top">

<?php include ('nav.php'); ?>

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

    <div class="container-fluid ">
        
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <span class="badge badge-danger badge-pill mb-3">Recommendation</span>
            </div>
        </div>
        
        <div class="row row-content">
            <div class="col-md-12 mb-3">

                <div class="card text-white bg-gradient-success mb-3">
                    <form role="form" action="#" method="post">  
                        <div class="card-header">
                            <span class=" text-info display-4" > Fertilizer Recommendation  </span>    
                            <span class="pull-right">
                                <button type="submit" value="Recommend" name="Fert_Recommend" class="btn btn-warning btn-submit">SUBMIT</button>
                            </span>        
                        </div>

                        <div class="card-body text-dark">
                            
                            <?php
                            require_once 'config/weather_config.php';

                            // Get weather data if location is set
                            $weather_data = null;
                            if (isset($_POST['location'])) {
                                $weather_data = getCurrentWeather($_POST['location']);
                            }
                            ?>
                            <div class="container mt-4">
                                <div class="card p-4">
                                    <h2 class="text-center mb-4">Fertilizer Recommendation</h2>
                                    <form method="post" action="">
                                        <div class="row">
                                            <!-- Location for weather data -->
                                            <div class="col-md-6 mb-3">
                                                <label for="location" class="form-label">Location</label>
                                                <input type="text" class="form-control" id="location" name="location" required>
                                            </div>
                                            
                                            <!-- Weather data will be automatically fetched -->
                                            <div class="col-md-6 mb-3">
                                                <label for="temperature" class="form-label">Temperature (°C)</label>
                                                <input type="number" class="form-control" id="temperature" name="temperature" 
                                                       value="<?php echo isset($weather_data['main']['temp']) ? $weather_data['main']['temp'] : ''; ?>" required>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="humidity" class="form-label">Humidity (%)</label>
                                                <input type="number" class="form-control" id="humidity" name="humidity" 
                                                       value="<?php echo isset($weather_data['main']['humidity']) ? $weather_data['main']['humidity'] : ''; ?>" required>
                                            </div>

                                            <!-- Manual soil test inputs -->
                                            <div class="col-md-6 mb-3">
                                                <label for="moisture" class="form-label">Soil Moisture (%)</label>
                                                <input type="number" class="form-control" id="moisture" name="moisture" required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="nitrogen" class="form-label">Nitrogen Level (mg/kg)</label>
                                                <input type="number" class="form-control" id="nitrogen" name="nitrogen" required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="phosphorus" class="form-label">Phosphorus Level (mg/kg)</label>
                                                <input type="number" class="form-control" id="phosphorus" name="phosphorus" required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="potassium" class="form-label">Potassium Level (mg/kg)</label>
                                                <input type="number" class="form-control" id="potassium" name="potassium" required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="soil_type" class="form-label">Soil Type</label>
                                                <select class="form-control" id="soil_type" name="soil_type" required>
                                                    <option value="">Select Soil Type</option>
                                                    <option value="Sandy">Sandy</option>
                                                    <option value="Loamy">Loamy</option>
                                                    <option value="Clay">Clay</option>
                                                    <option value="Black">Black</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="crop_type" class="form-label">Crop Type</label>
                                                <select class="form-control" id="crop_type" name="crop_type" required>
                                                    <option value="">Select Crop Type</option>
                                                    <option value="Maize">Maize</option>
                                                    <option value="Sugarcane">Sugarcane</option>
                                                    <option value="Cotton">Cotton</option>
                                                    <option value="Tobacco">Tobacco</option>
                                                    <option value="Paddy">Paddy</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary">Get Recommendation</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card text-white bg-gradient-success mb-3">
                    <div class="card-header">
                        <span class=" text-success display-4" > Result  </span>                    
                    </div>

                    <h4>
                        <?php 
                        if(isset($_POST['Fert_Recommend'])){
                            $n=trim($_POST['nitrogen']);
                            $p=trim($_POST['phosphorus']);
                            $k=trim($_POST['potassium']);
                            $t=trim($_POST['temperature']);
                            $h=trim($_POST['humidity']);
                            $sm=trim($_POST['moisture']);
                            $soil=trim($_POST['soil_type']);
                            $crop=trim($_POST['crop_type']);

                            echo "Recommended Fertilizer is : ";

                            $Jsonn=json_encode($n);
                            $Jsonp=json_encode($p);
                            $Jsonk=json_encode($k);
                            $Jsont=json_encode($t);
                            $Jsonh=json_encode($h);
                            $Jsonsm=json_encode($sm);
                            $Jsonsoil=json_encode($soil);
                            $Jsoncrop=json_encode($crop);

                            $command = escapeshellcmd("python ML/fertilizer_recommendation/fertilizer_recommendation.py $Jsonn $Jsonp $Jsonk $Jsont $Jsonh $Jsonsm $Jsonsoil $Jsoncrop ");
                            $output = passthru($command);
                            echo $output;                    
                        }
                        ?>
                    </h4>
                </div>
            </div>
        </div>  
    </div>
</section>

<?php require("footer.php"); ?>
</body>
</html>
