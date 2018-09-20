<?php

namespace WPBR\Tests\Core;

use WPBR\Tests\TestCase;
use function WPBR\App\Core as CoreFc;
use WPBR\App\Core;

/**
 * Contains Enqueue WPBR\Tests
 */
final class EnqueueTest extends TestCase {

    /**
     * @covers Enqueue::enqueueScripts
     * @covers Enqueue::enqueueScript
     */
    public function testIsLoadingScriptCorrectly() {

        $enqueue = CoreFc()->enqueue;
        $enqueue->enqueueScript();

        $enqueue->enqueueScripts();

        $this->assertTrue( wp_script_is( CoreFc()->slug ) );

    }

}
