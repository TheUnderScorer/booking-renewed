<?php

namespace WPBR\Tests\Example;

use function WPBR\abc12345\Core as CoreFc;
use WPBR\App\Core;
use WPBR\Tests\TestCase;

/**
 * Contains tests for example WPK plugin (wpk-abc12345)
 */
class CoreTest extends TestCase {

    public function testIsInitProperly() {

        $core = CoreFc();

        $this->assertInstanceOf( Core::class, $core );

    }

}
