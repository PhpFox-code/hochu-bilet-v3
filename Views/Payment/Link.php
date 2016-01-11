<form method="POST" action="https://www.liqpay.com/api/checkout" 
   accept-charset="utf-8" name="myForm">
     <input type="hidden" name="data" value="<?php echo $data ?>"/>
     <input type="hidden" name="signature" value="<?php echo $signature ?>"/>
     <input type="image" src="//static.liqpay.com/buttons/p1ru.radius.png" id="pay_send" />
</form>
<script>
	$(document).ready(function($){
		$('#pay_send').trigger('click');
	});
</script>