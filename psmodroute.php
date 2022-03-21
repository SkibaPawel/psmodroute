<?php
if (!defined('_CAN_LOAD_FILES_'))
	exit;

//include_once(dirname(__FILE__).'/MailAlert.php');

class psmodroute extends Module
{


	public function __construct()
	{
		
		$this->name = 'psmodroute';    
		$this->version = 1.0;
		$this->author = 'Paweł Skiba';		
		$this->need_instance = 0;
		$this->bootstrap = true;
		parent::__construct();		
		//if ($this->id)
//			$this->init();

		$this->displayName = $this->l('psmodroute');
		$this->description = $this->l('Display info at selected url ');
		$this->confirmUninstall = $this->l('Are you sure you want  uninstall?');
	}
		
	public function displayForm()
	{
		// Get default Language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');    
		$languages = $this->context->controller->getLanguages();   
		// Init Fields form array
		
			 
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Settings'),
			),         
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('title'),
					'name' => 'title',
					'lang' => true,
					//'size' => 64,
					'required' => true
				),                         
				array(
					'type' => 'text',
					'label' => $this->l('description'),
					'name' => 'description',
					'lang' => true,
					//'size' => 64,
					//'required' => true
				),    
				array(
					'type' => 'text',
					'label' => $this->l('URL'),
					'name' => 'url',
					'lang' => true,
					//'size' => 64,
					'required' => true
				),                            
			),        
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'button',
				'name' => 'form0'
			)
		);
		

		
		 
		$helper = new HelperForm();
		 
		// Module, token and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		 
		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->languages = $this->context->controller->getLanguages();
		 
		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;        // false -> remove toolbar
		$helper->toolbar_scroll = false;      // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit'.$this->name;
		
		$helper->toolbar_btn = array(
			'save' =>
			array(
				'desc' => $this->l('Save'),
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
				'&token='.Tools::getAdminTokenLite('AdminModules'),
			),
			'back' => array(
				'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			)
		);         
		$helper->toolbar_btn = array(); 
		// Load current value
		
	   
		foreach (Language::getLanguages(false) as $lang) {
			
			 $helper->fields_value['title'][$lang['id_lang']] = Configuration::get( 'title'.$this->name,  $lang['id_lang'] ); 
			 $helper->fields_value['description'][$lang['id_lang']] = Configuration::get( 'description'.$this->name,  $lang['id_lang'] ); 
			 $helper->fields_value['url'][$lang['id_lang']] = Configuration::get( 'url'.$this->name,  $lang['id_lang'] ); 
		  }
		$form0 =  $helper->generateForm($fields_form);     
		return $form0;
	}	
	
	
	
	
  public function getContent()
	{		
			$id_lang = (int)Context::getContext()->language->id;
			$languages = $this->context->controller->getLanguages();
			$default_language = (int)Configuration::get('PS_LANG_DEFAULT');	
		
		 $output = null;
	 

		if (Tools::isSubmit('submit'.$this->name))
		{		
			foreach (Language::getLanguages(false) as $lang) {		
				 $fields_value['title'][$lang['id_lang']] = Tools::getValue('title_'.(int)$lang['id_lang'], ''); 
				 $fields_value['description'][$lang['id_lang']] = Tools::getValue('description_'.(int)$lang['id_lang'], '' );
				 $fields_value['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], '');                    
			  } 		
			$error  = false;  
			foreach ($fields_value['title'] as $lang => $value){
				if (empty($value) || !Validate::isMessage($value)){
					$output .= $this->displayError( $this->l('Invalid Configuration value '). $this->l('title'));
					$error = true; 
				}
			} 			
			if(!$error){
				Configuration::updateValue('title'.$this->name, $fields_value['title']);
				$output .= $this->displayConfirmation($this->l('Settings updated'));			
			}
					
			$error  = false; 
			foreach ($fields_value['description'] as $lang => $value){
				if (/*empty($value) ||*/ !Validate::isMessage($value)){
					$output .= $this->displayError( $this->l('Invalid Configuration value '). $this->l('description'));
					$error = true; 
				}
			} 			
			if(!$error){
				Configuration::updateValue('description'.$this->name, $fields_value['description']);
				$output .= $this->displayConfirmation($this->l('Settings updated'));			
			}			
			
				
			$error  = false; 
			foreach ($fields_value['url'] as $lang => $value){
				if (empty($value) || !Validate::isMessage($value)){
					$output .= $this->displayError( $this->l('Invalid Configuration value '). $this->l('url'));
					$error = true; 
				}
			} 			
			if(!$error){
				//echo '<pre>'.print_r( $fields_value['url'] , true).	'</pre>' ; die; 
				Configuration::updateValue('url'.$this->name, $fields_value['url']);
				$output .= $this->displayConfirmation($this->l('Settings updated'));			
			}						
				
		}
		return $output.$this->displayForm();
	}
		
	
	



	public function install()
	{		
	
		//echo '<pre>'.print_r( Language::getLanguages(false) , true).	'</pre>' ; die; 
		
		foreach(Language::getLanguages(false) as $lang){
			if($lang['iso_code'] === 'pl'){
				//die('pl');
				Configuration::updateValue('url'.$this->name, [$lang['id_lang']=>'cześć' ]);
				Configuration::updateValue('title'.$this->name, [$lang['id_lang']=>'Cześć X13' ]);				
			}
			if($lang['iso_code'] === 'en'){
				Configuration::updateValue('url'.$this->name, [$lang['id_lang']=>'hello-world' ]);
				Configuration::updateValue('title'.$this->name, [$lang['id_lang']=>'Hello X13' ]);								
			}			
			
		}		
		
		return parent::install() and  $this->registerHook('ModuleRoutes')  /*$this->registerHook('displayProductButtons')*/;
	}

	public function uninstall($delete_params = true)
	{
		 
		//Configuration::deleteByName('title'.$this->name) &&  Configuration::deleteByName('description'.$this->name) &&	Configuration::deleteByName('url'.$this->name)
		
		return parent::uninstall()  &&  $this->unregisterHook( Hook::getIdByName('ModuleRoutes'))
			 &&Configuration::deleteByName('title'.$this->name) &&  Configuration::deleteByName('description'.$this->name) &&	Configuration::deleteByName('url'.$this->name)
		
		 ;		
	}

	public function hookModuleRoutes($params)
    {

		
		/* echo '<pre>'.print_r( Language::getLanguages(false) , true).	'</pre>' ; die; */
		$return = [];		
		foreach (Language::getLanguages(false) as $lang) {
			$return['psmodroute-route'.$lang['id_lang']]=  [
				'rule' => Configuration::get( 'url'.$this->name,  $lang['id_lang']),
				'controller' => 'info',
				'keywords' => [],
				'params' => [
					'fc' => 'module',
					'module' => 'psmodroute', 
					'id_lang' => $lang['id_lang']
				]
			];			

		}				
		// echo '<pre>'.print_r( $return , true).	'</pre>' ; die; 			
		return $return;
    }	
}
