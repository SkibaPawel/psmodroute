<?php
/**
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
 */

/**
 * @since 1.5.0
 */
 
 
class psmodrouteinfoModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

/*
	public function init()
	{
		$this->page_name = 'Konto'; 		
		//$this->display_column_left = false;
		//$this->display_column_right = false;
		parent::init();
	}   
*/

	public function getTemplateVarPage()
    {		
		// var_dump($this); die;
		
        $page = parent::getTemplateVarPage();
        $page['title'] =  Configuration::get( 'title'.$this->module->name,  (int)Context::getContext()->language->id );

        return $page;
    }
	
	public function initContent()
	{
		$this->display_column_left = true;
		parent::initContent();
		$this->page_name = 'Konto utworzone'; 
		//$this->title; 

		/*$cart = $this->context->cart;
		if (!$this->module->checkCurrency($cart))
			Tools::redirect('index.php?controller=order');
*/
		$this->context->smarty->assign(array(
			'psmodrouteDescription' =>   Configuration::get( 'description'.$this->module->name,  (int)Context::getContext()->language->id ), 
		
		
			 //'page'=>['title'=>'titletitle'] 
			/**'nbProducts' => $cart->nbProducts(),
			'cust_currency' => $cart->id_currency,
			'currencies' => $this->module->getCurrency((int)$cart->id_currency),
			'total' => $cart->getOrderTotal(true, Cart::BOTH),*/
			//'this_path' => $this->module->getPathUri(),
			///'this_path_bw' => $this->module->getPathUri(),
			//'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/'
		));

   
   
		if (version_compare(_PS_VERSION_, '1.7.0', '>=') === true){
			$this->setTemplate('module:psmodroute/views/templates/front/info.tpl');				
		}else{
			$this->setTemplate('info.tpl');	
		}
   
   
		
		
		
		
		
	}
} 
 
