<?php
function Itwpt_general(){
	global $general;
	Itwpt_Admin_Enqueue_Style(); // ADMIN ENQUEUE STYLE
	?>

	<div class="add-header general">
		<div class="row">
			<div class="col-lg-9">
				<div class="heading not-nav">
					<h3><?php echo esc_html__('GENERAL',PREFIX_ITWPT_TEXTDOMAIN);?></h3>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="control">
					<button class="btn btn-primary save <?php echo esc_attr(isset($_GET['edit'])?'update':''); ?>" type="button">
						<i class="icon-itsave"></i>
						<?php
                        if (isset($_GET['edit'])) {
                            echo esc_html__('update', PREFIX_ITWPT_TEXTDOMAIN);
                        } else {
                            echo esc_html__('save', PREFIX_ITWPT_TEXTDOMAIN);
                        }
                        ?>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="dbody">
        <div class="row itwpt-form enable">
            <div class="col-lg-12">
                <div class="row">
                    <?php Itwpt_Form_Fields($general); ?>
                </div>
            </div>
        </div>
	</div>

	<?php
    Itwpt_Loading();
	Itwpt_Admin_Enqueue_Script(); // ADMIN ENQUEUE SCRIPT
}
