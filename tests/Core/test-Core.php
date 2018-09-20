<?php

namespace WPBR\Tests\Core;

use WPBR\Tests\TestCase;
use WPBR\App\Core;
use function WPBR\App\Core as CoreFc;

/**
 * Main core functionality WPBR\Tests
 */
final class CoreTest extends TestCase {

    /**
     * @covers Core::getInstance
     */
    public function testIsInitPropery() {

        $core = CoreFc();

        $this->assertInstanceOf( Core::class, $core );
        $this->assertTrue( $core->hasLoaded() );

    }

}
