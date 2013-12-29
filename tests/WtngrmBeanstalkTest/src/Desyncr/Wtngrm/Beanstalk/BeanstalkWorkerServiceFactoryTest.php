<?php
namespace Desyncr\Wtngrm\Beanstalk\Factory;

/**
 * Generated by PHPUnit_SkeletonGenerator
 */
class BeanstalkWorkerServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BeanstalkWorkerServiceFactory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
       $servers = array('servers' =>
                        array('workers' =>
                            array(
                               array('host' => '127.0.0.1', 1111)
                           )
                       )
                   );
       $beanstalk = array(
           'beanstalk-adapter' => $servers
       );
       $this->config = array('wtngrm' => $beanstalk);

       $this->object = new BeanstalkWorkerServiceFactory;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Desyncr\Wtngrm\Beanstalk\Factory\BeanstalkWorkerServiceFactory::createService
     */
    public function testCreateService()
    {

        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface', array('get', 'has'))
                    ->getMock();

        $beanstalkMock = $this->getMockBuilder('BeanstalkWorker', 'addServer')->getMock();

        $beanstalkMock->expects($this->any())
            ->method('addServer')
            ->will($this->returnValue(true));

        $map = array(
            array('Config' , $this->config),
            array('Desyncr\Wtngrm\Beanstalk\Worker\BeanstalkWorker', $beanstalkMock)
        );

        $sm->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));

        $obj = $this->object->createService($sm);

        $this->
            assertInstanceOf('Desyncr\Wtngrm\Beanstalk\Service\BeanstalkWorkerService', $obj);

    }

    /**
     * @covers Desyncr\Wtngrm\Beanstalk\Factory\BeanstalkWorkerServiceFactory::createService
     */
    public function testCreateServiceOptions()
    {

        $sm = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface', array('get', 'has'))
                    ->getMock();

        $beanstalkMock = $this->getMockBuilder('BeanstalkWorker', array('addServer'))->getMock();
        $beanstalkMock->expects($this->any())
            ->method('addServer')
            ->will($this->returnValue(true));

        $map = array(
            array('Config', $this->config),
            array('Desyncr\Wtngrm\Beanstalk\Worker\BeanstalkWorker', $beanstalkMock)
        );

        $sm->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));

        $obj = $this->object->createService($sm);

        $this->assertEquals($this->config['wtngrm']['beanstalk-adapter']['servers'], $obj->servers);

    }
}
