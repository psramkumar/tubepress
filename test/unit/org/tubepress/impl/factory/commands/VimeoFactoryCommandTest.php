<?php

require_once dirname(__FILE__) . '/../../../../../../../sys/classes/org/tubepress/impl/factory/commands/VimeoFactoryCommand.class.php';

class org_tubepress_impl_factory_commands_VimeoFactoryCommandTest extends TubePressUnitTest {

    private $_sut;
    private $_multipleFeed;

    function setUp()
    {
        parent::setUp();
        $this->_sut          = new org_tubepress_impl_factory_commands_VimeoFactoryCommand();
        $this->_multipleFeed = file_get_contents(dirname(__FILE__) . '/../feeds/vimeo.txt');
    }

    /**
     * @expectedException Exception
     */
    function testExecNoArgs()
    {
        $this->_sut->execute();
    }
    
    function testRelativeDates()
    {
        $this->setOptions(array(org_tubepress_api_const_options_names_Display::RELATIVE_DATES => true));
        $context = new org_tubepress_impl_factory_VideoFactoryChainContext($this->_multipleFeed);
        $this->_sut->execute($context);
        $result = $context->getReturnValue();
        $this->assertTrue(is_array($result));
        $this->assertEquals(8, count($result));
        $video = $result[5];
        $this->assertEquals('2 years ago', $video->getTimePublished());
    }
    
    function testGetMultiple()
    {
        $context = new org_tubepress_impl_factory_VideoFactoryChainContext($this->_multipleFeed);
        $this->_sut->execute($context);
        $result = $context->getReturnValue();
        
        $this->assertTrue(is_array($result));
        $this->assertEquals(8, count($result));
        $video = $result[5];
        $this->assertEquals('makimono', $video->getAuthorDisplayName());
        $this->assertEquals('tagtool', $video->getAuthorUid());
        $this->assertEquals('', $video->getCategory());
        $this->assertEquals('N/A', $video->getCommentCount());
        $this->assertEquals('Tagtool performance by Austrian artist Die.Puntigam at Illuminating York, 30th o...', $video->getDescription());
        $this->assertEquals('6:52', $video->getDuration());
        $this->assertEquals('http://vimeo.com/7416172', $video->getHomeUrl());
        $this->assertEquals('7416172', $video->getId());
        $this->assertEquals(array('Tagtool', 'Die.Puntigam', 'Illuminating York', 'Wall of Light'), $video->getKeywords());
        $this->assertEquals('2', $video->getLikesCount());
        $this->assertEquals('', $video->getRatingAverage());
        $this->assertEquals('N/A', $video->getRatingCount());
        $this->assertEquals('http://b.vimeocdn.com/ts/317/800/31780003_100.jpg', $video->getThumbnailUrl());
        $this->assertEquals('', $video->getTimeLastUpdated());
        $this->assertEquals('Nov 3, 2009', $video->getTimePublished());
        $this->assertEquals('747', $video->getViewCount());
    }
    
    function testCanHandleMultiple()
    {
        $context = new org_tubepress_impl_factory_VideoFactoryChainContext($this->_multipleFeed);
        TubePressChainTestUtils::assertCommandCanHandle($this->_sut, $context);
    }
     
    function testCannotHandle()
    {
        $context = new org_tubepress_impl_factory_VideoFactoryChainContext('bla');
        TubePressChainTestUtils::assertCommandCannotHandle($this->_sut, $context);
    }
}