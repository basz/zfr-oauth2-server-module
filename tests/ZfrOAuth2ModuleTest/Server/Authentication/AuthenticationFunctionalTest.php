<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZfrOAuth2ModuleTest\Server\Authentication\Adapter;

use PHPUnit_Framework_TestCase;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request as HttpRequest;
use ZfrOAuth2\Server\Entity\AccessToken;
use ZfrOAuth2\Server\Exception\OAuth2Exception;
use ZfrOAuth2Module\Server\Authentication\Storage\AccessTokenStorage;

/**
 * @author  Marco Pivetta <ocramius@gmail.com>
 * @licence MIT
 *
 * @coversNothing
 */
class AuthenticationFunctionalTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ZfrOAuth2\Server\ResourceServer|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resourceServer;

    /**
     * @var AccessTokenStorage
     */
    private $authenticationStorage;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var \Zend\Mvc\MvcEvent|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mvcEvent;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->mvcEvent              = $this->getMock('Zend\Mvc\MvcEvent');
        $application                 = $this->getMock('Zend\Mvc\Application', [], [], '', false);
        $this->resourceServer        = $this->getMock('ZfrOAuth2\Server\ResourceServer', [], [], '', false);
        $this->authenticationStorage = new AccessTokenStorage($this->resourceServer, $application);
        $this->authenticationService = new AuthenticationService($this->authenticationStorage);

        $application->expects($this->any())->method('getMvcEvent')->will($this->returnValue($this->mvcEvent));
    }

    public function testSuccessAuthenticationOnValidToken()
    {
        $request = new HttpRequest();

        $this->mvcEvent->expects($this->any())->method('getRequest')->will($this->returnValue($request));

        $token = new AccessToken();
        $owner = $this->getMock('ZfrOAuth2\Server\Entity\TokenOwnerInterface');
        $token->setOwner($owner);

        $this
            ->resourceServer
            ->expects($this->atLeastOnce())
            ->method('getAccessToken')
            ->with($request)
            ->will($this->returnValue($token));


        $this->assertTrue($this->authenticationService->hasIdentity());
        $this->assertSame($owner, $this->authenticationService->getIdentity());
    }

    public function testFailAuthenticationOnNoToken()
    {
        $request = new HttpRequest();

        $this->mvcEvent->expects($this->any())->method('getRequest')->will($this->returnValue($request));

        $token = new AccessToken();
        $owner = $this->getMock('ZfrOAuth2\Server\Entity\TokenOwnerInterface');
        $token->setOwner($owner);

        $this
            ->resourceServer
            ->expects($this->atLeastOnce())
            ->method('getAccessToken')
            ->with($request)
            ->will($this->returnValue(null));

        $this->assertFalse($this->authenticationService->hasIdentity());
        $this->assertNull($this->authenticationService->getIdentity());
    }

    public function testFailAuthenticationOnExpiredToken()
    {
        $request = new HttpRequest();

        $this->mvcEvent->expects($this->any())->method('getRequest')->will($this->returnValue($request));

        $token = new AccessToken();
        $owner = $this->getMock('ZfrOAuth2\Server\Entity\TokenOwnerInterface');
        $token->setOwner($owner);

        $this
            ->resourceServer
            ->expects($this->atLeastOnce())
            ->method('getAccessToken')
            ->with($request)
            ->will($this->throwException(new OAuth2Exception('Expired token', 123)));

        $this->setExpectedException('ZfrOAuth2\Server\Exception\OAuth2Exception', 'Expired token', 123);

        $this->authenticationService->getIdentity();
    }

    public function testFailAuthenticationOnNoRequest()
    {
        $this->resourceServer->expects($this->never())->method('getAccessToken');

        $this->assertFalse($this->authenticationService->hasIdentity());
        $this->assertNull($this->authenticationService->getIdentity());
    }

    public function testFailAuthenticationOnNonHttpRequest()
    {
        $request = $this->getMock('Zend\Stdlib\RequestInterface');

        $this->mvcEvent->expects($this->any())->method('getRequest')->will($this->returnValue($request));

        $this->resourceServer->expects($this->never())->method('getAccessToken');

        $this->assertFalse($this->authenticationService->hasIdentity());
        $this->assertNull($this->authenticationService->getIdentity());
    }
}
