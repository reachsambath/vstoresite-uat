<?php
// @TODO
// -- limit markup and files to editor page
// -- only load on first load of the table

// -- ensure user table data is not overwritten by preset by re-using link with preset slug

// -- export the data for 'regular table' and create the file
// -- when preset is being loaded, 

// -- test duplicating table
// -- test import / export

add_action('admin_enqueue_scripts', 'wcpt_presets_enqueue_scripts');
function wcpt_presets_enqueue_scripts()
{
  // if( ! wcpt_preset__required() ){
  //   return;
  // }

  if (defined('WCPT_DEV')) {
    $min = '';
  } else {
    $min = '.min';
  }

  wp_enqueue_script(
    'wcpt-presets',
    WCPT_PLUGIN_URL . 'presets/js' . $min . '.js',
    array('jquery'),
    WCPT_VERSION,
    true
  );

  wp_enqueue_style(
    'wcpt-presets',
    WCPT_PLUGIN_URL . 'presets/css' . $min . '.css',
    false,
    WCPT_VERSION
  );
}

// presets grid markup
function wcpt_presets__get_grid_markup()
{
  ob_start();
  $presets = array(
    array(
      'name' => 'Regular table',
      'slug' => 'regular-table',
    ),

    array(
      'name' => 'List layout',
      'slug' => 'list-layout',
    ),
  );

  ?>

  <div class="wcpt-preset-outer">
    <h2 class="wcpt-preset-heading">Select Preset</h2>
    <ul class="wcpt-preset-context-message">
      <li><em>Instant product table!</em></li>
      <li>Below you will find 2 presets to help you get started.</li>
      <li>Just select a preset and your product table will be prepared immediately.</li>
      <li>These are the most commonly used product table layout, great for any shop.</li>
      <li>You can fully customize your product table once it is created from a preset.</li>
      <li>Or choose 'Blank' if you want to start creating table without a preset.</li>
    </ul>
    <div class="wcpt-presets">
      <div class="wcpt-presets__item wcpt-presets__item--blank" data-wcpt-preset-slug="blank">
        <img class="wcpt-presets__item__image" src="<?php echo WCPT_PLUGIN_URL . 'presets/thumb/blank.png'; ?>">
        <span class="wcpt-presets__item__name">
          Blank
          <span class="wcpt-presets__item__name__use">Use</span>
        </span>
        <span class="wcpt-presets__item__byline">No preset. Start with empty table editor. </span>
      </div>
      <br>
      <?php foreach ($presets as $preset): ?>
        <div class="wcpt-presets__item wcpt-presets__item--<?php echo $preset['slug']; ?>"
          data-wcpt-preset-slug="<?php echo $preset['slug']; ?>">
          <span class="wcpt-presets__item__name">
            <?php echo $preset['name']; ?>
            <span class="wcpt-presets__item__name__use">Use</span>
          </span>
          <img class="wcpt-presets__item__image"
            src="<?php echo WCPT_PLUGIN_URL . 'presets/thumb/' . $preset['slug'] . '.png'; ?>">
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php
  return ob_get_clean();
}

