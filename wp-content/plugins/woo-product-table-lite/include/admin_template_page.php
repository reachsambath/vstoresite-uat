<?php
function Itwpt_template(){
    global $admin_template_form;
    global $admin_template_selector;
    Itwpt_Admin_Enqueue_Style(); // ADMIN ENQUEUE STYLE
    ?>

    <div class="add-header">
        <div class="row">
            <div class="col-lg-9">
                <div class="heading not-nav">
                    <h3><?php echo esc_html__('TEMPLATES',PREFIX_ITWPT_TEXTDOMAIN);?></h3>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="control">
                    <a class="btn btn-primary " href="#">
                        <i class="icon-itsave"></i>
                        <?php
                        if (isset($_GET['edit'])) {
                            echo esc_html__('Update Template', PREFIX_ITWPT_TEXTDOMAIN);
                        } else {
                            echo esc_html__('Add New Template', PREFIX_ITWPT_TEXTDOMAIN);
                        }
                        ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="dbody template">
    <div class="row itwpt-form enable">
        <div class="itwpt-fields itwpt-field-video-link ">
            <div class="itwpt-inner-box">
                <a href="https://www.youtube.com/watch?v=9FRG6C5rSpA">
                    <img src="<?php echo esc_url(PREFIX_ITWPT_IMAGE_URL . 'video-learn-banner-mini.png') ?>">
                    <span>Template Manager is available in Pro Version</span>
                </a>
                <a href="https://codecanyon.net/item/woocommerec-product-table/25871270?s_rank=1" class="download-link">
                    Download Pro Version
                </a>
            </div>
        </div>
    </div>
    </div>

    <?php
    Itwpt_Loading();
    Itwpt_Admin_Enqueue_Script(); // ADMIN ENQUEUE SCRIPT
}
