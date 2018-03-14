<?php
	$t_item = $this->getVar("item");
	$va_comments = $this->getVar("comments");
	$vn_comments_enabled = 	$this->getVar("commentsEnabled");
	$vn_share_enabled = 	$this->getVar("shareEnabled");
	$vn_id = $t_item->get('ca_collections.collection_id');
	
	$t_list = new ca_lists();
	$vs_list_value =  $t_list->getItemIDFromList('yes_no', 'yes');

?>
<div class="container">
	<div class="row">
		<div class="col-sm-1"><div class='previousLink'>{{{previousLink}}}</div></div>
		<div class="col-sm-10">


<div class="container">
	<div class="row detailHead">
		<div class='col-xs-6 objNav'><!--- only shown at small screen size -->
			<div class='resultsLink'>{{{resultsLink}}}</div>
		</div>
		<div class='col-xs-5 pdfLink'>
	<?php		
			#print caNavLink($this->request, caGetThemeGraphic($this->request, 'pdf.png'), 'faDownload', 'Detail', 'objects', $vn_id.'/view/pdf/export_format/_pdf_ca_objects_summary');
	?>	
		</div><!-- end col --> 
	</div>
<div class="row">
	<div class='col-sm-6 col-md-6 col-lg-5 col-lg-offset-1' style="padding-right:30px;margin-bottom:40px;">
		{{{representationViewer}}}
<?php
			if ($va_catalog_id = $t_item->get('ca_collections.catalog_number')) {
				print "<div class='objIdno'>".$va_catalog_id."</div>";
			}	
?>		
	</div>	
	<div class='col-sm-6 col-md-6 col-lg-5'>
