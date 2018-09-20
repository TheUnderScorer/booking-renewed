<?php

namespace WPBR\App\Interfaces;

/**
 * Converts object to json
 */
interface Jsonable {

    /**
     * @return string
     */
    public function toJson(): string;

}
