<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Models\Interactions as ModelsInteractions;
use Kanvas\Packages\Social\Models\Tags as ModelsTags;
use Kanvas\Packages\Social\Services\Interactions;
use Kanvas\Packages\Social\Services\Tags;
use Kanvas\Packages\Test\Support\Models\Users;

class TagsCest
{
    public ModelsTags $tag;

    /**
     * Create a tag for test use.
     *
     * @return void
     */
    protected function createTagTest() : void
    {
        $this->tag = Tags::create(new Users, 'Tag for test');
    }

    /**
     * Test create of tags service.
     *
     * @param IntegrationTester $I
     *
     * @return void
     */
    public function createTag(IntegrationTester $I) : void
    {
        $tag = Tags::create(new Users, 'Test Tag');

        $I->assertEquals('Test Tag', $tag->name);
    }

    /**
     * Test get Tag from Tag Service.
     *
     * @param IntegrationTester $I
     * @before createTagTest
     *
     * @return void
     */
    public function getTag(IntegrationTester $I) : void
    {
        $tag = Tags::get($this->tag->getId());

        $I->assertEquals($this->tag->getId(), $tag->getId());
    }

    /**
     * Test update from Tags Service.
     *
     * @param IntegrationTester $I
     * @before createTagTest
     *
     * @return void
     */
    public function updateTag(IntegrationTester $I) : void
    {
        $tagUpdate = Tags::update($this->tag, 'Tag Update');

        $I->assertEquals('Tag Update', $tagUpdate->name);
    }

    /**
     * Test delete of tag.
     *
     * @param IntegrationTester $I
     * @before createTagTest
     *
     * @return void
     */
    public function deleteTag(IntegrationTester $I) : void
    {
        $I->assertTrue(
            Tags::delete($this->tag)
        );
    }

    /**
     * Test Tags Interaction.
     *
     * @param IntegrationTester $I
     * @before createTagTest
     *
     * @return void
     */
    public function tagsInteraction(IntegrationTester $I) : void
    {
        $I->assertFalse(
            Interactions::add(new Users(), $this->tag, ModelsInteractions::FOLLOWING)
        );
    }
}
