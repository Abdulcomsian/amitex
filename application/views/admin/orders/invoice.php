<!DOCTYPE html>
<html lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo lang('order_invoice'); ?> :: <?php echo $order_id; ?></title>
    <style type="text/css">
        .poductInfo {width: 100%; max-width: 575px; margin: 50px auto 0;}
        .poductInfo ul {text-align: right; padding: 0; margin-top: 25px;}
        .poductInfo ul li {font-size: 15px;list-style: none;}
        .productDetails {margin-bottom: 50px;}
        .productDetails p {font-size: 16px; font-weight: 600; text-align: right; text-decoration: underline;}
        .productDetails table {width: 100%; border-spacing: 0;}
        .productDetails table tbody tr:nth-child(odd) {background: #e7e6e7 !important;}
        .productDetails table tr th, .productDetails table tr td {vertical-align: middle; text-align: center;}
        .productDetails table tr th {background-color: #333 !important; color: #fff; -webkit-print-color-adjust: exact; text-transform: uppercase;}
        .productDetails table thead tr th {padding: 2px 5px; border-bottom: 1px solid #dee2e6; border-left: 1px solid #dee2e6;}
        .productDetails table thead tr th:last-child {border-left: 0}
        .productDetails table tbody tr th, .productDetails table tbody tr td {padding: 10px; border-bottom: 1px solid #dee2e6; }
        .productDetails table tbody tr td:last-child {border-right: 1px solid #dee2e6;}
        .productDetails table tbody tr td label {font-weight: 600; margin-left: 5px;}
        .productDetails table tbody tr td input {width: 70px; padding: 5px; border: 1px solid #dfdfdf; border-radius: 3px; box-shadow: 0 2px 18px 0 rgba(75 70 73 / 18%);}
        .footerText {font-size: 15px; font-weight: 600; text-align: center; padding: 50px 0;}
    </style>
  </head>
  <body style="font-family: 'Rubik', sans-serif;" dir="rtl">
        <div class="poductInfo">
            <h4 class="heading"><?php echo SITE_NAME; ?> <?php echo lang('order_details'); ?></h4>
            <ul>
                <li dir="ltr"><?php echo $order_id; ?> : <strong><?php echo lang('order_id'); ?></strong></li>
                <li dir="ltr"><?php echo $client_name; ?> : <strong><?php echo lang('client_name'); ?></strong></li>
                <li dir="ltr"><?php echo $order_date; ?> : <strong><?php echo lang('order_date'); ?></strong></li>
                <li dir="ltr"><?php echo CURRENCY_SYMBOL.$total_cart_amount; ?> : <strong><?php echo lang('order_amount'); ?></strong></li>
            </ul>

            <?php foreach($products_data as $product) { $order_variants = array(); ?>
                <?php foreach($order_details as $order) {
                    if($order['product_id'] == $product['product_id']){
                        $order_variants = json_decode($order['product_variants'],TRUE);
                    }
                } ?>
                <div class="productDetails">
                    <p><?php echo $product['product_name']; ?></p>
                    <span style="font-size:12px;color:#333333ad;">(<?php echo $product['subcategory_name']; ?> < <?php echo $product['category_name']; ?>)</span>
                    <table>
                        <thead>
                            <tr>
                                <th><?php echo lang('size'); ?><br><?php echo lang('color'); ?></th>
                                <?php foreach($product['size_variants'] as $size) { ?>
                                    <th><?php echo $size; ?></th>   
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($product['color_variants'] as $color) { ?>
                                <tr>
                                    <th><?php echo $color; ?></th>
                                    <?php foreach($product['product_varinats_prices'] as $varinat_price) { if($varinat_price['color_variant'] != $color) {continue;} $quantity = 0; ?>
                                        <td>
                                            <?php 
                                                $product_variant_price = getProductDiscountPrice($varinat_price['product_price'],$product['is_premium'],(($product['product_brand'] == 'Amitex') ? $percent_reduction_amitex :  $percent_reduction_michal));
                                            ?>
                                            <label><?php echo CURRENCY_SYMBOL.$product_variant_price; ?></label>
                                            <?php foreach($order_variants as $order) { 
                                                if($varinat_price['product_variant_id'] == $order['product_variant_id']){
                                                    $quantity = $order['quantity'];
                                                }
                                            } ?>
                                            <input type="number" value="<?php echo $quantity; ?>" readonly="">
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th><?php echo lang('checked'); ?></th>
                                <?php foreach($product['size_variants'] as $number) { ?>
                                    <td> </td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
  </body>
</html>