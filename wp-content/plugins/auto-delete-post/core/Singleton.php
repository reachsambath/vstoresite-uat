<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Singleton trait to implement the singleton pattern in classes.
 */
trait ADP_Singleton {
    /**
     * @var static|null The singleton instance
     */
    private static $instance = null;

    /**
     * Get the singleton instance.
     *
     * @return static
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Make the constructor private to prevent multiple instances.
     */
    private function __construct() {
        $this->init();
    }

    /**
     * Initialize the singleton instance.
     * This method should be overridden in the child class if initialization is needed.
     */
    protected function init() {}
    
    /**
     * Make clone magic method private to prevent cloning of the instance.
     */
    private function __clone() {}
    
    /**
     * Make wakeup magic method private to prevent unserializing of the instance.
     */
    public function __wakeup() {
        throw new \Exception( 'Cannot unserialize singleton' );
    }
}