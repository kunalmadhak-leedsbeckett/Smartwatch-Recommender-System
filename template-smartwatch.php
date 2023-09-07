<?php
/**
 * Template Name: Smartwatch fetch Data
 * Description: Smartwatch fetch Data Template
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MD-Prime
 */

	get_header();
	global $wpdb;
	$brand_name_selected = $_POST['brand_name'];
	$model_name_selected = $_POST['model_name'];
	$user_occupation = $_POST['user_occupation'];
	$user_age_range = $_POST['user_age_range'];
	$user_qualification = $_POST['user_qualification'];
	$user_gender = $_POST['user_gender'];

	$sql = $wpdb->prepare(
		"SELECT u.*, s.* FROM {$wpdb->prefix}user AS u INNER JOIN {$wpdb->prefix}smartwatch AS s ON u.smartwatch_id = s.id WHERE" // Initial condition
	);

	// Define the conditions to be checked to recommend the smartwatches
	$conditions = array(
		array('u.user_occupation = %s AND u.user_age_range = %s AND u.user_qualification = %s AND u.user_gender = %s', $user_occupation, $user_age_range, $user_qualification, $user_gender),
		array('u.user_occupation = %s AND u.user_age_range = %s AND u.user_qualification = %s', $user_occupation, $user_age_range, $user_qualification),
		array('u.user_occupation = %s AND u.user_age_range = %s AND u.user_gender = %s', $user_occupation, $user_age_range, $user_gender),
		array('u.user_occupation = %s AND u.user_qualification = %s AND u.user_gender = %s', $user_occupation, $user_qualification, $user_gender),
		array('u.user_occupation = %s AND u.user_qualification = %s', $user_occupation, $user_qualification),
		array('u.user_occupation = %s AND u.user_gender = %s', $user_occupation, $user_gender),
		array('u.user_age_range = %s AND u.user_qualification = %s', $user_age_range, $user_qualification),
		array('u.user_age_range = %s AND u.user_gender = %s', $user_age_range, $user_gender),
		array('u.user_qualification = %s AND u.user_gender = %s', $user_qualification, $user_gender),
		array('u.user_occupation = %s AND u.user_age_range = %s', $user_occupation, $user_age_range)
	);


	echo "<div class='recommended-smartwatch-main-container'>";
		echo "<h1> Recommended Smartwatches </h1>";
		echo "<div class='smartwatch-main-container'>";
			// Flag variable
			$matching_condition = false;

			foreach ($conditions as $condition) {
				$sql_condition = $wpdb->prepare(" ({$condition[0]})", $condition[1], $condition[2], $condition[3], $condition[4]);

				// Run the SQL query
				$results = $wpdb->get_results($sql . $sql_condition);

				if (count($results) === 1) { // Get Only one result
					foreach ($results as $row) {

						// Retrieve smartwatch data from the row
						$brand_name = $row->brand_name;
						$model_name = $row->model_name;
						$color = $row->color;
						$display = $row->display;
						$strap_material = $row->strap_material;
						$battery_life = $row->average_battery_life;

						// Display the smartwatch data
						echo "<div class='data_container'>";
							echo "<p><b>Brand Name: </b>" . $brand_name . "</p>";
							echo "<p><b>Model Name: </b>" . $model_name . "</p>";
							echo "<p><b>Color: </b>" . $color . "</p>";
							echo "<p><b>Display: </b></b>" . $display . "</p>";
							echo "<p><b>Average Battery Life: </b>" . $battery_life ." day's</p>";
							echo "<p><b>Strap Material: </b>" . $strap_material . "</p>";
						echo "</div>";
						$matching_condition = true;
						continue;
					}
				} elseif (count($results) > 1) { // Get more than one result
						foreach ($results as $row) {

							// Retrieve smartwatch data from the row
							$brand_name = $row->brand_name;
							$model_name = $row->model_name;
							$color = $row->color;
							$display = $row->display;
							$strap_material = $row->strap_material;
							$battery_life = $row->average_battery_life;

							if ($brand_name === $brand_name_selected && $model_name === $model_name_selected) {
								continue; 
							}

							// Display the smartwatch data
							echo "<div class='data_container'>";
								echo "<p><b>Brand Name: </b>" . $brand_name . "</p>";
								echo "<p><b>Model Name: </b>" . $model_name . "</p>";
								echo "<p><b>Color: </b>" . $color . "</p>";
								echo "<p><b>Display: </b></b>" . $display . "</p>";
								echo "<p><b>Average Battery Life: </b>" . $battery_life ." day's</p>";
								echo "<p><b>Strap Material: </b>" . $strap_material . "</p>";
							echo "</div>";
							$matching_condition = true;
							continue;
						}
					} 
				if ($matching_condition) {
					break;
				}
			}
		if (!$matching_condition) {
			echo "No Record Found!";
		}
		echo "</div>";

		// Go to back button
		echo "<div class='back_button'>";
			echo "<input type='button' value='Go back!' onclick='history.back()' class='button'>";
		echo "</div>";
	echo "</div>";
	get_footer();
?>

<!-- CSS of the whole page-->
<style>
	.recommended-smartwatch-main-container{
		margin: 100px auto;
		width: 90%;
		border: 3px solid #000000;
		padding: 10px;
		opacity: 0.9;
		background-color: #eeeeee;
		box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.9);
	}

	.recommended-smartwatch-main-container h1{
		text-align: center;
		padding-top: 10px;
		padding-bottom: 10px;
		width: 100%;
	}

	.smartwatch-main-container {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		width: 100%;
		margin: 25px auto;
	}

	.data_container {
		flex-basis: 21%;
		margin-right: 10px;
		margin-left: 10px;
		margin-bottom: 10px; 
		margin-top: 10px; 
		border: 1px solid #000000; 
		padding: 10px; 
		box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.5);
	}

	.data_container p{
		padding: 5px 0px;
	}

	.back_button{
		text-align:center;
		margin: 50px auto 50px;
	}

	input{
		margin: 0px!important;
		box-shadow: 0px 5px 8px 0px rgba(0,0,0,0.2);
		width:100%;
		padding:10px;
		border-radius: 10px;
		background-color: #000000!important;
		color: #ffffff!important;
		width:20%;
	}

	.button:hover{
		border: 0.5px solid transparent;
		cursor: pointer;
	}
</style>