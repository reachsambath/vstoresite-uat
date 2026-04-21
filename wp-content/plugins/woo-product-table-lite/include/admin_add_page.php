<?php
function Itwpt_Add_Page()
{
    global $admin_form;
    Itwpt_Admin_Enqueue_Style(); // ADMIN ENQUEUE STYLE
    ?>

    <div class="add-header shortcode">
        <div class="row">
            <div class="col-9">
                <div class="heading">
                    <h3><?php echo esc_html__('ADD NEW TABLE', PREFIX_ITWPT_TEXTDOMAIN); ?></h3>
                </div>
            </div>
            <div class="col-3">
                <div class="control">
                    <button class="btn btn-primary save <?php echo esc_attr(isset($_GET['edit']) ? 'update' : ''); ?>"
                            type="button">
                        <i class="icon-itsave"></i>
                        <?php
                        if (isset($_GET['edit'])) {
                            echo esc_html__('update', PREFIX_ITWPT_TEXTDOMAIN);
                        } else {
                            echo esc_html__('save', PREFIX_ITWPT_TEXTDOMAIN);
                        }
                        ?>
                    </button>
                    <div class="shortcode">
                        <span><?php echo esc_html('[it_woo_product_table id="' . (isset($_GET['edit']) ? $_GET['edit'] : '?') . '"]') ?></span>
                        <?php
                        if (isset($_GET['edit'])) {
                            ?>
                            <span class="clone-btn" data-id="<?php echo esc_html($_GET['edit']); ?>"><?php echo esc_html__('Copy', PREFIX_ITWPT_TEXTDOMAIN); ?></span>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-menu-box">
            <div class="col-lg-12">
                <div class="menu-sb">
                    <i class="icon-itmenu"></i>
                    <span><?php echo esc_html__('Menu', PREFIX_ITWPT_TEXTDOMAIN); ?></span>
                </div>
                <div class="clear"></div>
                <ul class="nav nav-tabs">
                    <?php

                    $array_nav =
                        array(
                            array(
                                "icon" => "columns",
                                "id"   => "columns",
                                "text" => esc_html__('columns', PREFIX_ITWPT_TEXTDOMAIN)
                            ),
                            array(
                                "icon" => "query",
                                "id"   => "query",
                                "text" => esc_html__('query', PREFIX_ITWPT_TEXTDOMAIN)
                            ),
                            array(
                                "icon" => "search",
                                "id"   => "search",
                                "text" => esc_html__('search box & paginations', PREFIX_ITWPT_TEXTDOMAIN)
                            ),
                            array(
                                "icon" => "template",
                                "id"   => "template",
                                "text" => esc_html__('template', PREFIX_ITWPT_TEXTDOMAIN)
                            ),
                            array(
                                "icon" => "settings",
                                "id"   => "settings",
                                "text" => esc_html__('settings', PREFIX_ITWPT_TEXTDOMAIN)
                            ),
                            array(
                                "icon" => "localization",
                                "id"   => "localization",
                                "text" => esc_html__('localization', PREFIX_ITWPT_TEXTDOMAIN)
                            ),
                        );

                    global $itwpt_svg;
                    foreach ($array_nav as $index => $item) {

                        $data = $itwpt_svg[$item['icon'] . '.svg'];
                        $base64 = 'data:image/svg+xml;base64,' . base64_encode($data);

                        ?>

                        <li class="nav-item" data-tab="<?php echo esc_attr($index); ?>">
                            <a class="nav-link <?php echo esc_attr($index == 0 ? 'active' : ''); ?>" href="#">
                                <div class="icon" style="background-image:url('<?php echo sprintf("%s",$base64); ?>')"
                                     data-base64="<?php echo esc_attr(base64_encode($data)); ?>"></div>
                                <?php echo esc_html($item['text']); ?>
                            </a>
                        </li>

                        <?php
                    }

                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="dbody add-content">
        <?php

        foreach ($array_nav as $index => $item) {

            $class = 'itwpt-form itwpt-form-tab-' . $index;
            if ($index === 0) {
                $class .= ' active';
            }
            ?>

            <div class="row <?php echo esc_attr($class); ?>">
                <div class="col-lg-9">
                    <div class="row">
                        <?php
                        Itwpt_Form_Fields($admin_form[$item['id']]);
                        ?>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="help">
                        <?php
                        Itwpt_Form_Help($admin_form[$item['id']]);
                        ?>
                    </div>
                </div>
            </div>

            <?php
        }

        ?>
    </div>

    <?php
    Itwpt_Loading();
    Itwpt_Admin_Enqueue_Script(); // ADMIN ENQUEUE SCRIPT
}
