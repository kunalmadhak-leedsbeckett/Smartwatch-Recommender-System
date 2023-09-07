<?php

/**
 * Template Name: User Data
 * Description: User Data Template
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MD-Prime
 */
get_header();
?>
<main id="primary" >
<div class="recommended-smartwatch-main-container">
		<h1>Most Recommended Smartwatches</h1>
		<?php
			// Query to fetch 1 recommended smartwatch from each smartwatch brand
			$query="select aa.* from (
							select  ROW_NUMBER() OVER (
								ORDER BY b.brand_name,a.avg
						   	) row_num, a.avg, b.* from(
								select brand_name,model_name,avg from (
									select a.brand_name,a.model_name,a.rating/b.usercount as avg from(
										select brand_name,model_name,sum(rating)as rating from wp_user group by brand_name,model_name
									) A join (
										select brand_name,model_name,count(2) as usercount from wp_user group by brand_name,model_name
									)b on a.brand_name=b.brand_name where a.model_name=b.model_name
								) asd
							)a join wp_smartwatch B on a.brand_name=b.brand_name where a.model_name=b.model_name order by row_num 
						) aa join (
							select max(row_num)row_id from (
								select  ROW_NUMBER() OVER (
									ORDER BY b.brand_name,a.avg
						   		) row_num,a.avg, b.*from(
									select brand_name,model_name,avg from (
										select a.brand_name,a.model_name,a.rating/b.usercount as avg from(
											select brand_name,model_name,sum(rating)as rating from wp_user group by brand_name,model_name
										) A join (
											select brand_name,model_name,count(2) as usercount from wp_user group by brand_name,model_name
										)b on a.brand_name=b.brand_name where a.model_name=b.model_name
									) asd
								)a join wp_smartwatch B on a.brand_name=b.brand_name where a.model_name=b.model_name order by row_num 
							)xx group by brand_name
						) b on aa.row_num=b.row_id;";

			$results = $wpdb->get_results($query);
		?>
			<div class="recommended-smartwatch-container">
				<?php
					foreach ($results as $result) {
						$brand_name = $result->brand_name;
						$model_name = $result->model_name;
						$color = $result->color;
						$display = $result->display;
						$battery_life = $result->average_battery_life;
						$strap_material = $result->strap_material;
				?>
						<!-- Print the fetched data-->
						<div class="recommended-smartwatches">	
							<p><b>Brand Name: </b><?php echo $brand_name; ?></p>
							<p><b>Modal Name: </b><?php echo $model_name; ?></p>
							<p><b>Color: </b><?php echo $color; ?></p>
							<p><b>Display: </b><?php echo $display; ?></p>
							<p><b>Average Battery Life: </b><?php echo $battery_life; ?> day's</p>
							<p><b>Strap Material: </b><?php echo $strap_material; ?></p>
						</div>
					<?php 
					} 
					?>
			</div>
	</div>


	<div class="popular-smartwatch-main-container">
		<h1>Most Popular Smartwatches</h1>
		<?php 
			// Query to fetch 1 popular smartwatch from each smartwatch brand
			$query="select * from {$wpdb->prefix}smartwatch where id in(
						select b.smartwatch_id from (
							select brand_name,max(row_id)row_id from (
								select ROW_NUMBER() OVER(order by brand_name,model_count)row_id, asd.* from(
									select brand_name, smartwatch_id,count(1)as model_count from {$wpdb->prefix}user group by brand_name, smartwatch_id
								)asd
							)asf group by brand_name
						)A join (
								select ROW_NUMBER() OVER(order by brand_name,model_count)row_id, asd.* from(
									select brand_name, smartwatch_id,count(1)as model_count from {$wpdb->prefix}user group by brand_name, smartwatch_id
								)asd
							)B on a.row_id=b.row_id
					)";

			$results = $wpdb->get_results($query);
		?>
			<div class="popular-smartwatch-container">
				<?php
					foreach ($results as $result) {
						$brand_name = $result->brand_name;
						$model_name = $result->model_name;
						$color = $result->color;
						$display = $result->display;
						$battery_life = $result->average_battery_life;
						$strap_material = $result->strap_material;
				?>
						<!-- Print the fetched data-->
						<div class="popular-smartwatches">	
							<p><b>Brand Name: </b><?php echo $brand_name; ?></p>
							<p><b>Modal Name: </b><?php echo $model_name; ?></p>
							<p><b>Color: </b><?php echo $color; ?></p>
							<p><b>Display: </b><?php echo $display; ?></p>
							<p><b>Average Battery Life: </b><?php echo $battery_life; ?> day's</p>
							<p><b>Strap Material: </b><?php echo $strap_material; ?></p>
						</div>
					<?php 
					} 
					?>
			</div>
	</div>

	<!-- Form to collect information from user to give recommendation of smartwatches -->
	<div class="user-form-section">
		<h1>Smartwatch Recommendation Form</h1>
		<form action="/smartwatch" method="POST" id="userForm">
			<table class="user_occupation_class">
				<tr>
					<td>
						<label for="user_occupation">Your Occupation:*</label>
					</td>
					<td>
						<select name="user_occupation" id="user_occupation" required>
							<?php
								echo '<option value="">Select Occupation</option>';
								// Retrieve unique user_occupation values from the database
								$occupation_list = $wpdb->get_col("SELECT DISTINCT user_occupation FROM {$wpdb->prefix}user ORDER BY user_occupation ASC");

								// Loop through the occupation_list and generate <option> tags
								foreach ($occupation_list as $occupation) {
									echo '<option value="' . $occupation . '">' . $occupation . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<br><br>
				<tr>
					<td>
						<label for="user_age_range">Your Age-Range:*</label>
					</td>
					<td>
						<select name="user_age_range" id="user_age_range" required>
							<?php
								echo '<option value="">Select Age Range</option>';
								// Retrieve unique age_range values from the database
								$age_ranges = $wpdb->get_col("SELECT DISTINCT user_age_range FROM {$wpdb->prefix}user ORDER BY user_age_range ASC");

								// Loop through the age_ranges and generate <option> tags
								foreach ($age_ranges as $age_range) {
									echo '<option value="' . $age_range . '">' . $age_range . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<br><br>
				<tr>
					<td>	
						<label for="user_qualification">Your Qualification:*</label>
					</td>
					<td>
						<select name="user_qualification" id="user_qualification" required>
							<?php
								echo '<option value="">Select Qualification</option>';
								// Retrieve unique user_qualification values from the database
								$qualification_list = $wpdb->get_col("SELECT DISTINCT user_qualification FROM {$wpdb->prefix}user ORDER BY user_qualification ASC");

								// Loop through the qualification_list and generate <option> tags
								foreach ($qualification_list as $qualification) {
									echo '<option value="' . $qualification . '">' . $qualification . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<br><br>
				<tr>
					<td>
						<label for="user_gender">Your Gender:*</label>
					</td>
					<td>
						<select name="user_gender" id="user_gender" required>
							<?php
								echo '<option value="">Select Gender</option>';
								// Retrieve unique gender values from the database
								$genders = $wpdb->get_col("SELECT DISTINCT user_gender FROM {$wpdb->prefix}user");

								// Loop through the genders and generate <option> tags
								foreach ($genders as $gender) {
									echo '<option value="' . $gender . '">' . $gender . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<br><br>
				<tr>
					<td>
						<label for="brand_name">Your Smartwatch Brands:</label>
					</td>
					<td>
						<select name="brand_name" id="brand_name">
							<?php
								echo '<option value="">Select Smartwatch Brand</option>';
								// Retrieve unique brands_list values from the database
								$brands_list = $wpdb->get_col("SELECT DISTINCT brand_name FROM {$wpdb->prefix}smartwatch ORDER BY brand_name ASC");

								// Loop through the brands_list and generate <option> tags
								foreach ($brands_list as $brand) {
									echo '<option value="' . $brand . '">' . $brand . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<br><br>
				<tr>
					<td>
						<label for="model_name">Your Smartwatch Models:</label>
					</td>
					<td>
						<select name="model_name" id="model_name">
							<?php
							?>
						</select>
					</td>
				</tr>
				<br><br>
				<tr>
					<td>
					</td>
					<td>
						<input type="submit" value="Submit" class="button">
					</td>
				</tr>
			</table>
		</form>
	</div>
</main>

<?php
get_footer();
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script>
	// JQuery for slider integration
	jQuery(document).ready(function(){
  		jQuery('.recommended-smartwatch-container').slick({
			dots: true,
			arrows: true,
			infinite: false, 
			speed: 300, 
			slidesToShow: 4, 
			slidesToScroll: 4,
			autoScroll: true
  		});
		  jQuery('.popular-smartwatch-container').slick({
			dots: true,
			arrows: true,
			infinite: false, 
			speed: 300, 
			slidesToShow: 4, 
			slidesToScroll: 4,
			autoScroll: true
  		});
 	});
</script>

<script>
	//Script to fetch smartwatch models after selecting the smartwatch brand
	const brandSelect = document.getElementById("brand_name");
    const modelSelect = document.getElementById("model_name");

    function populateModels() {
        const selectedBrand = brandSelect.value;
        modelSelect.innerHTML = "<option>Loading...</option>"; // Show a loading message
        fetch('<?php echo admin_url("admin-ajax.php"); ?>?action=get_models&brand=' + selectedBrand)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    modelSelect.innerHTML = ""; // Clear previous options
                    if(data.data === 'blank_data') {
      					const option = document.createElement("option");
                    	option.textContent = "No Models Found...";
                   		modelSelect.appendChild(option);
     				}
					else {
      					data.data.forEach(model => {
                        	const option = document.createElement("option");
                        	option.value = model;
                        	option.textContent = model;
                        	modelSelect.appendChild(option);
                     	});
     				}
                }
				else {
                    throw new Error('Data retrieval error');
                }
            })
            .catch(error => {
                console.error("Error fetching models:", error);
                modelSelect.innerHTML = "<option>Error fetching models</option>"; // Show an error message
            });
    }
    brandSelect.addEventListener("change", populateModels);
    populateModels(); // Call this on page load to pre-populate the models if a brand is already selected

	// Function to clear form fields
    function clearFormFields() {
        document.getElementById("user_occupation").selectedIndex = 0;
        document.getElementById("user_age_range").selectedIndex = 0;
        document.getElementById("user_qualification").selectedIndex = 0;
        document.getElementById("user_gender").selectedIndex = 0;
        document.getElementById("brand_name").selectedIndex = 0;
        document.getElementById("model_name").innerHTML = '<option value="">No Models Found...</option>';
    }
    window.onload = clearFormFields(); // Clear form fields when the page loads
</script>

<!-- CSS of the whole page-->
<style>
	.popular-smartwatch-main-container,
	.recommended-smartwatch-main-container,
	.user-form-section {
		margin: 100px auto;
		width: 85%;
		border: 3px solid #000000;
		padding: 10px;
		background-color: #eeeeee;
		opacity: 0.9;
		box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 1);
		/* overflow: scroll; */
	}

	.user-form-section {
		width: 50%;
	}

	.popular-smartwatch-main-container h1, .recommended-smartwatch-main-container h1, .user-form-section h1{
		text-align: center;
		padding-top: 10px;
		padding-bottom: 10px;
	}

	.popular-smartwatch-container, .recommended-smartwatch-container {
    	display: flex;
    	flex-wrap: nowrap;
    	justify-content: flex-start;
		width: 100%;
    	max-width: 1200px;
    	margin: 25px auto;
		/* overflow-x: auto; */
	}

	.popular-smartwatches, .recommended-smartwatches {
		flex: 0 0 auto;
		width: 20%;
    	margin-right: 10px;
		margin-left: 10px;
    	margin-bottom: 10px; 
		margin-top: 10px; 
    	border: 1px solid #000000; 
    	padding: 10px; 
		box-shadow: 0px 5px 10px 0px rgba(0, 0, 0, 0.5);
		height: 175px;
	}

	.popular-smartwatches h3, .popular-smartwatches h4, .popular-smartwatches p {
    	margin: 0;
    	padding: 5px 0;
	}

	.recommended-smartwatches h3, .recommended-smartwatches h4, .recommended-smartwatches p {
    	margin: 0;
    	padding: 5px 0;
	}

	.user_occupation_class select{
  		box-shadow: 0px 5px 8px 0px rgba(0,0,0,0.2);
  		width:100%;
  		padding:5px;
		border-radius: 10px;
	}

	.user_occupation_class td{
		padding: 10px;
	}

	.user_occupation_class{
		width:100%;
	}

	form br{
		display: none;
	}

	form tr td:first-child {
   		width: 26%
	}

	input{
		margin: 0px!important;
		box-shadow: 0px 5px 8px 0px rgba(0,0,0,0.2);
  		width:100%;
  		padding:10px;
		border-radius: 10px;
		background-color: #000000!important;
		color: #ffffff!important;
		width:40%;
	}
	.button:hover{
    	border: 1px solid transparent;
	}

	.slick-prev,
	.slick-next {
		font-size: 0;
		color: #000000;
		background-color: transparent;
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		cursor: pointer;
		z-index: 1;
		transition: color 0.3s;
	}

	.slick-prev:hover,
	.slick-next:hover {
		color: #e91e63;
	}

	.slick-prev {
		left: -20px;
	}

	.slick-next {
		right: -10px;
	}

	.slick-prev:before, 
	.slick-next:before {
		font-size: 30px;
		opacity: .75;
		color: #000000;
	}

	.slick-slider .slick-list.draggable{
		cursor: grab;
	}
</style>