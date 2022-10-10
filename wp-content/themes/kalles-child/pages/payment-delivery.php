<?php
    /*
        Template Name: Payment Delivery
    */

    get_header();
    $url = $_SERVER['REQUEST_URI'];	
    $parts = parse_url($url); 
    parse_str($parts['query'], $query); 
	$delAmount = $query['amount'];
	if($delAmount > 0){
?>
<div id="the4-content">
	<div class="page-head pr tc">
        <div class="container pr">
            <h1 class="tu mb__5 cw" itemprop="headline">Оплата</h1>
        </div>
    </div>
	<div class="container">
        <div class="row the4-page">
			<div class="entry col-md-12 col-xs-12 mt__60 mb__60">
				<div class="entry-content">
                    <p><?php pll_e('cartDel2'); ?></p>
                    <p><?php pll_e('cartRepeat2'); ?></p>
                    <form id="click_form" action="https://my.click.uz/services/pay" method="get" target="_blank">
                        <input type="hidden" name="amount" value="<?php echo $query['amount']; ?>" />
                        <input type="hidden" name="merchant_id" value="13751"/>
                        <input type="hidden" name="merchant_user_id" value="21707"/>
                        <input type="hidden" name="service_id" value="19252"/>
                        <input type="hidden" name="transaction_param" value="<?php echo $query['transaction_param']; ?>"/>
                        <input type="hidden" name="user_phone" value="<?php echo $query['user_phone']; ?>">
                        <input type="hidden" name="return_url" value="<?php echo $query['return_url']; ?>"/>
                        <button type="submit"><?php pll_e('cartTolov2'); ?></button>
                    </form>
                </div>
			</div>
		</div>
	</div>
</div>
<?php } else {?>
<div id="the4-content">
	<div class="page-head pr tc">
		<div class="container pr">
			<h1 class="tu mb__5 cw" itemprop="headline">Ваш заказ принят!</h1>
		</div>
	</div>
	<div class="container">
		<div class="row the4-page">
			<div class="entry col-md-12 col-xs-12 mt__60 mb__60">
				<div class="entry-content">
					<p>Вы успешно оплатили за товар! <strong>Заберите товар с офиса (3-этаж, Роза).</strong> Склад работает с 09:00 до 18:00 (Пн-Пт).</p>                    
				</div>
			</div>
		</div>
	</div>
</div>
<?php }?>
<?php get_footer();?>