<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Interactions;
use Kanvas\Packages\Social\Models\Interactions as ModelsInteractions;

class InteractionsCest
{
    /**
     * Test get Reaction by its name.
     *
     * @param IntegrationTester $I
     *
     * @return void
     */
    public function getInteractionByName(IntegrationTester $I) : void
    {
        $interaction = Interactions::getInteractionByName('react');

        $I->assertInstanceOf(ModelsInteractions::class, $interaction);
        $I->assertNotNull($interaction->id);
    }

    /**
     * Test getInteractionIdByName.
     *
     * @param IntegrationTester $I
     *
     * @return void
     */
    public function getInteractionIdByName(IntegrationTester $I) : void
    {
        $interactionId = Interactions::getInteractionIdByName('react');

        $I->assertEquals(ModelsInteractions::REACT, $interactionId);
    }
}
