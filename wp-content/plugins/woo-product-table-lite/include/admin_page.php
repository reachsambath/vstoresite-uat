<?php
function Itwpt_Admin_Page() {
	Itwpt_Admin_Enqueue_Style(); // ADMIN ENQUEUE STYLE
	?>

	<!-- TODO HEADER -->
	<div class="header">
		<div class="row">
			<div class="col-md-9">
				<div class="row align-items-end">
					<div class="col-md-10">
						<h4 class="title"><?php echo esc_html__('Product Table',PREFIX_ITWPT_TEXTDOMAIN);?></h4>
						<div class="sub-title">
							<?php echo esc_html__('make the best for yourself, just by clicking <br>on the new table you will create the best of your site.',PREFIX_ITWPT_TEXTDOMAIN);?>
						</div>
					</div>
					<div class="col-md-2">
                        <a href="<?php echo esc_url(get_admin_url().'admin.php?page=itwpt_add_new'); ?>"><button type="button" class="btn btn-primary add-table"><?php echo esc_html('add new table'); ?></button></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- TODO BODY -->
	<div class="dbody">
		<div class="row">

            <!-- TODO BODY -> CONTENT -->
			<div class="col-xl-9 col-lg-12">
                <div class="table-shc">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="t-id" scope="col"><?php echo esc_html__('*',PREFIX_ITWPT_TEXTDOMAIN);?></th>
                                <th class="t-name" scope="col"><?php echo esc_html__('NAME',PREFIX_ITWPT_TEXTDOMAIN);?></th>
                                <th class="t-date" scope="col"><?php echo esc_html__('DATE',PREFIX_ITWPT_TEXTDOMAIN);?></th>
                                <th class="t-shortcode" scope="col"><?php echo esc_html__('SHORTCODE',PREFIX_ITWPT_TEXTDOMAIN);?></th>
                                <th class="t-more" scope="col"><?php echo esc_html__('MORE',PREFIX_ITWPT_TEXTDOMAIN);?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php

                        $list = Itwpt_Get_Data_Table( 'itpt_posts' );
                        if(!empty($list)) {
                            foreach ($list as $NumList => $item) {

                                ?>

                                <tr>
                                    <th class="t-id" scope="row"><?php echo esc_html($NumList + 1); ?></th>
                                    <td class="t-name"><a
                                                href="<?php echo esc_url(get_admin_url() . 'admin.php?page=itwpt_add_new&edit=' . $item->id); ?>"><?php echo esc_html($item->title); ?></a>
                                    </td>
                                    <td class="t-date"><?php echo esc_html($item->date); ?></td>
<!--                                    <td class="t-update">--><?php //echo esc_html($item->update); ?><!--</td>-->
                                    <td class="t-shortcode">[it_woo_product_table id="<?php echo esc_html($item->id); ?>"] <span class="clone-btn" data-id="<?php echo esc_html($item->id); ?>"><?php echo esc_html__('Copy', PREFIX_ITWPT_TEXTDOMAIN); ?></span></td>
                                    <td class="t-more">
                                        <i class="icon-z more"></i>
                                        <div class="more-popup">
                                            <div class="delete" data-id="<?php echo esc_html($item->id); ?>">
                                                <i class="icon-z trash"></i>
                                                <span><?php echo esc_html__('delete', PREFIX_ITWPT_TEXTDOMAIN); ?></span>
                                            </div>
                                            <div class="edit"
                                                 data-link="<?php echo esc_url(get_admin_url() . 'admin.php?page=itwpt_add_new&edit=' . $item->id); ?>">
                                                <i class="icon-z edit"></i>
                                                <span><?php echo esc_html__('edit', PREFIX_ITWPT_TEXTDOMAIN); ?></span>
                                            </div>
                                            <div class="clone" data-id="<?php echo esc_html($item->id); ?>">
                                                <i class="icon-z copy"></i>
                                                <span><?php echo esc_html__('duplicate', PREFIX_ITWPT_TEXTDOMAIN); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                            }
                        }else{
                            ?>
                            <tr>
                                <td class="empty-list" colspan="6">
                                    <?php echo esc_html__('This list is empty.', PREFIX_ITWPT_TEXTDOMAIN); ?>
                                </td>
                            </tr>
                            <?php
                        }

                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TODO BODY -> SIDEBAR -->
            <div class="col-md-3 d-lg-none d-xl-block d-none">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="support">
                            <img src="<?php echo esc_url(PREFIX_ITWPT_IMAGE_URL . 'support-banner.png'); ?>">
                            <div class="title">
			                    <?php echo esc_html__('Support',PREFIX_ITWPT_TEXTDOMAIN);?>
                                <div class="sub-title">
				                    <?php echo esc_html__('iThemelandCo',PREFIX_ITWPT_TEXTDOMAIN);?>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary"><?php echo esc_html__('Support',PREFIX_ITWPT_TEXTDOMAIN);?></button>
                            <a href="#" class="fix"></a>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="learn">
                            <div class="text">
	                            <?php echo esc_html__('Learn Product Table',PREFIX_ITWPT_TEXTDOMAIN);?>
                            </div>
                            <div class="icon"><i class="icon-z arrow-right-line"></i></div>
                            <a href="#" class="fix"></a>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="quick-access">
                            <div class="item">
                                <div class="item-inner">
                                    <div class="icon">
                                        <i class="icon-z small add-folder"></i>
                                    </div>
                                    <div class="text">
                                        <?php echo esc_html__('New',PREFIX_ITWPT_TEXTDOMAIN);?>
                                    </div>
                                    <a href="<?php echo esc_url(get_admin_url().'admin.php?page=itwpt_add_new'); ?>" class="fix"></a>
                                </div>
                            </div>
                            <div class="item">
                                <div class="item-inner">
                                    <div class="icon">
                                        <i class="icon-z small setting"></i>
                                    </div>
                                    <div class="text">
		                                <?php echo esc_html__('General',PREFIX_ITWPT_TEXTDOMAIN);?>
                                    </div>
                                    <a href="<?php echo esc_url(get_admin_url().'admin.php?page=itwpt_general'); ?>" class="fix"></a>
                                </div>
                            </div>
                            <div class="item">
                                <div class="item-inner">
                                    <div class="icon">
                                        <i class="icon-z small template"></i>
                                    </div>
                                    <div class="text">
		                                <?php echo esc_html__('Template',PREFIX_ITWPT_TEXTDOMAIN);?>
                                    </div>
                                    <a href="<?php echo esc_url(get_admin_url().'admin.php?page=itwpt_template'); ?>" class="fix"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="video-learn">
                            <img src="<?php echo esc_url(PREFIX_ITWPT_IMAGE_URL . 'video-learn-banner.png'); ?>">
                            <div class="content">
                                <div class="text">
	                                <?php echo esc_html__('VIDEO LEARN IN <br> YOUTUBE',PREFIX_ITWPT_TEXTDOMAIN);?>
                                </div>
                                <button type="button" class="btn btn-primary"><?php echo esc_html__('View Page',PREFIX_ITWPT_TEXTDOMAIN);?></button>
                            </div>
                            <a href="#" class="fix"></a>
                        </div>
                    </div>
                </div>
            </div>

		</div>
	</div>

	<?php
    Itwpt_Loading();
	Itwpt_Admin_Enqueue_Script(); // ADMIN ENQUEUE SCRIPT
}