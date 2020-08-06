<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use Codeception\Lib\Di;
use IntegrationTester;
use Kanvas\Packages\Social\Models\Interactions as ModelsInteractions;
use Kanvas\Packages\Social\Models\Reactions as ModelsReactions;
use Kanvas\Packages\Social\Models\UsersInteractions;
use Kanvas\Packages\Social\Services\Interactions;
use Kanvas\Packages\Social\Services\Reactions;
use Kanvas\Packages\Test\Support\Models\Users;

class InteractionsCest
{
    /**
     * Test get Reaction by its name
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getInteractionByName(IntegrationTester $I): void
    {
        $interaction = Interactions::getInteractionByName('react');

        $I->assertInstanceOf(ModelsInteractions::class, $interaction);
        $I->assertNotNull($interaction->id);
        $reaction = Reactions::getReactionByName('test-reaction', new Users());

        $I->assertEquals('test-reaction', $reaction->name);
    }

    /**
     * Test getInteractionIdByName
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getInteractionIdByName(IntegrationTester $I): void
    {
        $interactionId = Interactions::getInteractionIdByName('react');

        $I->assertEquals(ModelsInteractions::REACT, $interactionId);
    }
}
