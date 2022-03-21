{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
<script type="text/javascript">{literal}
// <![CDATA[
oosHookJsCodeFunctions.push('oosHookJsCodeMailAlert');

function clearText() {
	if ($('#oos_customer_email').val() == '{/literal}{l s='your@email.com' mod='mailalerts'}{literal}')
		$('#oos_customer_email').val('');
}

function oosHookJsCodeMailAlert() {
	$.ajax({
		type: 'POST',
		url: "{/literal}{$link->getModuleLink('mailalerts', 'actions', ['process' => 'check'])}{literal}",
		data: 'id_product={/literal}{$id_product}{literal}&id_product_attribute='+$('#idCombination').val(),
		success: function (msg) {
			if ($.trim(msg) == '0') {
				$('#mailalert_link').show();
				$('#oos_customer_email').show();
			}
			else {
				$('#mailalert_link').hide();
				$('#oos_customer_email').hide();
			}
		}
	});
}

function  addNotification() {
	$.ajax({
		type: 'POST',
		url: "{/literal}{$link->getModuleLink('mailalerts', 'actions', ['process' => 'add'])}{literal}",
		data: 'id_product={/literal}{$id_product}{literal}&id_product_attribute='+$('#idCombination').val()+'&customer_email='+$('#oos_customer_email').val()+'',
		success: function (msg) {
			if ($.trim(msg) == '1') {
				$('#mailalert_link').hide();
				$('#oos_customer_email').hide();
				$('#oos_customer_email_result').html("{/literal}{l s='Request notification registered' mod='mailalerts'}{literal}");
				$('#oos_customer_email_result').css('color', 'green').show();
			}
			else if ($.trim(msg) == '2' ) {
				$('#oos_customer_email_result').html("{/literal}{l s='You already have an alert for this product' mod='mailalerts'}{literal}");
				$('#oos_customer_email_result').css('color', 'red').show();
			} else {
				$('#oos_customer_email_result').html("{/literal}{l s='Your e-mail address is invalid' mod='mailalerts'}{literal}");
				$('#oos_customer_email_result').css('color', 'red').show();
			}
		}
	});
	return false;
}

$(document).ready(function() {
	oosHookJsCodeMailAlert();
	$('#oos_customer_email').bind('keypress', function(e) {
		if(e.keyCode == 13)
		{
			addNotification();
			return false;
		}
	});
	
	
	$(".askforprice").click(function(e) {	
		e.preventDefault();
		$( "#dialogAskForPrice" ).dialog( "open" );
		return;
	});		  	
	
});

	function askforprice(email, price){
		//alert(email);
		var ret  = true; 
		$.ajax({
			async: false, 
			type: 'POST',	
			cache : false , 
			dataType: "json", 		
			url: "{/literal}{$link->getModuleLink('psaskforprice', 'actions', ['process' => 'add'])}{literal}",
			data: 'id_product={/literal}{$id_product}{literal}&id_product_attribute='+$('#idCombination').val()+'&askforprice_email='+email+'&askforprice_price='+price,
			success: function (msg) {		
				$('#erorsaskforprice').html('');		
				if(msg.errors != undefined ){					
					var str = '';
					$.each( msg.errors, function( key, value ) {
						str += '<div class="errorAaskForPrice">'+value+'</div>'
					});
					$('#erorsaskforprice').html(str);					
					//alert(msg);	
					ret  = false; 
					
				}
				$('#askforpriceContainer').hide();
			}
		});		
		return ret;
						
	};		  
{/literal}
	  $( function() {
		$( "#dialogAskForPrice" ).dialog({
				autoOpen: false, 
				modal: true, 
				minWidth: 400 , 
				//position: { my: "top", at: "top"}, 
				draggable: true,
				dialogClass: 'fixed-dialog', 
				buttons: {
					"{l s='Send' mod='askforprice'}": function() {
					if(	askforprice($('#askforprice_email').val() , $('#askforprice_price').val()    )){	
						$( this ).dialog( "close" );
					}
				},
				"{l s='Cancel' mod='askforprice'}": function() {
					$( this ).dialog( "close" );
				}
      }
			
			
			
			});
	  } );


//]]>
</script>
<div id="askforpriceContainer" >
	<button class="button askforprice btn">
		<!--<img src="/modules/pscomselect/pscomselect.png" alt="ivon_pscomselect" height="42" width="42">  -->
			<span>{l s='Ask for price'  mod='askforprice'}</span>
	</button>	
</div>	
	
	
<!--dialog-->
<div id="dialogAskForPrice" title="{l s='Ask for price' mod='askforprice'}" style="display:none;">
	{if isset($email) AND $email}
		<span>{l s='Your email' mod='askforprice'}:</span><input type="text" id="askforprice_email" name="customer_email" size="20"  autocomplete="off" value="{l s='your@email.com' mod='askforprice'}" class="mailalerts_oos_email" onclick="clearText();" /><br />
	{/if}	
	<span>{l s='Your price' mod='askforprice'}:</span><input type="text" id="askforprice_price" name="askforprice_price" size="20" value="" onclick="clearText();" autocomplete="off"  /><br />	
	<div id="erorsaskforprice">
	</div>	
	
</div>
<!--dialog end-->	