<?php
		$vn_label_col = "col-sm-4";
		$vn_data_col = "col-sm-8";
		print "<h1>".$t_item->get('ca_collections.preferred_labels')."</h1>";
		if ($vs_date = $t_item->get('ca_collections.display_date')) {
			print "<div class='unit row'><div class='{$vn_label_col} label'>Date</div><div class='$vn_data_col'>".$vs_date."</div></div>";
		}		
		$vs_verso_collection = null;
		if ($qr_collections = $t_item->get('ca_collections_x_collections.relation_id', array('returnAsSearchResult' => true))) {
			#print "hits=".$qr_collections->numHits(); 
			while($qr_collections->nextHit()) {
				if ($qr_collections->get('ca_collections.deleted') === null) { continue; } // you check for null because get() won't return info about deleted items
				
				if ($qr_collections->get('ca_collections_x_collections.current_collection') == $vs_list_value) {
					$vn_current_collection_id = $qr_collections->get('ca_collections_x_collections.collection_id');
					$t_collection = new ca_collections($vn_current_collection_id);
					if ($t_collection->get('ca_collections.public_private', array('convertCodesToDisplayText' => true)) != 'private'){
						print "<div class='unit row'><div class='{$vn_label_col} label'>Collection</div><div class='$vn_data_col'>".$qr_collections->getWithTemplate('<unit relativeTo="ca_collections" restrictToTypes="collection,other"><l>^ca_collections.preferred_labels</l></unit>');
						$vs_verso_collection = $qr_collections->getWithTemplate('<unit relativeTo="ca_collections" restrictToTypes="collection,other"><l>^ca_collections.preferred_labels</l></unit>');
					} else {
						print "<div class='unit row'><div class='{$vn_label_col} label'>Collection</div><div class='$vn_data_col'>".$qr_collections->getWithTemplate('<unit relativeTo="ca_collections" restrictToTypes="collection,other">^ca_collections.preferred_labels</unit>');
						$vs_verso_collection = $qr_collections->getWithTemplate('<unit relativeTo="ca_collections" restrictToTypes="collection,other">^ca_collections.preferred_labels</unit>');
					}
					if ($vs_credit_line = $qr_collections->get('ca_collections_x_collections.collection_line', array('restrictToTypes' => array('collection', 'other')))) {
						print ", ".$vs_credit_line;
					}
					if ($qr_collections->get('ca_collections_x_collections.uncertain') == $vs_list_value){
						"<i class='fa fa-question-circle' data-toggle='popover' data-trigger='hover' data-content='uncertain'></i>";
					}
					print "</div></div><!-- end unit -->";							
				}		
			}
		}		
		if ($vs_credit = $t_item->get('ca_collections.collection_line')) {
			print "<div class='unit row'><div class='{$vn_label_col} label'>Credit</div><div class='$vn_data_col'>".$vs_credit."</div></div>";
		}
		if ($vs_inst = $t_item->get('ca_collections.institutional_id')) {
			print "<div class='unit row'><div class='{$vn_label_col} label'>Institutional id</div><div class='$vn_data_col'>".$vs_inst."</div></div>";
		}				
		if ($vs_provenance_note = $t_item->get('ca_collections.provenance_note')) {
			print "<div class='unit row'><div class='{$vn_label_col} label'>Provenance Notes</div><div class='$vn_data_col'>".$vs_provenance_note."</div></div>";
		}	
		if ($vs_object_note = $t_item->get('ca_collections.object_note')) {
			print "<div class='unit row'><div class='{$vn_label_col} label'>Note</div><div class='$vn_data_col'>".$vs_object_note."</div></div>";
		}	
		if ($va_keywords = $t_item->get('ca_list_items.item_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values))) {
			$va_keyword_links = array();
			foreach ($va_keywords as $va_key => $va_keyword_id) {
				$va_keyword_links[] = caNavLink($this->request, caGetListItemByIDForDisplay($va_keyword_id), '', '', 'Browse', 'artworks/facet/term_facet/id/'.$va_keyword_id);	
			}
			print "<div class='unit row'><div class='{$vn_label_col} label'>Keywords</div><div class='$vn_data_col'>".join(', ', $va_keyword_links)."</div></div>";
		}							
?>			
	</div><!-- end col -->
</div><!-- end row -->
<div class='row'>
	<div class='col-sm-8 col-sm-offset-2'>
<?php
		if ($vs_remarks = $t_item->get('ca_collections.remarks')) {
			print "<div class='drawer'>";
			print "<h6><a href='#' data-toggleDiv='remarksDiv' class='togglertronic'>Remarks <i class='fa fa-plus drawerToggle'></i></a></h6>";
			print "<div id='remarksDiv'>".$vs_remarks."</div>";
			print "</div>";
		}
?>		
	</div><!-- end col -->
</div><!-- end row -->
<?php
	$vs_provenance = "";
	if ($va_provenance = $t_item->get('ca_collections_x_collections.relation_id', array('returnWithStructure' => true, 'restrictToTypes' => array('collection', 'other'), 'sort' => 'ca_collections_x_collections.rank', 'sortOrder' => 'ASC'))) {
		foreach ($va_provenance as $va_key => $va_relation_id) {
			$t_prov_rel = new ca_collections_x_collections($va_relation_id);
			$va_collection_ids = $t_prov_rel->get('ca_collections.collection_id', array('returnAsArray' => true));
			if(($key = array_search($vn_id, $va_collection_ids)) !== false) {
				unset($va_collection_ids[$key]);
			}
			$t_prov = new ca_collections($va_collection_ids[0]);
			if ($t_prov->get('ca_collections.public_private', array('convertCodesToDisplayText' => true)) == 'private') {
				$vs_provenance.= "<div>";
				$vs_provenance.= $t_prov->get('ca_collections.preferred_labels');
				if ($vs_display_date = $t_prov_rel->get('ca_collections_x_collections.display_date')) {
					$vs_provenance.= ", ".$vs_display_date;
				}				
				if ($vs_remark = $t_prov_rel->get('ca_collections_x_collections.collection_line')) {
					$vs_provenance.= ", ".$vs_remark;
				}				
				$vs_provenance.= "</div>";
			} elseif ($t_prov->get('access') != 0 ){
				$va_provenance_id = $t_item->get('ca_collections.collection_id');
				$vs_provenance_line = $t_prov->get('ca_collections.preferred_labels');				
				if ($t_prov_rel) {
					$vs_buf = array();
					if ($vs_auction_name = $t_prov_rel->get('ca_collections_x_collections.auction_name')) {
						$vs_buf[]= $vs_auction_name;
					}						
					if ($vs_sale = $t_prov_rel->get('ca_collections_x_collections.sale_name')) {
						$vs_buf[]= $vs_sale;
					}
					if ($vs_display_date = $t_prov_rel->get('ca_collections_x_collections.display_date')) {
						$vs_buf[]= $vs_display_date;
					}
					if ($vs_lot_number = $t_prov_rel->get('ca_collections_x_collections.lot_number')) {
						$vs_buf[]= "Lot number ".$vs_lot_number;
					}
					if ($t_prov_rel->get('ca_collections_x_collections.gift_artist') == $vs_list_value) {
						$vs_buf[] = "gift of the artist";
					}
					if ($t_prov_rel->get('ca_collections_x_collections.sold_yn') == 163) { 
						$vs_buf[]= "(not sold)";
					}	
					if (sizeof($vs_buf) > 0){
						$vs_provenance_line.= ", ".join(', ', $vs_buf);
					}
					if ($vs_remark = $t_prov_rel->get('ca_collections_x_collections.collection_line')) {
						$vs_provenance_line.= ", ".$vs_remark;
					}
				}
				if ($t_prov_rel->get('ca_collections_x_collections.uncertain') == $vs_list_value) {
					$vs_provenance_line.= " <i class='fa fa-question-circle' data-toggle='popover' data-trigger='hover' data-content='uncertain'></i>";
				}
				$vs_provenance_line.= "<i class='fa fa-chevron-right'></i><!-- end prov entry -->";
				$vs_provenance.= "<div>".caNavLink($this->request, $vs_provenance_line, '', '', 'Detail', 'collections/'.$va_provenance_id)."</div>";
			}
		}
	}
	if ($vs_provenance != "") {
		print "<div class='row'><div class='col-sm-8 col-sm-offset-2'><div class='drawer'>";
		print "<h6><a href='#' data-toggleDiv='provenanceDiv' class='togglertronic'>Provenance <i class='fa fa-plus drawerToggle'></i></a></h6>";
		print "<div id='provenanceDiv'>";
		print $vs_provenance;
		print "</div><!-- end provenanceDiv -->";
		print "</div><!-- end drawer --></div><!-- end col --></div><!-- end row -->";
	}
?>

{{{<ifcount code="ca_objects" relativeTo="ca_objects"  min="1">
	
	<div class="row"><div class='col-sm-12'>

		<div id="browseResultsContainer">
			<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>
		</div><!-- end browseResultsContainer -->
	</div><!-- end col --></div><!-- end row -->
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#browseResultsContainer").load("<?php print caNavUrl($this->request, '', 'Browse', 'works_in_collection', array('facet' => 'collection', 'id' => '^ca_collections.collection_id', 'detailNav' => 1), array('dontURLEncodeParameters' => true)); ?>", function() {
				jQuery('#browseResultsContainer').jscroll({
					autoTrigger: true,
					loadingHtml: '<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>',
					padding: 20,
					nextSelector: 'a.jscroll-next'
				});
			});		

            tronicTheToggles();
		});
	</script>
</ifcount>}}}

</div><!-- end container -->

		</div><!-- end col -->
		<div class="col-sm-1"><div class='nextLink'>{{{nextLink}}}</div></div>
	</div>
</div>
