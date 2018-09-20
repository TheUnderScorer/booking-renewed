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

        $output = $view->render( 'test', [ 'name' => 'Przemek' ] );

        $this->assertEquals( 'Hello Przemek', trim( $output ) );

    }

}
