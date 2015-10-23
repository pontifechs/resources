<?php

		$instanceArray = array();
		$obj = new Fund();

		$instanceArray = $obj->allAsArray();

		echo "<div class='adminRightHeader'>Fund</div>";

		if (count($instanceArray) > 0){
			?>
			<table class='linedDataTable'>
				<tr>
				<th style='width:25px;'>Code</th>
				<th style='width:100%;'>Name</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				</tr>
				<?php

				foreach($instanceArray as $instance) {
					echo "<tr>";
					echo "<td>" . $instance['fundCode'] . "</td>";
					echo "<td>" . $instance['shortName'] . "</td>";
					echo "<td><a href='ajax_forms.php?action=getAdminFundUpdateForm&updateID=" . $instance['fundID'] . "&height=178&width=260&modal=true' class='thickbox'><img src='images/edit.gif' alt='edit' title='edit'></a></td>";
					echo "<td><a href='javascript:deleteFund(\"Fund\", \"" . $instance['fundID'] . "\");'><img src='images/cross.gif' alt='remove' title='remove'></a></td>";
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo "(none found)<br />";
		}

		echo "<a href='ajax_forms.php?action=getAdminFundUpdateForm&updateID=&height=178&width=260&modal=true' class='thickbox'>add new fund</a><br/>";
		echo "<a href='importFunds.php?action=getAdminFundUpdateForm&updateID=&height=175&width=300&modal=true' class='thickbox'>import funds</a>";

?>
