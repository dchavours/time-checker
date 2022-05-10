<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/dchavours/
 * @since      1.0.0
 *
 * @package    Time_Checker
 * @subpackage Time_Checker/admin/partials
 *
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wp2mag.blogspot.com
 * @since      1.0.0
 *
 * @package    Course_Manager
 * @subpackage Course_Manager/admin/partials
 */
global $wpdb;
// I have to include this file below into different file because there should be no include statement here.
$dir = WP_PLUGIN_DIR . '/time-checker';







// There needs to be logic here that executes and shows the possible times for the dropdown.
// For enterting beginning/ending hours. 
// 
$sql_queue = new SQL_Init_Request();


// I should be able to call get_sql_vars_two() and have the file automatically know 
// $all_booking_starts_sql_command exists. 

$array_unique_time_starts_no_repeats = SQL_Init_Request::get_sql_vars_two()[0];
$array_unique_time_ends_no_repeats = SQL_Init_Request::get_sql_vars_two()[1];







?>




<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h1>&nbsp;</h1>


<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<!-- Javascript -->
<script>
   $(function() {
      $( "#datepicker-13" ).datepicker();
      $( "#datepicker-13" ).datepicker("show");
   });
</script>

<form action="<?php $dir . 'admin/class-time-checker-admin-sql.php'  ?>" method="post">
	<p>Enter Date:</p> 
	<input name="date" type = "text" id = "datepicker-13">
	<h1>&nbsp;</h1>
	<p>Enter Course:</p> 
	<select name="course_name" id="courseNameId">
	<?php foreach ( WC_Bookings_Admin::get_booking_products() as $product ) : ?>
		<option value="<?php echo esc_attr( $product->get_id() ); ?>">
		<?php echo esc_html( sprintf( '%s (#%s)', $product->get_name(), $product->get_id() ) ); ?>
		</option>
  	<?php endforeach; ?>
	</select>
	<h1>&nbsp;</h1>



<!--it would be dope if I could record a voice message on g-drive have it be here and then I click on it and it opens a window where it reads out what I said.   -->

<p>Enter Begining Hours:</p> 
	<select name="begin_hours" id="courseNameId">
	<?php foreach (SQL_Init_Request::match_pm_or_am($array_unique_time_starts_no_repeats) as $hour_end ) : ?>
	<option value="<?php echo $hour_end; ?>"><?php echo $hour_end; ?></option>
  	<?php endforeach; ?>
  	</select>

<p>Enter Ending Hours:</p> 
	<select name="end_hours" id="courseNameId">
	<?php foreach (SQL_Init_Request::match_pm_or_am($array_unique_time_ends_no_repeats ) as $hour_end ) : ?>
	<option value="<?php echo $hour_end; ?>"><?php echo $hour_end; ?></option>
	<?php endforeach; ?>
	</select>
	<h1>&nbsp;</h1>



<input type ="submit">


<h1>&nbsp;</h1>

</form>


<?php

if(
isset($_POST["date"]) && isset($_POST["course_name"])
&& isset($_POST["begin_hours"]) && isset($_POST["end_hours"])





){ 

  echo "401";
  echo "<br>";
  echo "begin time = ";
  echo $_POST["begin_hours"];
  echo "<br>";
  echo 402;
  echo "<br>";
  echo "end time = ";
  echo $_POST["end_hours"];

  echo "<br>";


  echo 403;
  echo "<br>";
  $array_unique_time_unit = $_POST['date'];
  $month = substr($array_unique_time_unit,0,2);
  $day = substr($array_unique_time_unit,3,2);
  $year = substr($array_unique_time_unit,6);
  echo "Date chosen = ";
  //$date_entered = $month.$day.$year;
  $date_entered = $year.$month.$day; 
  echo $date_entered;
  // I might have already done what 
  // I want as far as the date output.
  $day_start    = strtotime( 'midnight', strtotime( $day ) );
  $day_end      = strtotime( 'midnight +1 day', strtotime( $day ) ) - 1;

  $findDateBooking = new  WC_Bookings_Calendar();

  $product_filter  = isset( $_REQUEST['filter_bookings_product'] ) ? absint( $_REQUEST['filter_bookings_product'] ) : '';
  
  $booking_filter = array();
  if ( $product_filter ) {
     array_push( $booking_filter, $product_filter );
  }
//   $events = array();



  $product_id = $_POST['course_name'];
 


$array_booking_product_id_sql_cmd =
	SQL_Post_Request::find_metadata_sql($product_id);



// This is just a string its not actually executing the sql command and 
// returning what it queued. 
$parent_post_array_return = 
	SQL_Post_Request::find_ids($array_booking_product_id_sql_cmd)[1];



$a1 = SQL_Post_Request::pair_parent_with_child(SQL_Post_Request::reduce_sql_array_by_one_dimension($array_booking_product_id_sql_cmd), $parent_post_array_return, $product_id);


// Begin a2 creation.
$a2 = 
	SQL_Post_Request::find_ids($array_booking_product_id_sql_cmd)[0];
//echo "508";

//echo "509";

$no_match_entries =
array_diff(
	 SQL_Post_Request::fill_a2_simple_array($a2),
       	 SQL_Post_Request::fill_a1_simple_array($a1));

// $no_match_entries is appropriate

$a2 =  SQL_Post_Request::prune_a2($no_match_entries, $a2);


$combined_array = SQL_Post_Request::arrays_to_combine($a1,$a2);


$combine_un_assoc = array_values($combined_array);
var_dump($combine_un_assoc);

// Create table from combine_un_assoc after 
// a time filter is implemented.

SQL_Post_Request::date_enter_filter($date_entered, $combine_un_assoc);

}
