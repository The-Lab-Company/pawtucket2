<?php
/* ----------------------------------------------------------------------
 * controllers/ChronologyController.php
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2009-2010 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 
 	require_once(__CA_LIB_DIR__."/ca/BaseSearchController.php");
 	require_once(__CA_LIB_DIR__."/ca/Search/OccurrenceSearch.php");
 	require_once(__CA_LIB_DIR__."/ca/Search/EntitySearch.php");
 	require_once(__CA_LIB_DIR__."/ca/Search/ObjectSearch.php");
	require_once(__CA_MODELS_DIR__."/ca_objects.php");
 
 	class ChronologyController extends BaseSearchController {
 		# ------------------------------------------------------- 	
 		private $opb_cache_searches = false;
 		
 		protected $opa_periods = array(
			1 => array("start" =>1870, "end" => 1899, "label" => "1870 - 1900", "displayAllYears" => 1),
			2 => array("start" =>1900, "end" => 1919, "label" => "1900 - 1920", "displayAllYears" => 1),
			3 => array("start" =>1920, "end" => 1939, "label" => "1920 - 1940", "displayAllYears" => 1),
			4 => array("start" =>1940, "end" => 1959, "label" => "1940 - 1960", "displayAllYears" => 1),
			5 => array("start" =>1960, "end" => 1979, "label" => "1960 - 1980", "displayAllYears" => 1),
			6 => array("start" =>1980, "end" => 1999, "label" => "1980 - 2000", "displayAllYears" => 1),
			7 => array("start" =>2000, "end" => 2050, "label" => "2000 - present", "displayAllYears" => 1)
		);
		
		private $opa_access_values;
		private $opn_default_period;
		private $opo_plugin_config;			// plugin config file
 		private $ops_theme;						// current theme
 		
 			
 		# -------------------------------------------------------
 		public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
 			$this->ops_theme = __CA_THEME__;																	// get current theme
 			if(!is_dir(__CA_APP_DIR__.'/plugins/eastend/themes/'.$this->ops_theme.'/views')) {		// if theme is not defined for this plugin, try to use "default" theme
 				$this->ops_theme = 'default';
 			}

 			parent::__construct($po_request, $po_response, array(__CA_APP_DIR__.'/plugins/eastend/themes/'.$this->ops_theme.'/views'));
 			
 			MetaTagManager::addLink('stylesheet', $po_request->getBaseUrlPath()."/app/plugins/eastend/themes/".$this->ops_theme."/css/eastend.css",'text/css');
 		 	
 			$this->opo_plugin_config = Configuration::load($this->request->getAppConfig()->get('application_plugins').'/eastend/conf/eastend.conf');
 			
 			if (!(bool)$this->opo_plugin_config->get('enabled')) { die(_t('eastend plugin is not enabled')); }

			// redirect user if not logged in
			if ($this->request->config->get('pawtucket_requires_login')&&!($this->request->isLoggedIn())) {
                $this->response->setRedirect(caNavUrl($this->request, "", "LoginReg", "form"));
            }
            if($this->request->config->get("dont_enforce_access_settings")){
 				$this->opa_access_values = array();
 			}else{
 				$this->opa_access_values = caGetUserAccessValues($this->request);
 			}
 			
 			$this->opn_default_period = 1;
            
            $t_list = new ca_lists();
			$pn_type_restriction_id_entity = $t_list->getItemIDFromList('entity_types', 'individual');
			
			// set type restrictions for searches 
 			$o_search_result_context_entity = new ResultContext($this->request, "ca_entities", 'basic_search');
 			$o_search_result_context_entity->setTypeRestriction($pn_type_restriction_id_entity);
 			$o_search_result_context_entity->saveContext();
		}
 		# -------------------------------------------------------
 		function Index() {
 			
 			$va_periods = $this->opa_periods;
 			$this->view->setVar('periods', $va_periods);
 			
 			$this->view->setVar('access_values', $this->opa_access_values);
 			
 			$va_periods = $this->opa_periods;
 			$this->view->setVar('periods', $this->opa_periods);
 			$vn_year = $this->request->getParameter('year', pInteger);
 			$vn_period = $this->request->getParameter('period', pInteger);
			if(!$vn_period || (!$va_periods[$vn_period])){
				if($vn_year){
					# --- determine the period from the year
					foreach($va_periods as $i => $va_per_info){
						if(($vn_year >= $va_per_info["start"]) && ($vn_year <= $va_per_info["end"])){
							$vn_period = $i;
							break;
						}
					}
				}else{
					$vn_period = $this->opn_default_period;
				}
			}
			$this->view->setVar('period', $vn_period);
			if(!$vn_year){
				$vn_year = $va_periods[$vn_period]["start"];
			}
			$this->view->setVar('year', $vn_year);
 			
 			$vn_y = "";
 			if($va_periods[$vn_period]["displayAllYears"] == 1){
 				$vn_y = $va_periods[$vn_period]["start"]." to ".$va_periods[$vn_period]["end"];
 			}else{
 				$vn_y = $vn_year;
 			}
 			
			$va_period_data = array();
			
			$o_occ_search = new OccurrenceSearch();
			$qr_occs = $o_occ_search->search("ca_occurrences.event_date:\"".$vn_y."\"", array("sort" => "ca_occurrences.event_date", "no_cache" => !$this->opb_cache_searches, "checkAccess" => $this->opa_access_values));
			$va_period_data["occurrences"] = $qr_occs;
			
			$o_ent_search = new EntitySearch();
			$qr_entities = $o_ent_search->search("ca_entities.arrival_date:\"".$vn_y."\"", array("sort" => "ca_entities.lname", "no_cache" => !$this->opb_cache_searches, "checkAccess" => $this->opa_access_values));
			$va_period_data["entities"] = $qr_entities;
			
			$o_obj_search = new ObjectSearch();
			$qr_objects = $o_obj_search->search("ca_objects.creation_date:\"".$vn_y."\"", array("sort" => "ca_objects.creation_date", "no_cache" => !$this->opb_cache_searches, "checkAccess" => $this->opa_access_values));
			$va_period_data["objects"] = $qr_objects;
			
			$this->view->setVar('period_data', $va_period_data);
			
 			$this->render('chronology_period_html.php');
 		}
 		# ------------------------------------------------------- 
 	}
 ?>
