<?php
/********************************************************************
 * Catalog:				Empty Page Catalog
 * Description:			Show main shop page if catalog is not defined
 */


echo '<h1>Shoppe</h1>
<p>Welcome to the shop, '.$player.'! Here you can buy card packs that we are currently offering. Choose the product you want to purchase using your gained '.$arrayName.'!</p>

<blockquote class="wish">
	<center>'.$msg.'</center>
</blockquote>

<center>';
// Show store fronts or catalogs. You can either use an image or text for this
// If using an image, make sure to name it after the catalog's slug
$getCatalog = $database->query("SELECT * FROM `shop_catalog`");
while( $row = mysqli_fetch_assoc( $getCatalog ) )
{
	echo '<a href="'.$tcgurl.'shoppe.php?page='.$row['shop_slug'].'"><img src="'.$tcgurl.'shoppe/images/'.$row['shop_slug'].'.'.$tcgext.'" /></a> ';
}
echo '</center>';
?>