// set preset required meta flag
add_action('admin_init', 'wcpt_presets__set_preset_required_meta_flag');
function wcpt_presets__set_preset_required_meta_flag()
{
  // Check if we're on the table editor page
  if (!wcpt_preset__is_table_editor_page()) {
    return;
  }

  // Check if user has proper capabilities
  if (!current_user_can('create_wc_product_tables')) {
    return;
  }

  // Validate and sanitize post_id
  if (empty($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    return;
  }

  $post_id = intval($_GET['post_id']);

  // Verify post exists and is the correct type
  $post = get_post($post_id);
  if (!$post || $post->post_type !== 'wc_product_table') {
    return;
  }

  // If table is new (no data) then set preset required meta flag
  $table_data = get_post_meta($post_id, 'wcpt_data', true);
  if (!$table_data) {
    update_post_meta($post_id, 'wcpt_preset_required', true);
  }
}

// duplicate a preset to table
add_action('admin_init', 'wcpt_presets__duplicate_preset_to_table');
function wcpt_presets__duplicate_preset_to_table()
{
  // Check if we're on the table editor page
  if (!wcpt_preset__is_table_editor_page()) {
    return;
  }

  // Check for proper authorization
  if (!current_user_can('create_wc_product_tables')) {
    wp_die('Unauthorized action.');
  }

  // No preset selected yet
  if (empty($_GET['wcpt_preset'])) {
    return;
  }

  // Validate and sanitize post_id
  if (empty($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    return;
  }

  $post_id = intval($_GET['post_id']);

  // Verify post exists and is the correct type
  $post = get_post($post_id);
  if (!$post || $post->post_type !== 'wc_product_table') {
    return;
  }

  // Sanitize preset slug and validate against an allowlist (better approach)
  $slug = sanitize_file_name($_GET['wcpt_preset']);

  // You might want to create an allowlist of valid presets
  $allowed_presets = array('blank', 'regular-table', 'list-layout'); // Add all valid presets
  if (!in_array($slug, $allowed_presets)) {
    wp_die('Invalid preset selected.');
  }

  // Preset already applied on this table
  if (!wcpt_preset__required($post_id)) {
    return;
  }

  // Apply the preset
  update_post_meta($post_id, 'wcpt_preset_required', false); // Turn off 'preset required' flag

  wp_update_post(array(
    'ID' => $post_id,
    'post_title' => $slug == 'blank' ? 'New table' : ucwords(str_replace('-', ' ', $slug)),
    'post_status' => 'publish',
  ));

  if ($slug !== 'blank') {
    // Get data from json preset file
    $preset_path = WCPT_PLUGIN_PATH . 'presets/table/' . $slug . '.json';

    // More robust path validation to prevent directory traversal
    $real_preset_path = realpath($preset_path);
    $real_presets_dir = realpath(WCPT_PLUGIN_PATH . 'presets/table/');

    if ($real_preset_path && strpos($real_preset_path, $real_presets_dir) === 0 && file_exists($real_preset_path)) {
      $preset_json = file_get_contents($real_preset_path);
      $table_data = json_decode($preset_json, true);

      if ($table_data) {
        wcpt_new_ids($table_data);
        $table_data['id'] = $post_id;
        update_post_meta($post_id, 'wcpt_data', addslashes(json_encode($table_data)));
        update_post_meta($post_id, 'wcpt_preset_applied__message_required', true);
        update_post_meta($post_id, 'wcpt_preset_applied__slug', $slug);
      }
    }
  }
}

function wcpt_preset__maybe_display_message($post_id = false)
{
  if (!$post_id) {
    if (empty($_GET['post_id'])) {
      return false;
    }
    $post_id = $_GET['post_id'];
  }

  if (!get_post_meta($post_id, 'wcpt_preset_applied__message_required', true)) {
    return false;
  }

  $preset_slug = get_post_meta($post_id, 'wcpt_preset_applied__slug', true);
  $preset_name = ucwords(str_replace('-', ' ', $preset_slug));
  ?>
  <div class="wcpt-preset-applied-message">
    <span class="wcpt-preset-applied-message__dismiss"><?php wcpt_icon('x') ?></span>
    <h2 class="wcpt-preset-heading">Preset applied!</h2>
    <ul class="wcpt-preset-applied-message__list">
      <li>You selected the '<?php echo $preset_name; ?>' preset.</li>
      <li>Your new product table is ready 👍</li>
      <li>You can show it on your website right now. <br>
        <input type="text" class="wcpt-preset-applied-message__shortcode"
          value="<?php echo esc_attr('[product_table id="' . $post_id . '"]'); ?>">
        <button class="wcpt-preset-applied-message__shortcode-copy-button">Copy</button> <br>
        Just copy the above shortcode and paste it on a <a
          href="/wp-admin/post-new.php?post_type=page&wcpt_id=<?php echo $post_id; ?>" target="_blank">new page
          <?php wcpt_icon('external-link', 'wcpt-preset-applied-message__new-page-icon'); ?></a>.<br>
      </li>
      <li>You can fully customize your new product table using the table editor.<br>
        This includes category, styling, columns and filters.<br>
        <a href="https://www.youtube.com/watch?v=xoR97WwUmqA"
          target="_blank"><?php wcpt_icon('youtube', 'wcpt-preset-applied-message__youtube-icon'); ?> Video: How to
          customize my new product table</a>
      </li>
    </ul>
  </div>
  <?php

  update_post_meta($post_id, 'wcpt_preset_applied__message_required', false);

  return true;
}

// check if presets required
function wcpt_preset__required($post_id = false)
{
  if (!$post_id) {
    if (empty($_GET['post_id'])) {
      return false;
    }
    $post_id = $_GET['post_id'];
  }

  return get_post_meta($post_id, 'wcpt_preset_required', true);
}

function wcpt_preset__is_table_editor_page()
{
  return !empty($_GET['post_type']) &&
    $_GET['post_type'] === 'wc_product_table' &&
    !empty($_GET['page']) &&
    $_GET['page'] === 'wcpt-edit';
}