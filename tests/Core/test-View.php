<?php

namespace WPBR\Tests\Core;

use WPBR\Tests\TestCase;
use function WPBR\App\Core;

final class ViewTest extends TestCase {

    /**
     * @covers \WPBR\App\View::render
     */
    public function testIsRenderingProperly() {

        $view = Core()->view;

        $output = $view->renderAppContainer( 'app_test' );

        $this->assertContains( 'app_test', $output );

    }

}
