<?php
@include($tcgpath.'admin/class.lib.php');
@include($header);


// Check if user is logged in
if ( empty( $login ) )
{
	header("Location: account.php?do=login");
}

// Get catalog slug for page
$slug = $database->get_assoc("SELECT * FROM `shop_catalog`");

// Declare empty strings
$arrayList = '';
$arrayDiff = '';
$arrayName = '';

// Explode bombs
$curValue = explode(' | ', $general->getItem( 'itm_currency' ));
$curName = explode(', ', $settings->getValue( 'tcg_currency' ));
$curShop = explode(', ', $settings->getValue( 'shop_minimum' ));
for( $i=0; $i<count($curValue); $i++ )
{
	$tn = substr_replace($curName[$i],"",-4);
	if( $curValue[$i] > 1 )
	{
		$var = substr($tn, -1);
		if( $var == "y" )
		{
			$tn = substr_replace($tn,"ies",-1);
		}

		else if( $var == "o" )
		{
			$tn = substr_replace($tn,"oes",-1);
		}

		else
		{
			$tn = $tn.'s';
		}
	}

	else
	{
		$tn = $tn;
	}

	if( empty( $curValue[$i] ) )
	{
		$arrayList .= '<b>0</b> '.$tn.', ';
	}

	else
	{
		$arrayList .= '<b>'.$curValue[$i].'</b> '.$tn.', ';
	}

	$arrayDiff .= $curShop[$i] - 1;
	$arrayName .= $tn.' and ';
	$cleanAN = substr_replace($arrayName,"",-5);
	$cleanLI = substr_replace($arrayList,"",-2);
	if( $curValue[$i] <= $curShop[$i] - 1 )
	{
		$msg = "You don't have enough ".$cleanAN." to spend! Please play more games to earn more currencies.";
	}

	else
	{
		$msg = "You currently have ".$cleanLI." to spend!";
	}
}
$arrayName = substr_replace($arrayName,"",-5);




/********************************************************
 * Page:			Shoppe
 * Description:		Show main page of shoppe
 */
if( empty( $page ) )
{
	@include($tcgpath.'shoppe/index.php');
} // end empty $page




/********************************************************
 * Page:			Shop Catalog
 * Description:		Show page of shop catalogs
 */
else
{
	if( empty( $id ) )
	{
		$get = $database->get_assoc("SELECT * FROM `shop_catalog` WHERE `shop_slug`='$page'");
		echo '<h1>Shoppe <span class="fas fa-angle-right" aria-hidden="true"></span> '.$get['shop_catalog'].'</h1>
		<p>Here is the inventory list of our shop under the '.$get['shop_catalog'].' catalog! Feel free to browse and select what you need.</p>';

		$data = $database->query("SELECT * FROM `shop_items` WHERE `shop_catalog`='".$get['shop_id']."'");
		while( $row = mysqli_fetch_assoc( $data ) )
		{
			echo '<div class="tableBody" style="width:46%; display:inline-block; margin:5px;" align="center">
			<h3>'.$row['shop_item'].'</h3>
			<img src="'.$tcgurl.'shoppe/images/'.$row['shop_file'].'" /><br />'.
			$row['shop_description'].' <button onclick="window.location.href=\''.$tcgurl.'shoppe.php?page='.$page.'&id='.$row['shop_id'].'\';" class="btn-success" />Buy this</button>
			</div>';
		}
	}

	else
	{
		if( empty( $act ) )
		{
			$shopItem = $database->get_assoc("SELECT * FROM `shop_items` WHERE `shop_id`='$id'");
			echo '<h1>Shoppe : '.$shopItem['shop_item'].'</h1>
			<p>Are you sure you want to purchase this item? If yes, please indicate how many of this item you are planning to purchase from the form below:</p>
			<form method="post" action="'.$tcgurl.'shoppe.php?page='.$page.'&id='.$id.'&action=sent">
				<input type="hidden" name="shopID" value="'.$id.'" />
				<b>How many packs?</b> 
				<input type="text" name="amount" placeholder="1" style="width:20%;"> 
				<input type="submit" name="submit" class="btn-success" value="Buy" />
			</form>';
		}

		else {
			if( $page == "card-packs" )
			{
				@include($tcgpath.'shoppe/card-packs.php');
			}
			else if( $page == "coupons" )
			{
				@include($tcgpath.'shoppe/coupons.php');
			}
			else if( $page == "merchandise" )
			{
				@include($tcgpath.'shoppe/merchandise.php');
			}
			else
			{
				# If you are going to add a shop catalog other than the three above,
				# you will have to create your own catalog page under the shoppe/ folder.

				# If this catalog doesn't include cards, use the merchandise.php template.
				# Otherwise, use the card-packs.php template.

				# Make sure the $page string is the same as your shop slug from the shop_catalog table.
				@include($tcgpath.'shoppe/'.$page.'.php');
			}
		}
	}
}

@include($footer);
?>