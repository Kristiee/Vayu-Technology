<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php
	function readCSV($csvFile){
		$file_handle = fopen($csvFile, 'r');
		while (!feof($file_handle) ) {
			$line_of_text[] = fgetcsv($file_handle, 1024);
		}
		fclose($file_handle);
		return $line_of_text;
	}

	function layout($page_id) 
	{
		$csvFile = 'data/quotes.csv';
		$number=mt_rand(1,999);
		$csv = readCSV($csvFile); //displays csv as array
	
		switch($page_id) 
		{
			default: 
				echo '<p>The page was not found.</p>';
			case 'quotes.html':
				echo "<table class='table'>\n";

				$row = 0;
				$handle = fopen("quotes.csv", "r");
				echo '<table class="table table-bordered">';
					echo '<thead>';
						echo '<tr><b>';
							echo '<th><b>';
								print_r($csv[0][0]);
							echo '</th>';
							echo '<th><b>';
								print_r($csv[0][1]);
							echo '</th>';
						echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
						echo '<tr>';
							echo '<td>';
								print_r($csv[$number][0]);
							echo '</td>';
							echo '<td>';
								print_r($csv[$number][1]);
							echo '</td>';
						echo '</tr>';
					echo '</tbody>';
				echo '</table>';
				break;

			case 'quotes.json':
				$i=0;
				echo '<pre>'; 
				$json['json_'.$i]['title'] =($csv[0][0]);
				$json['json_'.$i]['Author'] =($csv[0][1]);
				$json['json_'.$i]['title'] =($csv[$number][0]);
				$json['json_'.$i]['Author'] =($csv[$number][1]);

				print json_encode($json);
				break;
				
			case 'quotes.xml':
				function csv2xml($file, $books = 'data', $Book = 'row')
				{
					$r = "<{$books}>\n";
					$row = 0;
					$cols = 0;
					$information = array();

					$handle = @fopen($file, 'r');
					if (!$handle) return $handle;
					
					while (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
					{
						if ($row > 0) $r .= "\t<{$Book}>\n";
						if (!$cols) $cols = count($data);
			
						for ($i = 0; ($i < $cols) && (strlen($data[$i]) > 0); $i++)
						{
							if ($row == 0)
							{
								$information[$i] = $data[$i];
								continue;
							}
							$r .= "\t\t<{$information[$i]}>";
							$r .= $data[$i];
							$r .= "</{$information[$i]}>\n";
						}
						if ($row > 0) $r .= "\t</{$Book}>\n";
						$row++;
					}
					fclose($handle);
					$r .= "</{$books}>";
					return $r;
				}
				$xml = csv2xml($csvFile, 'Books', 'Book');
				echo '<pre>', htmlentities($xml), '</pre>';
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Single index.php PHP script to load different page using URL Variable</title>
	</head>
	<body>
		<p style="text-align: center;"><a href="http://localhost/test/index4.php?page=quotes.html">HTML FORMAT</a> | <a href="http://localhost/test/index4.php?page=quotes.json">JSON FORMAT</a> | <a href="http://localhost/test/index4.php?page=quotes.xml">XML FORMAT</a></p>
		<?php
			if (isset($_GET['page'])) {

				$page_id = $_GET['page']; 
							//Get the request URL
				layout($page_id); //Call the function with the argument
			}
		?>
	</body>
</html>