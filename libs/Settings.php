<?php

namespace WPBR\App;

/**
 * Manages settings using ACF plugin
 *
 * @author Przemysław Żydek
 */
class Settings {

    /** @var string Option slug */
    protected $slug;

    /**
     * Settings constructor.
     *
     * @param string $slug
     */
    public function __construct( string $slug ) {
        $this->slug = $slug;
    }

    /**
     * Define default options for settings instance
     *
     * @param array $options
     *
     * @return bool
     */
    public function defineDefaults( array $options ): bool {

        //Don't set defaults if they were set before
        if ( ! empty( $this->getAll() ) ) {
            return false;
        }

        return $this->update( $options );
    }

    /**
     * Get all settings
     *
     * @return array
     */
    public function getAll(): array {
        return get_option( $this->slug, [] );
    }

    /**
     * Get option by its key
     *
     * @param string $key
     * @param bool   $default
     *
     * @return bool|mixed
     */
    public function get( string $key, $default = false ) {
        return $this->getAll()[ $key ] ?? $default;
    }

    /**
     * Update settings with new data
     *
     * @param array $data
     *
     * @return bool
     */
    public function update( array $data ): bool {
        return update_option( $this->slug, $data );
    }

}
