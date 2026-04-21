<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="wf-tab-content" data-id="<?php echo esc_attr($target_id);?>">
	<div class="wf-tab-content_customize_pro">
		<h2><?php esc_html_e('Basic packing slip template','print-invoices-packing-slip-labels-for-woocommerce');?></h2>
		<p><?php esc_html_e('Preview of your current packing slip template.','print-invoices-packing-slip-labels-for-woocommerce');?></p>
	</div>
	<div class="wf-tab-content-inner">
		<form method="post" class="wf_settings_form">
			<div class="wt_packing_slip_preview">
				<img src="<?php echo esc_url(WF_PKLIST_PLUGIN_URL . 'assets/images/packingslip-sample.svg'); ?>" alt="<?php esc_attr_e('Packing Slip Preview', 'print-invoices-packing-slip-labels-for-woocommerce'); ?>" style="max-width: 100%; height: auto; padding: 5px;background: #fff;">
			</div>
				<?php
					// Set nonce:
					if (function_exists('wp_nonce_field'))
					{
						wp_nonce_field('wf-update-packinglist-'.WF_PKLIST_POST_TYPE);
					}
					?>					
		</form>
		<?php
			if(false === $pro_installed){
				$sidebar_pro_link = 'https://www.webtoffee.com/product/woocommerce-pdf-invoices-packing-slips/?utm_source=free_plugin_sidebar&utm_medium=pdf_basic&utm_campaign=PDF_invoice&utm_content='.WF_PKLIST_VERSION;
				$packinglist_pro_feature_list = array(
					__('Professional templates for packing slip','print-invoices-packing-slip-labels-for-woocommerce'),
					__('Show or hide fields like billing address, weight, product image, and more','print-invoices-packing-slip-labels-for-woocommerce'),
					__('Add extra details such as product meta, order info, or custom attributes','print-invoices-packing-slip-labels-for-woocommerce'),
					__('Group and sort products in the table using flexible conditions','print-invoices-packing-slip-labels-for-woocommerce'),
				);
			?>
			<div class="wt_pro_plugin_promotion" style="width:30%; margin-right: 20px;">
				<div class="wt_pro_addon_tile_doc" style="<?php echo is_rtl() ? 'left:0;' : 'right:0;'; ?>">
					<div class="wt_customizer_pro_addon_widget_doc">
					<?php
						/**
						 * @since 4.7.0 - Add offer for Black Friday Cyber Monday 2024
						 */
						if( Wt_Pklist_Common::is_bfcm_season() ) {
						?>
						<div class="bfcm_doc_settings">
							<img src="<?php echo esc_url(WF_PKLIST_PLUGIN_URL . 'admin/modules/banner/assets/images/bfcm-doc-settings-coupon.svg'); ?>">
						</div>
						<?php
						}
					?>
						<div class="wt_customizer_pro_addon_widget_wrapper_doc">
							<div class="wt_customizer_pro_addon_widget_wrapper_doc_logo_title">
								<div class="wt_customizer_pro_addon_widget_wrapper_doc_logo_title_col_1">
									<img src="<?php echo esc_url(WF_PKLIST_PLUGIN_URL . 'assets/images/unlock-icon.png'); ?>" style="width: 24px; height: 24px;">
								</div>
								<div class="wt_customizer_pro_addon_widget_wrapper_doc_logo_title_col_2">
								    <h4><?php echo esc_html__('Unlock more customization','print-invoices-packing-slip-labels-for-woocommerce'); ?></h4>
									<p><?php esc_html_e('Create fully personalized packing slips that fit your brand — with Premium.','print-invoices-packing-slip-labels-for-woocommerce'); ?></p>
								</div>
							</div>
							<div class="wt_customizer_pro_addon_features_list_doc">
								<ul>
									<?php
										foreach($packinglist_pro_feature_list as $p_feature){
											?>
											<li><?php echo esc_html($p_feature); ?></li>
											<?php
										}
									?>
								</ul>
							</div>
							<p class="wt_customizer_pro_addon_features_list_doc_more"><?php esc_html_e('Plus, many more customization options','print-invoices-packing-slip-labels-for-woocommerce'); ?></p>
							<a class="wt_customizer_pro_addon_premium_link_div_doc" href="<?php echo esc_url($sidebar_pro_link); ?>" target="_blank">
							<img src="<?php echo esc_url(WF_PKLIST_PLUGIN_URL . 'assets/images/Crown.png'); ?>"><?php esc_html_e('Upgrade to premium','print-invoices-packing-slip-labels-for-woocommerce'); ?> 
							</a>
						</div>
						
					</div>
				</div>
			</div>
		<?php
		}
		?>
	</div>
</div>
<?php do_action('wf_pklist_document_out_settings_form');?> 