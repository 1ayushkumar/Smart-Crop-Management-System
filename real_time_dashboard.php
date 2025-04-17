<?php
require_once 'real_time_data.php';
include('header.php');

// Get settings from POST or use defaults
$location = isset($_POST['location']) ? $_POST['location'] : "Mumbai";
$soilType = isset($_POST['soil_type']) ? $_POST['soil_type'] : "Clay";
$cropType = isset($_POST['crop_type']) ? $_POST['crop_type'] : "Wheat";

// Initialize RealTimeDataManager with values
$rtManager = new RealTimeDataManager($location, $soilType, $cropType);

// Get the latest reading
$latestReading = $rtManager->getLatestReading();

// Update real-time data if requested
if (isset($_POST['update_data'])) {
    $updated = $rtManager->updateRealTimeData();
}
?>

<!DOCTYPE html>
<html>
<body class="bg-white" id="top">
    <?php include('nav.php'); ?>

    <div class="wrapper">
        <div class="section features-6 text-dark bg-white">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 mx-auto text-center">
                        <h3 class="display-3">Real-Time Crop Monitoring</h3>
                        <p class="lead">Current environmental conditions and sensor readings</p>
                    </div>
                </div>

                <!-- Settings Form -->
                <div class="row mb-4">
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Monitoring Settings</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="real_time_dashboard.php">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="soil_type">Soil Type</label>
                                        <select class="form-control" id="soil_type" name="soil_type" required>
                                            <?php
                                            $soilTypes = ["Clay", "Sandy", "Loamy", "Black", "Red", "Alluvial"];
                                            foreach ($soilTypes as $type) {
                                                $selected = ($type === $soilType) ? 'selected' : '';
                                                echo "<option value=\"$type\" $selected>$type</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="crop_type">Crop Type</label>
                                        <select class="form-control" id="crop_type" name="crop_type" required>
                                            <?php
                                            $cropTypes = ["Wheat", "Rice", "Corn", "Cotton", "Sugarcane", "Potato"];
                                            foreach ($cropTypes as $type) {
                                                $selected = ($type === $cropType) ? 'selected' : '';
                                                echo "<option value=\"$type\" $selected>$type</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="update_data" class="btn btn-primary">Update Settings & Refresh Data</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Weather Data Card -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Weather Conditions</h4>
                            </div>
                            <div class="card-body">
                                <?php if ($latestReading): ?>
                                <div class="info">
                                    <div class="d-flex justify-content-between">
                                        <span>Temperature:</span>
                                        <strong><?php echo number_format($latestReading['temperature'], 1); ?>°C</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <span>Humidity:</span>
                                        <strong><?php echo number_format($latestReading['humidity'], 1); ?>%</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <span>Last Updated:</span>
                                        <strong><?php echo date('Y-m-d H:i:s', strtotime($latestReading['reading_timestamp'])); ?></strong>
                                    </div>
                                </div>
                                <?php else: ?>
                                <p class="text-center">No weather data available</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Soil Data Card -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Soil Conditions</h4>
                            </div>
                            <div class="card-body">
                                <?php if ($latestReading): ?>
                                <div class="info">
                                    <div class="d-flex justify-content-between">
                                        <span>Soil Type:</span>
                                        <strong><?php echo $latestReading['soil_type']; ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <span>Moisture:</span>
                                        <strong><?php echo number_format($latestReading['moisture'], 1); ?>%</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <span>Nitrogen:</span>
                                        <strong><?php echo number_format($latestReading['nitrogen'], 1); ?> mg/kg</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <span>Phosphorus:</span>
                                        <strong><?php echo number_format($latestReading['phosphorus'], 1); ?> mg/kg</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <span>Potassium:</span>
                                        <strong><?php echo number_format($latestReading['potassium'], 1); ?> mg/kg</strong>
                                    </div>
                                </div>
                                <?php else: ?>
                                <p class="text-center">No soil data available</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <p class="text-muted">Data is automatically updated every few minutes</p>
                        <a href="real_time_dashboard.php" class="btn btn-primary">Refresh Data</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
